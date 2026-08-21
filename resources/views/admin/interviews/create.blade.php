@extends('layouts.admin')

@section('title', 'Schedule Interview: ' . $application->user->name)
@section('header_title', 'Interviews')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('admin.applications.show', $application) }}" class="hover:text-brand-500 transition">
            ← Back to Candidate Review
        </a>
    </div>

    <div>
        <span class="text-xs font-bold uppercase tracking-wider {{ $type === 'technical' ? 'text-blue-600 dark:text-blue-400' : 'text-purple-600 dark:text-purple-400' }}">
            {{ $type === 'technical' ? '⚡ Technical Assessment Round' : 'Mandatory HR Screening Round' }}
        </span>
        <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
            {{ $targetInterview && $targetInterview->status === 'scheduled' ? 'Reschedule' : 'Schedule' }} {{ $type === 'technical' ? 'Technical' : 'HR' }} Interview
        </h1>
        <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Assign an authorized interviewer and set date, time, and Google Meet URL for {{ $application->user->name }}.
        </p>
    </div>

    {{-- Candidate Context Snippet --}}
    <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-[#111111] dark:text-white">
                    Candidate: {{ $application->user->name }}
                </p>
                <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                    {{ $application->user->email }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-brand-500">
                    {{ $application->job->title }}
                </p>
                <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                    {{ $application->job->company }} • {{ $application->job->technical_interview_required ? 'Technical Job' : 'Non-Technical Job' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-4">
            <p class="text-xs font-bold text-red-600 dark:text-red-400">Please correct the following errors:</p>
            <ul class="mt-1 list-disc pl-5 text-xs text-red-600 dark:text-red-400 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.applications.interview.store', $application) }}" method="POST" class="rounded-2xl border border-[#E5E5E5] bg-white p-6 sm:p-8 dark:border-[#262626] dark:bg-[#141414] shadow-xs space-y-5">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">

        {{-- Interviewer Assignment Dropdown --}}
        <div>
            <label for="interviewer_id" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                {{ $type === 'technical' ? 'Assign Technical Interviewer *' : 'Assign HR Interviewer *' }}
            </label>
            <select
                id="interviewer_id"
                name="interviewer_id"
                required
                class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
            >
                <option value="">-- Select {{ $type === 'technical' ? 'TR Recruiter' : 'HR Specialist' }} --</option>
                @if($type === 'technical')
                    @foreach($trInterviewers as $tr)
                        <option
                            value="{{ $tr->id }}"
                            {{ old('interviewer_id', optional($targetInterview)->interviewer_id) == $tr->id ? 'selected' : '' }}
                        >
                            {{ $tr->name }} ({{ $tr->email }}) — Technical Recruiter
                        </option>
                    @endforeach
                @else
                    @foreach($hrInterviewers as $hr)
                        <option
                            value="{{ $hr->id }}"
                            {{ old('interviewer_id', optional($targetInterview)->interviewer_id) == $hr->id ? 'selected' : '' }}
                        >
                            {{ $hr->name }} ({{ $hr->email }}) — HR Recruiter
                        </option>
                    @endforeach
                @endif
            </select>
            <p class="mt-1 text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                {{ $type === 'technical' ? 'Only TR employees can be assigned to conduct technical rounds.' : 'Only HR employees can be assigned to conduct HR rounds.' }}
            </p>
        </div>

        {{-- Date --}}
        <div>
            <label for="interview_date" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                Interview Date *
            </label>
            <input
                id="interview_date"
                name="interview_date"
                type="date"
                value="{{ old('interview_date', optional($targetInterview)->interview_date?->format('Y-m-d')) }}"
                min="{{ now()->format('Y-m-d') }}"
                required
                class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
            >
        </div>

        {{-- Start & End Time --}}
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="start_time" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                    Start Time *
                </label>
                <input
                    id="start_time"
                    name="start_time"
                    type="time"
                    value="{{ old('start_time', optional($targetInterview)->start_time) }}"
                    required
                    class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
            </div>

            <div>
                <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                    End Time *
                </label>
                <input
                    id="end_time"
                    name="end_time"
                    type="time"
                    value="{{ old('end_time', optional($targetInterview)->end_time) }}"
                    required
                    class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
            </div>
        </div>

        {{-- Google Meet Link --}}
        <div>
            <label for="meeting_link" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                Google Meet Video URL *
            </label>
            <input
                id="meeting_link"
                name="meeting_link"
                type="url"
                value="{{ old('meeting_link', optional($targetInterview)->meeting_link) }}"
                placeholder="https://meet.google.com/xxx-xxxx-xxx"
                required
                class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
            >
            <p class="mt-1 text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                Candidate and assigned interviewer will receive this meeting link automatically with direct one-click access from their portals.
            </p>
            <p class="mt-1 text-[10px] font-semibold text-brand-500">
                💡 Google Meet Tip: Set meeting access to "Open to all with link" or invite the interviewer's Google account so they can enter without requiring host manual admission.
            </p>
        </div>

        {{-- Notes --}}
        <div>
            <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                Interview Notes / Instructions (Optional)
            </label>
            <textarea
                id="notes"
                name="notes"
                rows="4"
                placeholder="e.g. Focus on communication and system design assessment..."
                class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
            >{{ old('notes', optional($targetInterview)->notes) }}</textarea>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E5E5E5] dark:border-[#262626]">
            <a
                href="{{ route('admin.applications.show', $application) }}"
                class="rounded-xl border border-[#E5E5E5] bg-white px-5 py-2.5 text-xs font-bold text-[#111111] transition hover:bg-[#F7F7F7] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
            >
                Cancel
            </a>
            <button
                type="submit"
                class="rounded-xl {{ $type === 'technical' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-purple-600 hover:bg-purple-700' }} px-6 py-2.5 text-xs font-bold text-white shadow-xs transition"
            >
                {{ $targetInterview && $targetInterview->status === 'scheduled' ? 'Update Schedule →' : 'Schedule Interview →' }}
            </button>
        </div>
    </form>

</div>

@endsection