<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Job Portal') - Career Platform</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Theme Initialization Script (Prevents FOUC) --}}
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

    {{-- Tailwind CSS & Configuration --}}
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
                        dark: {
                            bg: '#0A0A0A',
                            card: '#141414',
                            subtle: '#1A1A1A',
                            border: '#262626',
                            text: '#FFFFFF',
                            muted: '#A1A1A1',
                        },
                        light: {
                            bg: '#FFFFFF',
                            card: '#F7F7F7',
                            subtle: '#FAFAFA',
                            border: '#E5E5E5',
                            text: '#111111',
                            muted: '#6B6B6B',
                        }
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
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #d4d4d4;
            border-radius: 9999px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #333333;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #F97316;
        }
    </style>
</head>

<body class="min-h-screen bg-white text-[#111111] dark:bg-[#0A0A0A] dark:text-[#FFFFFF] transition-colors duration-200 antialiased selection:bg-brand-500 selection:text-white flex flex-col justify-between">

    <div>
        {{-- Candidate Top Navbar --}}
        @include('components.navbar')

        {{-- Global Flash Notifications --}}
        @if(session('success'))
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-600 dark:text-emerald-400">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 rounded-2xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">✕</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Main Page Content --}}
        <main class="w-full">
            @yield('content')
        </main>
    </div>

    {{-- Candidate Minimal Footer --}}
    @include('components.footer')

</body>

</html>