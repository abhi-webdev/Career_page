<nav class="border-b border-slate-200 bg-white">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">

        {{-- Logo --}}

        <a
            href="{{ route('jobs.index') }}"
            class="text-2xl font-bold tracking-tight text-indigo-600"
        >
            JobPortal
        </a>


        {{-- Navigation --}}

        <div class="flex items-center gap-6">

            <a
                href="{{ route('jobs.index') }}"
                class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
            >
                Jobs
            </a>


            @auth

                {{-- Candidate Dashboard --}}

                <a
                    href="{{ route('dashboard') }}"
                    class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                >
                    Dashboard
                </a>

                @php
    $unreadNotifications =
        auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(5)
            ->get();

    $unreadCount =
        auth()->user()
            ->unreadNotifications()
            ->count();
@endphp


                {{-- Candidate Navigation --}}

                @if(auth()->user()->role !== 'admin')

                    <a
                        href="{{ route('applications.index') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        My Applications
                    </a>

                    <a
                        href="{{ route('profile') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        Profile
                    </a>

                @endif


                {{-- Admin Navigation --}}

                @if(auth()->user()->role === 'admin')

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        Admin Dashboard
                    </a>


                    <a
                        href="{{ route('admin.jobs.index') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        Manage Jobs
                    </a>


                    <a
                        href="{{ route('applications.index') }}"
                        class="text-sm font-medium text-slate-600 transition hover:text-indigo-600"
                    >
                        Applications
                    </a>

                @endif

                <div class="relative">

    <details class="group">

        <summary
            class="flex cursor-pointer
                   list-none items-center gap-2
                   text-sm font-medium
                   text-slate-600
                   hover:text-indigo-600"
        >

            <span class="relative">

                🔔

                @if($unreadCount > 0)

                    <span
                        class="absolute -right-2 -top-2
                               flex h-4 min-w-4
                               items-center justify-center
                               rounded-full bg-red-500
                               px-1 text-[10px]
                               font-bold text-white"
                    >
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>

                @endif

            </span>

            <span class="hidden sm:inline">
                Notifications
            </span>

        </summary>


        {{-- Notification Dropdown --}}

        <div
            class="absolute right-0 z-50 mt-3
                   w-80 overflow-hidden
                   rounded-2xl border
                   border-slate-200
                   bg-white shadow-xl"
        >

            <div
                class="flex items-center
                       justify-between
                       border-b border-slate-100
                       px-4 py-3"
            >

                <p
                    class="text-sm font-bold
                           text-slate-900"
                >
                    Notifications
                </p>


                @if($unreadCount > 0)

                    <form
                        method="POST"
                        action="{{ route(
                            'notifications.read-all'
                        ) }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="text-xs font-semibold
                                   text-indigo-600
                                   hover:text-indigo-700"
                        >
                            Mark all read
                        </button>

                    </form>

                @endif

            </div>


            @forelse(
                $unreadNotifications
                as $notification
            )

                <form
                    method="POST"
                    action="{{ route(
                        'notifications.read',
                        $notification->id
                    ) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="block w-full
                               border-b border-slate-100
                               px-4 py-4 text-left
                               transition hover:bg-slate-50"
                    >

                        <p
                            class="text-sm font-semibold
                                   text-slate-800"
                        >
                            {{ $notification->data['title'] }}
                        </p>

                        <p
                            class="mt-1 text-xs
                                   leading-5 text-slate-500"
                        >
                            {{ $notification->data['message'] }}
                        </p>

                        <p
                            class="mt-2 text-[11px]
                                   text-slate-400"
                        >
                            {{ $notification->created_at->diffForHumans() }}
                        </p>

                    </button>

                </form>

            @empty

                <div class="px-4 py-8 text-center">

                    <p class="text-2xl">
                        🔔
                    </p>

                    <p
                        class="mt-2 text-sm
                               font-medium text-slate-700"
                    >
                        No new notifications
                    </p>

                    <p
                        class="mt-1 text-xs
                               text-slate-400"
                    >
                        You're all caught up.
                    </p>

                </div>

            @endforelse

        </div>

    </details>

</div>


                {{-- User Name --}}

                <span class="text-sm text-slate-500">
                    {{ auth()->user()->name }}
                </span>


                {{-- Logout --}}

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700"
                    >
                        Logout
                    </button>

                </form>

            @else

                <a
                    href="{{ route('login') }}"
                    class="text-sm font-medium text-slate-600 hover:text-indigo-600"
                >
                    Login
                </a>

                <a
                    href="{{ route('register') }}"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"
                >
                    Register
                </a>

            @endauth

        </div>

    </div>

</nav>