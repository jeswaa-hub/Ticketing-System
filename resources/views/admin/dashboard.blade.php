@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-100 tracking-tight">Dashboard Overview</h1>
            <p class="text-sm text-slate-400 mt-1">Welcome back, {{ Auth::user()->name ?? 'Admin' }}.</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.tickets') }}" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-500 text-white text-sm font-bold hover:bg-sky-400 transition-colors shadow-lg shadow-sky-500/20">
                <i class="fa-solid fa-ticket"></i>
                <span>View Tickets</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-5 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-sky-500/30 transition-colors group">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <span class="flex items-center gap-1 text-[11px] font-medium text-slate-400 bg-slate-800/50 px-2 py-1 rounded-full">
                    {{ number_format($totalTickets) }}
                </span>
            </div>
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wider">Total Tickets</p>
            <p class="text-2xl font-bold text-slate-100 mt-1">{{ number_format($totalTickets) }}</p>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-indigo-500/30 transition-colors group">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <span class="flex items-center gap-1 text-[11px] font-medium text-amber-400 bg-amber-500/10 px-2 py-1 rounded-full">
                    {{ $openPercent }}%
                </span>
            </div>
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wider">Open Tickets</p>
            <p class="text-2xl font-bold text-slate-100 mt-1">{{ number_format($openTickets) }}</p>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-purple-500/30 transition-colors group">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <span class="flex items-center gap-1 text-[11px] font-medium text-sky-400 bg-sky-500/10 px-2 py-1 rounded-full">
                    {{ $resolvedPercent }}%
                </span>
            </div>
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wider">Resolved Tickets</p>
            <p class="text-2xl font-bold text-slate-100 mt-1">{{ number_format($resolvedTickets) }}</p>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/50 border border-slate-800 hover:border-amber-500/30 transition-colors group">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-400 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="flex items-center gap-1 text-[11px] font-medium text-rose-400 bg-rose-400/10 px-2 py-1 rounded-full">
                    {{ $urgentPercent }}%
                </span>
            </div>
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wider">Urgent Tickets</p>
            <p class="text-2xl font-bold text-slate-100 mt-1">{{ number_format($urgentTickets) }}</p>
        </div>
    </div>

    <div class="flex flex-col gap-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-slate-800/50">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-slate-100">Tickets by Department</h3>
                            <p class="mt-1 text-xs text-slate-500">Last {{ (int) ($departmentWindowDays ?? 30) }} days</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <form action="{{ route('admin.dashboard') }}" method="GET" class="js-auto-submit-form flex flex-col items-end gap-2">
                                @if (!empty($recentQ))
                                    <input type="hidden" name="recent_q" value="{{ $recentQ }}">
                                @endif
                                @if (!empty($recentStatus))
                                    <input type="hidden" name="recent_status" value="{{ $recentStatus }}">
                                @endif
                                @if (!empty($recentPriority))
                                    <input type="hidden" name="recent_priority" value="{{ $recentPriority }}">
                                @endif

                                <div class="grid grid-cols-2 gap-2">
                                    <select name="dept_days" data-auto-submit="1" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                                        @foreach ([7, 30, 90] as $d)
                                            <option value="{{ $d }}" @selected(((int) ($deptDays ?? 30)) === $d)>{{ $d }} days</option>
                                        @endforeach
                                    </select>

                                    <select name="dept_department" data-auto-submit="1" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                                        <option value="" @selected(empty($deptDepartment))>Top departments</option>
                                        @foreach (($departmentOptions ?? []) as $d)
                                            <option value="{{ $d }}" @selected(($deptDepartment ?? null) === $d)>{{ $d }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.dashboard', array_filter(['recent_q' => !empty($recentQ) ? $recentQ : null, 'recent_status' => !empty($recentStatus) ? $recentStatus : null, 'recent_priority' => !empty($recentPriority) ? $recentPriority : null])) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-[11px] font-semibold text-slate-200 hover:bg-slate-900/60 transition-colors">
                                        Reset
                                    </a>
                                </div>
                            </form>

                            <div class="mt-0.5 h-9 w-9 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center">
                                <i class="fa-solid fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    @php
                        $departments = $topDepartments ?? [];
                        if ($departments instanceof \Illuminate\Support\Collection) {
                            $departments = $departments->values()->all();
                        }
                        if (!is_array($departments)) {
                            $departments = [];
                        }

                        $departments = array_values(array_filter($departments, function ($row) {
                            $count = is_array($row) ? (int) ($row['count'] ?? 0) : 0;
                            return $count > 0;
                        }));

                        $totalDeptCount = 0;
                        foreach ($departments as $row) {
                            $totalDeptCount += (int) ($row['count'] ?? 0);
                        }

                        $colors = ['#38bdf8', '#34d399', '#fbbf24', '#a78bfa', '#fb7185', '#60a5fa', '#f97316', '#22c55e'];
                        $size = 170;
                        $cx = 85;
                        $cy = 85;
                        $r = 70;
                    @endphp

                    @if (!empty($deptNoTicketsFound) && !empty($deptDepartment))
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/40 px-6 py-10 text-center">
                            <div class="mx-auto mb-3 h-12 w-12 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center">
                                <i class="fa-solid fa-circle-xmark text-xl"></i>
                            </div>
                            <div class="text-base font-bold text-slate-100">No tickets found</div>
                            <div class="mt-1 text-sm text-slate-400">No tickets found in {{ $deptDepartment }}.</div>
                        </div>
                    @elseif (empty($departments) || $totalDeptCount <= 0)
                        <div class="py-10 text-center text-sm text-slate-500">No data yet.</div>
                    @else
                        <div class="flex items-center justify-between text-[11px] text-slate-500 mb-4">
                            <span>{{ number_format($totalDeptCount) }} tickets</span>
                            <span>{{ (int) ($departmentWindowDays ?? 30) }} days</span>
                        </div>

                        <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-3 flex items-center justify-center">
                            <svg viewBox="0 0 {{ $size }} {{ $size }}" class="w-full max-w-[220px] h-[190px]">
                                @if (count($departments) === 1)
                                    @php
                                        $row = $departments[0] ?? [];
                                        $deptName = (string) ($row['department'] ?? '');
                                        $count = (int) ($row['count'] ?? 0);
                                        $color = $colors[0] ?? '#38bdf8';
                                    @endphp
                                    <g>
                                        <title>{{ $deptName }}: {{ number_format($count) }}</title>
                                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="{{ $color }}" />
                                    </g>
                                @else
                                    @php
                                        $startAngle = -90.0;
                                        $sliceCount = count($departments);
                                    @endphp
                                    @foreach ($departments as $idx => $row)
                                        @php
                                            $deptName = (string) ($row['department'] ?? '');
                                            $count = (int) ($row['count'] ?? 0);
                                            $percent = $totalDeptCount > 0 ? (($count / $totalDeptCount) * 100) : 0;
                                            $angle = $totalDeptCount > 0 ? (($count / $totalDeptCount) * 360.0) : 0.0;

                                            $endAngle = $startAngle + $angle;
                                            if ($idx === $sliceCount - 1) {
                                                $endAngle = 270.0;
                                            }

                                            $x1 = $cx + ($r * cos(deg2rad($startAngle)));
                                            $y1 = $cy + ($r * sin(deg2rad($startAngle)));
                                            $x2 = $cx + ($r * cos(deg2rad($endAngle)));
                                            $y2 = $cy + ($r * sin(deg2rad($endAngle)));
                                            $largeArc = ($endAngle - $startAngle) > 180 ? 1 : 0;
                                            $color = $colors[$idx % count($colors)] ?? '#38bdf8';

                                            $path = 'M ' . $cx . ' ' . $cy
                                                . ' L ' . number_format($x1, 3, '.', '') . ' ' . number_format($y1, 3, '.', '')
                                                . ' A ' . $r . ' ' . $r . ' 0 ' . $largeArc . ' 1 ' . number_format($x2, 3, '.', '') . ' ' . number_format($y2, 3, '.', '')
                                                . ' Z';
                                        @endphp
                                        <g>
                                            <title>{{ $deptName }}: {{ number_format($count) }} ({{ number_format($percent, 1) }}%)</title>
                                            <path d="{{ $path }}" fill="{{ $color }}" class="transition-opacity hover:opacity-90" />
                                        </g>
                                        @php
                                            $startAngle = $endAngle;
                                        @endphp
                                    @endforeach
                                @endif
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-slate-800/50">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-slate-100">Ticket Issues by Category</h3>
                            <p class="mt-1 text-xs text-slate-500">Last {{ (int) ($departmentWindowDays ?? 30) }} days</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <form action="{{ route('admin.dashboard') }}" method="GET" class="js-auto-submit-form flex flex-col items-end gap-2">
                                @if (!empty($recentQ))
                                    <input type="hidden" name="recent_q" value="{{ $recentQ }}">
                                @endif
                                @if (!empty($recentStatus))
                                    <input type="hidden" name="recent_status" value="{{ $recentStatus }}">
                                @endif
                                @if (!empty($recentPriority))
                                    <input type="hidden" name="recent_priority" value="{{ $recentPriority }}">
                                @endif

                                <div class="grid grid-cols-2 gap-2">
                                    <select name="dept_days" data-auto-submit="1" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                                        @foreach ([7, 30, 90] as $d)
                                            <option value="{{ $d }}" @selected(((int) ($deptDays ?? 30)) === $d)>{{ $d }} days</option>
                                        @endforeach
                                    </select>

                                    <select name="dept_department" data-auto-submit="1" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                                        <option value="" @selected(empty($deptDepartment))>Top departments</option>
                                        @foreach (($departmentOptions ?? []) as $d)
                                            <option value="{{ $d }}" @selected(($deptDepartment ?? null) === $d)>{{ $d }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.dashboard', array_filter(['recent_q' => !empty($recentQ) ? $recentQ : null, 'recent_status' => !empty($recentStatus) ? $recentStatus : null, 'recent_priority' => !empty($recentPriority) ? $recentPriority : null])) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-[11px] font-semibold text-slate-200 hover:bg-slate-900/60 transition-colors">
                                        Reset
                                    </a>
                                </div>
                            </form>

                            <div class="mt-0.5 h-9 w-9 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center">
                                <i class="fa-solid fa-chart-column"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    @php
                        $categories = $topCategories ?? [];
                        if ($categories instanceof \Illuminate\Support\Collection) {
                            $categories = $categories->values()->all();
                        }
                        if (!is_array($categories)) {
                            $categories = [];
                        }

                        $categories = array_values(array_filter($categories, function ($row) {
                            $count = is_array($row) ? (int) ($row['count'] ?? 0) : 0;
                            return $count > 0;
                        }));

                        $maxCount = 1;
                        foreach ($categories as $row) {
                            $maxCount = max($maxCount, (int) ($row['count'] ?? 0));
                        }

                        $colors = ['#38bdf8', '#34d399', '#fbbf24', '#a78bfa', '#fb7185', '#60a5fa', '#f97316', '#22c55e'];
                        $w = 340;
                        $h = 170;
                        $padX = 18;
                        $padY = 18;
                        $plotW = $w - ($padX * 2);
                        $plotH = $h - ($padY * 2);
                        $gap = 6;
                        $barCount = count($categories);
                        $barW = $barCount > 0 ? (($plotW - ($gap * max(0, $barCount - 1))) / $barCount) : $plotW;

                        $labelMap = is_array($categoryLabels ?? null) ? $categoryLabels : [];

                        $totalCategoryCount = 0;
                        foreach ($categories as $row) {
                            $totalCategoryCount += (int) ($row['count'] ?? 0);
                        }
                    @endphp

                    @if (empty($categories))
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/40 px-6 py-10 text-center">
                            <div class="mx-auto mb-3 h-12 w-12 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center">
                                <i class="fa-solid fa-circle-xmark text-xl"></i>
                            </div>
                            <div class="text-base font-bold text-slate-100">No tickets found</div>
                            <div class="mt-1 text-sm text-slate-400">
                                @if (!empty($deptDepartment))
                                    No tickets found in {{ $deptDepartment }}.
                                @else
                                    No tickets found.
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-between text-[11px] text-slate-500 mb-4">
                            <span>{{ number_format($totalCategoryCount) }} tickets</span>
                            <span>{{ (int) ($departmentWindowDays ?? 30) }} days</span>
                        </div>

                        <div class="rounded-2xl border border-slate-800 bg-slate-950/40 p-3">
                            <svg viewBox="0 0 {{ $w }} {{ $h }}" class="w-full h-[170px]">
                                <line x1="{{ $padX }}" y1="{{ $padY + $plotH }}" x2="{{ $padX + $plotW }}" y2="{{ $padY + $plotH }}" stroke="rgba(148,163,184,0.18)" stroke-width="1" />

                                @foreach ($categories as $idx => $row)
                                    @php
                                        $categoryKey = (string) ($row['category'] ?? '');
                                        $label = $labelMap[$categoryKey] ?? $categoryKey;
                                        $count = (int) ($row['count'] ?? 0);

                                        $height = $maxCount > 0 ? (($count / $maxCount) * $plotH) : 0;
                                        $x = $padX + ($idx * ($barW + $gap));
                                        $y = $padY + ($plotH - $height);
                                        $color = $colors[$idx % count($colors)] ?? '#38bdf8';
                                    @endphp
                                    <g>
                                        <title>{{ $label }}: {{ number_format($count) }}</title>
                                        <rect x="{{ number_format($x, 3, '.', '') }}" y="{{ number_format($y, 3, '.', '') }}" width="{{ number_format($barW, 3, '.', '') }}" height="{{ number_format($height, 3, '.', '') }}" rx="4" fill="{{ $color }}" class="transition-opacity hover:opacity-90" />
                                    </g>
                                @endforeach
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-800/50 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-100">Recent Tickets</h3>
                    <a href="{{ route('admin.tickets') }}" class="text-xs font-medium text-sky-400 hover:text-sky-300 transition-colors whitespace-nowrap">View All</a>
                </div>

                <form action="{{ route('admin.dashboard') }}" method="GET" class="js-auto-submit-form w-full sm:w-auto">
                    @if (request()->query('dept_days') !== null)
                        <input type="hidden" name="dept_days" value="{{ (int) ($deptDays ?? 30) }}">
                    @endif
                    @if (!empty($deptDepartment))
                        <input type="hidden" name="dept_department" value="{{ $deptDepartment }}">
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <input
                            name="recent_q"
                            type="text"
                            value="{{ $recentQ ?? '' }}"
                            placeholder="Search (code, subject, requester)"
                            data-auto-submit-text="1"
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all"
                        >

                        <select
                            name="recent_status"
                            data-auto-submit="1"
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all"
                        >
                            <option value="">All Status</option>
                            @foreach (['active' => 'Active', 'pending' => 'Pending', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $value => $label)
                                <option value="{{ $value }}" @selected(($recentStatus ?? null) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>

                        <select
                            name="recent_priority"
                            data-auto-submit="1"
                            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all"
                        >
                            <option value="">All Priority</option>
                            @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $value => $label)
                                <option value="{{ $value }}" @selected(($recentPriority ?? null) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-2 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.dashboard', array_filter(['dept_days' => request()->query('dept_days') !== null ? (int) ($deptDays ?? 30) : null, 'dept_department' => !empty($deptDepartment) ? $deptDepartment : null])) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-800 bg-slate-950 px-3 py-2 text-xs font-semibold text-slate-200 hover:bg-slate-900/60 transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm text-slate-400">
                    <thead class="bg-slate-900/50 text-xs uppercase font-semibold text-slate-500">
                    <thead class="bg-slate-900/50 text-xs uppercase font-semibold text-slate-500">
                        <tr>
                            <th class="px-4 sm:px-6 py-4">Ticket</th>
                            <th class="px-4 sm:px-6 py-4">Subject</th>
                            <th class="px-4 sm:px-6 py-4">Requester</th>
                            <th class="px-4 sm:px-6 py-4">Priority</th>
                            <th class="px-4 sm:px-6 py-4">Status</th>
                            <th class="px-4 sm:px-6 py-4 text-right">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @php
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

                        @forelse ($recentTickets as $ticket)
                            @php
                                $priorityBadge = $priorityBadges[$ticket->priority] ?? ['label' => ucfirst($ticket->priority), 'class' => 'bg-slate-700/30 text-slate-300', 'icon' => 'fa-flag'];
                                $statusBadge = $statusBadges[$ticket->status] ?? ['label' => ucfirst($ticket->status), 'class' => 'bg-slate-700/30 text-slate-300'];
                            @endphp
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 sm:px-6 py-4 font-mono">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-slate-500 hover:text-sky-400 underline underline-offset-4 decoration-slate-700 hover:decoration-sky-400 transition-colors">
                                        #{{ $ticket->code }}
                                    </a>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span class="font-medium text-slate-200">{{ $ticket->subject }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-slate-200">{{ $ticket->requester_name }}</td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-medium {{ $priorityBadge['class'] }}">
                                        <i class="fa-solid {{ $priorityBadge['icon'] }}"></i>
                                        {{ $priorityBadge['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium {{ $statusBadge['class'] }}">
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-right text-slate-400">{{ $ticket->created_at?->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 sm:px-6 py-10 text-center text-sm text-slate-500">
                                    No tickets yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.js-auto-submit-form [data-auto-submit="1"]').forEach((el) => {
                el.addEventListener('change', () => {
                    const form = el.closest('form');
                    if (form) {
                        form.submit();
                    }
                });
            });

            const timers = new WeakMap();
            document.querySelectorAll('.js-auto-submit-form [data-auto-submit-text="1"]').forEach((el) => {
                el.addEventListener('input', () => {
                    const form = el.closest('form');
                    if (!form) return;

                    const prev = timers.get(el);
                    if (prev) {
                        window.clearTimeout(prev);
                    }

                    const t = window.setTimeout(() => {
                        form.submit();
                    }, 450);
                    timers.set(el, t);
                });

                el.addEventListener('blur', () => {
                    const form = el.closest('form');
                    if (form) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
