@extends('layouts.app')

@section('title', 'Candidate Dashboard')

@section('content')

<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">

    {{-- Welcome Header --}}
    <div class="border-b border-[#E5E5E5] pb-8 dark:border-[#262626]">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
                Developer Career Hub
            </span>
            @if($employee)
                <span class="font-mono text-xs font-extrabold text-emerald-600 bg-emerald-500/10 px-2.5 py-0.5 rounded-lg border border-emerald-500/20">
                    Employee ID: {{ $employee->employee_code }}
                </span>
            @endif
        </div>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-4xl dark:text-white">
            Welcome back, {{ Auth::user()->name }}
        </h1>
        <p class="mt-2 text-sm text-[#6B6B6B] dark:text-[#A1A1A1]">
            Your recruitment journey, interview appointments, and job offers at a glance.
        </p>
    </div>

    {{-- ========================================================= --}}
    {{-- HIRED CANDIDATE EMPLOYEE CONFIRMATION BANNER --}}
    {{-- ========================================================= --}}
    @if($employee)
        <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-6 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-600 text-sm font-bold text-white shadow-xs">
                            ✓
                        </span>
                        <h2 class="text-sm font-extrabold text-emerald-800 dark:text-emerald-300">
                            Congratulations! You are officially hired at {{ $employee->application->job->company }}
                        </h2>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-emerald-800 dark:text-emerald-200">
                        <p><strong>Employee ID:</strong> <span class="font-mono font-bold">{{ $employee->employee_code }}</span></p>
                        <span>•</span>
                        <p><strong>Position:</strong> {{ $employee->application->job->title }}</p>
                        <span>•</span>
                        <p><strong>Official Joining Date:</strong> <span class="font-bold">{{ $employee->joining_date->format('d M Y') }}</span> ({{ $employee->joining_date->diffForHumans() }})</p>
                        <span>•</span>
                        <p><strong>Status:</strong> <span class="capitalize font-bold {{ $employee->status === 'active' ? 'text-emerald-700' : 'text-amber-700 dark:text-amber-300' }}">{{ $employee->status }}</span></p>
                    </div>
                </div>

                <div class="shrink-0 flex items-center gap-2">
                    <a
                        href="{{ route('offers.current') }}"
                        class="inline-flex items-center gap-1 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition"
                    >
                        <span>View Offer & Signed Document ↗</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Metric Stat Cards Grid --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Applications --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#141414] transition hover:border-brand-500">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Submitted
                </span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 text-sm font-bold text-brand-500">
                    💼
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                {{ $totalApplications }}
            </p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                Total job applications
            </p>
        </div>

        {{-- Interviews --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#141414] transition hover:border-brand-500">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Interviews
                </span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 text-sm font-bold text-brand-500">
                    📹
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                {{ $totalInterviews }}
            </p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                Evaluations scheduled
            </p>
        </div>

        {{-- Total Offers --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#141414] transition hover:border-brand-500">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Offers
                </span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 text-sm font-bold text-brand-500">
                    🎉
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                {{ $totalOffers }}
            </p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                Received proposals
            </p>
        </div>

        {{-- Hiring / Accepted Status --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#141414] transition hover:border-brand-500">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Hiring Status
                </span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-sm font-bold text-emerald-500">
                    ✓
                </span>
            </div>
            <p class="mt-3 text-2xl font-extrabold tracking-tight text-emerald-600 dark:text-emerald-400">
                {{ $employee ? 'Hired ✓' : ($acceptedOffers > 0 ? $acceptedOffers . ' Accepted' : 'In Progress') }}
            </p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                {{ $employee ? 'Code: ' . $employee->employee_code : 'Confirmed placements' }}
            </p>
        </div>

    </div>

    {{-- Main 2-Column Content Grid --}}
    <div class="grid gap-8 lg:grid-cols-12">

        {{-- Left: Upcoming Interview & Latest Offer --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Upcoming Interview --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 dark:border-[#262626] dark:bg-[#141414]">
                <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                    <h2 class="text-base font-bold text-[#111111] dark:text-white">
                        Upcoming Interview
                    </h2>
                    <span class="text-xs font-bold text-brand-500 uppercase tracking-wider">
                        Next Round
                    </span>
                </div>

                @if($upcomingInterview)
                    <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#1A1A1A]">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-brand-500">
                                    {{ $upcomingInterview->application->job->company }}
                                </span>
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $upcomingInterview->type === 'technical' ? 'bg-blue-500/10 text-blue-600' : 'bg-purple-500/10 text-purple-600' }}">
                                    {{ $upcomingInterview->type === 'technical' ? 'Technical Round' : 'HR Round' }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-[#111111] dark:text-white mt-0.5">
                                {{ $upcomingInterview->application->job->title }}
                            </h3>
                            <p class="mt-1.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                                📅 {{ $upcomingInterview->interview_date->format('l, d F Y') }} • {{ \Carbon\Carbon::parse($upcomingInterview->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($upcomingInterview->end_time)->format('h:i A') }}
                            </p>
                        </div>

                        @if($upcomingInterview->meeting_link)
                            <a
                                href="{{ $upcomingInterview->meeting_link }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 px-5 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
                            >
                                <span>📹 Join Meeting</span>
                                <span>↗</span>
                            </a>
                        @endif
                    </div>
                @else
                    <div class="p-8 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                        ✨ No upcoming interviews scheduled at the moment.
                    </div>
                @endif
            </div>

            {{-- Latest Offer --}}
            @if($latestOffer)
                <div class="rounded-2xl border border-brand-500/30 bg-[#F7F7F7] p-6 dark:border-brand-500/30 dark:bg-[#141414]">
                    <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                        <div>
                            <span class="text-xs font-bold text-brand-500 uppercase tracking-wide">
                                Employment Offer
                            </span>
                            <h2 class="text-base font-bold text-[#111111] dark:text-white">
                                {{ $latestOffer->application->job->title }}
                            </h2>
                        </div>
                        <span class="rounded-full border border-brand-500/30 bg-brand-500/10 px-3 py-1 text-xs font-bold text-brand-500 capitalize">
                            {{ $latestOffer->status === 'accepted' ? 'Offer Accepted ✓' : $latestOffer->status }}
                        </span>
                    </div>

                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">Offered Compensation</p>
                            <p class="text-xl font-extrabold text-[#111111] dark:text-white mt-0.5">
                                ₹{{ number_format($latestOffer->salary, 2) }}
                            </p>
                            @if($latestOffer->joining_date)
                                <p class="text-[11px] text-brand-500 font-semibold mt-1">
                                    Joining Date: {{ $latestOffer->joining_date->format('d M Y') }}
                                </p>
                            @endif
                        </div>

                        <a
                            href="{{ route('offers.current') }}"
                            class="inline-flex items-center gap-1 text-xs font-bold text-brand-500 hover:text-brand-600 transition"
                        >
                            <span>{{ $latestOffer->status === 'accepted' ? 'View Offer Hub →' : 'Respond to Offer →' }}</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>

        {{-- Right: Quick Actions & Profile Summary --}}
        <div class="lg:col-span-4 space-y-6">

            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 dark:border-[#262626] dark:bg-[#141414]">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                    Quick Navigation
                </h2>
                <div class="mt-4 space-y-2">
                    <a
                        href="{{ route('jobs.index') }}"
                        class="flex items-center justify-between rounded-xl border border-[#E5E5E5] bg-white p-3.5 text-xs font-bold text-[#111111] transition hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                        <span>Explore Open Roles</span>
                        <span>→</span>
                    </a>

                    <a
                        href="{{ route('applications.index') }}"
                        class="flex items-center justify-between rounded-xl border border-[#E5E5E5] bg-white p-3.5 text-xs font-bold text-[#111111] transition hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                        <span>Track My Applications</span>
                        <span>→</span>
                    </a>

                    <a
                        href="{{ route('profile') }}"
                        class="flex items-center justify-between rounded-xl border border-[#E5E5E5] bg-white p-3.5 text-xs font-bold text-[#111111] transition hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                        <span>Manage Profile & Resumes</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            {{-- Latest Application Snippet --}}
            @if($latestApplication)
                @php
                    $isLatestAppHired = $latestApplication->employee && ($latestApplication->offer && $latestApplication->offer->status === 'accepted');
                @endphp
                <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 dark:border-[#262626] dark:bg-[#141414]">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                            Most Recent Application
                        </h3>
                        @if($isLatestAppHired)
                            <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                ✓ Hired
                            </span>
                        @else
                            <span class="rounded-full bg-slate-500/10 px-2 py-0.5 text-[10px] font-bold capitalize text-[#6B6B6B] dark:text-[#A1A1A1]">
                                {{ $latestApplication->status }}
                            </span>
                        @endif
                    </div>
                    <div class="mt-3">
                        <p class="text-sm font-bold text-[#111111] dark:text-white">
                            {{ $latestApplication->job->title }}
                        </p>
                        <p class="text-xs text-brand-500 font-semibold">
                            {{ $latestApplication->job->company }}
                        </p>
                        <p class="mt-2 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                            Submitted {{ $latestApplication->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>

@endsection