@if (session('success') || session('error') || $errors->any())
    <div class="fixed inset-x-0 top-3 flex justify-end px-4 sm:px-6 z-50 pointer-events-none">
        <div class="w-full max-w-sm space-y-3 pointer-events-auto">
            @if (session('success'))
                <div class="js-alert rounded-xl border border-slate-800 bg-slate-900/50 px-4 py-3 text-sm text-slate-200 transition-all duration-300 shadow-lg shadow-slate-950/30">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/10 text-xs font-bold text-emerald-400 border border-emerald-500/20">
                            ✓
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-100">Success</p>
                            <p class="mt-0.5 text-slate-300">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="js-alert rounded-xl border border-slate-800 bg-slate-900/50 px-4 py-3 text-sm text-slate-200 transition-all duration-300 shadow-lg shadow-slate-950/30">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-rose-500/10 text-xs font-bold text-rose-400 border border-rose-500/20">
                            !
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-slate-100">Error</p>
                            <p class="mt-0.5 text-slate-300">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="js-alert rounded-xl border border-slate-800 bg-slate-900/50 px-4 py-3 text-sm text-slate-200 transition-all duration-300 shadow-lg shadow-slate-950/30">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-rose-500/10 text-xs font-bold text-rose-400 border border-rose-500/20">
                            !
                        </div>
                        <div class="flex-1 space-y-1">
                            <p class="font-medium text-slate-100">There were some problems with your input.</p>
                            <ul class="list-disc space-y-0.5 pl-4 text-slate-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.js-alert').forEach((el) => {
                window.setTimeout(() => {
                    el.classList.add('opacity-0', 'translate-y-2');
                    window.setTimeout(() => {
                        el.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
@endif
