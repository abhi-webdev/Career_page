@extends('layouts.admin')

@section('title', 'Recruitment Analytics')
@section('header_title', 'Analytics & ATS Overview')

@section('content')

<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Top Greeting & Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
                Pipeline Intelligence
            </span>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
                Recruitment Dashboard
            </h1>
            <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                Real-time tracking of candidate applications, interview velocity, offer decisions, and employee onboarding.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a
                href="{{ route('admin.employees.index') }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-[#E5E5E5] bg-white px-4 py-2.5 text-xs font-bold text-[#111111] shadow-xs transition hover:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
            >
                <span>👔</span>
                <span>Employee Directory</span>
            </a>
            <a
                href="{{ route('admin.jobs.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
            >
                <span>+</span>
                <span>Create Job Opening</span>
            </a>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- 1. PRIMARY METRIC STAT CARDS --}}
    {{-- ========================================================= --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        
        {{-- Total Applications --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Total Applications
                </span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 text-xs font-bold text-brand-500">
                    👥
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                {{ $totalApplications }}
            </p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                Across all published roles
            </p>
        </div>

        {{-- Active Job Openings --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Active Roles
                </span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 text-xs font-bold text-brand-500">
                    💼
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                {{ $totalJobs }}
            </p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                Live on candidate portal
            </p>
        </div>

        {{-- Total Interviews --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Interviews Pipeline
                </span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 text-xs font-bold text-brand-500">
                    📹
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                {{ $totalInterviews }}
            </p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                Total interviews conducted
            </p>
        </div>

        {{-- Offers Sent --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Offers Issued
                </span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-xs font-bold text-emerald-500">
                    🎉
                </span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                {{ $totalOffers }}
            </p>
            <p class="mt-1 text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
                {{ $offerAccepted }} Accepted • {{ $offerPending }} Pending
            </p>
        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- 2. EMPLOYEE ONBOARDING ANALYTICS ROW --}}
    {{-- ========================================================= --}}
    <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
        <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
            <div class="flex items-center gap-2.5">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-500/10 font-bold text-brand-500">
                    👔
                </span>
                <div>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        Employee & Onboarding Metrics
                    </h2>
                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                        Hired candidates with signed offer letters and scheduled joining dates
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.employees.index') }}" class="text-xs font-bold text-brand-500 hover:underline">
                View All Employees →
            </a>
        </div>

        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total Employees --}}
            <div class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Total Employees
                </span>
                <p class="mt-2 text-2xl font-extrabold text-[#111111] dark:text-white">
                    {{ $totalEmployees }}
                </p>
                <p class="mt-0.5 text-[11px] text-brand-500 font-semibold">Completed hires</p>
            </div>

            {{-- Upcoming Joinings --}}
            <div class="rounded-xl border border-brand-500/30 bg-brand-500/10 p-4">
                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                    Upcoming Joinings
                </span>
                <p class="mt-2 text-2xl font-extrabold text-brand-500">
                    {{ $upcomingJoiningsCount }}
                </p>
                <p class="mt-0.5 text-[11px] text-brand-600 dark:text-brand-400 font-semibold">Scheduled in future</p>
            </div>

            {{-- Active Employees --}}
            <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                    Active
                </span>
                <p class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">
                    {{ $activeEmployees }}
                </p>
                <p class="mt-0.5 text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">Joined & active</p>
            </div>

            {{-- Pending Joinings --}}
            <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-4">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">
                    Pending
                </span>
                <p class="mt-2 text-2xl font-extrabold text-amber-600 dark:text-amber-400">
                    {{ $pendingEmployees }}
                </p>
                <p class="mt-0.5 text-[11px] text-amber-600 dark:text-amber-400 font-semibold">Awaiting start date</p>
            </div>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- 3. RECRUITMENT FUNNEL & STATUS DISTRIBUTION --}}
    {{-- ========================================================= --}}
    <div class="grid gap-6 lg:grid-cols-12">
        
        {{-- Recruitment Funnel Card --}}
        <div class="lg:col-span-7 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Hiring Pipeline Funnel
                    </h2>
                    <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                        Candidate volume progression across stages
                    </p>
                </div>
                <span class="rounded-full bg-brand-500/10 px-2.5 py-0.5 text-xs font-bold text-brand-500">
                    {{ $totalApplications > 0 ? round(($selectedCandidates / $totalApplications) * 100, 1) : 0 }}% Conversion
                </span>
            </div>

            <div class="mt-6 space-y-4">
                {{-- Step 1: Applied --}}
                @php
                    $appliedPct = 100;
                    $shortlistedPct = $totalApplications > 0 ? round(($funnelShortlisted / $totalApplications) * 100) : 0;
                    $interviewPct = $totalApplications > 0 ? round(($funnelInterview / $totalApplications) * 100) : 0;
                    $selectedPct = $totalApplications > 0 ? round(($funnelSelected / $totalApplications) * 100) : 0;
                    $acceptedPct = $totalApplications > 0 ? round(($funnelAccepted / $totalApplications) * 100) : 0;
                @endphp

                <div>
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-[#111111] dark:text-white">1. Applied Candidates</span>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $funnelApplied }} ({{ $appliedPct }}%)</span>
                    </div>
                    <div class="mt-1.5 h-2.5 w-full rounded-full bg-[#F7F7F7] dark:bg-[#1A1A1A]">
                        <div class="h-2.5 rounded-full bg-[#111111] dark:bg-white" style="width: {{ $appliedPct }}%"></div>
                    </div>
                </div>

                {{-- Step 2: Shortlisted --}}
                <div>
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-[#111111] dark:text-white">2. Shortlisted for Review</span>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $funnelShortlisted }} ({{ $shortlistedPct }}%)</span>
                    </div>
                    <div class="mt-1.5 h-2.5 w-full rounded-full bg-[#F7F7F7] dark:bg-[#1A1A1A]">
                        <div class="h-2.5 rounded-full bg-blue-500" style="width: {{ $shortlistedPct }}%"></div>
                    </div>
                </div>

                {{-- Step 3: Interviewed --}}
                <div>
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-[#111111] dark:text-white">3. Interview Evaluated</span>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $funnelInterview }} ({{ $interviewPct }}%)</span>
                    </div>
                    <div class="mt-1.5 h-2.5 w-full rounded-full bg-[#F7F7F7] dark:bg-[#1A1A1A]">
                        <div class="h-2.5 rounded-full bg-brand-500" style="width: {{ $interviewPct }}%"></div>
                    </div>
                </div>

                {{-- Step 4: Selected for Offer --}}
                <div>
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-[#111111] dark:text-white">4. Selected for Offer</span>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $funnelSelected }} ({{ $selectedPct }}%)</span>
                    </div>
                    <div class="mt-1.5 h-2.5 w-full rounded-full bg-[#F7F7F7] dark:bg-[#1A1A1A]">
                        <div class="h-2.5 rounded-full bg-emerald-500" style="width: {{ $selectedPct }}%"></div>
                    </div>
                </div>

                {{-- Step 5: Offer Accepted & Hired --}}
                <div>
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-[#111111] dark:text-white">5. Offer Accepted & Hired</span>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $funnelAccepted }} ({{ $acceptedPct }}%)</span>
                    </div>
                    <div class="mt-1.5 h-2.5 w-full rounded-full bg-[#F7F7F7] dark:bg-[#1A1A1A]">
                        <div class="h-2.5 rounded-full bg-emerald-600" style="width: {{ $acceptedPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Application Status Breakdown --}}
        <div class="lg:col-span-5 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                    Stage Distribution
                </h2>

                <div class="mt-5 space-y-3">
                    @foreach($statusCounts as $statusName => $count)
                        @php
                            $badgeColor = match($statusName) {
                                'Pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'Shortlisted' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                'Interview' => 'bg-brand-500/10 text-brand-500 border-brand-500/20',
                                'Selected' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'Rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                            };
                        @endphp
                        <div class="flex items-center justify-between py-1 text-xs">
                            <span class="inline-flex rounded-full border px-2.5 py-0.5 font-bold {{ $badgeColor }}">
                                {{ $statusName }}
                            </span>
                            <span class="font-bold text-[#111111] dark:text-white">
                                {{ $count }} candidates
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 text-xs dark:border-[#262626] dark:bg-[#1A1A1A]">
                <p class="font-bold text-[#111111] dark:text-white">Quick Action</p>
                <p class="mt-0.5 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Review shortlisted candidates waiting for interview rounds.</p>
                <a href="{{ route('admin.applications.index') }}" class="mt-2 inline-block font-bold text-brand-500 hover:underline">
                    Go to Applications ATS →
                </a>
            </div>
        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- 4. UPCOMING JOININGS & UPCOMING INTERVIEWS --}}
    {{-- ========================================================= --}}
    <div class="grid gap-6 lg:grid-cols-12">
        
        {{-- Upcoming Joinings Card --}}
        <div class="lg:col-span-6 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                <div class="flex items-center gap-2">
                    <span class="text-base">📅</span>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        Upcoming Joinings
                    </h2>
                </div>
                <span class="text-xs font-bold text-brand-500">
                    {{ $upcomingJoinings->count() }} Pending Arrival
                </span>
            </div>

            @if($upcomingJoinings->count() > 0)
                <div class="mt-4 divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                    @foreach($upcomingJoinings as $emp)
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-500/10 font-bold text-brand-500 text-xs">
                                    {{ $emp->employee_code }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-[#111111] dark:text-white">
                                        {{ $emp->user->name }}
                                    </p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                        {{ $emp->application->job->title }} • {{ $emp->application->job->company }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="inline-flex rounded-full bg-brand-500/10 px-2.5 py-0.5 text-[11px] font-bold text-brand-500 border border-brand-500/20">
                                    {{ $emp->joining_date->format('d M Y') }}
                                </span>
                                <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1] mt-0.5">
                                    {{ $emp->joining_date->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    ✨ No pending upcoming joinings scheduled.
                </div>
            @endif
        </div>

        {{-- Upcoming Scheduled Interviews --}}
        <div class="lg:col-span-6 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                <div class="flex items-center gap-2">
                    <span class="text-base">📹</span>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        Upcoming Interviews
                    </h2>
                </div>
                <span class="text-xs font-bold text-brand-500">
                    {{ $upcomingInterviews->count() }} Scheduled
                </span>
            </div>

            @if($upcomingInterviews->count() > 0)
                <div class="mt-4 divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                    @foreach($upcomingInterviews as $interview)
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold text-[#111111] dark:text-white">
                                    {{ $interview->application->user->name }}
                                </p>
                                <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $interview->application->job->title }} • {{ $interview->interview_date->format('d M Y') }} at {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }}
                                </p>
                            </div>

                            <a
                                href="{{ route('admin.applications.show', $interview->application) }}"
                                class="shrink-0 rounded-lg border border-[#E5E5E5] px-2.5 py-1.5 text-xs font-bold text-[#111111] hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:text-white transition"
                            >
                                Review →
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    ✨ No upcoming interviews scheduled today.
                </div>
            @endif
        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- 5. RECENT CANDIDATE APPLICATIONS TABLE --}}
    {{-- ========================================================= --}}
    <div class="overflow-hidden rounded-2xl border border-[#E5E5E5] bg-white shadow-xs dark:border-[#262626] dark:bg-[#141414]">
        <div class="flex items-center justify-between border-b border-[#E5E5E5] px-6 py-4 dark:border-[#262626]">
            <div>
                <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                    Latest Candidate Submissions
                </h2>
                <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Recently submitted applications waiting for review
                </p>
            </div>
            <a
                href="{{ route('admin.applications.index') }}"
                class="text-xs font-bold text-brand-500 hover:text-brand-600 transition"
            >
                View Full ATS Feed →
            </a>
        </div>

        @if($recentApplications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#111111] dark:text-white">
                    <thead class="bg-[#F7F7F7] text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]">
                        <tr>
                            <th class="px-6 py-3.5">Candidate</th>
                            <th class="px-6 py-3.5">Position</th>
                            <th class="px-6 py-3.5">Applied Date</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5] font-medium dark:divide-[#262626]">
                        @foreach($recentApplications as $app)
                            @php
                                $appStatus = strtolower($app->status);
                                $statusBadge = match($appStatus) {
                                    'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'shortlisted' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'interview' => 'bg-brand-500/10 text-brand-500 border-brand-500/20',
                                    'selected' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                    default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                };
                            @endphp
                            <tr class="transition hover:bg-[#F7F7F7] dark:hover:bg-[#1A1A1A]">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-[#111111] dark:text-white">{{ $app->user->name }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $app->user->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold">{{ $app->job->title }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $app->job->company }}</p>
                                </td>
                                <td class="px-6 py-4 text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $app->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full border px-2.5 py-0.5 text-[11px] font-bold capitalize {{ $statusBadge }}">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('admin.applications.show', $app) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-brand-500/10 px-3 py-1.5 text-xs font-bold text-brand-500 transition hover:bg-brand-500 hover:text-white"
                                    >
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@endsection