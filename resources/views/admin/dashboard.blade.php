<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="min-h-screen bg-slate-50">


    {{-- ========================================================= --}}
    {{-- NAVBAR --}}
    {{-- ========================================================= --}}

    <nav class="border-b border-slate-200 bg-white">

        <div
            class="mx-auto flex max-w-7xl
                   items-center justify-between
                   px-6 py-4"
        >

            <div>

                <h1 class="text-lg font-bold text-slate-900">
                    Job Portal
                </h1>

                <p class="text-xs text-slate-500">
                    Admin Panel
                </p>

            </div>


            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="rounded-lg border
                           border-slate-200
                           bg-white px-4 py-2
                           text-sm font-semibold
                           text-slate-700
                           transition
                           hover:bg-slate-50"
                >
                    Logout
                </button>

            </form>

        </div>

    </nav>



    {{-- ========================================================= --}}
    {{-- MAIN --}}
    {{-- ========================================================= --}}

    <main
        class="mx-auto max-w-7xl
               px-6 py-10"
    >


        {{-- Header --}}

        <div>

            <p
                class="text-sm font-semibold
                       text-indigo-600"
            >
                Admin Dashboard
            </p>

            <h2
                class="mt-2 text-3xl font-bold
                       tracking-tight text-slate-900"
            >
                Welcome,
                {{ Auth::user()->name }}
            </h2>

            <p
                class="mt-2 text-sm text-slate-500"
            >
                Manage candidates, applications,
                interviews and offers.
            </p>

        </div>



        {{-- ========================================================= --}}
        {{-- ADMIN INFO --}}
        {{-- ========================================================= --}}

        <div
            class="mt-8 rounded-2xl
                   border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <div
                class="grid gap-6
                       sm:grid-cols-3"
            >

                <div>

                    <p
                        class="text-xs font-semibold
                               uppercase tracking-wide
                               text-slate-400"
                    >
                        Name
                    </p>

                    <p
                        class="mt-1 text-sm
                               font-semibold
                               text-slate-800"
                    >
                        {{ Auth::user()->name }}
                    </p>

                </div>


                <div>

                    <p
                        class="text-xs font-semibold
                               uppercase tracking-wide
                               text-slate-400"
                    >
                        Email
                    </p>

                    <p
                        class="mt-1 text-sm
                               font-semibold
                               text-slate-800"
                    >
                        {{ Auth::user()->email }}
                    </p>

                </div>


                <div>

                    <p
                        class="text-xs font-semibold
                               uppercase tracking-wide
                               text-slate-400"
                    >
                        Role
                    </p>

                    <span
                        class="mt-1 inline-flex
                               rounded-full
                               bg-indigo-50 px-3 py-1
                               text-xs font-semibold
                               capitalize
                               text-indigo-700"
                    >
                        {{ Auth::user()->role }}
                    </span>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- OFFER STATISTICS --}}
        {{-- ========================================================= --}}

        <div class="mt-8">

            <div>

                <p
                    class="text-xs font-semibold
                           uppercase tracking-wide
                           text-slate-400"
                >
                    Offer Overview
                </p>

                <h3
                    class="mt-1 text-xl font-bold
                           text-slate-900"
                >
                    Candidate Responses
                </h3>

            </div>


            <div
                class="mt-5 grid gap-5
                       md:grid-cols-3"
            >


                {{-- Pending --}}

                <div
                    class="rounded-2xl
                           border border-blue-200
                           bg-blue-50 p-6"
                >

                    <div
                        class="flex items-center
                               justify-between"
                    >

                        <div>

                            <p
                                class="text-xs font-semibold
                                       uppercase tracking-wide
                                       text-blue-600"
                            >
                                Awaiting Response
                            </p>

                            <p
                                class="mt-2 text-3xl
                                       font-bold text-blue-900"
                            >
                                {{ $offerPending }}
                            </p>

                        </div>


                        <div
                            class="flex h-11 w-11
                                   items-center
                                   justify-center
                                   rounded-xl
                                   bg-blue-100
                                   text-lg"
                        >
                            ⏳
                        </div>

                    </div>

                    <p
                        class="mt-3 text-sm
                               text-blue-600"
                    >
                        Offers waiting for candidates
                    </p>

                </div>



                {{-- Accepted --}}

                <div
                    class="rounded-2xl
                           border border-emerald-200
                           bg-emerald-50 p-6"
                >

                    <div
                        class="flex items-center
                               justify-between"
                    >

                        <div>

                            <p
                                class="text-xs font-semibold
                                       uppercase tracking-wide
                                       text-emerald-600"
                            >
                                Accepted Offers
                            </p>

                            <p
                                class="mt-2 text-3xl
                                       font-bold text-emerald-900"
                            >
                                {{ $offerAccepted }}
                            </p>

                        </div>


                        <div
                            class="flex h-11 w-11
                                   items-center
                                   justify-center
                                   rounded-xl
                                   bg-emerald-100
                                   text-lg"
                        >
                            ✓
                        </div>

                    </div>

                    <p
                        class="mt-3 text-sm
                               text-emerald-600"
                    >
                        Candidates who accepted
                    </p>

                </div>



                {{-- Declined --}}

                <div
                    class="rounded-2xl
                           border border-red-200
                           bg-red-50 p-6"
                >

                    <div
                        class="flex items-center
                               justify-between"
                    >

                        <div>

                            <p
                                class="text-xs font-semibold
                                       uppercase tracking-wide
                                       text-red-600"
                            >
                                Declined Offers
                            </p>

                            <p
                                class="mt-2 text-3xl
                                       font-bold text-red-900"
                            >
                                {{ $offerDeclined }}
                            </p>

                        </div>


                        <div
                            class="flex h-11 w-11
                                   items-center
                                   justify-center
                                   rounded-xl
                                   bg-red-100
                                   text-lg"
                        >
                            ×
                        </div>

                    </div>

                    <p
                        class="mt-3 text-sm
                               text-red-600"
                    >
                        Candidates who declined
                    </p>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- QUICK ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="mt-10">

            <p
                class="text-xs font-semibold
                       uppercase tracking-wide
                       text-slate-400"
            >
                Quick Actions
            </p>


            <div
                class="mt-4 grid gap-4
                       sm:grid-cols-2
                       lg:grid-cols-3"
            >

                <a
                    href="{{ route('admin.jobs.index') }}"
                    class="rounded-2xl border
                           border-slate-200
                           bg-white p-5
                           transition
                           hover:border-indigo-200
                           hover:shadow-sm"
                >

                    <p
                        class="font-semibold
                               text-slate-900"
                    >
                        Manage Jobs
                    </p>

                    <p
                        class="mt-1 text-sm
                               text-slate-500"
                    >
                        Create, edit and manage
                        job openings.
                    </p>

                </a>


                <a
                    href="{{ route('applications.index') }}"
                    class="rounded-2xl border
                           border-slate-200
                           bg-white p-5
                           transition
                           hover:border-indigo-200
                           hover:shadow-sm"
                >

                    <p
                        class="font-semibold
                               text-slate-900"
                    >
                        My Applications
                    </p>

                    <p
                        class="mt-1 text-sm
                               text-slate-500"
                    >
                        View candidate application
                        activity.
                    </p>

                </a>


                <a
                    href="{{ route('admin.dashboard') }}"
                    class="rounded-2xl border
                           border-slate-200
                           bg-white p-5
                           transition
                           hover:border-indigo-200
                           hover:shadow-sm"
                >

                    <p
                        class="font-semibold
                               text-slate-900"
                    >
                        Dashboard
                    </p>

                    <p
                        class="mt-1 text-sm
                               text-slate-500"
                    >
                        View recruitment overview.
                    </p>

                </a>

            </div>

        </div>


    </main>

</body>

</html>