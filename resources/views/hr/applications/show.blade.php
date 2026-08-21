@extends('layouts.hr')

@section('title', 'Candidate Dossier: ' . $application->user->name)
@section('header_title', 'Applicant Profile & HR Screening')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Breadcrumb Back --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('hr.applications.index') }}" class="hover:text-purple-600 transition">
            Candidates
        </a>
        <span>/</span>
        <span class="text-[#111111] dark:text-white font-bold">{{ $application->user->name }}</span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">
                    HR Recruitment Dossier
                </span>
                @if($application->requiresTechnicalInterview())
                    <span class="rounded-full bg-blue-500/10 px-2.5 py-0.5 text-[10px] font-bold text-blue-600 dark:text-blue-400 border border-blue-500/20">
                        ⚡ Technical Interview Required
                    </span>
                @else
                    <span class="rounded-full bg-slate-500/10 px-2.5 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-500/20">
                        Technical Interview Not Required
                    </span>
                @endif
            </div>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
                {{ $application->user->name }}
            </h1>
            <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                {{ $application->user->email }} • Applied for <strong class="text-[#111111] dark:text-white">{{ $application->job->title }}</strong> ({{ $application->job->company }})
            </p>
        </div>

        <span class="inline-flex rounded-full border border-purple-500/30 bg-purple-500/10 px-4 py-1.5 text-xs font-bold capitalize text-purple-600 dark:text-purple-400">
            Stage: {{ $application->status }}
        </span>
    </div>

    {{-- 2-Column Grid --}}
    <div class="grid gap-6 lg:grid-cols-12">

        <div class="lg:col-span-7 space-y-6">
            {{-- Candidate Details --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    Candidate Information
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
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Applied Date</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $application->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Job Position</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $application->job->title }}</p>
                    </div>
                </div>
            </div>

            {{-- Resume Document --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    Attached Resume
                </h2>
                @if($application->resume)
                    <div class="mt-4 flex items-center justify-between rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A]">
                        <div>
                            <p class="text-xs font-bold text-[#111111] dark:text-white">{{ $application->resume->file_name }}</p>
                            <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">Candidate CV</p>
                        </div>
                        <a
                            href="{{ asset('storage/' . $application->resume->file_path) }}"
                            target="_blank"
                            class="inline-flex items-center gap-1 rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-purple-700 transition"
                        >
                            <span>Open Resume ↗</span>
                        </a>
                    </div>
                @else
                    <p class="mt-4 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">No resume uploaded.</p>
                @endif
            </div>

            {{-- Cover Letter --}}
            @if($application->cover_letter)
                <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                        Cover Letter
                    </h2>
                    <p class="mt-4 text-xs whitespace-pre-line text-[#111111] dark:text-white bg-[#F7F7F7] dark:bg-[#1A1A1A] p-4 rounded-xl">
                        {{ $application->cover_letter }}
                    </p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-5 space-y-6">

            {{-- Mandatory HR Interview Action & Evaluation Card --}}
            <div class="rounded-2xl border border-purple-500/30 bg-white p-6 dark:border-purple-500/30 dark:bg-[#141414] shadow-xs">
                <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                            Mandatory HR Interview
                        </h2>
                        <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Screening & culture assessment</p>
                    </div>
                    @if($application->hrInterview)
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $application->hrInterview->status === 'completed' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-purple-500/10 text-purple-600' }}">
                            {{ $application->hrInterview->status }}
                        </span>
                    @endif
                </div>

                @php $hrInterview = $application->hrInterview ?? $application->interview; @endphp

                @if($hrInterview)
                    <div class="mt-4 space-y-3 text-xs">
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Date & Time:</span>
                            <span class="font-bold text-[#111111] dark:text-white">
                                {{ $hrInterview->interview_date->format('d M Y') }} • {{ \Carbon\Carbon::parse($hrInterview->start_time)->format('h:i A') }}
                            </span>
                        </div>

                        @if($hrInterview->meeting_link)
                            <a
                                href="{{ $hrInterview->meeting_link }}"
                                target="_blank"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-purple-500/30 bg-purple-500/10 py-2 text-xs font-bold text-purple-600 hover:bg-purple-600 hover:text-white transition"
                            >
                                <span>📹 Join HR Meeting</span>
                                <span>↗</span>
                            </a>
                        @endif

                        {{-- If Scheduled: Show Feedback & Pass/Fail evaluation form --}}
                        @if($hrInterview->status === 'scheduled')
                            <div class="pt-3 border-t border-[#E5E5E5] dark:border-[#262626]">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-2">
                                    Complete Interview & Submit HR Decision
                                </h3>
                                <form action="{{ route('hr.applications.interview.complete', $application) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                            HR Evaluation Outcome <span class="text-red-500">*</span>:
                                        </label>
                                        <div class="grid grid-cols-2 gap-2">
                                            <label class="flex items-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-2.5 text-xs font-bold text-emerald-700 dark:text-emerald-300 cursor-pointer">
                                                <input type="radio" name="result" value="passed" checked class="accent-emerald-600">
                                                <span>✓ PASS HR Round</span>
                                            </label>
                                            <label class="flex items-center gap-2 rounded-xl border border-red-500/30 bg-red-500/10 p-2.5 text-xs font-bold text-red-700 dark:text-red-300 cursor-pointer">
                                                <input type="radio" name="result" value="failed" class="accent-red-600">
                                                <span>✕ FAIL HR Round</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                            HR Feedback Notes:
                                        </label>
                                        <textarea
                                            name="admin_feedback"
                                            rows="3"
                                            placeholder="Enter culture fit feedback, communication skills, compensation alignment..."
                                            class="w-full rounded-xl border border-[#E5E5E5] bg-white p-2.5 text-xs text-[#111111] outline-none focus:border-purple-600 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                                        ></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                            Attachment (Optional):
                                        </label>
                                        <input
                                            type="file"
                                            name="feedback_attachment"
                                            accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                                            class="block w-full text-xs text-[#6B6B6B] file:mr-3 file:rounded-xl file:border-0 file:bg-purple-600 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white hover:file:bg-purple-700 cursor-pointer"
                                        >
                                    </div>

                                    <button
                                        type="submit"
                                        class="w-full rounded-xl bg-purple-600 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-purple-700 transition"
                                    >
                                        Save & Submit HR Outcome ✓
                                    </button>
                                </form>
                            </div>
                        @elseif($hrInterview->status === 'completed')
                            <div class="rounded-xl border border-purple-500/30 bg-purple-500/10 p-3.5 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700 dark:text-purple-300">
                                        HR Outcome:
                                    </span>
                                    <span class="font-bold uppercase text-xs {{ $hrInterview->result === 'passed' ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $hrInterview->result ?? 'Completed' }}
                                    </span>
                                </div>
                                @if($hrInterview->admin_feedback)
                                    <p class="text-xs text-[#111111] dark:text-white whitespace-pre-line">
                                        {{ $hrInterview->admin_feedback }}
                                    </p>
                                @endif
                                @if($hrInterview->feedback_attachment_path)
                                    <div class="pt-1.5 border-t border-purple-500/20 flex items-center justify-between">
                                        <span class="text-[11px] font-bold text-purple-800 dark:text-purple-300">Attachment:</span>
                                        <a
                                            href="{{ route('hr.applications.interview.download-attachment', $application) }}"
                                            class="text-xs font-bold text-purple-600 hover:underline"
                                        >
                                            Download 📥
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-4 text-center">
                        <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                            No HR interview scheduled yet.
                        </p>
                        <a
                            href="{{ route('hr.applications.interview.create', $application) }}"
                            class="mt-3 inline-flex items-center gap-1 rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-purple-700 transition"
                        >
                            + Schedule Mandatory HR Interview
                        </a>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>

@endsection
