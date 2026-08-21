@extends('layouts.tr')

@section('title', 'Technical Recruitment Overview')
@section('header_title', 'Engineering Candidate Evaluations & Sourcing')

@section('content')

<div class="space-y-8">

    {{-- Metric Stat Cards Row --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Active Candidates --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Total Candidates</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-500/10 text-sm font-bold text-blue-600 dark:text-blue-400">👥</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">{{ $metrics['totalCandidates'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Applications in funnel</p>
        </div>

        {{-- Scheduled Interviews --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Scheduled Rounds</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-500/10 text-sm font-bold text-blue-600 dark:text-blue-400">📹</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-blue-600 dark:text-blue-400">{{ $metrics['scheduledInterviews'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Technical meetings pending</p>
        </div>

        {{-- Completed Evaluations --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Completed Rounds</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-sm font-bold text-emerald-600 dark:text-emerald-400">✓</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-emerald-600 dark:text-emerald-400">{{ $metrics['completedInterviews'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Evaluations conducted</p>
        </div>

        {{-- Selected Candidates --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Selected Candidates</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 text-sm font-bold text-brand-500">🎉</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-brand-500">{{ $metrics['selectedCandidates'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Recommended for offer</p>
        </div>

    </div>

    {{-- 2-Column Section --}}
    <div class="grid gap-6 lg:grid-cols-12">

        {{-- Upcoming Technical Interviews --}}
        <div class="lg:col-span-6 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                <div class="flex items-center gap-2">
                    <span class="text-base">📹</span>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        Upcoming Technical Rounds
                    </h2>
                </div>
                <a href="{{ route('tr.interviews.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    View All →
                </a>
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
                                    {{ $interview->application->job->title }} • {{ $interview->interview_date->format('d M Y') }}, {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }}
                                </p>
                            </div>

                            @if($interview->meeting_link)
                                <a
                                    href="{{ $interview->meeting_link }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition"
                                >
                                    <span>Meet ↗</span>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    ✨ No technical interviews scheduled today.
                </div>
            @endif
        </div>

        {{-- Recent Assessments & Feedbacks --}}
        <div class="lg:col-span-6 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                <div class="flex items-center gap-2">
                    <span class="text-base">📝</span>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        Recent Evaluations
                    </h2>
                </div>
                <a href="{{ route('tr.applications.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    Candidate Pool →
                </a>
            </div>

            @if($recentEvaluations->count() > 0)
                <div class="mt-4 divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                    @foreach($recentEvaluations as $rev)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold text-[#111111] dark:text-white">
                                    {{ $rev->application->user->name }}
                                </p>
                                <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $rev->application->job->title }} • {{ $rev->updated_at->diffForHumans() }}
                                </p>
                            </div>
                            <a
                                href="{{ route('tr.applications.show', $rev->application) }}"
                                class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:underline"
                            >
                                <span>Scorecard →</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    No completed evaluations on record.
                </div>
            @endif
        </div>

    </div>

</div>

@endsection
