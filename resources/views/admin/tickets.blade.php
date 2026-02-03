@extends('layouts.admin')
@include('layouts.alerts')
@section('title', 'Ticket Management')

@section('content')
    <!-- Page Title -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-100 tracking-tight">Ticket Management</h1>
            <p class="text-sm text-slate-400 mt-1">Manage and track all tickets.</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.tickets.export.list', request()->only(['priority','status'])) }}" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 text-white text-sm font-bold hover:bg-emerald-400 transition-colors shadow-lg shadow-emerald-500/20">
                <i class="fa-solid fa-file-excel"></i>
                Export
            </a>
            <button id="create-ticket-btn" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-500 text-white text-sm font-bold hover:bg-sky-400 transition-colors shadow-lg shadow-sky-500/20">
                <i class="fa-solid fa-plus"></i>
                <span>Create Ticket</span>
            </button>
        </div>
    </div>
    <!-- Filter/Search Bar (Placeholder) -->
    <div class="mb-6 flex gap-4">
        <div class="relative flex-1 max-w-md">
            <input type="text" placeholder="Search tickets..." class="w-full bg-slate-900 border border-slate-800 rounded-xl py-3 pl-4 pr-12 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600">
            <button class="absolute right-2 top-1 p-2 text-slate-500 hover:text-slate-200 transition-colors">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
        <div id="ticket-filter" class="relative">
            <button id="ticket-filter-toggle" type="button" class="px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-slate-200 hover:border-slate-700 transition-all">
                <i class="fa-solid fa-filter mr-2"></i> Filter
            </button>

            <div id="ticket-filter-menu" class="hidden absolute right-0 mt-2 w-72 rounded-2xl border border-slate-800 bg-slate-950/80 backdrop-blur p-4 shadow-xl shadow-slate-950/40 z-10">
                <form action="{{ route('admin.tickets') }}" method="GET" class="space-y-3">
                    <div class="space-y-1.5">
                        <label for="filter-priority" class="block text-[11px] font-bold uppercase tracking-widest text-slate-500">Priority</label>
                        <select id="filter-priority" name="priority" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all appearance-none">
                            <option value="">All</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="filter-status" class="block text-[11px] font-bold uppercase tracking-widest text-slate-500">Status</label>
                        <select id="filter-status" name="status" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all appearance-none">
                            <option value="">All</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1">
                        <a href="{{ route('admin.tickets') }}" class="inline-flex justify-center items-center px-4 py-2.5 rounded-xl border border-slate-800 text-slate-200 hover:bg-slate-800/30 transition-colors text-sm font-semibold">
                            Reset
                        </a>
                        <button type="submit" class="inline-flex justify-center items-center px-4 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-400 transition-colors text-sm font-semibold shadow-lg shadow-sky-500/20">
                            Apply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-400">
                <thead class="bg-slate-900/50 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Ticket ID</th>
                        <th class="px-6 py-4">Subject / Title</th>
                        <th class="px-6 py-4">Requester</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4">Assigned To</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Priority Level</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Time Start</th>
                        <th class="px-6 py-4">Time End</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @php
                        $categoryLabels = is_array($categoryLabels ?? null) ? $categoryLabels : [];

                        $priorityBadges = [
                            'low' => ['label' => 'Low', 'class' => 'bg-orange-500/10 text-orange-300', 'icon' => 'fa-arrow-down'],
                            'medium' => ['label' => 'Medium', 'class' => 'bg-orange-600/10 text-orange-300', 'icon' => 'fa-equals'],
                            'high' => ['label' => 'High', 'class' => 'bg-red-500/10 text-red-300', 'icon' => 'fa-arrow-up'],
                            'urgent' => ['label' => 'Urgent', 'class' => 'bg-red-800/30 text-red-200', 'icon' => 'fa-bolt'],
                        ];

                        $statusBadges = [
                            'active' => ['label' => 'Active', 'class' => 'bg-emerald-500/10 text-emerald-400'],
                            'pending' => ['label' => 'Pending', 'class' => 'bg-amber-500/10 text-amber-400'],
                            'resolved' => ['label' => 'Resolved', 'class' => 'bg-sky-500/10 text-sky-400'],
                            'closed' => ['label' => 'Closed', 'class' => 'bg-slate-700/30 text-slate-300'],
                        ];
                    @endphp

                    @forelse ($tickets as $ticket)
                        @php
                            $priorityBadge = $priorityBadges[$ticket->priority] ?? ['label' => ucfirst($ticket->priority), 'class' => 'bg-slate-700/30 text-slate-300', 'icon' => 'fa-flag'];
                            $statusBadge = $statusBadges[$ticket->status] ?? ['label' => ucfirst($ticket->status), 'class' => 'bg-slate-700/30 text-slate-300'];
                        @endphp
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-mono">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-slate-500 hover:text-sky-400 underline underline-offset-4 decoration-slate-700 hover:decoration-sky-400 transition-colors">
                                    #{{ $ticket->code }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-200">{{ $ticket->subject }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-200">{{ $ticket->requester_name }}</td>
                            <td class="px-6 py-4 text-slate-200">{{ $ticket->department ?: '-' }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $categoryLabels[$ticket->category] ?? $ticket->category }}</td>
                            <td class="px-6 py-4 text-slate-200">{{ $ticket->ticket_type ? ucfirst($ticket->ticket_type) : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-medium {{ $priorityBadge['class'] }}">
                                    <i class="fa-solid {{ $priorityBadge['icon'] }}"></i>
                                    {{ $priorityBadge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400">{{ $ticket->ticket_date ? $ticket->ticket_date->format('M d, Y') : '-' }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $ticket->time_start ? \Illuminate\Support\Carbon::parse($ticket->time_start)->format('h:i A') : '-' }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $ticket->time_end ? \Illuminate\Support\Carbon::parse($ticket->time_end)->format('h:i A') : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium {{ $statusBadge['class'] }}">
                                    {{ $statusBadge['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-10 text-center text-sm text-slate-500">
                                No tickets yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-800/50">
            {{ $tickets->links() }}
        </div>
    </div>

    <!-- Create Ticket Modal -->
    <div id="create-ticket-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity opacity-0" id="modal-backdrop"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto py-4">
            <div class="flex min-h-full items-center justify-center p-4 sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-slate-900 border border-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modal-panel">
                    <div class="px-6 py-5 border-b border-slate-800/50 flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <h3 class="text-lg font-semibold text-slate-100" id="modal-title">Create New Ticket</h3>
                            <p class="mt-1 text-sm text-slate-400">Fill in the details and save to create a new ticket.</p>
                        </div>
                        <button type="button" class="shrink-0 inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-800 text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors" id="close-modal-btn" aria-label="Close">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
                        @csrf

                        <div class="space-y-2">
                            <label for="subject" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Subject / Title</label>
                            <input type="text" name="subject" id="subject" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="Brief summary of the issue">
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Description</label>
                            <textarea name="description" id="description" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600 resize-none" placeholder="Describe the details of the request or issue"></textarea>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="requester_name" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Requester</label>
                                <input type="text" name="requester_name" id="requester_name" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="e.g. Juan Dela Cruz">
                            </div>
                            <div class="space-y-2">
                                <label for="requester_email" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Requester Email</label>
                                <input type="email" name="requester_email" id="requester_email" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="e.g. juan@example.com">
                            </div>
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
                                <label for="department_select" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Department</label>
                                <select name="department_select" id="department_select" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all appearance-none">
                                    @foreach ($departmentOptions as $departmentOption)
                                        <option value="{{ $departmentOption }}" {{ $departmentSelectValue === $departmentOption ? 'selected' : '' }}>{{ $departmentOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="ticket_type" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Ticket Type</label>
                                <select name="ticket_type" id="ticket_type" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all appearance-none">
                                    <option value="single" {{ $ticketTypeValue === 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="multiple" {{ $ticketTypeValue === 'multiple' ? 'selected' : '' }}>Multiple</option>
                                </select>
                            </div>
                        </div>

                        <div id="department_custom_wrapper" class="{{ $departmentSelectValue === 'Other' ? '' : 'hidden' }} space-y-2">
                            <label for="department_custom" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Other Department</label>
                            <input type="text" name="department_custom" id="department_custom" value="{{ old('department_custom') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="Enter department">
                        </div>

                        <div class="space-y-2">
                            <label for="assigned_to_user_id" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Assigned To</label>
                            <select name="assigned_to_user_id" id="assigned_to_user_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all appearance-none">
                                <option value="">Unassigned</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="date" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Date</label>
                                <input type="date" name="date" id="date" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label for="status" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Status</label>
                                <select name="status" id="status" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all appearance-none">
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="category_select" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Category</label>
                                <select name="category_select" id="category_select" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all appearance-none">
                                    @php
                                        $selectedCategory = old('category_select') ?? '';
                                    @endphp
                                    <option value="" disabled {{ $selectedCategory === '' ? 'selected' : '' }}>Select category</option>
                                    @foreach (($ticketCategories ?? collect()) as $ticketCategory)
                                        <option value="{{ $ticketCategory->key }}" {{ $selectedCategory === $ticketCategory->key ? 'selected' : '' }}>{{ $ticketCategory->label }}</option>
                                    @endforeach
                                    <option value="Other" {{ $selectedCategory === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="priority" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Priority Level</label>
                                <select name="priority" id="priority" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all appearance-none">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>

                        <div id="category_custom_wrapper" class="{{ old('category_select') === 'Other' ? '' : 'hidden' }} space-y-2">
                            <label for="category_custom" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Other Category</label>
                            <input type="text" name="category_custom" id="category_custom" value="{{ old('category_custom') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="Enter category">
                        </div>

                        <div class="space-y-2">
                            <label for="attachments" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Upload Images</label>
                            <label class="flex flex-col items-center justify-center w-full border border-dashed border-slate-700 rounded-2xl px-4 py-6 text-center bg-slate-950/40 hover:border-sky-500/60 hover:bg-slate-900/60 transition-colors cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500/10 text-sky-400">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-slate-100">Click to upload</p>
                                        <p class="text-xs text-slate-500">PNG, JPG up to 5MB each</p>
                                    </div>
                                </div>
                                <input id="attachments" name="attachments[]" type="file" accept="image/*" multiple class="hidden">
                            </label>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="time_start" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Time Start</label>
                                <input type="time" name="time_start" id="time_start" value="{{ old('time_start', now()->format('H:i')) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label for="time_end" class="block text-xs font-medium text-slate-400 uppercase tracking-wide mt-2">Time End</label>
                                <input type="time" name="time_end" id="time_end" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                            </div>
                        </div>

                        <div class="pt-5 mt-1 border-t border-slate-800/50 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                            <button type="submit" class="admin-theme-create-ticket-btn px-4 py-2.5 rounded-xl transition-colors text-sm font-bold shadow-lg ml-auto mt-2">Create Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('create-ticket-modal');
            const backdrop = document.getElementById('modal-backdrop');
            const panel = document.getElementById('modal-panel');
            
            const openBtn = document.getElementById('create-ticket-btn');
            const closeBtn = document.getElementById('close-modal-btn');
            const cancelBtn = document.getElementById('cancel-modal-btn');
            const filterWrapper = document.getElementById('ticket-filter');
            const filterToggle = document.getElementById('ticket-filter-toggle');
            const filterMenu = document.getElementById('ticket-filter-menu');
            const departmentSelect = document.getElementById('department_select');
            const departmentCustomWrapper = document.getElementById('department_custom_wrapper');
            const departmentCustomInput = document.getElementById('department_custom');
            const categorySelect = document.getElementById('category_select');
            const categoryCustomWrapper = document.getElementById('category_custom_wrapper');
            const categoryCustomInput = document.getElementById('category_custom');

            function openModal() {
                modal.classList.remove('hidden');
                // Trigger reflow
                void modal.offsetWidth;
                
                // Animate in
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }

            function closeModal() {
                // Animate out
                backdrop.classList.add('opacity-0');
                panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
                panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

                // Wait for animation to finish
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            function syncDepartmentCustom() {
                if (!departmentSelect || !departmentCustomWrapper) return;
                const isOther = departmentSelect.value === 'Other';
                departmentCustomWrapper.classList.toggle('hidden', !isOther);
                if (departmentCustomInput) {
                    departmentCustomInput.required = isOther;
                }
            }

            if (departmentSelect) {
                departmentSelect.addEventListener('change', syncDepartmentCustom);
                syncDepartmentCustom();
            }

            function syncCategoryCustom() {
                if (!categorySelect || !categoryCustomWrapper) return;
                const isOther = categorySelect.value === 'Other';
                categoryCustomWrapper.classList.toggle('hidden', !isOther);
                if (categoryCustomInput) {
                    categoryCustomInput.required = isOther;
                }
            }

            if (categorySelect) {
                categorySelect.addEventListener('change', syncCategoryCustom);
                syncCategoryCustom();
            }

            if (openBtn) openBtn.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

            // Close on backdrop click
            modal.addEventListener('click', (e) => {
                if (e.target === backdrop) {
                    closeModal();
                }
            });

            // Close on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            const closeFilterMenu = () => {
                if (filterMenu) {
                    filterMenu.classList.add('hidden');
                }
            };

            if (filterToggle && filterMenu) {
                filterToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    filterMenu.classList.toggle('hidden');
                });
            }

            document.addEventListener('click', (e) => {
                if (!filterWrapper) {
                    return;
                }
                if (!filterWrapper.contains(e.target)) {
                    closeFilterMenu();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeFilterMenu();
                }
            });
        });
    </script>
@endsection
