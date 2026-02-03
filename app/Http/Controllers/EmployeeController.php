<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $ticketsQuery = Ticket::query()
            ->where('requester_email', $user?->email);

        $ticketStats = (clone $ticketsQuery)
            ->selectRaw("COUNT(*) as total")
            ->selectRaw("SUM(CASE WHEN status IN ('active','pending') THEN 1 ELSE 0 END) as open")
            ->selectRaw("SUM(CASE WHEN status IN ('resolved','closed') THEN 1 ELSE 0 END) as past")
            ->first();

        $totalTickets = (int) ($ticketStats->total ?? 0);
        $openTickets = (int) ($ticketStats->open ?? 0);
        $pastTickets = (int) ($ticketStats->past ?? 0);

        $recentTickets = (clone $ticketsQuery)
            ->with([
                'comments' => function ($query) {
                    $query
                        ->whereHas('user', function ($userQuery) {
                            $userQuery->where('user_type', 'admin');
                        })
                        ->with(['user:id,name,user_type'])
                        ->latest();
                },
            ])
            ->latest()
            ->take(5)
            ->get(['id', 'code', 'subject', 'department', 'category', 'ticket_type', 'priority', 'status', 'created_at']);

        $admins = User::query()
            ->where('user_type', 'admin')
            ->orderBy('name')
            ->get(['id', 'name']);

        $ticketCategories = DB::table('ticket_categories')
            ->orderBy('label')
            ->get(['key', 'label']);

        return view('employee.employee_dashboard', compact(
            'totalTickets',
            'openTickets',
            'pastTickets',
            'recentTickets',
            'admins',
            'ticketCategories',
        ));
    }

    public function storeTicket(Request $request)
    {
        $user = Auth::user();

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
            'department_select' => ['required', 'string', Rule::in($departmentOptions)],
            'department_custom' => ['nullable', 'string', 'max:60', 'required_if:department_select,Other'],
            'category_select' => ['required', 'string', Rule::in($categoryOptions)],
            'category_custom' => ['nullable', 'string', 'max:60', 'required_if:category_select,Other'],
            'ticket_type' => ['required', 'string', 'in:single,multiple'],
            'priority' => ['required', 'string', 'in:low,medium,high,urgent'],
            'assigned_to_user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('user_type', 'admin');
                }),
            ],
            'date' => ['nullable', 'date'],
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

        Ticket::query()->create([
            'code' => $code,
            'subject' => $validated['subject'],
            'description' => $validated['description'] ?? null,
            'requester_name' => $user?->name ?? 'Employee',
            'requester_email' => $user?->email,
            'department' => $department !== '' ? $department : null,
            'assigned_to_user_id' => $validated['assigned_to_user_id'] ?? null,
            'category' => $category !== '' ? $category : null,
            'ticket_type' => $validated['ticket_type'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'ticket_date' => $validated['date'] ?? null,
            'time_start' => now()->format('H:i'),
            'time_end' => null,
            'attachments' => $attachments ?: null,
        ]);

        return redirect()
            ->route('employee.dashboard')
            ->with('success', 'Ticket submitted.');
    }

    public function tickets(Request $request)
    {
        $user = Auth::user();

        $allowedTabs = ['open', 'past'];
        $tab = $request->query('tab', 'open');
        if (!in_array($tab, $allowedTabs, true)) {
            $tab = 'open';
        }

        $ticketsQuery = Ticket::query()
            ->where('requester_email', $user?->email);

        if ($tab === 'open') {
            $ticketsQuery->whereIn('status', ['active', 'pending']);
        } else {
            $ticketsQuery->whereIn('status', ['resolved', 'closed']);
        }

        $q = $request->query('q');
        if (is_string($q) && trim($q) !== '') {
            $q = trim($q);
            $ticketsQuery->where(function ($query) use ($q) {
                $query
                    ->where('code', 'like', '%' . $q . '%')
                    ->orWhere('subject', 'like', '%' . $q . '%')
                    ->orWhere('category', 'like', '%' . $q . '%')
                    ->orWhere('priority', 'like', '%' . $q . '%')
                    ->orWhere('status', 'like', '%' . $q . '%');
            });
        }

        $tickets = $ticketsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $allTicketsQuery = Ticket::query()->where('requester_email', $user?->email);
        $openCount = (clone $allTicketsQuery)->whereIn('status', ['active', 'pending'])->count();
        $pastCount = (clone $allTicketsQuery)->whereIn('status', ['resolved', 'closed'])->count();

        $categoryLabels = DB::table('ticket_categories')
            ->orderBy('label')
            ->pluck('label', 'key')
            ->all();

        return view('employee.tickets', compact('tickets', 'tab', 'openCount', 'pastCount', 'categoryLabels'));
    }
}
