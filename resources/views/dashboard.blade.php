@extends('layouts.app')

@section('title', 'Candidate Dashboard')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div>

            <p class="text-sm font-semibold text-indigo-600">
                Candidate Dashboard
            </p>

            <h1
                class="mt-2 text-3xl font-bold
                       tracking-tight text-slate-900"
            >
                Welcome back, {{ auth()->user()->name }}
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Here's an overview of your job applications
                and recruitment progress.
            </p>

        </div>


        {{-- ========================================================= --}}
        {{-- STATISTICS --}}
        {{-- ========================================================= --}}

        <div
            class="mt-8 grid gap-4
                   sm:grid-cols-2
                   lg:grid-cols-4"
        >

            {{-- Applications --}}

            <div
                class="rounded-2xl border
                       border-slate-200 bg-white p-5
                       shadow-sm"
            >

                <p
                    class="text-xs font-semibold uppercase
                           tracking-wide text-slate-400"
                >
                    Applications
                </p>

                <p
                    class="mt-2 text-3xl font-bold
                           text-slate-900"
                >
                    {{ $totalApplications }}
                </p>

                <p
                    class="mt-1 text-sm text-slate-500"
                >
                    Jobs applied for
                </p>

            </div>


            {{-- Interviews --}}

            <div
                class="rounded-2xl border
                       border-blue-200 bg-blue-50 p-5"
            >

                <p
                    class="text-xs font-semibold uppercase
                           tracking-wide text-blue-600"
                >
                    Interviews
                </p>

                <p
                    class="mt-2 text-3xl font-bold
                           text-blue-900"
                >
                    {{ $totalInterviews }}
                </p>

                <p
                    class="mt-1 text-sm text-blue-600"
                >
                    Interviews scheduled
                </p>

            </div>


            {{-- Offers --}}

            <div
                class="rounded-2xl border
                       border-violet-200 bg-violet-50 p-5"
            >

                <p
                    class="text-xs font-semibold uppercase
                           tracking-wide text-violet-600"
                >
                    Offers
                </p>

                <p
                    class="mt-2 text-3xl font-bold
                           text-violet-900"
                >
                    {{ $totalOffers }}
                </p>

                <p
                    class="mt-1 text-sm text-violet-600"
                >
                    Employment offers
                </p>

            </div>


            {{-- Accepted --}}

            <div
                class="rounded-2xl border
                       border-emerald-200 bg-emerald-50 p-5"
            >

                <p
                    class="text-xs font-semibold uppercase
                           tracking-wide text-emerald-600"
                >
                    Accepted
                </p>

                <p
                    class="mt-2 text-3xl font-bold
                           text-emerald-900"
                >
                    {{ $acceptedOffers }}
                </p>

                <p
                    class="mt-1 text-sm text-emerald-600"
                >
                    Offers accepted
                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- UPCOMING INTERVIEW --}}
        {{-- ========================================================= --}}

        @if($upcomingInterview)

            <div class="mt-8">

                <div
                    class="overflow-hidden rounded-2xl
                           border border-indigo-200
                           bg-white shadow-sm"
                >

                    <div
                        class="border-b border-slate-100
                               bg-indigo-50 px-6 py-4"
                    >

                        <p
                            class="text-xs font-semibold
                                   uppercase tracking-wide
                                   text-indigo-600"
                        >
                            Upcoming Interview
                        </p>

                    </div>


                    <div class="p-6">

                        <div
                            class="flex flex-col gap-5
                                   sm:flex-row
                                   sm:items-center
                                   sm:justify-between"
                        >

                            <div>

                                <h2
                                    class="text-xl font-bold
                                           text-slate-900"
                                >
                                    {{ $upcomingInterview->application->job->title }}
                                </h2>

                                <p
                                    class="mt-1 text-sm
                                           text-slate-500"
                                >
                                    {{ $upcomingInterview->application->job->company }}
                                </p>

                            </div>


                            <span
                                class="inline-flex w-fit
                                       rounded-full border
                                       border-blue-200
                                       bg-blue-50 px-3 py-1.5
                                       text-xs font-semibold
                                       text-blue-700"
                            >
                                Scheduled
                            </span>

                        </div>


                        <div
                            class="mt-6 grid gap-4
                                   sm:grid-cols-3"
                        >

                            <div
                                class="rounded-xl
                                       bg-slate-50 p-4"
                            >

                                <p
                                    class="text-xs font-semibold
                                           uppercase tracking-wide
                                           text-slate-400"
                                >
                                    Date
                                </p>

                                <p
                                    class="mt-1 text-sm
                                           font-semibold
                                           text-slate-800"
                                >
                                    {{ $upcomingInterview->interview_date->format('d M Y') }}
                                </p>

                            </div>


                            <div
                                class="rounded-xl
                                       bg-slate-50 p-4"
                            >

                                <p
                                    class="text-xs font-semibold
                                           uppercase tracking-wide
                                           text-slate-400"
                                >
                                    Time
                                </p>

                                <p
                                    class="mt-1 text-sm
                                           font-semibold
                                           text-slate-800"
                                >
                                    {{ \Carbon\Carbon::parse($upcomingInterview->start_time)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($upcomingInterview->end_time)->format('h:i A') }}
                                </p>

                            </div>


                            <div
                                class="rounded-xl
                                       bg-slate-50 p-4"
                            >

                                <p
                                    class="text-xs font-semibold
                                           uppercase tracking-wide
                                           text-slate-400"
                                >
                                    Status
                                </p>

                                <p
                                    class="mt-1 text-sm
                                           font-semibold
                                           capitalize
                                           text-indigo-600"
                                >
                                    {{ $upcomingInterview->status }}
                                </p>

                            </div>

                        </div>


                        @if($upcomingInterview->meeting_link)

                            <div class="mt-5">

                                <a
                                    href="{{ $upcomingInterview->meeting_link }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex rounded-xl
                                           bg-indigo-600 px-5 py-3
                                           text-sm font-semibold
                                           text-white transition
                                           hover:bg-indigo-700"
                                >
                                    🎥 Join Google Meet
                                </a>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- TWO COLUMN SECTION --}}
        {{-- ========================================================= --}}

        <div
            class="mt-8 grid gap-6
                   lg:grid-cols-2"
        >


            {{-- Latest Application --}}

            <div
                class="rounded-2xl border
                       border-slate-200 bg-white
                       p-6 shadow-sm"
            >

                <div
                    class="flex items-center
                           justify-between"
                >

                    <div>

                        <p
                            class="text-xs font-semibold
                                   uppercase tracking-wide
                                   text-slate-400"
                        >
                            Latest Application
                        </p>

                        <h2
                            class="mt-1 text-lg font-bold
                                   text-slate-900"
                        >
                            Recent Activity
                        </h2>

                    </div>

                    <a
                        href="{{ route('applications.index') }}"
                        class="text-sm font-semibold
                               text-indigo-600
                               hover:text-indigo-700"
                    >
                        View All →
                    </a>

                </div>


                @if($latestApplication)

                    <div
                        class="mt-5 rounded-xl
                               bg-slate-50 p-5"
                    >

                        <h3
                            class="font-semibold
                                   text-slate-900"
                        >
                            {{ $latestApplication->job->title }}
                        </h3>

                        <p
                            class="mt-1 text-sm
                                   text-slate-500"
                        >
                            {{ $latestApplication->job->company }}
                        </p>


                        <div
                            class="mt-4 flex items-center
                                   justify-between"
                        >

                            <span
                                class="rounded-full
                                       bg-indigo-50 px-3 py-1
                                       text-xs font-semibold
                                       capitalize
                                       text-indigo-700"
                            >
                                {{ $latestApplication->status }}
                            </span>

                            <span
                                class="text-xs
                                       text-slate-400"
                            >
                                {{ $latestApplication->created_at->format('d M Y') }}
                            </span>

                        </div>

                    </div>

                @else

                    <p
                        class="mt-6 text-sm
                               text-slate-500"
                    >
                        You haven't applied for any jobs yet.
                    </p>

                    <a
                        href="{{ route('jobs.index') }}"
                        class="mt-4 inline-flex
                               rounded-xl bg-indigo-600
                               px-4 py-2.5 text-sm
                               font-semibold text-white"
                    >
                        Explore Jobs
                    </a>

                @endif

            </div>


            {{-- Latest Offer --}}

            <div
                class="rounded-2xl border
                       border-slate-200 bg-white
                       p-6 shadow-sm"
            >

                <div
                    class="flex items-center
                           justify-between"
                >

                    <div>

                        <p
                            class="text-xs font-semibold
                                   uppercase tracking-wide
                                   text-slate-400"
                        >
                            Latest Offer
                        </p>

                        <h2
                            class="mt-1 text-lg font-bold
                                   text-slate-900"
                        >
                            Employment Offer
                        </h2>

                    </div>

                </div>


                @if($latestOffer)

                    <div
                        class="mt-5 rounded-xl
                               bg-emerald-50 p-5"
                    >

                        <p
                            class="font-semibold
                                   text-slate-900"
                        >
                            {{ $latestOffer->application->job->title }}
                        </p>

                        <p
                            class="mt-1 text-sm
                                   text-slate-500"
                        >
                            {{ $latestOffer->application->job->company }}
                        </p>


                        <div
                            class="mt-4 grid grid-cols-2
                                   gap-3"
                        >

                            <div>

                                <p
                                    class="text-xs
                                           text-slate-400"
                                >
                                    Salary
                                </p>

                                <p
                                    class="mt-1 text-sm
                                           font-bold
                                           text-slate-900"
                                >
                                    ₹{{ number_format($latestOffer->salary, 2) }}
                                </p>

                            </div>


                            <div>

                                <p
                                    class="text-xs
                                           text-slate-400"
                                >
                                    Status
                                </p>

                                <p
                                    class="mt-1 text-sm
                                           font-semibold
                                           capitalize
                                           text-emerald-700"
                                >
                                    {{ $latestOffer->status }}
                                </p>

                            </div>

                        </div>


                        <a
                            href="{{ route('applications.index') }}"
                            class="mt-5 inline-flex
                                   rounded-xl bg-emerald-600
                                   px-4 py-2.5 text-sm
                                   font-semibold text-white
                                   transition
                                   hover:bg-emerald-700"
                        >
                            View Offer
                        </a>

                    </div>

                @else

                    <div
                        class="mt-5 rounded-xl
                               bg-slate-50 p-5"
                    >

                        <p
                            class="text-sm font-medium
                                   text-slate-700"
                        >
                            No active offers
                        </p>

                        <p
                            class="mt-1 text-xs
                                   text-slate-500"
                        >
                            Your employment offers will
                            appear here.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BROWSE JOBS --}}
        {{-- ========================================================= --}}

        <div
            class="mt-8 rounded-2xl
                   bg-slate-900 p-6
                   sm:p-8"
        >

            <div
                class="flex flex-col gap-5
                       sm:flex-row
                       sm:items-center
                       sm:justify-between"
            >

                <div>

                    <h2
                        class="text-xl font-bold
                               text-white"
                    >
                        Looking for your next opportunity?
                    </h2>

                    <p
                        class="mt-1 text-sm
                               text-slate-300"
                    >
                        Explore available positions
                        and find your next role.
                    </p>

                </div>


                <a
                    href="{{ route('jobs.index') }}"
                    class="inline-flex w-fit
                           rounded-xl bg-white
                           px-5 py-3 text-sm
                           font-semibold text-slate-900
                           transition hover:bg-slate-100"
                >
                    Browse Jobs →
                </a>

            </div>

        </div>

    </div>

</div>

@endsection