<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login • Ticketing System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite alternate;
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="min-h-screen bg-[#020617] text-slate-100 font-sans antialiased overflow-x-hidden">
    
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-sky-500/10 rounded-full blur-[120px] animate-blob"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px] animate-blob animation-delay-2000"></div>
        <div class="absolute top-[30%] right-[20%] w-[300px] h-[300px] bg-indigo-500/10 rounded-full blur-[100px] animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 flex min-h-screen">
        <div class="w-full lg:w-[45%] flex flex-col p-6 sm:p-12">
            <header class="flex items-center justify-between mb-12">
                <a href="/" class="group flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-sky-400 transition-colors">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-800 bg-slate-900 group-hover:border-sky-500/50 transition-all">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div>
                    Back to site
                </a>
                <div class="flex items-center gap-2 rounded-full border border-slate-800 bg-slate-900/50 px-3 py-1 text-[11px] text-slate-300 backdrop-blur-md">
                    <span class="h-2 w-2 rounded-full bg-sky-500 shadow-[0_0_8px_rgba(14,165,233,0.6)]"></span>
                    Ticketing v2.0
                </div>
            </header>

            <main class="my-auto mx-auto w-full max-w-[400px]">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold tracking-tight text-white mb-3">Welcome back</h1>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Access your dashboard to manage support requests and system updates.
                    </p>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-sky-500 to-emerald-500 rounded-2xl opacity-10 group-hover:opacity-20 transition duration-500"></div>
                    <div class="relative rounded-2xl border border-slate-800 bg-slate-900/40 backdrop-blur-xl p-8 shadow-2xl">
                        <form action="{{ route('login') }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="space-y-2">
                                <label for="email" class="text-xs font-semibold text-slate-300 uppercase tracking-wider ml-1">Email Address</label>
                                <div class="relative">
                                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="name@company.com" 
                                        class="w-full rounded-xl border border-slate-700 bg-slate-950/50 pl-11 pr-4 py-3 text-sm text-white placeholder-slate-600 transition-all focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 outline-none">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between items-center ml-1">
                                    <label for="password" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Password</label>
                                </div>
                                <div class="relative">
                                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                                    <input type="password" id="password" name="password" required placeholder="••••••••" 
                                        class="w-full rounded-xl border border-slate-700 bg-slate-950/50 pl-11 pr-11 py-3 text-sm text-white placeholder-slate-600 transition-all focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 outline-none">
                                    <button type="button" onclick="toggleVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                                        <i class="fa-solid fa-eye text-sm" id="eye-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="w-full group relative flex items-center justify-center gap-2 rounded-xl bg-sky-500 px-6 py-3.5 text-sm font-bold text-white transition-all hover:bg-sky-400 hover:shadow-[0_0_20px_rgba(14,165,233,0.4)] active:scale-[0.98]">
                                Sign In
                                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </main>

            <footer class="mt-auto pt-8 flex items-center justify-between text-[11px] text-slate-500">
                <p>&copy; 2026 Support Hub Inc.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-slate-300 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-slate-300 transition-colors">Terms</a>
                </div>
            </footer>
        </div>

        <aside class="hidden lg:flex flex-1 relative bg-[#010413] border-l border-slate-800/50 items-center justify-center p-16">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,_rgba(14,165,233,0.05),_transparent_70%)]"></div>
                <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
            </div>

            <div class="relative z-10 w-full max-w-lg">
                <div class="space-y-6 mb-12">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-widest">
                        <i class="fa-solid fa-shield-check"></i> 100% Secure Environment
                    </div>
                    <h2 class="text-5xl font-bold text-white leading-[1.1]">The smart way to <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-emerald-400">handle support.</span></h2>
                    <p class="text-slate-400 text-lg leading-relaxed">
                        Say goodbye to messy email threads. Manage all internal requests in one centralized, high-performance dashboard.
                    </p>
                </div>

                <div class="relative rounded-3xl border border-slate-700 bg-slate-900/50 backdrop-blur-2xl p-2 shadow-2xl">
                    <div class="rounded-2xl bg-slate-950 overflow-hidden border border-slate-800">
                        <div class="bg-slate-900/80 px-4 py-3 border-b border-slate-800 flex items-center justify-between">
                            <div class="flex gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-500/20"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-500/20"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/20"></div>
                            </div>
                            <div class="text-[10px] text-slate-500 font-mono tracking-tighter uppercase">Queue Status: Active</div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-sky-500/5 border border-sky-500/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-sky-500 flex items-center justify-center text-white shadow-lg shadow-sky-500/20">
                                        <i class="fa-solid fa-bolt text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-white">System Integration</div>
                                        <div class="text-[10px] text-slate-500">Pending Approval</div>
                                    </div>
                                </div>
                                <div class="text-[10px] font-bold text-sky-400">#TCK-442</div>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-900/50 border border-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-clock text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-300">Database Scaling</div>
                                        <div class="text-[10px] text-slate-500">In Progress</div>
                                    </div>
                                </div>
                                <div class="text-[10px] font-bold text-slate-500">#TCK-440</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <script>
        function toggleVisibility() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
