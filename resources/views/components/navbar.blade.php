<nav class="sticky top-0 z-40 border-b border-[#E5E5E5] bg-white/95 backdrop-blur-md transition-colors duration-200 dark:border-[#262626] dark:bg-[#0A0A0A]/95">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">

        {{-- Brand Logo --}}
        <div class="flex items-center gap-8">
            <a href="{{ route('jobs.index') }}" class="group flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-500 font-bold text-white shadow-xs transition duration-200 group-hover:scale-105 group-hover:bg-brand-600">
                    💼
                </div>
                <span class="text-lg font-bold tracking-tight text-[#111111] transition dark:text-white">
                    Job<span class="text-brand-500">Portal</span>
                </span>
            </a>

            {{-- Desktop Primary Navigation --}}
            <div class="hidden md:flex items-center gap-1">
                <a
                    href="{{ route('jobs.index') }}"
                    class="rounded-xl px-3.5 py-2 text-sm font-semibold transition {{ request()->routeIs('jobs.*') && !request()->routeIs('admin.*') ? 'bg-brand-500/10 text-brand-500 dark:bg-brand-500/20' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#141414] dark:hover:text-white' }}"
                >
                    Explore Jobs
                </a>

                @auth
                    @if(auth()->user()->role !== 'admin')
                        <a
                            href="{{ route('dashboard') }}"
                            class="rounded-xl px-3.5 py-2 text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-brand-500/10 text-brand-500 dark:bg-brand-500/20' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#141414] dark:hover:text-white' }}"
                        >
                            Dashboard
                        </a>

                        <a
                            href="{{ route('applications.index') }}"
                            class="rounded-xl px-3.5 py-2 text-sm font-semibold transition {{ request()->routeIs('applications.index') ? 'bg-brand-500/10 text-brand-500 dark:bg-brand-500/20' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#141414] dark:hover:text-white' }}"
                        >
                            My Applications
                        </a>

                        <a
                            href="{{ route('offers.current') }}"
                            class="rounded-xl px-3.5 py-2 text-sm font-semibold transition {{ request()->routeIs('offers.*') || request()->routeIs('applications.offer.*') ? 'bg-brand-500/10 text-brand-500 dark:bg-brand-500/20' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#141414] dark:hover:text-white' }}"
                        >
                            Offer
                        </a>

                        <a
                            href="{{ route('profile') }}"
                            class="rounded-xl px-3.5 py-2 text-sm font-semibold transition {{ request()->routeIs('profile*') ? 'bg-brand-500/10 text-brand-500 dark:bg-brand-500/20' : 'text-[#6B6B6B] hover:bg-[#F7F7F7] hover:text-[#111111] dark:text-[#A1A1A1] dark:hover:bg-[#141414] dark:hover:text-white' }}"
                        >
                            Profile
                        </a>
                    @else
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-brand-500 px-3.5 py-2 text-sm font-semibold text-white shadow-xs transition hover:bg-brand-600"
                        >
                            <span>⚡ Admin ATS</span>
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Right Hand Side Tools --}}
        <div class="flex items-center gap-2.5 sm:gap-3">

            {{-- Theme Toggle Button --}}
            <button
                onclick="toggleTheme()"
                type="button"
                aria-label="Toggle Theme"
                class="flex h-9 w-9 items-center justify-center rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] text-sm text-[#111111] transition hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white dark:hover:border-brand-500 dark:hover:text-brand-500"
            >
                <span class="dark:hidden">🌙</span>
                <span class="hidden dark:inline">☀️</span>
            </button>

            @auth
                @php
                    $unreadNotifications = auth()->user()
                        ->unreadNotifications()
                        ->latest()
                        ->take(5)
                        ->get();

                    $unreadCount = auth()->user()
                        ->unreadNotifications()
                        ->count();
                @endphp

                {{-- Notifications Center --}}
                <div class="relative">
                    <details class="group">
                        <summary class="flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] text-sm text-[#111111] transition hover:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white">
                            <span class="relative">
                                🔔
                                @if($unreadCount > 0)
                                    <span class="absolute -right-2 -top-2 flex h-4 min-w-4 items-center justify-center rounded-full bg-brand-500 px-1 text-[9px] font-bold text-white">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </span>
                        </summary>

                        <div class="absolute right-0 z-50 mt-2 w-80 overflow-hidden rounded-2xl border border-[#E5E5E5] bg-white shadow-xl dark:border-[#262626] dark:bg-[#141414]">
                            <div class="flex items-center justify-between border-b border-[#E5E5E5] bg-[#F7F7F7] px-4 py-3 dark:border-[#262626] dark:bg-[#1A1A1A]">
                                <p class="text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                                    Notifications
                                </p>

                                @if($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.read-all') }}">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-brand-500 hover:text-brand-600">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                                @forelse($unreadNotifications as $notification)
                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                        @csrf
                                        <button type="submit" class="block w-full p-3.5 text-left transition hover:bg-[#F7F7F7] dark:hover:bg-[#1A1A1A]">
                                            <p class="text-xs font-bold text-[#111111] dark:text-white">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </p>
                                            <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1] line-clamp-2">
                                                {{ $notification->data['message'] ?? '' }}
                                            </p>
                                            <p class="mt-1 text-[10px] text-slate-400">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </button>
                                    </form>
                                @empty
                                    <div class="p-6 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                                        ✨ You're all caught up.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </details>
                </div>

                {{-- User Avatar + Logout --}}
                <div class="hidden sm:flex items-center gap-3 border-l border-[#E5E5E5] pl-3 dark:border-[#262626]">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 font-bold text-brand-500 dark:bg-brand-500/20">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-xs font-bold text-[#111111] dark:text-white">
                            {{ auth()->user()->name }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3 py-1.5 text-xs font-semibold text-[#111111] transition hover:border-red-500 hover:text-red-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                        >
                            Logout
                        </button>
                    </form>
                </div>

            @else
                <div class="hidden sm:flex items-center gap-2 border-l border-[#E5E5E5] pl-3 dark:border-[#262626]">
                    <a
                        href="{{ route('login') }}"
                        class="rounded-xl px-3.5 py-2 text-xs font-semibold text-[#111111] transition hover:text-brand-500 dark:text-white"
                    >
                        Sign In
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-brand-600"
                    >
                        Register
                    </a>
                </div>
            @endauth

            {{-- Mobile Hamburger Menu Button --}}
            <button
                onclick="toggleCandidateMobileMenu()"
                class="flex h-9 w-9 items-center justify-center rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] text-sm text-[#111111] dark:border-[#262626] dark:bg-[#141414] dark:text-white md:hidden"
            >
                ☰
            </button>

        </div>

    </div>

    {{-- Mobile Dropdown Drawer --}}
    <div id="candidate-mobile-menu" class="hidden border-t border-[#E5E5E5] bg-white px-4 py-4 dark:border-[#262626] dark:bg-[#0A0A0A] md:hidden">
        <div class="space-y-1">
            <a
                href="{{ route('jobs.index') }}"
                class="block rounded-xl px-3 py-2 text-sm font-semibold {{ request()->routeIs('jobs.*') && !request()->routeIs('admin.*') ? 'bg-brand-500 text-white' : 'text-[#6B6B6B] dark:text-[#A1A1A1]' }}"
            >
                Explore Jobs
            </a>

            @auth
                @if(auth()->user()->role !== 'admin')
                    <a
                        href="{{ route('dashboard') }}"
                        class="block rounded-xl px-3 py-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-brand-500 text-white' : 'text-[#6B6B6B] dark:text-[#A1A1A1]' }}"
                    >
                        Dashboard
                    </a>

                    <a
                        href="{{ route('applications.index') }}"
                        class="block rounded-xl px-3 py-2 text-sm font-semibold {{ request()->routeIs('applications.index') ? 'bg-brand-500 text-white' : 'text-[#6B6B6B] dark:text-[#A1A1A1]' }}"
                    >
                        My Applications
                    </a>

                    <a
                        href="{{ route('offers.current') }}"
                        class="block rounded-xl px-3 py-2 text-sm font-semibold {{ request()->routeIs('offers.*') || request()->routeIs('applications.offer.*') ? 'bg-brand-500 text-white' : 'text-[#6B6B6B] dark:text-[#A1A1A1]' }}"
                    >
                        Offer
                    </a>

                    <a
                        href="{{ route('profile') }}"
                        class="block rounded-xl px-3 py-2 text-sm font-semibold {{ request()->routeIs('profile*') ? 'bg-brand-500 text-white' : 'text-[#6B6B6B] dark:text-[#A1A1A1]' }}"
                    >
                        Profile
                    </a>
                @else
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="block rounded-xl bg-brand-500 px-3 py-2 text-sm font-semibold text-white"
                    >
                        ⚡ Admin ATS Dashboard
                    </a>
                @endif

                <div class="border-t border-[#E5E5E5] pt-3 mt-3 dark:border-[#262626]">
                    <p class="px-3 text-xs font-bold text-[#111111] dark:text-white mb-2">Signed in as {{ auth()->user()->name }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-xl border border-red-500/30 bg-red-500/10 px-3 py-2 text-left text-xs font-semibold text-red-600 dark:text-red-400">
                            Log Out
                        </button>
                    </form>
                </div>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('login') }}" class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2 text-center text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#141414] dark:text-white">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-brand-500 py-2 text-center text-xs font-semibold text-white">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <script>
        function toggleCandidateMobileMenu() {
            const menu = document.getElementById('candidate-mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>

</nav>