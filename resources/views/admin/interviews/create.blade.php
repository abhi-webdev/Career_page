@extends('layouts.app')

@section('title', 'Schedule Interview')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Back --}}

        <a
            href="{{ route('admin.applications.show', $application) }}"
            class="text-sm font-medium text-slate-500 hover:text-indigo-600"
        >
            ← Back to Application
        </a>


        {{-- Header --}}

        <div class="mt-8">

            <p class="text-sm font-semibold text-indigo-600">
                Interview Management
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                Schedule Interview
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Schedule an interview for {{ $application->user->name }}.
            </p>

        </div>


        {{-- Candidate Card --}}

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                Candidate
            </p>

            <h2 class="mt-2 text-lg font-bold text-slate-900">
                {{ $application->user->name }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ $application->user->email }}
            </p>


            <div class="mt-4 border-t border-slate-100 pt-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Position
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $application->job->title }}
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $application->job->company }}
                </p>

            </div>

        </div>


        {{-- Validation Errors --}}

        @if($errors->any())

            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4">

                <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Form --}}

        <form
            action="{{ route('admin.applications.interview.store', $application) }}"
            method="POST"
            class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >

            @csrf


            {{-- Date --}}

            <div>

                <label
                    for="interview_date"
                    class="text-sm font-semibold text-slate-700"
                >
                    Interview Date
                </label>

                <input
                    id="interview_date"
                    name="interview_date"
                    type="date"
                    value="{{ old('interview_date', optional($application->interview)->interview_date?->format('Y-m-d')) }}"
                    min="{{ now()->format('Y-m-d') }}"
                    required
                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

            </div>


            {{-- Time --}}

            <div class="mt-6 grid gap-6 sm:grid-cols-2">

                <div>

                    <label
                        for="start_time"
                        class="text-sm font-semibold text-slate-700"
                    >
                        Start Time
                    </label>

                    <input
                        id="start_time"
                        name="start_time"
                        type="time"
                        value="{{ old('start_time', optional($application->interview)->start_time) }}"
                        required
                        class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                </div>


                <div>

                    <label
                        for="end_time"
                        class="text-sm font-semibold text-slate-700"
                    >
                        End Time
                    </label>

                    <input
                        id="end_time"
                        name="end_time"
                        type="time"
                        value="{{ old('end_time', optional($application->interview)->end_time) }}"
                        required
                        class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                </div>

            </div>


            {{-- Google Meet --}}

            <div class="mt-6">

                <label
                    for="meeting_link"
                    class="text-sm font-semibold text-slate-700"
                >
                    Google Meet Link
                </label>

                <input
                    id="meeting_link"
                    name="meeting_link"
                    type="url"
                    value="{{ old('meeting_link', optional($application->interview)->meeting_link) }}"
                    placeholder="https://meet.google.com/xxx-xxxx-xxx"
                    required
                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

                <p class="mt-2 text-xs text-slate-400">
                    Create the Google Meet separately and paste the meeting URL here.
                </p>

            </div>


            {{-- Notes --}}

            <div class="mt-6">

                <label
                    for="notes"
                    class="text-sm font-semibold text-slate-700"
                >
                    Interview Notes
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="5"
                    placeholder="Add any instructions for the candidate..."
                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('notes', optional($application->interview)->notes) }}</textarea>

            </div>


            {{-- Submit --}}

            <div class="mt-8 flex justify-end gap-3">

                <a
                    href="{{ route('admin.applications.show', $application) }}"
                    class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Schedule Interview
                </button>

            </div>

        </form>

    </div>

</div>

@endsection