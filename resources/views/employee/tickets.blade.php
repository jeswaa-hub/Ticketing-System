@extends('layouts.employee')

@section('title', 'My Tickets')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100">
    <header class="sticky top-0 z-20 border-b border-slate-800/50 bg-slate-950/80 backdrop-blur">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:h-16 sm:items-center justify-between gap-3 py-3 sm:py-0">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500 via-sky-500 to-emerald-400 flex items-center justify-center text-xs font-bold tracking-tight text-white shadow-lg shadow-sky-500/20">
                        TS
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-slate-100 leading-5">My Tickets</div>
                        <div class="text-[11px] text-slate-500 leading-4 truncate">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('employee.dashboard') }}" class="inline-flex flex-1 sm:flex-none justify-center items-center gap-2 rounded-xl border border-slate-800 bg-slate-900/40 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-900/70 transition-colors">
                        <i class="fa-solid fa-house text-slate-400"></i>
                        Dashboard
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
                <h1 class="text-2xl font-bold tracking-tight text-slate-100">Tickets</h1>
                <p class="mt-1 text-sm text-slate-400">You can view your active/pending and past tickets here.</p>
            </div>
            <a href="{{ route('employee.dashboard') }}" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-bold text-white hover:bg-sky-400 transition-colors shadow-lg shadow-sky-500/20">
                <i class="fa-solid fa-plus"></i>
                New Ticket
            </a>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="inline-flex max-w-full overflow-x-auto rounded-xl border border-slate-800 bg-slate-900/40 p-1">
                <a href="{{ route('employee.tickets', ['tab' => 'open'] + request()->except('page')) }}"
                   class="whitespace-nowrap px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ $tab === 'open' ? 'bg-slate-950 text-sky-300' : 'text-slate-300 hover:text-slate-100' }}">
                    Active / Pending
                    <span class="ml-1 text-[11px] text-slate-500">({{ number_format($openCount ?? 0) }})</span>
                </a>
                <a href="{{ route('employee.tickets', ['tab' => 'past'] + request()->except('page')) }}"
                   class="whitespace-nowrap px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ $tab === 'past' ? 'bg-slate-950 text-sky-300' : 'text-slate-300 hover:text-slate-100' }}">
                    Past
                    <span class="ml-1 text-[11px] text-slate-500">({{ number_format($pastCount ?? 0) }})</span>
                </a>
            </div>

            <form action="{{ route('employee.tickets') }}" method="GET" class="relative w-full sm:max-w-md">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-600"></i>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search by code, subject, status..."
                    class="w-full rounded-xl border border-slate-800 bg-slate-950 py-3 pl-11 pr-12 text-sm text-slate-100 placeholder:text-slate-600 focus:outline-none focus:border-sky-500/60 focus:ring-1 focus:ring-sky-500/40"
                >
                <button type="submit" class="absolute right-2 top-1.5 p-2 text-slate-500 hover:text-slate-200 transition-colors" aria-label="Search">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>

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
            $categoryLabels = is_array($categoryLabels ?? null) ? $categoryLabels : [];
        @endphp

        <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
            <div class="sm:hidden divide-y divide-slate-800/50">
                @forelse ($tickets as $ticket)
                    @php
                        $statusBadge = $statusBadges[$ticket->status] ?? ['label' => ucfirst($ticket->status), 'class' => 'bg-slate-800/60 text-slate-300 border border-slate-700'];
                        $priorityBadge = $priorityBadges[$ticket->priority] ?? ['label' => ucfirst($ticket->priority), 'class' => 'bg-slate-800/60 text-slate-300 border border-slate-700'];
                        $categoryLabel = $categoryLabels[$ticket->category] ?? ucfirst($ticket->category);
                    @endphp
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-200">#{{ $ticket->code }}</div>
                                <div class="mt-1 font-medium text-slate-200 truncate">{{ $ticket->subject }}</div>
                                @if ($ticket->description)
                                    <div class="mt-1 text-xs text-slate-500 truncate">{{ $ticket->description }}</div>
                                @endif
                            </div>
                            <div class="shrink-0 text-xs text-slate-500">{{ $ticket->created_at?->diffForHumans() }}</div>
                        </div>
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="text-xs text-slate-400">{{ $ticket->department ?: '-' }}</span>
                            <span class="text-xs text-slate-400">{{ $categoryLabel }}</span>
                            <span class="text-xs text-slate-500">{{ $ticket->ticket_type ? ucfirst($ticket->ticket_type) : '-' }}</span>
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-[10px] font-semibold {{ $priorityBadge['class'] }}">{{ $priorityBadge['label'] }}</span>
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-[10px] font-semibold {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-10 text-center text-sm text-slate-500">
                        {{ $tab === 'open' ? 'No active/pending tickets yet.' : 'No past tickets yet.' }}
                    </div>
                @endforelse
            </div>

            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full min-w-[880px] text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/50 text-xs uppercase font-semibold text-slate-500">
                        <tr>
                            <th class="px-4 sm:px-6 py-4">Ticket</th>
                            <th class="px-4 sm:px-6 py-4">Subject</th>
                            <th class="px-4 sm:px-6 py-4">Department</th>
                            <th class="px-4 sm:px-6 py-4">Category</th>
                            <th class="px-4 sm:px-6 py-4">Type</th>
                            <th class="px-4 sm:px-6 py-4">Priority</th>
                            <th class="px-4 sm:px-6 py-4">Status</th>
                            <th class="px-4 sm:px-6 py-4 text-right">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @forelse ($tickets as $ticket)
                            @php
                                $statusBadge = $statusBadges[$ticket->status] ?? ['label' => ucfirst($ticket->status), 'class' => 'bg-slate-800/60 text-slate-300 border border-slate-700'];
                                $priorityBadge = $priorityBadges[$ticket->priority] ?? ['label' => ucfirst($ticket->priority), 'class' => 'bg-slate-800/60 text-slate-300 border border-slate-700'];
                                $categoryLabel = $categoryLabels[$ticket->category] ?? ucfirst($ticket->category);
                            @endphp
                            <tr class="hover:bg-slate-800/20 transition-colors">
                                <td class="px-4 sm:px-6 py-4 text-slate-200 font-semibold">#{{ $ticket->code }}</td>
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="font-medium text-slate-200">{{ $ticket->subject }}</div>
                                    @if ($ticket->description)
                                        <div class="text-xs text-slate-500 mt-0.5 truncate max-w-md">{{ $ticket->description }}</div>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-slate-300">{{ $ticket->department ?: '-' }}</td>
                                <td class="px-4 sm:px-6 py-4 text-slate-300">{{ $categoryLabel }}</td>
                                <td class="px-4 sm:px-6 py-4 text-slate-300">{{ $ticket->ticket_type ? ucfirst($ticket->ticket_type) : '-' }}</td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-[10px] font-semibold {{ $priorityBadge['class'] }}">{{ $priorityBadge['label'] }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-[10px] font-semibold {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-right text-slate-500">{{ $ticket->created_at?->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 sm:px-6 py-10 text-center text-sm text-slate-500">
                                    {{ $tab === 'open' ? 'No active/pending tickets yet.' : 'No past tickets yet.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-6 py-4 border-t border-slate-800/50">
                {{ $tickets->links() }}
            </div>
        </div>
    </main>
</div>
@endsection
