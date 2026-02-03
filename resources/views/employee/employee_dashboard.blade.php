@extends('layouts.employee')

@section('title', 'Employee Dashboard')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100">
    <header class="sticky top-0 z-20 border-b border-slate-800/50 bg-slate-950/80 backdrop-blur">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:h-16 sm:items-center justify-between gap-3 py-3 sm:py-0">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500 via-sky-500 to-emerald-400 flex items-center justify-center text-xs font-bold tracking-tight text-white shadow-lg shadow-sky-500/20">
                        {{ strtoupper(mb_substr(trim(Auth::user()->name ?? 'Employee'), 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-slate-100 leading-5">{{ Auth::user()->name ?? 'Employee' }}</div>
                        <div class="text-[11px] text-slate-500 leading-4 truncate">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('employee.tickets') }}" class="inline-flex flex-1 sm:flex-none justify-center items-center gap-2 rounded-xl border border-slate-800 bg-slate-900/40 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-900/70 transition-colors">
                        <i class="fa-solid fa-list-check text-slate-400"></i>
                        My Tickets
                    </a>
                    <a href="{{ route('logout') }}" class="inline-flex flex-1 sm:flex-none justify-center items-center gap-2 rounded-xl border border-slate-800 px-3 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-900/60 transition-colors">
                        <i class="fa-solid fa-right-from-bracket text-slate-400"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-100">Submit a Ticket</h1>
                <p class="mt-1 text-sm text-slate-400">Submit your concern right away and track its status.</p>
            </div>
            <a href="{{ route('employee.tickets') }}" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-bold text-white hover:bg-sky-400 transition-colors shadow-lg shadow-sky-500/20">
                <i class="fa-solid fa-ticket"></i>
                View My Tickets
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-5">
                <div class="flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400">
                        <i class="fa-solid fa-circle-notch"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400 bg-slate-800/50 px-2 py-1 rounded-full">{{ number_format($openTickets ?? 0) }}</span>
                </div>
                <div class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Active / Pending</div>
                <div class="mt-1 text-2xl font-bold text-slate-100">{{ number_format($openTickets ?? 0) }}</div>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-5">
                <div class="flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400 bg-slate-800/50 px-2 py-1 rounded-full">{{ number_format($pastTickets ?? 0) }}</span>
                </div>
                <div class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Resolved / Closed</div>
                <div class="mt-1 text-2xl font-bold text-slate-100">{{ number_format($pastTickets ?? 0) }}</div>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-5">
                <div class="flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400 bg-slate-800/50 px-2 py-1 rounded-full">{{ number_format($totalTickets ?? 0) }}</span>
                </div>
                <div class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Total Tickets</div>
                <div class="mt-1 text-2xl font-bold text-slate-100">{{ number_format($totalTickets ?? 0) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
                <div class="px-4 sm:px-6 py-5 border-b border-slate-800/50">
                    <h2 class="text-sm font-semibold text-slate-100">New Ticket</h2>
                    <p class="mt-1 text-xs text-slate-500">Fill out the form then submit. Status will start as pending.</p>
                </div>

                <form action="{{ route('employee.tickets.store') }}" method="POST" enctype="multipart/form-data" class="px-4 sm:px-6 py-6 space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <label for="ticket-subject" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Subject</label>
                        <input id="ticket-subject" name="subject" value="{{ old('subject') }}" type="text" required class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40" placeholder="e.g. Cannot access system / printer issue / request new account">
                        @error('subject')
                            <div class="text-xs text-rose-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="ticket-description" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Description</label>
                        <textarea id="ticket-description" name="description" rows="5" class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40" placeholder="Explain the issue and include important details.">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-xs text-rose-400">{{ $message }}</div>
                        @enderror
                    </div>

                    @php
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
                        $departmentSelectValue = old('department_select', 'TOD');
                        $ticketTypeValue = old('ticket_type', 'single');
                    @endphp

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label for="ticket-department-select" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Department</label>
                            <select id="ticket-department-select" name="department_select" required class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40">
                                @foreach ($departmentOptions as $departmentOption)
                                    <option value="{{ $departmentOption }}" {{ $departmentSelectValue === $departmentOption ? 'selected' : '' }}>{{ $departmentOption }}</option>
                                @endforeach
                            </select>
                            @error('department_select')
                                <div class="text-xs text-rose-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="ticket-type" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Ticket Type</label>
                            <select id="ticket-type" name="ticket_type" required class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40">
                                <option value="single" {{ $ticketTypeValue === 'single' ? 'selected' : '' }}>Single</option>
                                <option value="multiple" {{ $ticketTypeValue === 'multiple' ? 'selected' : '' }}>Multiple</option>
                            </select>
                            @error('ticket_type')
                                <div class="text-xs text-rose-400">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="ticket-department-custom-wrapper" class="{{ $departmentSelectValue === 'Other' ? '' : 'hidden' }} space-y-2">
                        <label for="ticket-department-custom" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Other Department</label>
                        <input id="ticket-department-custom" name="department_custom" value="{{ old('department_custom') }}" type="text" class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40" placeholder="Enter department">
                        @error('department_custom')
                            <div class="text-xs text-rose-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label for="ticket-category" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Category</label>
                            <select id="ticket-category" name="category_select" required class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40">
                                @php
                                    $selectedCategory = old('category_select') ?? (($ticketCategories ?? collect())->first()->key ?? null);
                                @endphp
                                @foreach (($ticketCategories ?? collect()) as $category)
                                    <option value="{{ $category->key }}" {{ $selectedCategory === $category->key ? 'selected' : '' }}>{{ $category->label }}</option>
                                @endforeach
                                <option value="Other" {{ $selectedCategory === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category_select')
                                <div class="text-xs text-rose-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="ticket-priority" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Priority</label>
                            <select id="ticket-priority" name="priority" required class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40">
                                @php
                                    $priorities = [
                                        'low' => 'Low',
                                        'medium' => 'Medium',
                                        'high' => 'High',
                                        'urgent' => 'Urgent',
                                    ];
                                    $selectedPriority = old('priority', 'medium');
                                @endphp
                                @foreach ($priorities as $value => $label)
                                    <option value="{{ $value }}" {{ $selectedPriority === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('priority')
                                <div class="text-xs text-rose-400">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="ticket-category-custom-wrapper" class="{{ old('category_select') === 'Other' ? '' : 'hidden' }} space-y-2">
                        <label for="ticket-category-custom" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Other Category</label>
                        <input id="ticket-category-custom" name="category_custom" value="{{ old('category_custom') }}" type="text" class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40" placeholder="Enter category">
                        @error('category_custom')
                            <div class="text-xs text-rose-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="assigned-to-user-id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Assigned To (optional)</label>
                        <select id="assigned-to-user-id" name="assigned_to_user_id" class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40">
                            <option value="">Unassigned</option>
                            @foreach (($admins ?? []) as $admin)
                                <option value="{{ $admin->id }}" {{ (string) old('assigned_to_user_id') === (string) $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to_user_id')
                            <div class="text-xs text-rose-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="ticket-date" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Date (optional)</label>
                        <input id="ticket-date" name="date" value="{{ old('date', now()->toDateString()) }}" type="date" class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-3 text-sm text-slate-100 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40">
                        @error('date')
                            <div class="text-xs text-rose-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="ticket-attachments" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Attachments (optional)</label>
                        <input id="ticket-attachments" name="attachments[]" type="file" multiple accept="image/*" class="block w-full text-sm text-slate-300 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-800 file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-slate-200 hover:file:bg-slate-700 transition-colors">
                        @error('attachments')
                            <div class="text-xs text-rose-400">{{ $message }}</div>
                        @enderror
                        @error('attachments.*')
                            <div class="text-xs text-rose-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pt-5 border-t border-slate-800/50 flex flex-col sm:flex-row items-stretch sm:items-center sm:justify-end gap-3">
                        <button type="reset" class="inline-flex w-full sm:w-auto justify-center rounded-xl border border-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-200 hover:bg-slate-900/60 transition-colors">Clear</button>
                        <button type="submit" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-bold text-white hover:bg-sky-400 transition-colors shadow-lg shadow-sky-500/20">Submit Ticket</button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
                <div class="px-4 sm:px-6 py-5 border-b border-slate-800/50 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-100">Recent Tickets</h2>
                        <p class="mt-1 text-xs text-slate-500">Your latest submissions.</p>
                    </div>
                    <a href="{{ route('employee.tickets') }}" class="text-xs font-semibold text-sky-400 hover:text-sky-300 transition-colors">View all</a>
                </div>
                <div class="divide-y divide-slate-800/50">
                    @php
                        $statusBadges = [
                            'active' => ['label' => 'Active', 'class' => 'bg-sky-500/10 text-sky-300 border border-sky-500/20'],
                            'pending' => ['label' => 'Pending', 'class' => 'bg-amber-500/10 text-amber-300 border border-amber-500/20'],
                            'resolved' => ['label' => 'Resolved', 'class' => 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20'],
                            'closed' => ['label' => 'Closed', 'class' => 'bg-slate-800/60 text-slate-300 border border-slate-700'],
                        ];
                        $priorityBadges = [
                            'low' => ['label' => 'Low', 'class' => 'bg-orange-500/10 text-orange-300 border border-orange-500/20'],
                            'medium' => ['label' => 'Medium', 'class' => 'bg-orange-600/10 text-orange-300 border border-orange-600/20'],
                            'high' => ['label' => 'High', 'class' => 'bg-rose-500/10 text-rose-300 border border-rose-500/20'],
                            'urgent' => ['label' => 'Urgent', 'class' => 'bg-red-500/10 text-red-300 border border-red-500/20'],
                        ];
                    @endphp

                    @forelse (($recentTickets ?? []) as $ticket)
                        @php
                            $statusBadge = $statusBadges[$ticket->status] ?? ['label' => ucfirst($ticket->status), 'class' => 'bg-slate-800/60 text-slate-300 border border-slate-700'];
                            $priorityBadge = $priorityBadges[$ticket->priority] ?? ['label' => ucfirst($ticket->priority), 'class' => 'bg-slate-800/60 text-slate-300 border border-slate-700'];
                        @endphp
                        <div class="px-4 sm:px-6 py-4 hover:bg-slate-800/20 transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-xs text-slate-500">#{{ $ticket->code }}</div>
                                    <div class="mt-1 font-semibold text-slate-100 truncate">{{ $ticket->subject }}</div>
                                    <div class="mt-1 text-[11px] text-slate-500">{{ $ticket->department ?: '-' }} · {{ $ticket->ticket_type ? ucfirst($ticket->ticket_type) : '-' }}</div>
                                    <div class="mt-1 text-[11px] text-slate-500">{{ $ticket->created_at?->diffForHumans() }}</div>
                                </div>
                                <div class="shrink-0 flex flex-col items-end gap-2">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-[10px] font-semibold {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-[10px] font-semibold {{ $priorityBadge['class'] }}">{{ $priorityBadge['label'] }}</span>
                                </div>
                            </div>

                            <div class="mt-4 border-t border-slate-800/50 pt-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Admin Comments</div>
                                    <div class="text-[10px] text-slate-500">{{ ($ticket->comments ?? collect())->count() }} total</div>
                                </div>

                                @forelse (($ticket->comments ?? collect())->take(2) as $comment)
                                    <div class="mt-3 rounded-xl border border-slate-800 bg-slate-950/40 p-3">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="text-xs font-semibold text-slate-200">{{ $comment->user?->name ?? 'Admin' }}</div>
                                            <div class="text-[10px] text-slate-500">{{ $comment->created_at?->timezone('Asia/Manila')->format('M d, Y h:i A') }}</div>
                                        </div>
                                        <div class="mt-2 text-xs text-slate-300 whitespace-pre-wrap">{{ $comment->body }}</div>
                                    </div>
                                @empty
                                    <div class="mt-3 text-xs text-slate-500">No admin comments yet.</div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center text-sm text-slate-500">
                            Wala pa kang ticket. Submit ka na sa form.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const departmentSelect = document.getElementById('ticket-department-select');
        const departmentCustomWrapper = document.getElementById('ticket-department-custom-wrapper');
        const departmentCustomInput = document.getElementById('ticket-department-custom');
        const categorySelect = document.getElementById('ticket-category');
        const categoryCustomWrapper = document.getElementById('ticket-category-custom-wrapper');
        const categoryCustomInput = document.getElementById('ticket-category-custom');

        const syncDepartmentCustom = () => {
            if (!departmentSelect || !departmentCustomWrapper) return;
            const isOther = departmentSelect.value === 'Other';
            departmentCustomWrapper.classList.toggle('hidden', !isOther);
            if (departmentCustomInput) {
                departmentCustomInput.required = isOther;
            }
        };

        if (departmentSelect) {
            departmentSelect.addEventListener('change', syncDepartmentCustom);
            syncDepartmentCustom();
        }

        const syncCategoryCustom = () => {
            if (!categorySelect || !categoryCustomWrapper) return;
            const isOther = categorySelect.value === 'Other';
            categoryCustomWrapper.classList.toggle('hidden', !isOther);
            if (categoryCustomInput) {
                categoryCustomInput.required = isOther;
            }
        };

        if (categorySelect) {
            categorySelect.addEventListener('change', syncCategoryCustom);
            syncCategoryCustom();
        }
    });
</script>
@endsection
