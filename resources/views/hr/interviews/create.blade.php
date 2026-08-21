@extends('layouts.hr')

@section('title', 'Schedule Mandatory HR Interview - ' . $application->user->name)
@section('header_title', 'Mandatory HR Interview Scheduling')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb Back --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('hr.applications.show', $application) }}" class="hover:text-purple-600 transition">
            Candidate Profile
        </a>
        <span>/</span>
        <span class="text-[#111111] dark:text-white font-bold">Schedule HR Interview</span>
    </div>

    {{-- Form Container --}}
    <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 sm:p-8 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
        <h1 class="text-xl font-bold tracking-tight text-[#111111] dark:text-white">
            Schedule Mandatory HR Interview
        </h1>
        <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Candidate: <strong class="text-[#111111] dark:text-white">{{ $application->user->name }}</strong> ({{ $application->user->email }}) • Position: <strong class="text-[#111111] dark:text-white">{{ $application->job->title }}</strong>
        </p>

        <form action="{{ route('hr.applications.interview.store', $application) }}" method="POST" class="mt-6 space-y-4">
            @csrf

            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1">
                        Interview Date <span class="text-red-500">*</span>:
                    </label>
                    <input
                        type="date"
                        name="interview_date"
                        value="{{ old('interview_date', now()->addDay()->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3.5 py-2.5 text-xs text-[#111111] outline-none focus:border-purple-600 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1">
                        Start Time <span class="text-red-500">*</span>:
                    </label>
                    <input
                        type="time"
                        name="start_time"
                        value="{{ old('start_time', '10:00') }}"
                        required
                        class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3.5 py-2.5 text-xs text-[#111111] outline-none focus:border-purple-600 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1">
                        End Time <span class="text-red-500">*</span>:
                    </label>
                    <input
                        type="time"
                        name="end_time"
                        value="{{ old('end_time', '10:45') }}"
                        required
                        class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3.5 py-2.5 text-xs text-[#111111] outline-none focus:border-purple-600 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1">
                    Google Meet / Video Link <span class="text-red-500">*</span>:
                </label>
                <input
                    type="url"
                    name="meeting_link"
                    placeholder="https://meet.google.com/abc-defg-hij"
                    value="{{ old('meeting_link') }}"
                    required
                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3.5 py-2.5 text-xs text-[#111111] outline-none focus:border-purple-600 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1">
                    HR Assessment Focus & Instructions:
                </label>
                <textarea
                    name="notes"
                    rows="3"
                    placeholder="E.g. Culture fit, communication assessment, compensation expectations, background screening..."
                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-3 text-xs text-[#111111] outline-none focus:border-purple-600 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button
                    type="submit"
                    class="rounded-xl bg-purple-600 px-6 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-purple-700 transition"
                >
                    Confirm & Schedule HR Interview ✓
                </button>
                <a
                    href="{{ route('hr.applications.show', $application) }}"
                    class="rounded-xl border border-[#E5E5E5] bg-white px-4 py-2.5 text-xs font-bold text-[#6B6B6B] hover:text-[#111111] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>

@endsection
