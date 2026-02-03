@extends('layouts.admin')
@include('layouts.alerts')
@section('title', 'Ticket Details')

@section('content')
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

        $priorityBadge = $priorityBadges[$ticket->priority] ?? ['label' => ucfirst($ticket->priority), 'class' => 'bg-slate-700/30 text-slate-300', 'icon' => 'fa-flag'];
        $statusBadge = $statusBadges[$ticket->status] ?? ['label' => ucfirst($ticket->status), 'class' => 'bg-slate-700/30 text-slate-300'];
    @endphp

    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div class="min-w-0">
            <a href="{{ route('admin.tickets') }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-slate-200 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                Back to tickets
            </a>
            <div class="mt-3 flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-md bg-sky-500/10 text-sky-400 text-[10px] font-bold uppercase tracking-wider">#{{ $ticket->code }}</span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $priorityBadge['class'] }}">
                    <i class="fa-solid {{ $priorityBadge['icon'] }}"></i>
                    {{ $priorityBadge['label'] }}
                </span>
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
            </div>
            <h1 class="mt-2 text-2xl font-bold text-slate-100 tracking-tight break-words">{{ ucfirst($ticket->subject) }}</h1>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto shrink-0"></div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
                <div class="px-4 sm:px-6 py-5 border-b border-slate-800/50">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Ticket Details</div>
                </div>
                <div class="px-4 sm:px-6 py-6 space-y-6">
                    <div class="space-y-2">
                        <div class="text-[11px] font-bold uppercase tracking-widest text-slate-500 flex items-center gap-2">
                            <i class="fa-solid fa-align-left text-sky-500"></i>
                            Description
                        </div>
                        <div class="p-4 rounded-xl border border-slate-800 bg-slate-950/50 text-slate-300 text-sm leading-snug">
                            {{ $ticket->description ?: 'No description provided.' }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Attachments</div>
                        @if (!empty($ticket->attachments))
                            <div class="flex flex-wrap gap-3">
                                @foreach ($ticket->attachments as $path)
                                    <a href="{{ asset('storage/'.$path) }}" target="_blank" rel="noopener" class="w-24 h-24 rounded-xl border border-slate-800 bg-slate-950 overflow-hidden hover:border-slate-700 transition-colors">
                                        <img src="{{ asset('storage/'.$path) }}" alt="Attachment" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-slate-500">No attachments.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
                <div class="px-4 sm:px-6 py-5 border-b border-slate-800/50 flex items-center justify-between gap-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Comments</div>
                    <div class="text-xs text-slate-500">{{ $ticket->comments->count() }} total</div>
                </div>

                <div class="px-4 sm:px-6 py-6 space-y-4">
                    @forelse ($ticket->comments as $comment)
                        <div class="p-4 rounded-xl border border-slate-800 bg-slate-950/40">
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-sm font-semibold text-slate-200">{{ $comment->user?->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-slate-500">{{ $comment->created_at?->timezone('Asia/Manila')->format('M d, Y h:i A') }}</div>
                            </div>
                            <div class="mt-2 text-sm text-slate-300 whitespace-pre-wrap">{{ $comment->body }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500">No comments yet.</div>
                    @endforelse

                    @php
                        $canComment = auth()->user()?->user_type === 'admin';
                    @endphp

                    <div class="pt-2 border-t border-slate-800/50"></div>

                    @if ($canComment)
                        <form action="{{ route('admin.tickets.comments.store', $ticket) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="space-y-2">
                                <label for="comment-body" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Add Comment</label>
                                <textarea id="comment-body" name="body" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600 resize-none" placeholder="Write your comment..."></textarea>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end">
                                <button type="submit" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-400 transition-colors text-sm font-bold shadow-lg shadow-sky-500/20">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    Post Comment
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-sm text-slate-500">
                            Admin only can add comments.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
                <div class="px-4 sm:px-6 py-5 border-b border-slate-800/50">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Summary</div>
                </div>
                <div class="px-4 sm:px-6 py-6 space-y-4">
                    <form action="{{ route('admin.tickets.status.update', $ticket) }}" method="POST" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <div class="text-xs font-bold text-slate-500 uppercase">Status</div>
                        <div class="flex items-center gap-2">
                                <select name="status" class="flex-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all appearance-none">
                                    <option value="active" {{ $ticket->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="on-hold" {{ $ticket->status === 'on-hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            <button type="submit" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-sky-500 text-white hover:bg-sky-400 transition-colors shadow-lg shadow-sky-500/20">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </form>
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-xs font-bold text-slate-500 uppercase">Requester</div>
                        <div class="text-sm text-slate-200">{{ $ticket->requester_name }}</div>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-xs font-bold text-slate-500 uppercase">Requester Email</div>
                        <div class="text-sm text-slate-200">{{ $ticket->requester_email ?: '-' }}</div>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-xs font-bold text-slate-500 uppercase">Department</div>
                        <div class="text-sm text-slate-200">{{ $ticket->department ?: '-' }}</div>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-xs font-bold text-slate-500 uppercase">Assigned To</div>
                        <div class="text-sm text-slate-200">{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</div>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-xs font-bold text-slate-500 uppercase">Category</div>
                        <div class="text-sm text-slate-200">{{ $categoryLabels[$ticket->category] ?? $ticket->category }}</div>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-xs font-bold text-slate-500 uppercase">Ticket Type</div>
                        <div class="text-sm text-slate-200">{{ $ticket->ticket_type ? ucfirst($ticket->ticket_type) : '-' }}</div>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-xs font-bold text-slate-500 uppercase">Date</div>
                        <div class="text-sm text-slate-200">{{ $ticket->ticket_date ? $ticket->ticket_date->format('M d, Y') : '-' }}</div>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-xs font-bold text-slate-500 uppercase">Time Start</div>
                        <div class="text-sm text-slate-200">{{ $ticket->time_start ? \Illuminate\Support\Carbon::parse($ticket->time_start)->format('h:i A') : '-' }}</div>
                    </div>
                    <form action="{{ route('admin.tickets.time-end.update', $ticket) }}" method="POST" class="flex items-center justify-between gap-4">
                        @csrf
                        @method('PATCH')
                        <div class="text-xs font-bold text-slate-500 uppercase">Time End</div>
                        <div class="flex items-center gap-2">
                            <input
                                type="time"
                                name="time_end"
                                value="{{ $ticket->time_end ? \Illuminate\Support\Carbon::parse($ticket->time_end)->format('H:i') : '' }}"
                                class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all"
                            >
                            <button type="submit" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-sky-500 text-white hover:bg-sky-400 transition-colors shadow-lg shadow-sky-500/20">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
