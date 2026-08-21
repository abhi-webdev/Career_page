@extends('layouts.hr')

@section('title', 'HR Overview')
@section('header_title', 'Human Resources Operations & Onboarding')

@section('content')

<div class="space-y-8">

    {{-- Metric Stat Cards Row --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total Employees --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Total Staff</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-500/10 text-sm font-bold text-purple-600 dark:text-purple-400">👔</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">{{ $metrics['totalEmployees'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Hired employee directory</p>
        </div>

        {{-- Upcoming Joinings --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Upcoming Joinings</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 text-sm font-bold text-brand-500">📅</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-brand-500">{{ $metrics['upcomingJoinings'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Awaiting start date</p>
        </div>

        {{-- Active Employees --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Active Staff</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-sm font-bold text-emerald-600 dark:text-emerald-400">✓</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-emerald-600 dark:text-emerald-400">{{ $metrics['activeEmployees'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Joined and confirmed</p>
        </div>

        {{-- Pending Onboarding --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Pending Onboarding</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-500/10 text-sm font-bold text-amber-600 dark:text-amber-400">⏳</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-amber-600 dark:text-amber-400">{{ $metrics['pendingEmployees'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Pending induction</p>
        </div>

    </div>

    {{-- Main 2-Column Section --}}
    <div class="grid gap-6 lg:grid-cols-12">

        {{-- Upcoming Joinings Schedule --}}
        <div class="lg:col-span-6 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                <div class="flex items-center gap-2">
                    <span class="text-base">📅</span>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        Upcoming Joining Schedule
                    </h2>
                </div>
                <a href="{{ route('hr.employees.index', ['status' => 'pending']) }}" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline">
                    View All →
                </a>
            </div>

            @if($upcomingJoinings->count() > 0)
                <div class="mt-4 divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                    @foreach($upcomingJoinings as $joining)
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-500/10 font-bold text-purple-600 text-xs font-mono">
                                    {{ $joining->employee_code }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-[#111111] dark:text-white">
                                        {{ $joining->user->name }}
                                    </p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                        {{ $joining->application->job->title }} • {{ $joining->application->job->company }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="inline-flex rounded-full bg-purple-500/10 px-2.5 py-0.5 text-[11px] font-bold text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                    {{ $joining->joining_date->format('d M Y') }}
                                </span>
                                <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1] mt-0.5">
                                    {{ $joining->joining_date->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    ✨ No pending joinings scheduled in the immediate pipeline.
                </div>
            @endif
        </div>

        {{-- Recent Employees Added --}}
        <div class="lg:col-span-6 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                <div class="flex items-center gap-2">
                    <span class="text-base">👔</span>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        Recently Hired Staff
                    </h2>
                </div>
                <a href="{{ route('hr.employees.index') }}" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline">
                    Directory →
                </a>
            </div>

            @if($recentEmployees->count() > 0)
                <div class="mt-4 divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                    @foreach($recentEmployees as $emp)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold text-[#111111] dark:text-white">
                                    {{ $emp->user->name }}
                                </p>
                                <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $emp->employee_code }} • {{ $emp->application->job->title }}
                                </p>
                            </div>
                            <a
                                href="{{ route('hr.employees.show', $emp) }}"
                                class="inline-flex items-center gap-1 text-xs font-bold text-purple-600 hover:underline"
                            >
                                <span>Profile →</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    No employees on record yet.
                </div>
            @endif
        </div>

    </div>

</div>

@endsection
