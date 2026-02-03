<!DOCTYPE html>
<html lang="en" class="admin-theme" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') • Ticketing System</title>
    <script>
        (() => {
            const storageKey = 'admin_theme';
            let theme = 'dark';
            try {
                theme = localStorage.getItem(storageKey) || 'dark';
            } catch (e) {}
            if (theme !== 'light' && theme !== 'dark') {
                theme = 'dark';
            }
            document.documentElement.dataset.theme = theme;
        })();
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #sidebar {
            transition: transform 300ms, width 300ms;
        }

        @media (min-width: 1024px) {
            .sidebar-collapsed #sidebar {
                width: 5rem;
            }

            .sidebar-collapsed #sidebar .sidebar-logo {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }

            .sidebar-collapsed #sidebar .sidebar-logo-text,
            .sidebar-collapsed #sidebar .sidebar-label,
            .sidebar-collapsed #sidebar .sidebar-section-title,
            .sidebar-collapsed #sidebar .sidebar-user-details {
                display: none;
            }

            .sidebar-collapsed #sidebar .sidebar-link {
                justify-content: center;
                gap: 0;
            }

            .sidebar-collapsed #sidebar .sidebar-user {
                justify-content: center;
            }
        }
    </style>
</head>
@include('layouts.alerts')
<body class="bg-slate-950 text-slate-100 font-sans antialiased">
    <div class="min-h-screen flex relative">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-950 border-r border-slate-800 transform -translate-x-full lg:translate-x-0 lg:static lg:flex flex-col transition-transform duration-300">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800/50">
                <div class="sidebar-logo flex items-center gap-3">
                    <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-indigo-500 via-sky-500 to-emerald-400 flex items-center justify-center text-xs font-bold tracking-tight text-white shadow-lg shadow-sky-500/20">
                        TS
                    </div>
                    <span class="sidebar-logo-text font-bold text-lg tracking-tight">Admin<span class="text-sky-400">Panel</span></span>
                </div>
                <button id="sidebar-close" class="lg:hidden text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-6 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-sky-500/10 text-sky-400' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50 group' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center {{ request()->routeIs('admin.dashboard') ? '' : 'group-hover:text-sky-400 transition-colors' }}"></i>
                    <span class="sidebar-label">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.tickets') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.tickets') ? 'bg-sky-500/10 text-sky-400' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50 group' }}">
                    <i class="fa-solid fa-ticket w-5 text-center {{ request()->routeIs('admin.tickets') ? '' : 'group-hover:text-sky-400 transition-colors' }}"></i>
                    <span class="sidebar-label">Ticket Management</span>
                </a>

                <a href="{{ route('admin.users') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.users') ? 'bg-sky-500/10 text-sky-400' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50 group' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center {{ request()->routeIs('admin.users') ? '' : 'group-hover:text-sky-400 transition-colors' }}"></i>
                    <span class="sidebar-label">User Management</span>
                </a>

                <div class="pt-6 pb-2">
                    <p class="sidebar-section-title px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">System</p>
                </div>
                
                <a href="{{ route('admin.settings') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.settings') ? 'bg-sky-500/10 text-sky-400' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50 group' }}">
                    <i class="fa-solid fa-gear w-5 text-center {{ request()->routeIs('admin.settings') ? '' : 'group-hover:text-sky-400 transition-colors' }}"></i>
                    <span class="sidebar-label">Settings</span>
                </a>
            </nav>

            <!-- User Profile (Bottom Sidebar) -->
            <div class="p-4 border-t border-slate-800/50">
                <div class="sidebar-user flex items-center gap-3 px-3 py-2">
                    <div class="h-9 w-9 rounded-full bg-slate-800 flex items-center justify-center text-slate-400">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="sidebar-user-details flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-200 truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <div id="sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-slate-950/60 backdrop-blur-sm lg:hidden"></div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-950">
            <!-- Header (Mobile Toggle & Top Actions) -->
            <header class="h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 border-b border-slate-800/50 bg-slate-950/80 backdrop-blur sticky top-0 z-20">
                <div class="flex items-center gap-2">
                    <button id="sidebar-toggle" class="lg:hidden p-2 text-slate-400 hover:text-white">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <button id="sidebar-collapse" type="button" class="hidden lg:inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-800 text-slate-400 hover:text-slate-100 hover:bg-slate-800/50 transition-all">
                        <i class="fa-solid fa-angles-left sidebar-collapse-icon-expanded"></i>
                        <i class="fa-solid fa-angles-right sidebar-collapse-icon-collapsed hidden"></i>
                    </button>
                </div>

                <div class="flex items-center gap-4 ml-auto">
                    <button class="p-2 text-slate-400 hover:text-sky-400 transition-colors relative">
                        <i class="fa-regular fa-bell"></i>
                        <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                    </button>
                    <div class="h-6 w-px bg-slate-800"></div>
                    <button id="admin-theme-toggle" type="button" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-800 text-slate-400 hover:text-slate-100 hover:bg-slate-800/50 transition-all" aria-label="Toggle theme">
                        <svg class="admin-theme-icon-sun h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z" />
                            <path d="M12 2v2" />
                            <path d="M12 20v2" />
                            <path d="M4.93 4.93l1.41 1.41" />
                            <path d="M17.66 17.66l1.41 1.41" />
                            <path d="M2 12h2" />
                            <path d="M20 12h2" />
                            <path d="M4.93 19.07l1.41-1.41" />
                            <path d="M17.66 6.34l1.41-1.41" />
                        </svg>
                        <svg class="admin-theme-icon-moon h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z" />
                        </svg>
                    </button>
                    <a href="{{ route('logout') }}" class="text-sm font-medium text-slate-400 hover:text-rose-400 transition-colors">
                        Logout
                    </a>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const sidebarCollapse = document.getElementById('sidebar-collapse');
        const collapseIconExpanded = document.querySelector('.sidebar-collapse-icon-expanded');
        const collapseIconCollapsed = document.querySelector('.sidebar-collapse-icon-collapsed');
        const collapseStorageKey = 'admin_sidebar_collapsed';
        const desktopMq = window.matchMedia('(min-width: 1024px)');
        const themeStorageKey = 'admin_theme';
        const themeToggle = document.getElementById('admin-theme-toggle');
        const themeIconSun = document.querySelector('.admin-theme-icon-sun');
        const themeIconMoon = document.querySelector('.admin-theme-icon-moon');

        const setCollapsedState = (collapsed, { persist = true } = {}) => {
            document.body.classList.toggle('sidebar-collapsed', collapsed);
            if (persist) {
                try {
                    localStorage.setItem(collapseStorageKey, collapsed ? '1' : '0');
                } catch (e) {}
            }

            if (collapseIconExpanded && collapseIconCollapsed) {
                if (collapsed) {
                    collapseIconExpanded.classList.add('hidden');
                    collapseIconCollapsed.classList.remove('hidden');
                } else {
                    collapseIconCollapsed.classList.add('hidden');
                    collapseIconExpanded.classList.remove('hidden');
                }
            }
        };

        const loadCollapsedState = () => {
            let collapsed = false;
            try {
                collapsed = localStorage.getItem(collapseStorageKey) === '1';
            } catch (e) {}
            setCollapsedState(desktopMq.matches && collapsed, { persist: false });
        };

        const setTheme = (theme, { persist = true } = {}) => {
            if (theme !== 'light' && theme !== 'dark') {
                theme = 'dark';
            }
            document.documentElement.dataset.theme = theme;
            if (persist) {
                try {
                    localStorage.setItem(themeStorageKey, theme);
                } catch (e) {}
            }
            if (themeIconSun && themeIconMoon) {
                if (theme === 'light') {
                    themeIconSun.classList.add('hidden');
                    themeIconMoon.classList.remove('hidden');
                } else {
                    themeIconMoon.classList.add('hidden');
                    themeIconSun.classList.remove('hidden');
                }
            }
            if (themeToggle) {
                themeToggle.setAttribute('aria-label', theme === 'light' ? 'Switch to dark theme' : 'Switch to light theme');
            }
        };

        const loadTheme = () => {
            let theme = document.documentElement.dataset.theme || 'dark';
            try {
                theme = localStorage.getItem(themeStorageKey) || theme;
            } catch (e) {}
            setTheme(theme, { persist: false });
        };

        const openSidebar = () => {
            if (!sidebar) return;
            sidebar.classList.remove('-translate-x-full');
            if (sidebarBackdrop) sidebarBackdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeSidebar = () => {
            if (!sidebar) return;
            sidebar.classList.add('-translate-x-full');
            if (sidebarBackdrop) sidebarBackdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                openSidebar();
            });
        }

        if (sidebarClose) {
            sidebarClose.addEventListener('click', () => {
                closeSidebar();
            });
        }

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', () => {
                closeSidebar();
            });
        }

        if (sidebar) {
            sidebar.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (!desktopMq.matches) {
                        closeSidebar();
                    }
                });
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;
            if (desktopMq.matches) return;
            if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });

        if (sidebarCollapse) {
            sidebarCollapse.addEventListener('click', () => {
                setCollapsedState(!document.body.classList.contains('sidebar-collapsed'));
            });
        }

        if (desktopMq && typeof desktopMq.addEventListener === 'function') {
            desktopMq.addEventListener('change', () => {
                loadCollapsedState();
                if (desktopMq.matches) {
                    closeSidebar();
                }
            });
        } else if (desktopMq && typeof desktopMq.addListener === 'function') {
            desktopMq.addListener(() => {
                loadCollapsedState();
                if (desktopMq.matches) {
                    closeSidebar();
                }
            });
        }

        loadCollapsedState();
        loadTheme();

        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const nextTheme = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
                setTheme(nextTheme);
            });
        }
    </script>
</body>
</html>
