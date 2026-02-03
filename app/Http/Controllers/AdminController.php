<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $ticketStats = Ticket::query()
            ->selectRaw("COUNT(*) as total")
            ->selectRaw("SUM(CASE WHEN status IN ('active','pending') THEN 1 ELSE 0 END) as open")
            ->selectRaw("SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved")
            ->selectRaw("SUM(CASE WHEN priority = 'urgent' AND status NOT IN ('resolved','closed') THEN 1 ELSE 0 END) as urgent")
            ->first();

        $totalTickets = (int) ($ticketStats->total ?? 0);
        $openTickets = (int) ($ticketStats->open ?? 0);
        $resolvedTickets = (int) ($ticketStats->resolved ?? 0);
        $urgentTickets = (int) ($ticketStats->urgent ?? 0);

        $openPercent = $totalTickets > 0 ? round(($openTickets / $totalTickets) * 100, 1) : 0.0;
        $resolvedPercent = $totalTickets > 0 ? round(($resolvedTickets / $totalTickets) * 100, 1) : 0.0;
        $urgentPercent = $totalTickets > 0 ? round(($urgentTickets / $totalTickets) * 100, 1) : 0.0;

        $allowedPriorities = ['low', 'medium', 'high', 'urgent'];
        $allowedStatuses = ['active', 'pending', 'resolved', 'closed'];

        $recentPriority = $request->query('recent_priority');
        if (!is_string($recentPriority) || !in_array($recentPriority, $allowedPriorities, true)) {
            $recentPriority = null;
        }

        $recentStatus = $request->query('recent_status');
        if (!is_string($recentStatus) || !in_array($recentStatus, $allowedStatuses, true)) {
            $recentStatus = null;
        }

        $recentQ = $request->query('recent_q');
        if (!is_string($recentQ)) {
            $recentQ = null;
        } else {
            $recentQ = trim($recentQ);
            if ($recentQ === '') {
                $recentQ = null;
            }
        }

        $recentTicketsQuery = Ticket::query()->latest();
        if ($recentPriority) {
            $recentTicketsQuery->where('priority', $recentPriority);
        }
        if ($recentStatus) {
            $recentTicketsQuery->where('status', $recentStatus);
        }
        if ($recentQ) {
            $recentTicketsQuery->where(function ($q) use ($recentQ) {
                $q
                    ->where('code', 'like', '%' . $recentQ . '%')
                    ->orWhere('subject', 'like', '%' . $recentQ . '%')
                    ->orWhere('requester_name', 'like', '%' . $recentQ . '%');
            });
        }

        $recentTickets = $recentTicketsQuery
            ->take(8)
            ->get(['id', 'code', 'subject', 'requester_name', 'priority', 'status', 'created_at']);

        $baseDepartmentOptions = [
            'TOD',
            'Supply Office',
            'HR',
            'Accounting',
            'Cashier',
            'ORD',
            'COA',
            'Motorpool',
            'Other',
        ];

        $allowedDepartmentDays = [7, 30, 90];
        $deptDays = $request->query('dept_days');
        $deptDays = is_string($deptDays) ? (int) $deptDays : (is_int($deptDays) ? $deptDays : null);
        if (!in_array($deptDays, $allowedDepartmentDays, true)) {
            $deptDays = 30;
        }

        $deptDepartment = $request->query('dept_department');
        if (!is_string($deptDepartment)) {
            $deptDepartment = null;
        } else {
            $deptDepartment = trim($deptDepartment);
            if ($deptDepartment === '') {
                $deptDepartment = null;
            }
        }

        $departmentWindowDays = $deptDays;
        $departmentCounts = DB::table('tickets')
            ->where('created_at', '>=', now()->subDays($departmentWindowDays))
            ->selectRaw("COALESCE(NULLIF(TRIM(department), ''), 'Unspecified') as department")
            ->selectRaw('COUNT(*) as count')
            ->groupBy('department')
            ->orderByDesc('count')
            ->get();

        $ticketsByDepartment = $departmentCounts
            ->map(function ($row) {
                return [
                    'department' => (string) ($row->department ?? ''),
                    'count' => (int) ($row->count ?? 0),
                ];
            })
            ->values();

        $totalDeptTickets = (int) $ticketsByDepartment->sum('count');
        $deptNoTicketsFound = false;

        if ($deptDepartment !== null) {
            $selectedCount = (int) ($ticketsByDepartment->firstWhere('department', $deptDepartment)['count'] ?? 0);
            $othersCount = max(0, $totalDeptTickets - $selectedCount);

            $topDepartments = collect();
            if ($selectedCount > 0) {
                $topDepartments->push([
                    'department' => $deptDepartment,
                    'count' => $selectedCount,
                ]);
                if ($othersCount > 0) {
                    $topDepartments->push([
                        'department' => 'Others',
                        'count' => $othersCount,
                    ]);
                }
            } else {
                $deptNoTicketsFound = true;
            }
        } else {
            $topDepartments = $ticketsByDepartment->take(8)->values();
            $othersCount = (int) $ticketsByDepartment->slice(8)->sum('count');
            if ($othersCount > 0) {
                $topDepartments->push([
                    'department' => 'Others',
                    'count' => $othersCount,
                ]);
            }
        }

        $departmentOptionsFromTickets = DB::table('tickets')
            ->selectRaw("DISTINCT COALESCE(NULLIF(TRIM(department), ''), 'Unspecified') as department")
            ->orderBy('department')
            ->pluck('department')
            ->map(fn ($d) => (string) $d)
            ->values()
            ->all();

        $departmentOptions = collect(array_merge($baseDepartmentOptions, $departmentOptionsFromTickets))
            ->map(fn ($d) => trim((string) $d))
            ->filter(fn ($d) => $d !== '')
            ->unique()
            ->sort()
            ->values()
            ->all();

        $categoryCountsQuery = DB::table('tickets')
            ->where('created_at', '>=', now()->subDays($departmentWindowDays));
        if ($deptDepartment !== null) {
            $categoryCountsQuery->whereRaw("COALESCE(NULLIF(TRIM(department), ''), 'Unspecified') = ?", [$deptDepartment]);
        }

        $categoryCounts = $categoryCountsQuery
            ->selectRaw("COALESCE(NULLIF(TRIM(category), ''), 'Unspecified') as category")
            ->selectRaw('COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        $ticketsByCategory = $categoryCounts
            ->map(function ($row) {
                return [
                    'category' => (string) ($row->category ?? ''),
                    'count' => (int) ($row->count ?? 0),
                ];
            })
            ->values();

        $topCategories = $ticketsByCategory->take(6)->values();
        $otherCategoriesCount = (int) $ticketsByCategory->slice(6)->sum('count');
        if ($otherCategoriesCount > 0) {
            $topCategories->push([
                'category' => 'Others',
                'count' => $otherCategoriesCount,
            ]);
        }

        $categoryLabels = DB::table('ticket_categories')
            ->pluck('label', 'key')
            ->mapWithKeys(fn ($label, $key) => [(string) $key => (string) $label])
            ->all();

        return view('admin.dashboard', compact(
            'totalTickets',
            'openTickets',
            'resolvedTickets',
            'urgentTickets',
            'openPercent',
            'resolvedPercent',
            'urgentPercent',
            'recentTickets',
            'recentPriority',
            'recentStatus',
            'recentQ',
            'departmentWindowDays',
            'deptDays',
            'deptDepartment',
            'deptNoTicketsFound',
            'departmentOptions',
            'topDepartments',
            'topCategories',
            'categoryLabels',
        ));
    }

    public function tickets(Request $request)
    {
        $allowedPriorities = ['low', 'medium', 'high', 'urgent'];
        $allowedStatuses = ['active', 'pending', 'resolved', 'closed'];

        $ticketsQuery = Ticket::query()->with(['assignedTo']);

        $priority = $request->query('priority');
        if (is_string($priority) && in_array($priority, $allowedPriorities, true)) {
            $ticketsQuery->where('priority', $priority);
        }

        $status = $request->query('status');
        if (is_string($status) && in_array($status, $allowedStatuses, true)) {
            $ticketsQuery->where('status', $status);
        }

        $tickets = $ticketsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $admins = User::query()
            ->where('user_type', 'admin')
            ->orderBy('name')
            ->get(['id', 'name']);

        $ticketCategories = DB::table('ticket_categories')
            ->orderBy('label')
            ->get(['key', 'label']);

        $categoryLabels = $ticketCategories
            ->pluck('label', 'key')
            ->all();

        return view('admin.tickets', compact('tickets', 'admins', 'ticketCategories', 'categoryLabels'));
    }

    public function storeTicket(Request $request)
    {
        $departmentOptions = [
            'TOD',
            'Supply Office',
            'HR',
            'Accounting',
            'Cashier',
            'ORD',
            'COA',
            'Motorpool',
            'Other',
        ];

        $categoryOptions = DB::table('ticket_categories')
            ->orderBy('label')
            ->pluck('key')
            ->all();
        $categoryOptions[] = 'Other';

        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'requester_name' => ['required', 'string', 'max:255'],
            'requester_email' => ['nullable', 'email', 'max:255'],
            'department_select' => ['required', 'string', Rule::in($departmentOptions)],
            'department_custom' => ['nullable', 'string', 'max:60', 'required_if:department_select,Other'],
            'assigned_to_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:active,pending,resolved,closed'],
            'category_select' => ['required', 'string', Rule::in($categoryOptions)],
            'category_custom' => ['nullable', 'string', 'max:60', 'required_if:category_select,Other'],
            'ticket_type' => ['required', 'string', 'in:single,multiple'],
            'priority' => ['required', 'string', 'in:low,medium,high,urgent'],
            'time_start' => ['nullable', 'date_format:H:i'],
            'time_end' => ['nullable', 'date_format:H:i'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['nullable', 'image', 'max:5120'],
        ]);

        $department = trim((string) ($validated['department_select'] ?? ''));
        if ($department === 'Other') {
            $department = trim((string) ($validated['department_custom'] ?? ''));
        }

        $category = trim((string) ($validated['category_select'] ?? ''));
        if ($category === 'Other') {
            $category = trim((string) ($validated['category_custom'] ?? ''));
        }

        $timeStart = $validated['time_start'] ?? null;
        if ($timeStart === null || $timeStart === '') {
            $timeStart = now()->format('H:i');
        }

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments', []) as $file) {
                if (!$file) {
                    continue;
                }
                $attachments[] = $file->store('ticket-attachments', 'public');
            }
        }

        do {
            $code = 'TCK-' . random_int(1000, 9999);
        } while (Ticket::query()->where('code', $code)->exists());

        $ticket = Ticket::query()->create([
            'code' => $code,
            'subject' => $validated['subject'],
            'description' => $validated['description'] ?? null,
            'requester_name' => $validated['requester_name'],
            'requester_email' => $validated['requester_email'] ?? null,
            'department' => $department !== '' ? $department : null,
            'assigned_to_user_id' => $validated['assigned_to_user_id'] ?? null,
            'category' => $category !== '' ? $category : null,
            'ticket_type' => $validated['ticket_type'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'ticket_date' => $validated['date'] ?? null,
            'time_start' => $timeStart,
            'time_end' => $validated['time_end'] ?? null,
            'attachments' => $attachments ?: null,
        ]);

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket created.');
    }

    public function showTicket(Ticket $ticket)
    {
        $ticket->load(['assignedTo', 'comments.user']);

        $categoryLabels = DB::table('ticket_categories')
            ->orderBy('label')
            ->pluck('label', 'key')
            ->all();

        return view('admin.ticket-show', compact('ticket', 'categoryLabels'));
    }

    public function exportTicket(Ticket $ticket)
    {
        $ticket->load(['assignedTo', 'comments.user']);

        $code = (string) ($ticket->code ?: $ticket->id);
        $filename = 'ticket-' . $code . '.csv';

        return response()->streamDownload(function () use ($ticket) {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Field', 'Value']);
            fputcsv($handle, ['Code', (string) $ticket->code]);
            fputcsv($handle, ['Subject', (string) $ticket->subject]);
            fputcsv($handle, ['Description', (string) ($ticket->description ?? '')]);
            fputcsv($handle, ['Attachments', is_array($ticket->attachments) ? implode('; ', $ticket->attachments) : '']);
            fputcsv($handle, ['Requester', (string) $ticket->requester_name]);
            fputcsv($handle, ['Requester Email', (string) ($ticket->requester_email ?? '')]);
            fputcsv($handle, ['Department', (string) ($ticket->department ?? '')]);
            fputcsv($handle, ['Assigned To', (string) ($ticket->assignedTo?->name ?? '')]);
            fputcsv($handle, ['Category', (string) ($ticket->category ?? '')]);
            fputcsv($handle, ['Ticket Type', (string) ($ticket->ticket_type ?? '')]);
            fputcsv($handle, ['Priority', (string) ($ticket->priority ?? '')]);
            fputcsv($handle, ['Status', (string) ($ticket->status ?? '')]);
            fputcsv($handle, ['Date', $ticket->ticket_date ? $ticket->ticket_date->format('Y-m-d') : '']);
            fputcsv($handle, ['Time Start', (string) ($ticket->time_start ?? '')]);
            fputcsv($handle, ['Time End', (string) ($ticket->time_end ?? '')]);

            fputcsv($handle, ['']);
            fputcsv($handle, ['Comments']);
            fputcsv($handle, ['User', 'Date', 'Comment']);

            foreach ($ticket->comments as $comment) {
                fputcsv($handle, [
                    (string) ($comment->user?->name ?? 'Unknown'),
                    $comment->created_at ? $comment->created_at->timezone('Asia/Manila')->format('Y-m-d H:i:s') : '',
                    (string) ($comment->body ?? ''),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportTickets(Request $request)
    {
        $allowedPriorities = ['low', 'medium', 'high', 'urgent'];
        $allowedStatuses = ['active', 'pending', 'resolved', 'closed'];

        $ticketsQuery = Ticket::query()->with(['assignedTo']);

        $priority = $request->query('priority');
        if (is_string($priority) && in_array($priority, $allowedPriorities, true)) {
            $ticketsQuery->where('priority', $priority);
        }

        $status = $request->query('status');
        if (is_string($status) && in_array($status, $allowedStatuses, true)) {
            $ticketsQuery->where('status', $status);
        }

        $filename = 'tickets-' . date('Y-m-d_His') . '.xls';
        $exportedAt = now()->format('F j, Y h:i A');

        return response()->streamDownload(function () use ($ticketsQuery, $exportedAt) {
            $escape = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

            echo "\xEF\xBB\xBF";
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>';
            echo '<table border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-family:Arial, sans-serif;font-size:11pt;">';

            $headerCellStyle = 'background:#10b981;color:#ffffff;font-weight:bold;padding:8px;border:1px solid #d1d5db;white-space:nowrap;';
            $cellStyle = 'padding:6px;border:1px solid #d1d5db;vertical-align:top;';
            $textCellStyle = $cellStyle . "mso-number-format:'@';";
            $titleCellStyle = 'font-size:16pt;font-weight:bold;padding:10px;border:1px solid #d1d5db;background:#0f172a;color:#ffffff;text-align:left;';
            $subtitleCellStyle = 'font-size:11pt;font-weight:normal;padding:8px;border:1px solid #d1d5db;background:#f8fafc;color:#0f172a;text-align:left;';

            $columns = [
                'Code',
                'Subject',
                'Requester Name',
                'Requester Email',
                'Department',
                'Assigned To',
                'Category',
                'Ticket Type',
                'Priority',
                'Status',
                'Ticket Date',
                'Time Start',
                'Time End',
                'Created At',
            ];
            $colSpan = count($columns);

            echo '<tr><th colspan="' . $escape($colSpan) . '" style="' . $titleCellStyle . '">' . $escape('Ticket Management') . '</th></tr>';
            echo '<tr><td colspan="' . $escape($colSpan) . '" style="' . $subtitleCellStyle . '">' . $escape('Exported: ' . $exportedAt) . '</td></tr>';
            echo '<tr><td colspan="' . $escape($colSpan) . '" style="' . $subtitleCellStyle . '"></td></tr>';

            echo '<tr>';
            foreach ($columns as $col) {
                echo '<th style="' . $headerCellStyle . '">' . $escape($col) . '</th>';
            }
            echo '</tr>';

            $ticketsQuery
                ->orderByDesc('created_at')
                ->select([
                    'id',
                    'code',
                    'subject',
                    'requester_name',
                    'requester_email',
                    'department',
                    'assigned_to_user_id',
                    'category',
                    'ticket_type',
                    'priority',
                    'status',
                    'ticket_date',
                    'time_start',
                    'time_end',
                    'created_at',
                ])
                ->chunk(500, function ($tickets) use ($escape, $cellStyle, $textCellStyle) {
                    foreach ($tickets as $ticket) {
                        echo '<tr>';

                        echo '<td style="' . $textCellStyle . '">' . $escape($ticket->code ?? '') . '</td>';
                        echo '<td style="' . $cellStyle . '">' . $escape($ticket->subject ?? '') . '</td>';
                        echo '<td style="' . $cellStyle . '">' . $escape($ticket->requester_name ?? '') . '</td>';
                        echo '<td style="' . $cellStyle . '">' . $escape($ticket->requester_email ?? '') . '</td>';
                        echo '<td style="' . $cellStyle . '">' . $escape($ticket->department ?? '') . '</td>';
                        echo '<td style="' . $cellStyle . '">' . $escape($ticket->assignedTo?->name ?? '') . '</td>';
                        echo '<td style="' . $cellStyle . '">' . $escape($ticket->category ?? '') . '</td>';
                        echo '<td style="' . $cellStyle . '">' . $escape($ticket->ticket_type ?? '') . '</td>';
                        echo '<td style="' . $cellStyle . '">' . $escape($ticket->priority ?? '') . '</td>';
                        echo '<td style="' . $cellStyle . '">' . $escape($ticket->status ?? '') . '</td>';
                        echo '<td style="' . $textCellStyle . '">' . $escape($ticket->ticket_date ? $ticket->ticket_date->format('Y-m-d') : '') . '</td>';
                        echo '<td style="' . $textCellStyle . '">' . $escape($ticket->time_start ?? '') . '</td>';
                        echo '<td style="' . $textCellStyle . '">' . $escape($ticket->time_end ?? '') . '</td>';
                        echo '<td style="' . $textCellStyle . '">' . $escape($ticket->created_at ? $ticket->created_at->format('Y-m-d H:i:s') : '') . '</td>';

                        echo '</tr>';
                    }
                });

            echo '</table></body></html>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function updateTicketStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:active,pending,resolved,closed'],
        ]);

        $ticket->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket status updated.');
    }

    public function updateTicketTimeEnd(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'time_end' => ['nullable', 'date_format:H:i'],
        ]);

        $ticket->update([
            'time_end' => $validated['time_end'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Time end updated.');
    }

    public function storeTicketComment(Request $request, Ticket $ticket)
    {
        $user = Auth::user();
        if (!$user || $user->user_type !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        TicketComment::query()->create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', 'Comment added.');
    }

    public function users(Request $request)
    {
        $usersQuery = User::query();

        $q = $request->query('q');
        if (is_string($q) && trim($q) !== '') {
            $q = trim($q);
            $usersQuery->where(function ($query) use ($q) {
                $query
                    ->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        $users = $usersQuery
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'user_type' => ['required', 'string', Rule::in(['admin', 'employee'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'user_type' => $validated['user_type'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User created.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'user_type' => ['required', 'string', Rule::in(['admin', 'employee'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $update = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'user_type' => $validated['user_type'],
        ];

        if (($validated['password'] ?? null) !== null && $validated['password'] !== '') {
            $update['password'] = Hash::make($validated['password']);
        }

        $user->update($update);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User updated.');
    }

    public function userActivity(User $user)
    {
        $lastSeenAt = $user->last_seen_at;
        $online = false;
        if ($lastSeenAt) {
            $online = $lastSeenAt->gt(now()->subMinutes(5));
        }

        $activities = DB::table('employee_activity_logs')
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->limit(10)
            ->get(['method', 'path', 'created_at'])
            ->map(function ($row) {
                $method = strtoupper((string) ($row->method ?? ''));
                $path = (string) ($row->path ?? '');

                $action = trim($method . ' ' . $path);
                if ($method === 'GET' && $path === '/employee/dashboard') {
                    $action = 'Opened Dashboard';
                } elseif ($method === 'GET' && $path === '/employee/tickets') {
                    $action = 'Viewed My Tickets';
                } elseif ($method === 'POST' && $path === '/employee/tickets') {
                    $action = 'Submitted a Ticket';
                }

                return [
                    'method' => $method,
                    'path' => $path,
                    'action' => $action,
                    'created_at' => $row->created_at,
                ];
            })
            ->all();

        return response()->json([
            'online' => $online,
            'last_seen_at' => $lastSeenAt?->toISOString(),
            'activities' => $activities,
        ]);
    }

    public function settings()
    {
        $ticketCategories = DB::table('ticket_categories')
            ->orderBy('label')
            ->get(['id', 'key', 'label', 'created_at']);

        return view('admin.settings', compact('ticketCategories'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->user_type !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $update = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (($validated['password'] ?? null) !== null && $validated['password'] !== '') {
            $update['password'] = Hash::make($validated['password']);
        }

        $user->update($update);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Settings updated.');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'key' => ['nullable', 'string', 'max:50', 'regex:/^[a-z0-9_-]+$/', Rule::unique('ticket_categories', 'key')],
            'label' => ['required', 'string', 'max:60'],
        ]);

        $label = trim((string) $validated['label']);
        $key = trim((string) ($validated['key'] ?? ''));

        if ($key === '') {
            $key = (string) Str::slug($label);
        }

        $baseKey = $key;
        $counter = 2;
        while (DB::table('ticket_categories')->where('key', $key)->exists()) {
            $key = $baseKey . '-' . $counter;
            $counter++;
        }

        DB::table('ticket_categories')->insert([
            'key' => $key,
            'label' => $label,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Category added.');
    }

    public function destroyCategory(int $categoryId)
    {
        $ticketCategory = DB::table('ticket_categories')->where('id', $categoryId)->first();
        if (!$ticketCategory) {
            return redirect()
                ->route('admin.settings')
                ->with('error', 'Category not found.');
        }

        $categoryKey = (string) ($ticketCategory->key ?? '');
        $inUse = $categoryKey !== '' && Ticket::query()->where('category', $categoryKey)->exists();
        if ($inUse) {
            return redirect()
                ->route('admin.settings')
                ->with('error', 'Cannot delete this category because it is used by existing tickets.');
        }

        DB::table('ticket_categories')->where('id', $categoryId)->delete();

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Category deleted.');
    }
}
