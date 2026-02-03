@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-100 tracking-tight">Settings</h1>
        <p class="text-sm text-slate-400 mt-1">Manage your account.</p>
    </div>

    @php
        $user = Auth::user();
        $name = $user?->name ?? 'Admin';
        $email = $user?->email ?? '';
        $initial = strtoupper(mb_substr(trim($name), 0, 1));
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <nav class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-slate-900/50 border border-slate-800 rounded-xl text-sky-400 font-medium">
                    <i class="fa-solid fa-user-gear w-5 text-center"></i>
                    Profile Information
                </a>
                <a href="#ticket-categories" class="flex items-center gap-3 px-4 py-3 border border-slate-800 rounded-xl text-slate-400 hover:text-slate-100 hover:bg-slate-900/50 transition-colors font-medium">
                    <i class="fa-solid fa-tags w-5 text-center"></i>
                    Ticket Categories
                </a>
            </nav>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="p-4 sm:p-6 rounded-2xl border border-slate-800 bg-slate-900/30">
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-100">Profile Information</h2>
                    <button type="button" id="admin-settings-edit-btn" class="inline-flex items-center justify-center h-9 w-9 rounded-xl border border-slate-800 text-slate-300 hover:bg-slate-900/60 hover:text-sky-300 transition-colors" aria-label="Edit credentials">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>

                <form id="admin-settings-form" action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div class="h-20 w-20 rounded-full bg-slate-800 flex items-center justify-center text-2xl font-bold text-slate-200">
                                {{ $initial }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-2">
                            <label for="admin-settings-name" class="text-xs font-medium text-slate-400 uppercase">Name</label>
                            <input id="admin-settings-name" name="name" type="text" value="{{ old('name', $name) }}" readonly class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                            @error('name')
                                <div class="text-xs text-rose-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="admin-settings-email" class="text-xs font-medium text-slate-400 uppercase">Email Address</label>
                            <input id="admin-settings-email" name="email" type="email" value="{{ old('email', $email) }}" readonly class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                            @error('email')
                                <div class="text-xs text-rose-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-400 uppercase">Password</label>
                            <div id="admin-settings-password-mask" class="w-full rounded-xl border border-slate-800 bg-slate-950 px-4 py-2.5 text-slate-500 select-none">••••••••</div>

                            <div id="admin-settings-password-fields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 hidden">
                                <div class="space-y-2">
                                    <label for="admin-settings-password" class="text-xs font-medium text-slate-400 uppercase">New Password</label>
                                    <input id="admin-settings-password" name="password" type="password" autocomplete="new-password" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                                    @error('password')
                                        <div class="text-xs text-rose-400">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label for="admin-settings-password-confirmation" class="text-xs font-medium text-slate-400 uppercase">Confirm Password</label>
                                    <input id="admin-settings-password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="admin-settings-actions" class="pt-4 border-t border-slate-800/50 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 hidden">
                        <button type="button" id="admin-settings-cancel-btn" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 rounded-xl border border-slate-800 text-slate-200 hover:bg-slate-900/60 transition-colors text-sm font-semibold">
                            Cancel
                        </button>
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-400 transition-colors text-sm font-bold shadow-lg shadow-sky-500/20">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <div id="ticket-categories" class="p-4 sm:p-6 rounded-2xl border border-slate-800 bg-slate-900/30">
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-100">Ticket Categories</h2>
                        <p class="mt-1 text-xs text-slate-500">Add categories to make ticket forms dynamic.</p>
                    </div>
                </div>

                <form action="{{ route('admin.settings.categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="category-key" class="text-xs font-medium text-slate-400 uppercase">Key (optional)</label>
                            <input id="category-key" name="key" type="text" value="{{ old('key') }}" placeholder="e.g. software" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                            @error('key')
                                <div class="text-xs text-rose-400">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="category-label" class="text-xs font-medium text-slate-400 uppercase">Label</label>
                            <input id="category-label" name="label" type="text" value="{{ old('label') }}" required placeholder="e.g. Software Request" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all">
                            @error('label')
                                <div class="text-xs text-rose-400">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex justify-center items-center px-4 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-400 transition-colors text-sm font-bold shadow-lg shadow-sky-500/20">
                            Add Category
                        </button>
                    </div>
                </form>

                <div class="mt-6 rounded-2xl border border-slate-800 bg-slate-950/40 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-slate-800/50 flex items-center justify-between">
                        <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Current Categories</div>
                        <div class="text-xs text-slate-500">{{ ($ticketCategories ?? collect())->count() }} total</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-slate-900/50 text-xs uppercase font-semibold text-slate-500">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3">Key</th>
                                    <th class="px-4 sm:px-6 py-3">Label</th>
                                    <th class="px-4 sm:px-6 py-3">Created</th>
                                    <th class="px-4 sm:px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50">
                                @forelse (($ticketCategories ?? collect()) as $ticketCategory)
                                    <tr>
                                        <td class="px-4 sm:px-6 py-3 font-mono text-xs text-slate-400">{{ $ticketCategory->key }}</td>
                                        <td class="px-4 sm:px-6 py-3 text-slate-200">{{ $ticketCategory->label }}</td>
                                        <td class="px-4 sm:px-6 py-3 text-xs text-slate-500">{{ $ticketCategory->created_at ? \Illuminate\Support\Carbon::parse($ticketCategory->created_at)->format('M d, Y') : '-' }}</td>
                                        <td class="px-4 sm:px-6 py-3 text-right">
                                            <form action="{{ route('admin.settings.categories.destroy', $ticketCategory->id) }}" method="POST" class="js-category-delete" data-category-label="{{ $ticketCategory->label }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-rose-500/30 bg-rose-500/10 px-2.5 py-2 text-xs font-semibold text-rose-300 hover:bg-rose-500/20 transition-colors" title="Delete" aria-label="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 sm:px-6 py-8 text-center text-sm text-slate-500">No categories yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editBtn = document.getElementById('admin-settings-edit-btn');
            const cancelBtn = document.getElementById('admin-settings-cancel-btn');
            const actions = document.getElementById('admin-settings-actions');
            const nameInput = document.getElementById('admin-settings-name');
            const emailInput = document.getElementById('admin-settings-email');
            const passwordMask = document.getElementById('admin-settings-password-mask');
            const passwordFields = document.getElementById('admin-settings-password-fields');
            const passwordInput = document.getElementById('admin-settings-password');
            const passwordConfirmInput = document.getElementById('admin-settings-password-confirmation');

            const initialName = nameInput?.value ?? '';
            const initialEmail = emailInput?.value ?? '';

            const setEditing = (editing) => {
                if (nameInput) nameInput.readOnly = !editing;
                if (emailInput) emailInput.readOnly = !editing;

                if (passwordMask) passwordMask.classList.toggle('hidden', editing);
                if (passwordFields) passwordFields.classList.toggle('hidden', !editing);
                if (actions) actions.classList.toggle('hidden', !editing);

                if (!editing) {
                    if (nameInput) nameInput.value = initialName;
                    if (emailInput) emailInput.value = initialEmail;
                    if (passwordInput) passwordInput.value = '';
                    if (passwordConfirmInput) passwordConfirmInput.value = '';
                }
            };

            if (editBtn) editBtn.addEventListener('click', () => setEditing(true));
            if (cancelBtn) cancelBtn.addEventListener('click', () => setEditing(false));

            const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
            if (hasErrors) {
                setEditing(true);
            }

            const deleteModal = document.getElementById('category-delete-modal');
            const deleteModalTitle = document.getElementById('category-delete-modal-title');
            const deleteModalMessage = document.getElementById('category-delete-modal-message');
            const deleteModalCancel = document.getElementById('category-delete-modal-cancel');
            const deleteModalConfirm = document.getElementById('category-delete-modal-confirm');
            const deleteModalBackdrop = document.getElementById('category-delete-modal-backdrop');
            const deleteModalClose = document.getElementById('category-delete-modal-close');

            let pendingDeleteForm = null;

            const closeDeleteModal = () => {
                if (!deleteModal) return;
                deleteModal.classList.add('hidden');
                pendingDeleteForm = null;
            };

            const openDeleteModal = ({ label }) => {
                if (!deleteModal) return;
                const safeLabel = typeof label === 'string' && label.trim() !== '' ? label.trim() : 'this category';
                if (deleteModalTitle) deleteModalTitle.textContent = `Delete "${safeLabel}"?`;
                if (deleteModalMessage) {
                    deleteModalMessage.textContent = 'This action can’t be undone.';
                }
                deleteModal.classList.remove('hidden');
                if (deleteModalConfirm) {
                    window.setTimeout(() => deleteModalConfirm.focus(), 0);
                }
            };

            document.querySelectorAll('form.js-category-delete').forEach((form) => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    pendingDeleteForm = form;
                    const label = form.getAttribute('data-category-label') || '';
                    openDeleteModal({ label });
                });
            });

            if (deleteModalCancel) {
                deleteModalCancel.addEventListener('click', closeDeleteModal);
            }
            if (deleteModalClose) {
                deleteModalClose.addEventListener('click', closeDeleteModal);
            }
            if (deleteModalBackdrop) {
                deleteModalBackdrop.addEventListener('click', closeDeleteModal);
            }
            if (deleteModalConfirm) {
                deleteModalConfirm.addEventListener('click', () => {
                    if (pendingDeleteForm) {
                        pendingDeleteForm.submit();
                    }
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        });
    </script>

    <div id="category-delete-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="category-delete-modal-title" aria-describedby="category-delete-modal-message">
        <div id="category-delete-modal-backdrop" class="absolute inset-0 bg-slate-950/75 backdrop-blur-sm"></div>
        <div class="relative mx-auto flex min-h-full max-w-lg items-center justify-center p-4 sm:p-6">
            <div class="w-full overflow-hidden rounded-2xl border border-slate-800 bg-slate-950 shadow-2xl shadow-slate-950/60">
                <div class="flex items-start gap-4 border-b border-slate-800/60 bg-slate-900/30 px-5 py-4 sm:px-6">
                    <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-500/10 text-rose-300 border border-rose-500/20">
                        <i class="fa-solid fa-trash"></i>
                    </div>
                    <div class="flex-1">
                        <h3 id="category-delete-modal-title" class="text-base font-semibold text-slate-100"></h3>
                        <p id="category-delete-modal-message" class="mt-1 text-sm text-slate-300"></p>
                    </div>
                    <button id="category-delete-modal-close" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-800 text-slate-300 hover:bg-slate-900/60 hover:text-slate-100" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="px-5 py-4 sm:px-6">
                    <div class="rounded-xl border border-amber-500/20 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
                        If this category is used by existing tickets, deletion will be blocked.
                    </div>
                </div>
                <div class="flex flex-col-reverse gap-2 border-t border-slate-800/60 px-5 py-4 sm:flex-row sm:items-center sm:justify-end sm:px-6">
                    <button id="category-delete-modal-cancel" type="button" class="inline-flex w-full justify-center rounded-xl border border-slate-800 bg-slate-950 px-4 py-2.5 text-sm font-semibold text-slate-200 hover:bg-slate-900/60 sm:w-auto">
                        Cancel
                    </button>
                    <button id="category-delete-modal-confirm" type="button" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-rose-500 sm:w-auto">
                        <i class="fa-solid fa-trash"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
