@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-100 tracking-tight">User Management</h1>
            <p class="text-sm text-slate-400 mt-1">Manage system users, roles, and permissions.</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <button id="create-user-btn" type="button" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-500 text-white text-sm font-bold hover:bg-sky-400 transition-colors shadow-lg shadow-sky-500/20">
                <i class="fa-solid fa-user-plus"></i>
                <span>Add User</span>
            </button>
        </div>
    </div>

    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <form action="{{ route('admin.users') }}" method="GET" class="relative w-full sm:flex-1 sm:max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-500"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search users..." class="w-full bg-slate-900 border border-slate-800 rounded-xl py-3 pl-11 pr-12 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600">
            <button type="submit" class="absolute right-2 top-1 p-2 text-slate-500 hover:text-slate-200 transition-colors">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    @php
        $roleBadges = [
            'admin' => ['label' => 'Administrator', 'class' => 'bg-purple-500/10 text-purple-400 border border-purple-500/20', 'icon' => 'fa-shield-halved'],
            'employee' => ['label' => 'Employee', 'class' => 'bg-sky-500/10 text-sky-400 border border-sky-500/20', 'icon' => 'fa-user-gear'],
        ];
    @endphp

    <div class="rounded-2xl border border-slate-800 bg-slate-900/30 overflow-hidden">
        <div class="sm:hidden divide-y divide-slate-800/50">
            @forelse ($users as $user)
                @php
                    $userType = $user->user_type ?: 'employee';
                    $roleBadge = $roleBadges[$userType] ?? ['label' => ucfirst($userType), 'class' => 'bg-slate-800 text-slate-400 border border-slate-700', 'icon' => 'fa-user'];

                    $nameParts = preg_split('/\s+/', trim($user->name ?: 'User')) ?: [];
                    $initials = '';
                    foreach (array_slice($nameParts, 0, 2) as $part) {
                        $initials .= strtoupper(mb_substr($part, 0, 1));
                    }
                    $initials = $initials ?: 'U';
                @endphp
                <div class="p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-slate-800 flex items-center justify-center text-xs text-slate-300 shrink-0">{{ $initials }}</div>
                                <div class="min-w-0">
                                    <div class="font-medium text-slate-200 truncate">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500 truncate">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $roleBadge['class'] }}">
                                    <i class="fa-solid {{ $roleBadge['icon'] }} text-[10px]"></i> {{ $roleBadge['label'] }}
                                </span>
                                <span class="text-xs text-slate-500">{{ $user->created_at?->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-800 text-slate-300 hover:bg-slate-800/40 hover:text-sky-300 transition-colors edit-user-btn"
                                data-user-id="{{ $user->id }}"
                                data-user-name="{{ $user->name }}"
                                data-user-email="{{ $user->email }}"
                                data-user-type="{{ $userType }}"
                                data-update-url="{{ route('admin.users.update', $user) }}"
                                data-activity-url="{{ route('admin.users.activity', $user) }}"
                                aria-label="Edit user"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-10 text-center text-sm text-slate-500">
                    No users found.
                </div>
            @endforelse
        </div>

        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm text-slate-400">
                <thead class="bg-slate-900/50 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="px-4 sm:px-6 py-4">User</th>
                        <th class="px-4 sm:px-6 py-4">Role</th>
                        <th class="px-4 sm:px-6 py-4">Joined Date</th>
                        <th class="px-4 sm:px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse ($users as $user)
                        @php
                            $userType = $user->user_type ?: 'employee';
                            $roleBadge = $roleBadges[$userType] ?? ['label' => ucfirst($userType), 'class' => 'bg-slate-800 text-slate-400 border border-slate-700', 'icon' => 'fa-user'];

                            $nameParts = preg_split('/\s+/', trim($user->name ?: 'User')) ?: [];
                            $initials = '';
                            foreach (array_slice($nameParts, 0, 2) as $part) {
                                $initials .= strtoupper(mb_substr($part, 0, 1));
                            }
                            $initials = $initials ?: 'U';
                        @endphp
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center text-xs text-slate-300">{{ $initials }}</div>
                                    <div>
                                        <div class="font-medium text-slate-200">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $roleBadge['class'] }}">
                                    <i class="fa-solid {{ $roleBadge['icon'] }} text-[10px]"></i> {{ $roleBadge['label'] }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4">{{ $user->created_at?->format('M d, Y') }}</td>
                            <td class="px-4 sm:px-6 py-4 text-right">
                                <button
                                    type="button"
                                    class="text-slate-400 hover:text-sky-400 transition-colors mx-1 edit-user-btn"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    data-user-email="{{ $user->email }}"
                                    data-user-type="{{ $userType }}"
                                    data-update-url="{{ route('admin.users.update', $user) }}"
                                    data-activity-url="{{ route('admin.users.activity', $user) }}"
                                    aria-label="Edit user"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 sm:px-6 py-10 text-center text-sm text-slate-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 sm:px-6 py-4 border-t border-slate-800/50">
            {{ $users->links() }}
        </div>
    </div>

    <div id="create-user-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="create-user-modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity opacity-0" id="create-user-modal-backdrop"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto py-4">
            <div class="flex min-h-full items-center justify-center p-4 sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-slate-900 border border-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="create-user-modal-panel">
                    <div class="px-6 py-5 border-b border-slate-800/50 flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <h3 class="text-lg font-semibold text-slate-100" id="create-user-modal-title">Add New User</h3>
                            <p class="mt-1 text-sm text-slate-400">Create an employee account.</p>
                        </div>
                        <button type="button" class="shrink-0 inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-800 text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors" id="close-create-user-modal-btn" aria-label="Close">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.users.store') }}" method="POST" class="px-6 py-6 space-y-5">
                        @csrf

                        <div class="space-y-2">
                            <label for="new-user-name" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Name</label>
                            <input type="text" name="name" id="new-user-name" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="Full name" required>
                        </div>

                        <div class="space-y-2">
                            <label for="new-user-email" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Email</label>
                            <input type="email" name="email" id="new-user-email" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="name@example.com" required>
                        </div>

                        <div class="space-y-2">
                            <label for="new-user-type" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">User Type</label>
                            <select name="user_type" id="new-user-type" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                                <option value="employee" selected>Employee</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="new-user-password" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Password</label>
                                <input type="password" name="password" id="new-user-password" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="Min 8 characters" required>
                            </div>
                            <div class="space-y-2">
                                <label for="new-user-password-confirm" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="new-user-password-confirm" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="Repeat password" required>
                            </div>
                        </div>

                        <div class="pt-5 mt-1 border-t border-slate-800/50 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                            <button id="cancel-create-user-modal-btn" type="button" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 rounded-xl border border-slate-800 text-slate-200 hover:bg-slate-800/30 transition-colors text-sm font-semibold">
                                Cancel
                            </button>
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-400 transition-colors text-sm font-bold shadow-lg shadow-sky-500/20">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-user-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="edit-user-modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity opacity-0" id="edit-user-modal-backdrop"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto py-4">
            <div class="flex min-h-full items-center justify-center p-4 sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-slate-900 border border-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="edit-user-modal-panel">
                    <div class="px-6 py-5 border-b border-slate-800/50 flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <h3 class="text-lg font-semibold text-slate-100" id="edit-user-modal-title">Edit User</h3>
                            <p class="mt-1 text-sm text-slate-400" id="edit-user-modal-subtitle">Update user details.</p>
                        </div>
                        <button type="button" class="shrink-0 inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-800 text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors" id="close-edit-user-modal-btn" aria-label="Close">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    <div class="px-6 py-6 grid gap-6 lg:grid-cols-2">
                        <form id="edit-user-form" action="#" method="POST" class="space-y-5">
                            @csrf
                            @method('PATCH')

                            <div class="space-y-2">
                                <label for="edit-user-name" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Name</label>
                                <input type="text" name="name" id="edit-user-name" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="Full name" required>
                            </div>

                            <div class="space-y-2">
                                <label for="edit-user-email" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Email</label>
                                <input type="email" name="email" id="edit-user-email" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="name@example.com" required>
                            </div>

                            <div class="space-y-2">
                                <label for="edit-user-type" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">User Type</label>
                                <select name="user_type" id="edit-user-type" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                                    <option value="employee">Employee</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="edit-user-password" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">New Password</label>
                                    <input type="password" name="password" id="edit-user-password" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="Leave blank to keep">
                                </div>
                                <div class="space-y-2">
                                    <label for="edit-user-password-confirm" class="block text-xs font-medium text-slate-400 uppercase tracking-wide">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="edit-user-password-confirm" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all placeholder:text-slate-600" placeholder="Repeat password">
                                </div>
                            </div>

                            <div class="pt-5 mt-1 border-t border-slate-800/50 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                                <button id="cancel-edit-user-modal-btn" type="button" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 rounded-xl border border-slate-800 text-slate-200 hover:bg-slate-800/30 transition-colors text-sm font-semibold">
                                    Cancel
                                </button>
                                <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-400 transition-colors text-sm font-bold shadow-lg shadow-sky-500/20">
                                    Save Changes
                                </button>
                            </div>
                        </form>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-800 bg-slate-950/50">
                                <div class="min-w-0">
                                    <h3 class="text-sm font-medium text-slate-200">User Status</h3>
                                    <p class="text-xs text-slate-500 mt-0.5" id="employee-login-status">Loading...</p>
                                </div>
                                <span id="login-indicator" class="h-3 w-3 rounded-full bg-slate-800"></span>
                            </div>

                            <div class="p-4 rounded-xl border border-slate-800 bg-slate-950/50">
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <h3 class="text-sm font-medium text-slate-200">Recent Activity</h3>
                                    <span class="text-[11px] text-slate-500" id="employee-activity-meta"></span>
                                </div>
                                <div id="employee-activity-log" class="space-y-2 max-h-64 overflow-y-auto pr-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('create-user-modal');
            const backdrop = document.getElementById('create-user-modal-backdrop');
            const panel = document.getElementById('create-user-modal-panel');

            const openBtn = document.getElementById('create-user-btn');
            const closeBtn = document.getElementById('close-create-user-modal-btn');
            const cancelBtn = document.getElementById('cancel-create-user-modal-btn');

            function openModal() {
                if (!modal || !backdrop || !panel) {
                    return;
                }

                modal.classList.remove('hidden');
                void modal.offsetWidth;

                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }

            function closeModal() {
                if (!modal || !backdrop || !panel) {
                    return;
                }

                backdrop.classList.add('opacity-0');
                panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
                panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            if (openBtn) openBtn.addEventListener('click', openModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

            if (modal && backdrop) {
                modal.addEventListener('click', (e) => {
                    if (e.target === backdrop) {
                        closeModal();
                    }
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('edit-user-modal');
            const backdrop = document.getElementById('edit-user-modal-backdrop');
            const panel = document.getElementById('edit-user-modal-panel');
            const closeBtn = document.getElementById('close-edit-user-modal-btn');
            const cancelBtn = document.getElementById('cancel-edit-user-modal-btn');
            const form = document.getElementById('edit-user-form');
            const nameInput = document.getElementById('edit-user-name');
            const emailInput = document.getElementById('edit-user-email');
            const typeSelect = document.getElementById('edit-user-type');
            const passwordInput = document.getElementById('edit-user-password');
            const passwordConfirmInput = document.getElementById('edit-user-password-confirm');
            const subtitle = document.getElementById('edit-user-modal-subtitle');
            const statusText = document.getElementById('employee-login-status');
            const indicator = document.getElementById('login-indicator');
            const activityLog = document.getElementById('employee-activity-log');
            const activityMeta = document.getElementById('employee-activity-meta');

            let activityUrl = null;
            let pollTimer = null;

            const pad2 = (n) => String(n).padStart(2, '0');

            const escapeHtml = (value) => {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const formatDateTime = (value) => {
                if (!value) return 'Never';
                const parsed = new Date(value);
                if (!Number.isNaN(parsed.getTime())) {
                    return `${parsed.getFullYear()}-${pad2(parsed.getMonth() + 1)}-${pad2(parsed.getDate())} ${pad2(parsed.getHours())}:${pad2(parsed.getMinutes())}:${pad2(parsed.getSeconds())}`;
                }
                return String(value);
            };

            const setOnline = (online) => {
                if (!indicator) return;
                indicator.classList.remove('bg-slate-800', 'bg-emerald-500', 'bg-rose-500');
                indicator.classList.add(online ? 'bg-emerald-500' : 'bg-rose-500');
            };

            const clearActivityUI = () => {
                if (statusText) statusText.textContent = 'Loading...';
                if (activityMeta) activityMeta.textContent = '';
                if (activityLog) activityLog.innerHTML = '';
                setOnline(false);
            };

            const renderActivities = (activities) => {
                if (!activityLog) return;
                if (!Array.isArray(activities) || activities.length === 0) {
                    activityLog.innerHTML = `<div class="text-xs text-slate-500">No activity yet.</div>`;
                    return;
                }

                activityLog.innerHTML = activities
                    .map((item) => {
                        const method = (item?.method || '').toString().toUpperCase();
                        const path = (item?.path || '').toString();
                        const action = (item?.action || '').toString();
                        const methodLabel = 'POST';
                        const pathLabel = (() => {
                            const raw = (path || '—').toString();
                            const withoutQuery = raw.split('?')[0].split('#')[0];
                            const trimmed = withoutQuery.replace(/\/+$/g, '');
                            const parts = trimmed.split('/').filter(Boolean);
                            return parts.length > 0 ? parts[parts.length - 1] : trimmed || raw || '—';
                        })();
                        const createdAt = formatDateTime(item?.created_at);
                        return `
                            <div class="flex items-start justify-between gap-3 rounded-xl border border-slate-800 bg-slate-950/40 px-3 py-2">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900 px-2 py-0.5 text-[10px] font-semibold text-slate-300">${escapeHtml(methodLabel)}</span>
                                        <span class="text-xs text-slate-300 break-all">${escapeHtml(action || pathLabel || '—')}</span>
                                    </div>
                                    <div class="mt-1 text-[11px] text-slate-500">${escapeHtml(pathLabel)} • ${escapeHtml(createdAt)}</div>
                                </div>
                            </div>
                        `;
                    })
                    .join('');
            };

            const pollActivity = async () => {
                if (!activityUrl) return;
                try {
                    const res = await fetch(activityUrl, {
                        headers: { 'Accept': 'application/json' },
                    });
                    if (!res.ok) {
                        throw new Error('Request failed');
                    }
                    const data = await res.json();
                    const online = !!data?.online;
                    const lastSeenAt = data?.last_seen_at;
                    const activities = data?.activities || [];

                    setOnline(online);
                    if (statusText) {
                        statusText.textContent = `${online ? 'Online' : 'Offline'} • Last active: ${formatDateTime(lastSeenAt)}`;
                    }
                    if (activityMeta) {
                        activityMeta.textContent = `Showing ${Array.isArray(activities) ? activities.length : 0} latest`;
                    }
                    renderActivities(activities);
                } catch (e) {
                    if (statusText) statusText.textContent = 'Unable to load status.';
                    if (activityMeta) activityMeta.textContent = '';
                    setOnline(false);
                    if (activityLog) activityLog.innerHTML = `<div class="text-xs text-slate-500">Unable to load activity.</div>`;
                }
            };

            function openModal() {
                if (!modal || !backdrop || !panel) {
                    return;
                }

                modal.classList.remove('hidden');
                void modal.offsetWidth;

                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }

            function closeModal() {
                if (!modal || !backdrop || !panel) {
                    return;
                }

                backdrop.classList.add('opacity-0');
                panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
                panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

                if (pollTimer) {
                    clearInterval(pollTimer);
                    pollTimer = null;
                }
                activityUrl = null;

                setTimeout(() => {
                    modal.classList.add('hidden');
                    clearActivityUI();
                    if (form) form.setAttribute('action', '#');
                    if (nameInput) nameInput.value = '';
                    if (emailInput) emailInput.value = '';
                    if (typeSelect) typeSelect.value = 'employee';
                    if (passwordInput) passwordInput.value = '';
                    if (passwordConfirmInput) passwordConfirmInput.value = '';
                    if (subtitle) subtitle.textContent = 'Update user details.';
                }, 300);
            }

            const openForUserButton = (btn) => {
                const updateUrl = btn?.dataset?.updateUrl || null;
                activityUrl = btn?.dataset?.activityUrl || null;
                const name = btn?.dataset?.userName || '';
                const email = btn?.dataset?.userEmail || '';
                const userType = btn?.dataset?.userType || 'employee';

                if (form && updateUrl) form.setAttribute('action', updateUrl);
                if (nameInput) nameInput.value = name;
                if (emailInput) emailInput.value = email;
                if (typeSelect) typeSelect.value = userType;
                if (passwordInput) passwordInput.value = '';
                if (passwordConfirmInput) passwordConfirmInput.value = '';
                if (subtitle) subtitle.textContent = email ? `Editing ${email}` : 'Update user details.';

                clearActivityUI();
                openModal();

                pollActivity();
                if (pollTimer) clearInterval(pollTimer);
                pollTimer = setInterval(pollActivity, 5000);
            };

            document.querySelectorAll('.edit-user-btn').forEach((btn) => {
                btn.addEventListener('click', () => openForUserButton(btn));
            });

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

            if (modal && backdrop) {
                modal.addEventListener('click', (e) => {
                    if (e.target === backdrop) {
                        closeModal();
                    }
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
@endsection
