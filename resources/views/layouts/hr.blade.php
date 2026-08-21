<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HR Management Dashboard') - HR Portal</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Theme Initialization Script --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
            window.dispatchEvent(new Event('theme-changed'));
        }
    </script>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#F97316',
                            600: '#EA580C',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
</head>

<body class="min-h-screen bg-[#F7F7F7] text-[#111111] dark:bg-[#0A0A0A] dark:text-[#FFFFFF] transition-colors duration-200 antialiased flex flex-col md:flex-row">

    {{-- Mobile Backdrop --}}
    <div id="mobile-sidebar-backdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 z-40 hidden bg-black/60 backdrop-blur-xs transition-opacity md:hidden"></div>

    {{-- HR Sidebar --}}
    <aside id="hr-sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col justify-between border-r border-[#E5E5E5] bg-white p-5 transition-transform duration-200 ease-in-out dark:border-[#262626] dark:bg-[#141414] -translate-x-full md:static md:translate-x-0">
        <div>
            {{-- Brand Logo --}}
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-5 dark:border-[#262626]">
                <a href="{{ route('hr.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-600 text-base font-bold text-white shadow-sm">
                        👥
                    </div>
                    <div>
                        <span class="text-base font-bold tracking-tight text-[#111111] dark:text-white">JobPortal</span>
                        <span class="block text-[10px] font-bold uppercase tracking-widest text-purple-600 dark:text-purple-400">HR Portal</span>
                    </div>
                </a>
                <button onclick="toggleMobileSidebar()" class="flex h-8 w-8 items-center justify-center rounded-lg border border-[#E5E5E5] text-slate-500 hover:text-slate-800 dark:border-[#262626] dark:text-slate-400 md:hidden">
                    ✕
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="mt-6 space-y-1.5">
                <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    HR Operations
                </p>

                {{-- Dashboard --}}
                <a
                    href="{{ route('hr.dashboard') }}"
                    class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition {{ request()->routeIs('hr.dashboard') ? 'bg-purple-600 text-white shadow-xs' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#1A1A1A] dark:hover:text-white' }}"
                >
                    <span class="text-base">📊</span>
                    <span>HR Overview</span>
                </a>

                {{-- Employees --}}
                <a
                    href="{{ route('hr.employees.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition {{ request()->routeIs('hr.employees.*') ? 'bg-purple-600 text-white shadow-xs' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#1A1A1A] dark:hover:text-white' }}"
                >
                    <span class="text-base">👔</span>
                    <span>Employees</span>
                </a>

                {{-- Candidates / Applications --}}
                <a
                    href="{{ route('hr.applications.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition {{ request()->routeIs('hr.applications.*') ? 'bg-purple-600 text-white shadow-xs' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#1A1A1A] dark:hover:text-white' }}"
                >
                    <span class="text-base">📋</span>
                    <span>Candidates</span>
                </a>

                {{-- HR Interviews --}}
                <a
                    href="{{ route('hr.interviews.index') }}"
                    class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition {{ request()->routeIs('hr.interviews.*') ? 'bg-purple-600 text-white shadow-xs' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#1A1A1A] dark:hover:text-white' }}"
                >
                    <span class="text-base">📹</span>
                    <span>My Interviews</span>
                </a>

                {{-- HR Profile --}}
                <a
                    href="{{ route('hr.profile') }}"
                    class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition {{ request()->routeIs('hr.profile*') ? 'bg-purple-600 text-white shadow-xs' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#1A1A1A] dark:hover:text-white' }}"
                >
                    <span class="text-base">👤</span>
                    <span>HR Profile</span>
                </a>
            </nav>
        </div>

        {{-- Footer --}}
        <div class="border-t border-[#E5E5E5] pt-4 dark:border-[#262626] space-y-3">
            <button
                onclick="toggleTheme()"
                type="button"
                class="flex w-full items-center justify-between rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3.5 py-2 text-xs font-semibold text-[#111111] transition hover:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
            >
                <span class="flex items-center gap-2">
                    <span class="dark:hidden">☀️ Light Mode</span>
                    <span class="hidden dark:inline">🌙 Dark Mode</span>
                </span>
                <span class="text-[10px] uppercase font-bold text-brand-500">Toggle</span>
            </button>

            <div class="flex items-center justify-between pt-1">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-purple-500/10 font-bold text-purple-600 dark:bg-purple-500/20">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <p class="truncate text-xs font-bold text-[#111111] dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-purple-600 dark:text-purple-400 font-semibold uppercase">HR Specialist</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        title="Log Out"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400 transition"
                    >
                        🚪
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Workspace --}}
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-x-hidden">
        {{-- Header --}}
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-[#E5E5E5] bg-white px-4 sm:px-6 lg:px-8 dark:border-[#262626] dark:bg-[#141414]">
            <div class="flex items-center gap-3">
                <button
                    onclick="toggleMobileSidebar()"
                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-[#E5E5E5] text-slate-700 hover:bg-slate-50 dark:border-[#262626] dark:text-slate-300 md:hidden"
                >
                    ☰
                </button>
                <div class="hidden sm:block">
                    <p class="text-xs font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">
                        HR Portal
                    </p>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        @yield('header_title', 'Staff & Onboarding Hub')
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="inline-flex rounded-full border border-purple-500/30 bg-purple-500/10 px-3 py-1 text-xs font-bold text-purple-600 dark:text-purple-400">
                    HR Access
                </span>
            </div>
        </header>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-[10px] font-bold text-white">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-auto mt-4 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 rounded-2xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-xs font-semibold text-red-600 dark:text-red-400">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">✕</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 w-full p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('hr-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
</body>

</html>
