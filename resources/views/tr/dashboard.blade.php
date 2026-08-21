@extends('layouts.tr')

@section('title', 'TR Overview')
@section('header_title', 'Engineering Evaluation & Technical Interviews')

@section('content')

<div class="space-y-8">

    {{-- Metric Stat Cards Row --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Today's Tech Interviews --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Today's Tech Rounds</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-500/10 text-sm font-bold text-blue-600 dark:text-blue-400">⚡</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-blue-600 dark:text-blue-400">{{ $metrics['todayInterviews'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Scheduled for today</p>
        </div>

        {{-- Upcoming Scheduled --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Upcoming Scheduled</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500/10 text-sm font-bold text-brand-500">⏳</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-brand-500">{{ $metrics['scheduledInterviews'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Awaiting evaluation</p>
        </div>

        {{-- Completed --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Evaluations Completed</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-sm font-bold text-emerald-600 dark:text-emerald-400">✓</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-emerald-600 dark:text-emerald-400">{{ $metrics['completedInterviews'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Scorecards submitted</p>
        </div>

        {{-- Tech Candidate Pool --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Tech Candidate Pool</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-500/10 text-sm font-bold text-purple-600 dark:text-purple-400">👥</span>
            </div>
            <p class="mt-3 text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">{{ $metrics['totalCandidates'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Technical job applicants</p>
        </div>

    </div>

    {{-- Today's Scheduled Technical Interviews --}}
    <div class="rounded-2xl border border-blue-500/30 bg-blue-500/5 p-6 dark:border-blue-500/30 dark:bg-[#141414] shadow-xs">
        <div class="flex items-center justify-between border-b border-blue-500/20 pb-4">
            <div class="flex items-center gap-2.5">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-xs font-bold text-white">⚡</span>
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Today's Scheduled Technical Rounds
                    </h2>
                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                        {{ now()->format('l, d F Y') }} • Assigned to you
                    </p>
                </div>
            </div>
            <a href="{{ route('tr.interviews.index', ['filter' => 'today']) }}" class="text-xs font-bold text-blue-600 hover:underline">
                View All Today ({{ $metrics['todayInterviews'] }}) →
            </a>
        </div>

        @if($todayInterviews->count() > 0)
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($todayInterviews as $interview)
                    <div class="rounded-xl border border-blue-500/30 bg-white p-4 dark:border-blue-500/30 dark:bg-[#1A1A1A] space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-bold text-[#111111] dark:text-white">
                                    {{ $interview->application->user->name }}
                                </p>
                                <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $interview->application->job->title }}
                                </p>
                            </div>
                            <span class="rounded-full px-2 py-0.5 text-[9px] font-bold uppercase {{ $interview->status === 'completed' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-blue-600 text-white' }}">
                                {{ $interview->status === 'completed' ? ($interview->result ?? 'Completed') : 'Today' }}
                            </span>
                        </div>

                        <div class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1] flex items-center justify-between pt-2 border-t border-[#E5E5E5] dark:border-[#262626]">
                            <span>🕒 Time:</span>
                            <span class="font-bold text-[#111111] dark:text-white">
                                {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            @if($interview->meeting_link)
                                <a
                                    href="{{ $interview->meeting_link }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center justify-center gap-1 rounded-lg bg-blue-600 py-1.5 text-xs font-bold text-white hover:bg-blue-700 transition"
                                >
                                    <span>📹 Join Meet</span>
                                    <span>↗</span>
                                </a>
                            @else
                                <span class="rounded-lg bg-[#F7F7F7] py-1.5 text-center text-xs text-slate-400">No Meet Link</span>
                            @endif

                            <a
                                href="{{ route('tr.interviews.show', $interview) }}"
                                class="inline-flex items-center justify-center gap-1 rounded-lg border border-[#E5E5E5] bg-white py-1.5 text-xs font-bold text-[#111111] hover:bg-[#F7F7F7] dark:border-[#262626] dark:bg-[#141414] dark:text-white transition"
                            >
                                <span>Scorecard →</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                🎉 No technical interviews scheduled for today. Check upcoming queue below.
            </div>
        @endif
    </div>

    {{-- Main 2-Column Section (Upcoming vs Recent Completed) --}}
    <div class="grid gap-6 lg:grid-cols-12">

        {{-- Upcoming Interviews List --}}
        <div class="lg:col-span-6 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                <div class="flex items-center gap-2">
                    <span class="text-base">📅</span>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        Upcoming Technical Rounds
                    </h2>
                </div>
                <a href="{{ route('tr.interviews.index', ['filter' => 'upcoming']) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
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
                                    {{ $interview->application->job->title }} • {{ $interview->application->job->company }}
                                </p>
                            </div>

                            <div class="text-right flex items-center gap-3">
                                <div>
                                    <span class="inline-flex rounded-full bg-blue-500/10 px-2.5 py-0.5 text-[11px] font-bold text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                        {{ $interview->interview_date->format('d M Y') }}
                                    </span>
                                    <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1] mt-0.5">
                                        {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }}
                                    </p>
                                </div>
                                <a
                                    href="{{ route('tr.interviews.show', $interview) }}"
                                    class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-blue-700 transition"
                                >
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    No upcoming technical interviews in your queue.
                </div>
            @endif
        </div>

        {{-- Recently Completed Scorecards --}}
        <div class="lg:col-span-6 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                <div class="flex items-center gap-2">
                    <span class="text-base">✓</span>
                    <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                        Recent Evaluation History
                    </h2>
                </div>
                <a href="{{ route('tr.interviews.index', ['filter' => 'completed']) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    Completed ({{ $metrics['completedInterviews'] }}) →
                </a>
            </div>

            @if($recentEvaluations->count() > 0)
                <div class="mt-4 divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                    @foreach($recentEvaluations as $interview)
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold text-[#111111] dark:text-white">
                                    {{ $interview->application->user->name }}
                                </p>
                                <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $interview->application->job->title }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $interview->result === 'passed' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                                    {{ $interview->result ?? 'Completed' }}
                                </span>
                                @if($interview->feedback_submitted_at)
                                    <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1] mt-0.5">
                                        {{ $interview->feedback_submitted_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    No completed evaluations yet.
                </div>
            @endif
        </div>

    </div>

</div>

@endsection
