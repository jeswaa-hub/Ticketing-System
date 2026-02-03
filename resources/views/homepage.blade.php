<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticketing System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 font-sans antialiased">
    <div class="flex flex-col min-h-screen animate-[fadeIn_0.6s_ease-out]">
        <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur z-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16 gap-3">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-indigo-500 via-sky-500 to-emerald-400 flex items-center justify-center text-xs font-bold tracking-tight">
                        TS
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm font-semibold tracking-tight">Ticketing System</span>
                        <span class="text-[11px] text-slate-400">Smart event tickets for modern venues</span>
                    </div>
                </div>

                <nav class="hidden md:flex items-center gap-8 text-xs font-medium text-slate-300">
                    <a href="#" class="hover:text-sky-400 transition-colors">Home</a>
                    <a href="#" class="hover:text-sky-400 transition-colors">Events</a>
                    <a href="#" class="hover:text-sky-400 transition-colors">My Tickets</a>
                    <a href="#" class="hover:text-sky-400 transition-colors">Support</a>
                </nav>

                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-xs font-medium text-slate-200 hover:text-sky-400 transition-colors">
                        Log in
                    </a>
                    <a href="#" class="inline-flex items-center rounded-full bg-sky-500 px-3 py-1.5 text-[11px] font-semibold text-slate-950 shadow-sm shadow-sky-500/40 hover:bg-sky-400 transition-colors">
                        Get Started
                    </a>
                </div>

                <button
                    type="button"
                    id="mobile-menu-toggle"
                    class="md:hidden inline-flex items-center justify-center rounded-full border border-slate-700 px-2.5 py-1.5 text-[11px] text-slate-200 hover:border-sky-500 hover:text-sky-400 transition-colors"
                >
                    <span class="sr-only">Toggle navigation</span>
                    <span class="flex flex-col gap-0.5">
                        <span class="h-[2px] w-4 rounded bg-slate-200"></span>
                        <span class="h-[2px] w-4 rounded bg-slate-200"></span>
                        <span class="h-[2px] w-4 rounded bg-slate-200"></span>
                    </span>
                </button>
            </div>
            <div id="mobile-menu" class="md:hidden hidden border-t border-slate-800 bg-slate-950/95">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3 space-y-3">
                    <nav class="flex flex-col gap-2 text-xs font-medium text-slate-300">
                        <a href="#" class="py-1 hover:text-sky-400 transition-colors">Home</a>
                        <a href="#" class="py-1 hover:text-sky-400 transition-colors">Events</a>
                        <a href="#" class="py-1 hover:text-sky-400 transition-colors">My Tickets</a>
                        <a href="#" class="py-1 hover:text-sky-400 transition-colors">Support</a>
                    </nav>
                    <div class="flex flex-col gap-2 text-xs">
                        <a href="#" class="inline-flex items-center justify-center rounded-full border border-slate-700 px-3 py-1.5 font-medium text-slate-200 hover:border-sky-500 hover:text-sky-400 transition-colors">
                            Log in
                        </a>
                        <a href="#" class="inline-flex items-center justify-center rounded-full bg-sky-500 px-3 py-1.5 font-semibold text-slate-950 hover:bg-sky-400 transition-colors">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <section class="border-b border-slate-800 bg-gradient-to-b from-slate-950 via-slate-950 to-slate-950">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 grid lg:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)] gap-10 lg:gap-16 items-center">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-2 rounded-full border border-slate-800 bg-slate-900/60 px-2.5 py-1 text-[11px] text-slate-300 shadow-sm shadow-sky-500/20 animate-pulse">
                            <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500/10 text-[9px] text-emerald-400 animate-ping">
                                ●
                            </span>
                            Real-time ticketing dashboard
                        </div>

                        <div class="space-y-4">
                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-semibold tracking-tight text-slate-50 transition-transform duration-500 ease-out hover:-translate-y-1">
                                Manage events, sell tickets, and scan entries in one place.
                            </h1>
                            <p class="text-sm sm:text-base text-slate-300 max-w-xl">
                                Create events in minutes, launch secure online ticket sales, and track check-ins live at the gate. Built for concerts, conferences, cinemas, and everything in between.
                            </p>
                        </div>

                        <form class="mt-4 space-y-3 rounded-2xl border border-slate-800 bg-slate-900/60 p-3 sm:p-4 transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-sky-500/20">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="relative flex-1">
                                    <input
                                        type="text"
                                        placeholder="Search events, venues, or organizers"
                                        class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 pl-9 text-xs sm:text-sm text-slate-100 placeholder-slate-500 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                                    >
                                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-500 text-xs">
                                        🔍
                                    </span>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <select class="w-28 sm:w-32 rounded-xl border border-slate-700 bg-slate-900/80 px-2.5 py-2 text-[11px] sm:text-xs text-slate-100 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500">
                                        <option>Any date</option>
                                        <option>Today</option>
                                        <option>This week</option>
                                        <option>This month</option>
                                    </select>
                                    <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-xl bg-sky-500 px-3 py-2 text-[11px] sm:text-xs font-semibold text-slate-950 transform hover:-translate-y-0.5 hover:bg-sky-400 transition-all duration-200">
                                        Find tickets
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-[10px] text-slate-400">
                                <span>Popular:</span>
                                <button type="button" class="rounded-full border border-slate-700 px-2 py-1 hover:border-sky-500 hover:text-sky-400 transition-colors">
                                    Concerts
                                </button>
                                <button type="button" class="rounded-full border border-slate-700 px-2 py-1 hover:border-sky-500 hover:text-sky-400 transition-colors">
                                    Sports
                                </button>
                                <button type="button" class="rounded-full border border-slate-700 px-2 py-1 hover:border-sky-500 hover:text-sky-400 transition-colors">
                                    Conferences
                                </button>
                                <button type="button" class="rounded-full border border-slate-700 px-2 py-1 hover:border-sky-500 hover:text-sky-400 transition-colors">
                                    Cinema
                                </button>
                            </div>
                        </form>

                        <dl class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-md text-xs">
                            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 px-3 py-2.5 transition-transform duration-300 hover:-translate-y-1 hover:border-sky-500/70">
                                <dt class="text-[11px] text-slate-400">Tickets processed</dt>
                                <dd class="mt-1 text-lg font-semibold text-slate-50">120K+</dd>
                            </div>
                            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 px-3 py-2.5 transition-transform duration-300 hover:-translate-y-1 hover:border-sky-500/70">
                                <dt class="text-[11px] text-slate-400">Live events</dt>
                                <dd class="mt-1 text-lg font-semibold text-slate-50">340</dd>
                            </div>
                            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 px-3 py-2.5 transition-transform duration-300 hover:-translate-y-1 hover:border-emerald-500/70">
                                <dt class="text-[11px] text-slate-400">Avg. check-in time</dt>
                                <dd class="mt-1 text-lg font-semibold text-emerald-400">3s</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="relative">
                        <div class="absolute -inset-6 rounded-3xl bg-gradient-to-tr from-sky-500/20 via-indigo-500/10 to-emerald-400/10 blur-2xl animate-[slowPulse_6s_ease-in-out_infinite]"></div>
                        <div class="relative rounded-3xl border border-slate-800 bg-slate-900/80 p-4 shadow-2xl shadow-sky-500/10 transition-transform duration-500 hover:-translate-y-2 hover:shadow-sky-500/30">
                            <div class="flex items-center justify-between gap-2 pb-4">
                                <div>
                                    <p class="text-[11px] font-medium text-slate-300">Tonight at the Arena</p>
                                    <p class="text-sm font-semibold text-slate-50">Summer Lights Festival</p>
                                </div>
                                <span class="rounded-full bg-emerald-500/10 px-2 py-1 text-[10px] font-semibold text-emerald-400">
                                    82% sold
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-3 pb-4 text-[11px]">
                                <div class="rounded-2xl border border-slate-800 bg-slate-950/70 px-3 py-2">
                                    <div class="text-slate-400">Tickets sold</div>
                                    <div class="mt-1 text-sm font-semibold text-slate-50">4,120</div>
                                </div>
                                <div class="rounded-2xl border border-slate-800 bg-slate-950/70 px-3 py-2">
                                    <div class="text-slate-400">Revenue</div>
                                    <div class="mt-1 text-sm font-semibold text-slate-50">$184,600</div>
                                </div>
                                <div class="rounded-2xl border border-slate-800 bg-slate-950/70 px-3 py-2">
                                    <div class="text-slate-400">Check-ins</div>
                                    <div class="mt-1 text-sm font-semibold text-emerald-400">1,983</div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-3 space-y-3">
                                <div class="flex items-center justify-between text-[11px] text-slate-300">
                                    <span>Gate scanners</span>
                                    <span class="inline-flex items-center gap-1 text-emerald-400">
                                        Live
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-[10px]">
                                    <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-2">
                                        <div class="text-slate-400">North Gate</div>
                                        <div class="mt-1 font-semibold text-slate-50">732</div>
                                        <div class="text-[9px] text-emerald-400">OK</div>
                                    </div>
                                    <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-2">
                                        <div class="text-slate-400">South Gate</div>
                                        <div class="mt-1 font-semibold text-slate-50">611</div>
                                        <div class="text-[9px] text-emerald-400">OK</div>
                                    </div>
                                    <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-2">
                                        <div class="text-slate-400">VIP</div>
                                        <div class="mt-1 font-semibold text-slate-50">140</div>
                                        <div class="text-[9px] text-amber-400">Queue</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="border-b border-slate-800 bg-slate-950">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-slate-50">
                                Browse by category
                            </h2>
                            <p class="mt-1 text-xs text-slate-400">
                                From stadium shows to intimate workshops, find tickets in a click.
                            </p>
                        </div>
                        <button type="button" class="inline-flex items-center gap-1 rounded-full border border-slate-700 px-3 py-1.5 text-[11px] font-medium text-slate-200 hover:border-sky-500 hover:text-sky-400 transition-colors">
                            View all categories
                        </button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 text-xs">
                        <article class="relative rounded-2xl border border-slate-800 bg-gradient-to-br from-sky-500/10 via-slate-950 to-slate-950 p-4 transition-transform duration-300 hover:-translate-y-2 hover:border-sky-500/70 hover:shadow-xl hover:shadow-sky-500/30">
                            <div class="mb-3 inline-flex items-center justify-center h-9 w-9 rounded-xl bg-sky-500/20 text-sky-300 text-sm">
                                ♪
                            </div>
                            <h3 class="text-sm font-semibold text-slate-50">Concerts & Festivals</h3>
                            <p class="mt-1 text-[11px] text-slate-400">
                                Manage arena shows, club gigs, and outdoor festivals with real-time capacity.
                            </p>
                            <p class="mt-3 text-[11px] text-sky-300">
                                3,200+ events
                            </p>
                        </article>

                        <article class="relative rounded-2xl border border-slate-800 bg-gradient-to-br from-emerald-500/10 via-slate-950 to-slate-950 p-4 transition-transform duration-300 hover:-translate-y-2 hover:border-emerald-500/70 hover:shadow-xl hover:shadow-emerald-500/30">
                            <div class="mb-3 inline-flex items-center justify-center h-9 w-9 rounded-xl bg-emerald-500/20 text-emerald-300 text-sm">
                                ⚽
                            </div>
                            <h3 class="text-sm font-semibold text-slate-50">Sports & Leagues</h3>
                            <p class="mt-1 text-[11px] text-slate-400">
                                From local leagues to derby days, control seating and entry flows effortlessly.
                            </p>
                            <p class="mt-3 text-[11px] text-emerald-300">
                                1,100+ fixtures
                            </p>
                        </article>

                        <article class="relative rounded-2xl border border-slate-800 bg-gradient-to-br from-fuchsia-500/10 via-slate-950 to-slate-950 p-4 transition-transform duration-300 hover:-translate-y-2 hover:border-fuchsia-500/70 hover:shadow-xl hover:shadow-fuchsia-500/30">
                            <div class="mb-3 inline-flex items-center justify-center h-9 w-9 rounded-xl bg-fuchsia-500/20 text-fuchsia-300 text-sm">
                                🎭
                            </div>
                            <h3 class="text-sm font-semibold text-slate-50">Theatre & Cinema</h3>
                            <p class="mt-1 text-[11px] text-slate-400">
                                Seat-level control, showtime schedules, and mobile passes for every guest.
                            </p>
                            <p class="mt-3 text-[11px] text-fuchsia-300">
                                850+ venues
                            </p>
                        </article>

                        <article class="relative rounded-2xl border border-slate-800 bg-gradient-to-br from-amber-500/10 via-slate-950 to-slate-950 p-4 transition-transform duration-300 hover:-translate-y-2 hover:border-amber-500/70 hover:shadow-xl hover:shadow-amber-500/30">
                            <div class="mb-3 inline-flex items-center justify-center h-9 w-9 rounded-xl bg-amber-500/20 text-amber-300 text-sm">
                                💼
                            </div>
                            <h3 class="text-sm font-semibold text-slate-50">Conferences & Workshops</h3>
                            <p class="mt-1 text-[11px] text-slate-400">
                                Multi-day passes, parallel tracks, and badge scanning built in.
                            </p>
                            <p class="mt-3 text-[11px] text-amber-300">
                                640+ organizers
                            </p>
                        </article>
                    </div>
                </div>
            </section>

            <section class="border-b border-slate-800 bg-slate-950">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-slate-50">
                                Featured events
                            </h2>
                            <p class="mt-1 text-xs text-slate-400">
                                A snapshot of what a guest sees when browsing your events.
                            </p>
                        </div>
                        <button type="button" class="inline-flex items-center gap-1 rounded-full border border-slate-700 px-3 py-1.5 text-[11px] font-medium text-slate-200 hover:border-sky-500 hover:text-sky-400 transition-colors">
                            View all events
                        </button>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 text-xs">
                        <article class="group rounded-2xl border border-slate-800 bg-slate-950 hover:border-sky-500/70 hover:bg-slate-900/80 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:shadow-sky-500/30">
                            <div class="h-28 rounded-t-2xl bg-gradient-to-br from-fuchsia-500 via-sky-500 to-emerald-400"></div>
                            <div class="p-4 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="text-sm font-semibold text-slate-50 group-hover:text-sky-300">
                                        Neon Nights Tour
                                    </h3>
                                    <span class="rounded-full bg-slate-800 px-2 py-1 text-[9px] text-slate-300">
                                        Concert
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400">
                                    Sat, 12 April • City Arena • 8:00 PM
                                </p>
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-sky-300 font-medium">
                                        From $39
                                    </span>
                                    <span class="text-slate-400">
                                        1,240 / 1,600 sold
                                    </span>
                                </div>
                            </div>
                        </article>

                        <article class="group rounded-2xl border border-slate-800 bg-slate-950 hover:border-sky-500/70 hover:bg-slate-900/80 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:shadow-sky-500/30">
                            <div class="h-28 rounded-t-2xl bg-gradient-to-br from-amber-500 via-rose-500 to-purple-500"></div>
                            <div class="p-4 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="text-sm font-semibold text-slate-50 group-hover:text-sky-300">
                                        Product Makers Summit
                                    </h3>
                                    <span class="rounded-full bg-slate-800 px-2 py-1 text-[9px] text-slate-300">
                                        Conference
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400">
                                    3-day pass • Downtown Convention Center
                                </p>
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-sky-300 font-medium">
                                        From $89
                                    </span>
                                    <span class="text-slate-400">
                                        620 / 900 sold
                                    </span>
                                </div>
                            </div>
                        </article>

                        <article class="group rounded-2xl border border-slate-800 bg-slate-950 hover:border-sky-500/70 hover:bg-slate-900/80 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:shadow-sky-500/30">
                            <div class="h-28 rounded-t-2xl bg-gradient-to-br from-sky-500 via-indigo-500 to-slate-900"></div>
                            <div class="p-4 space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="text-sm font-semibold text-slate-50 group-hover:text-sky-300">
                                        Midnight Premiere
                                    </h3>
                                    <span class="rounded-full bg-slate-800 px-2 py-1 text-[9px] text-slate-300">
                                        Cinema
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400">
                                    Fri, 28 March • Grand Cinema Hall 4
                                </p>
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-sky-300 font-medium">
                                        From $12
                                    </span>
                                    <span class="text-slate-400">
                                        210 / 260 sold
                                    </span>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section class="bg-slate-950">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 grid gap-8 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] items-center">
                    <div class="space-y-4">
                        <h2 class="text-base sm:text-lg font-semibold text-slate-50">
                            Designed for teams at the gate and at the desk
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-400 max-w-xl">
                            Give your staff fast scanning, simple seat changes, and clear dashboards while your guests enjoy secure, mobile-friendly tickets that just work.
                        </p>
                        <dl class="grid gap-4 sm:grid-cols-2 text-xs">
                            <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4 space-y-1.5">
                                <dt class="font-semibold text-slate-50">Real-time check-in data</dt>
                                <dd class="text-[11px] text-slate-400">
                                    Monitor capacity, queues, and late arrivals from a single unified view.
                                </dd>
                            </div>
                            <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4 space-y-1.5">
                                <dt class="font-semibold text-slate-50">Flexible ticket types</dt>
                                <dd class="text-[11px] text-slate-400">
                                    Early-bird, VIP, staff, sponsor, and more with custom limits and pricing.
                                </dd>
                            </div>
                            <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4 space-y-1.5">
                                <dt class="font-semibold text-slate-50">Secure QR codes</dt>
                                <dd class="text-[11px] text-slate-400">
                                    Unique, time-bound QR tickets to prevent fraudulent re-use at the entrance.
                                </dd>
                            </div>
                            <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4 space-y-1.5">
                                <dt class="font-semibold text-slate-50">Exports & reporting</dt>
                                <dd class="text-[11px] text-slate-400">
                                    Download attendee lists, financial summaries, and settlement reports in seconds.
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-3xl border border-dashed border-slate-800 bg-slate-950/60 p-5 space-y-4 transition-transform duration-300 hover:-translate-y-2 hover:border-sky-500/70 hover:shadow-xl hover:shadow-sky-500/30">
                        <h3 class="text-sm font-semibold text-slate-50">
                            Ready to launch your next event?
                        </h3>
                        <p class="text-xs text-slate-400">
                            Create a free organizer account and set up your first event today. No setup fees, no contracts, and you only pay per ticket sold.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="#" class="inline-flex flex-1 items-center justify-center rounded-xl bg-sky-500 px-3 py-2 text-[11px] sm:text-xs font-semibold text-slate-950 hover:bg-sky-400 transition-colors">
                                Create organizer account
                            </a>
                            <button type="button" class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-700 px-3 py-2 text-[11px] sm:text-xs font-semibold text-slate-200 hover:border-sky-500 hover:text-sky-400 transition-colors">
                                Book a demo
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-500">
                            Trusted by teams running concerts, sports fixtures, film festivals, and community events worldwide.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-800 bg-slate-950">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-slate-500">
                <div class="flex items-center gap-2">
                    <span class="h-6 w-6 rounded-lg bg-slate-900 flex items-center justify-center text-[10px] font-semibold text-slate-200">
                        TS
                    </span>
                    <span>© {{ date('Y') }} Ticketing System. All rights reserved.</span>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="#" class="hover:text-sky-400 transition-colors">Terms</a>
                    <a href="#" class="hover:text-sky-400 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-sky-400 transition-colors">Status</a>
                </div>
            </div>
        </footer>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggle = document.getElementById('mobile-menu-toggle');
            var menu = document.getElementById('mobile-menu');
            if (toggle && menu) {
                toggle.addEventListener('click', function () {
                    menu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
