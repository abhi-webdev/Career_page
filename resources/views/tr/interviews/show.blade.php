@extends('layouts.tr')

@section('title', 'Technical Interview: ' . $application->user->name)
@section('header_title', 'My Assigned Technical Interview')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Breadcrumb Back --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('tr.interviews.index') }}" class="hover:text-blue-600 transition">
            ← Back to My Technical Interviews
        </a>
        <span>/</span>
        <span class="text-[#111111] dark:text-white font-bold">{{ $application->user->name }}</span>
    </div>

    {{-- Header Banner --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                    ⚡ Technical Assessment Round
                </span>
                <span class="rounded-full bg-blue-500/10 px-2.5 py-0.5 text-[10px] font-bold text-blue-700 dark:text-blue-300 border border-blue-500/20">
                    Assigned to: {{ $interview->interviewer ? $interview->interviewer->name : 'You' }}
                </span>
            </div>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
                {{ $application->user->name }}
            </h1>
            <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                {{ $application->user->email }} • Position: <strong class="text-[#111111] dark:text-white">{{ $application->job->title }}</strong> ({{ $application->job->company }})
            </p>
        </div>

        <span class="inline-flex rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-1.5 text-xs font-bold capitalize text-blue-600 dark:text-blue-400">
            Interview: {{ $interview->status }}
        </span>
    </div>

    {{-- Main 2-Column Grid --}}
    <div class="grid gap-6 lg:grid-cols-12">

        {{-- Left: Candidate Profile, Skills & Resume --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- Candidate Details & Skills --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    Technical Candidate Profile
                </h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 text-xs">
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Full Name</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $application->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Email Address</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $application->user->email }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Target Role</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $application->job->title }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Experience Requirement</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $application->job->experience ?? 'Standard' }}</p>
                    </div>
                </div>

                {{-- Skills Required --}}
                @if($application->job->skills && count($application->job->skills) > 0)
                    <div class="mt-4 pt-3 border-t border-[#E5E5E5] dark:border-[#262626]">
                        <span class="text-[11px] font-bold text-[#6B6B6B] dark:text-[#A1A1A1]">Required Skills & Stack:</span>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach($application->job->skills as $skill)
                                <span class="rounded-lg bg-blue-500/10 px-2.5 py-1 text-xs font-bold text-blue-700 dark:text-blue-300">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- HR Screening Summary --}}
            @if($application->hrInterview)
                <div class="rounded-2xl border border-purple-500/30 bg-purple-500/5 p-6 dark:border-purple-500/30 dark:bg-[#141414] shadow-xs">
                    <div class="flex items-center justify-between border-b border-purple-500/20 pb-3">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-purple-700 dark:text-purple-300">
                            ✓ HR Screening Summary
                        </h2>
                        <span class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold uppercase text-emerald-600">
                            {{ $application->hrInterview->result ?? 'Passed' }}
                        </span>
                    </div>
                    <div class="mt-3 text-xs space-y-2">
                        <div class="flex justify-between text-[#6B6B6B] dark:text-[#A1A1A1]">
                            <span>HR Interviewer:</span>
                            <span class="font-bold text-[#111111] dark:text-white">{{ $application->hrInterview->interviewer?->name ?? 'HR Team' }}</span>
                        </div>
                        @if($application->hrInterview->admin_feedback)
                            <div class="pt-2">
                                <span class="text-[11px] font-bold text-[#111111] dark:text-white">HR Evaluation Note:</span>
                                <p class="mt-1 text-xs text-[#111111] dark:text-white whitespace-pre-line bg-white dark:bg-[#1A1A1A] p-3 rounded-xl border border-purple-500/20">
                                    {{ $application->hrInterview->admin_feedback }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Attached Resume --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    Technical Resume
                </h2>
                @if($application->resume)
                    <div class="mt-4 flex items-center justify-between rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A]">
                        <div>
                            <p class="text-xs font-bold text-[#111111] dark:text-white">{{ $application->resume->file_name }}</p>
                            <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">Uploaded document</p>
                        </div>
                        <a
                            href="{{ asset('storage/' . $application->resume->file_path) }}"
                            target="_blank"
                            class="inline-flex items-center gap-1 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition"
                        >
                            <span>Open Resume ↗</span>
                        </a>
                    </div>
                @else
                    <p class="mt-4 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">No resume attached.</p>
                @endif
            </div>

        </div>

        {{-- Right: Interview Timeline & Scorecard Evaluation Form --}}
        <div class="lg:col-span-5 space-y-6">

            <div class="rounded-2xl border border-blue-500/30 bg-white p-6 dark:border-blue-500/30 dark:bg-[#141414] shadow-xs">
                <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                            Interview Schedule
                        </h2>
                        <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                            {{ $interview->interview_date->format('l, d M Y') }}
                        </p>
                    </div>
                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $interview->status === 'completed' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-blue-500/10 text-blue-600' }}">
                        {{ $interview->status }}
                    </span>
                </div>

                <div class="mt-4 space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Time:</span>
                        <span class="font-bold text-[#111111] dark:text-white">
                            {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Technical Interviewer:</span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">
                            {{ $interview->interviewer ? $interview->interviewer->name : 'You' }} (TR)
                        </span>
                    </div>

                    @if($interview->notes)
                        <div class="pt-2 border-t border-[#E5E5E5] dark:border-[#262626]">
                            <span class="text-[11px] font-bold text-[#111111] dark:text-white">Technical Instructions:</span>
                            <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1] mt-0.5 whitespace-pre-line">
                                {{ $interview->notes }}
                            </p>
                        </div>
                    @endif

                    @if($interview->meeting_link)
                        <div class="pt-2">
                            <a
                                href="{{ $interview->meeting_link }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition"
                            >
                                <span>📹 Join Google Meet</span>
                                <span>↗</span>
                            </a>
                        </div>
                    @endif

                    {{-- If Scheduled: Show Evaluation and Decision Form --}}
                    @if($interview->status === 'scheduled')
                        <div class="pt-4 border-t border-[#E5E5E5] dark:border-[#262626]">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-2">
                                Complete Technical Evaluation
                            </h3>
                            <form action="{{ route('tr.applications.interview.complete', $application) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                        Technical Recommendation <span class="text-red-500">*</span>:
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="flex items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-2.5 text-xs font-bold text-emerald-700 dark:text-emerald-300 cursor-pointer">
                                            <input type="radio" name="result" value="passed" checked class="accent-emerald-600">
                                            <span>✓ PASS Technical Round</span>
                                        </label>
                                        <label class="flex items-center gap-2 rounded-xl border border-red-500/30 bg-red-500/10 p-2.5 text-xs font-bold text-red-700 dark:text-red-300 cursor-pointer">
                                            <input type="radio" name="result" value="failed" class="accent-red-600">
                                            <span>✕ FAIL Technical Round</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                        Technical Feedback & Code Review Notes:
                                    </label>
                                    <textarea
                                        name="admin_feedback"
                                        rows="3"
                                        placeholder="System architecture, coding proficiency, problem solving, data structures..."
                                        class="w-full rounded-xl border border-[#E5E5E5] bg-white p-2.5 text-xs text-[#111111] outline-none focus:border-blue-600 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                                    ></textarea>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                        Scorecard Attachment (Optional):
                                    </label>
                                    <input
                                        type="file"
                                        name="feedback_attachment"
                                        accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                                        class="block w-full text-xs text-[#6B6B6B] file:mr-3 file:rounded-xl file:border-0 file:bg-blue-600 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white hover:file:bg-blue-700 cursor-pointer"
                                    >
                                </div>

                                <button
                                    type="submit"
                                    class="w-full rounded-xl bg-blue-600 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition"
                                >
                                    Submit Technical Evaluation & Complete Interview ✓
                                </button>
                            </form>
                        </div>
                    @elseif($interview->status === 'completed')
                        <div class="rounded-xl border border-blue-500/30 bg-blue-500/10 p-3.5 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 dark:text-blue-300">
                                    Submitted Recommendation:
                                </span>
                                <span class="font-bold uppercase text-xs {{ $interview->result === 'passed' ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $interview->result ?? 'Completed' }}
                                </span>
                            </div>
                            @if($interview->admin_feedback)
                                <p class="text-xs text-[#111111] dark:text-white whitespace-pre-line">
                                    {{ $interview->admin_feedback }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
