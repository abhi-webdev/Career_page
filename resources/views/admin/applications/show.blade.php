@extends('layouts.app')

@section('title', 'Application Details')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Back --}}

        <a
            href="{{ route('admin.applications.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-indigo-600"
        >
            ← Back to Applications
        </a>


        {{-- Header --}}

        <div class="mt-8">

            <p class="text-sm font-semibold text-indigo-600">
                Candidate Application
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                {{ $application->user->name }}
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                {{ $application->user->email }}
            </p>

        </div>


        {{-- Success Message --}}

        @if(session('success'))

            <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">

                ✓ {{ session('success') }}

            </div>

        @endif


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


        <div class="mt-8 grid gap-6 lg:grid-cols-3">


            {{-- LEFT SIDE --}}

            <div class="space-y-6 lg:col-span-2">


                {{-- Candidate Information --}}

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-slate-900">
                        Candidate Information
                    </h2>


                    <div class="mt-5 grid gap-5 sm:grid-cols-2">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Name
                            </p>

                            <p class="mt-1 text-sm font-medium text-slate-800">
                                {{ $application->user->name }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Email
                            </p>

                            <p class="mt-1 text-sm font-medium text-slate-800">
                                {{ $application->user->email }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Job Information --}}

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-slate-900">
                        Job Information
                    </h2>


                    <div class="mt-5">

                        <h3 class="text-xl font-bold text-slate-900">
                            {{ $application->job->title }}
                        </h3>

                        <p class="mt-1 text-sm font-medium text-indigo-600">
                            {{ $application->job->company }}
                        </p>


                        @if($application->job->location)

                            <p class="mt-3 text-sm text-slate-500">
                                📍 {{ $application->job->location }}
                            </p>

                        @endif

                    </div>

                </div>


                {{-- Resume --}}

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <div class="flex items-center justify-between">

                        <h2 class="text-lg font-bold text-slate-900">
                            Resume
                        </h2>

                    </div>


                    @if($application->resume)

                        <div class="mt-5 flex flex-col gap-4 rounded-xl bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">

                            <div>

                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $application->resume->file_name }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Uploaded
                                    {{ $application->resume->created_at->diffForHumans() }}
                                </p>

                            </div>


                            <a
                                href="{{ asset('storage/' . $application->resume->file_path) }}"
                                target="_blank"
                                class="inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                            >
                                View Resume
                            </a>

                        </div>

                    @else

                        <div class="mt-5 rounded-xl bg-slate-50 p-4">

                            <p class="text-sm text-slate-500">
                                No resume attached to this application.
                            </p>

                        </div>

                    @endif

                </div>


                {{-- Cover Letter --}}

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-slate-900">
                        Cover Letter
                    </h2>


                    @if($application->cover_letter)

                        <div class="mt-5 rounded-xl bg-slate-50 p-5">

                            <p class="whitespace-pre-line text-sm leading-7 text-slate-600">
                                {{ $application->cover_letter }}
                            </p>

                        </div>

                    @else

                        <p class="mt-5 text-sm text-slate-500">
                            No cover letter provided.
                        </p>

                    @endif

                </div>


                {{-- Application Date --}}

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-slate-900">
                        Application Information
                    </h2>

                    <div class="mt-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Applied On
                        </p>

                        <p class="mt-1 text-sm font-medium text-slate-700">
                            {{ $application->created_at->format('d M Y, h:i A') }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- RIGHT SIDE --}}

            <div>

                <div class="sticky top-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-slate-900">
                        Application Status
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Update the candidate's current application status.
                    </p>


                    {{-- Status Form --}}

                    <form
                        action="{{ route('admin.applications.status', $application) }}"
                        method="POST"
                        class="mt-6"
                    >

                        @csrf

                        @method('PATCH')


                        <label
                            for="status"
                            class="text-sm font-semibold text-slate-700"
                        >
                            Status
                        </label>


                        <select
                            id="status"
                            name="status"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                            <option
                                value="pending"
                                {{ $application->status === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="shortlisted"
                                {{ $application->status === 'shortlisted' ? 'selected' : '' }}
                            >
                                Shortlisted
                            </option>

                            <option
                                value="interview"
                                {{ $application->status === 'interview' ? 'selected' : '' }}
                            >
                                Interview
                            </option>

                            <option
                                value="selected"
                                {{ $application->status === 'selected' ? 'selected' : '' }}
                            >
                                Selected
                            </option>

                            <option
                                value="rejected"
                                {{ $application->status === 'rejected' ? 'selected' : '' }}
                            >
                                Rejected
                            </option>

                        </select>


                        <button
                            type="submit"
                            class="mt-4 w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                        >
                            Update Status
                        </button>

                    </form>

                    @if($application->status !== 'rejected')


                    <!-- Interview Section -->

    <div class="mt-6 border-t border-slate-100 pt-6">

        <h3 class="text-sm font-bold text-slate-900">
            Interview
        </h3>

        <p class="mt-1 text-xs leading-5 text-slate-500">
            Schedule or update the candidate's interview.
        </p>


        <a
            href="{{ route('admin.applications.interview.create', $application) }}"
            class="mt-4 block w-full rounded-xl bg-slate-900 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-slate-700"
        >
            {{ $application->interview ? 'Update Interview' : 'Schedule Interview' }}
        </a>

    </div>

@endif


@if($application->interview)

    <div class="mt-6 rounded-2xl border border-indigo-100 bg-indigo-50 p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">
                    Scheduled Interview
                </p>

                <h2 class="mt-1 text-lg font-bold text-slate-900">
                    Interview Details
                </h2>

            </div>

            @php
    $interviewStatusClasses = match($application->interview->status) {

        'scheduled' =>
            'border-blue-200 bg-blue-50 text-blue-700',

        'completed' =>
            'border-emerald-200 bg-emerald-50 text-emerald-700',

        'cancelled' =>
            'border-red-200 bg-red-50 text-red-700',

        default =>
            'border-slate-200 bg-slate-50 text-slate-700',
    };
@endphp

<span
    class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold capitalize {{ $interviewStatusClasses }}"
>
    {{ $application->interview->status }}
</span>

        </div>


        <div class="mt-5 grid gap-4 sm:grid-cols-3">

            <div class="rounded-xl bg-white p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Date
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $application->interview->interview_date->format('d M Y') }}
                </p>

            </div>


            <div class="rounded-xl bg-white p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Time
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-800">

                    {{ \Carbon\Carbon::parse($application->interview->start_time)->format('h:i A') }}

                    -

                    {{ \Carbon\Carbon::parse($application->interview->end_time)->format('h:i A') }}

                </p>

            </div>


            <div class="rounded-xl bg-white p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Platform
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-800">
                    Google Meet
                </p>

            </div>

        </div>


        <a
            href="{{ $application->interview->meeting_link }}"
            target="_blank"
            rel="noopener noreferrer"
            class="mt-5 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
        >
            Open Google Meet →
        </a>



    </div>

@endif


@if($application->interview && $application->interview->status === 'scheduled')

    <form
        action="{{ route('admin.applications.interview.cancel', $application) }}"
        method="POST"
        class="mt-3"
        onsubmit="return confirm('Are you sure you want to cancel this interview?');"
    >

        @csrf

        @method('PATCH')

        <button
            type="submit"
            class="w-full rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-100"
        >
            Cancel Interview
        </button>

    </form>

@endif

@if($application->interview && $application->interview->status === 'scheduled')

    <form
        action="{{ route('admin.applications.interview.complete', $application) }}"
        method="POST"
        class="mt-3"
    >

        @csrf

        @method('PATCH')

        <button
            type="submit"
            class="w-full rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100"
        >
            Mark Interview Completed
        </button>

    </form>

@endif


<!-- offer section -->

@if(
    $application->status === 'selected' &&
    $application->interview &&
    $application->interview->status === 'completed'
)

    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

        <div>

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Candidate Selected
            </p>

            <h2 class="mt-1 text-lg font-bold text-slate-900">
                Employment Offer
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                The interview has been completed and this candidate
                is selected. You can now create an employment offer.
            </p>

        </div>


        <a
            href="{{ route('admin.applications.offer.create', $application) }}"
            class="mt-5 block rounded-xl bg-emerald-600 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-emerald-700"
        >
            {{ $application->offer ? 'Update Offer' : 'Create Offer' }}
        </a>

    </div>

@endif


<!-- offer send  -->

@if($application->offer)

    <div class="mt-6 rounded-2xl border border-emerald-200 bg-white p-6 shadow-sm">

        <div class="flex items-start justify-between gap-4">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    Employment Offer
                </p>

                <h2 class="mt-1 text-lg font-bold text-slate-900">
                    Offer Details
                </h2>

            </div>


            <span
                class="rounded-full border px-3 py-1 text-xs font-semibold capitalize
                {{ $application->offer->status === 'sent'
                    ? 'border-blue-200 bg-blue-50 text-blue-700'
                    : 'border-slate-200 bg-slate-50 text-slate-600'
                }}"
            >
                {{ $application->offer->status }}
            </span>

        </div>


        <div class="mt-5 grid gap-4 sm:grid-cols-3">

            <div class="rounded-xl bg-slate-50 p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Salary
                </p>

                <p class="mt-1 text-sm font-bold text-slate-800">
                    ₹{{ number_format($application->offer->salary, 2) }}
                </p>

            </div>


            <div class="rounded-xl bg-slate-50 p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Joining Date
                </p>

                <p class="mt-1 text-sm font-bold text-slate-800">
                    {{ $application->offer->joining_date->format('d M Y') }}
                </p>

            </div>


            <div class="rounded-xl bg-slate-50 p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Offer Expiry
                </p>

                <p class="mt-1 text-sm font-bold text-slate-800">

                    {{ $application->offer->offer_expiry_date
                        ? $application->offer->offer_expiry_date->format('d M Y')
                        : 'No expiry'
                    }}

                </p>

            </div>

        </div>


        {{-- Send Offer --}}

        @if($application->offer->status === 'draft')

            <form
                action="{{ route('admin.applications.offer.send', $application) }}"
                method="POST"
                class="mt-6"
                onsubmit="return confirm('Send this offer to the candidate?');"
            >

                @csrf

                <button
                    type="submit"
                    class="w-full rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
                >
                    Send Offer to Candidate
                </button>

            </form>

        @elseif($application->offer->status === 'sent')

            <div class="mt-6 rounded-xl bg-blue-50 p-4">

                <p class="text-sm font-semibold text-blue-700">
                    Offer Sent
                </p>

                <p class="mt-1 text-xs text-blue-600">
                    The offer has been sent to
                    {{ $application->user->email }}.
                </p>

            </div>

        @endif

    </div>

    <div class="mt-5">

    @if($application->offer->offer_letter_path)

        <a
            href="{{ asset('storage/' . $application->offer->offer_letter_path) }}"
            target="_blank"
            class="inline-flex w-full items-center justify-center rounded-xl border border-indigo-200 bg-indigo-50 px-5 py-3 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100"
        >
            View Offer Letter PDF
        </a>

    @else

        <form
            action="{{ route('admin.applications.offer.generate-letter', $application) }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
            >
                Generate Offer Letter
            </button>

        </form>

    @endif

</div>

@endif

{{-- ========================================================= --}}
{{-- OFFER RESPONSE --}}
{{-- ========================================================= --}}

@if($application->offer)

    <div class="mt-6 border-t border-slate-100 pt-6">

        <p
            class="text-xs font-semibold uppercase
                   tracking-wide text-slate-400"
        >
            Candidate Response
        </p>


        @if($application->offer->status === 'sent')

            <div
                class="mt-4 rounded-xl border
                       border-blue-200 bg-blue-50 p-4"
            >

                <p
                    class="text-sm font-semibold text-blue-700"
                >
                    Awaiting Candidate Response
                </p>

                <p
                    class="mt-1 text-xs text-blue-600"
                >
                    The offer has been sent and is waiting
                    for the candidate's decision.
                </p>

            </div>


        @elseif($application->offer->status === 'accepted')

            <div
                class="mt-4 rounded-xl border
                       border-emerald-200 bg-emerald-50 p-4"
            >

                <div class="flex items-start gap-3">

                    <div
                        class="flex h-9 w-9 shrink-0
                               items-center justify-center
                               rounded-full bg-emerald-600
                               font-bold text-white"
                    >
                        ✓
                    </div>

                    <div>

                        <p
                            class="text-sm font-semibold
                                   text-emerald-800"
                        >
                            Offer Accepted
                        </p>

                        <p
                            class="mt-1 text-xs text-emerald-700"
                        >
                            The candidate has accepted the
                            employment offer.
                        </p>

                    </div>

                </div>

            </div>


        @elseif($application->offer->status === 'declined')

            <div
                class="mt-4 rounded-xl border
                       border-red-200 bg-red-50 p-4"
            >

                <div class="flex items-start gap-3">

                    <div
                        class="flex h-9 w-9 shrink-0
                               items-center justify-center
                               rounded-full bg-red-600
                               font-bold text-white"
                    >
                        ×
                    </div>

                    <div>

                        <p
                            class="text-sm font-semibold
                                   text-red-800"
                        >
                            Offer Declined
                        </p>

                        <p
                            class="mt-1 text-xs text-red-700"
                        >
                            The candidate has declined the
                            employment offer.
                        </p>

                    </div>

                </div>

            </div>


        @endif

    </div>

@endif


                    {{-- Current Status --}}

                    <div class="mt-6 border-t border-slate-100 pt-6">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Current Status
                        </p>

                        <div class="mt-2">

                            @php

                                $statusClasses = match(strtolower($application->status)) {

                                    'pending' =>
                                        'bg-amber-50 text-amber-700 border-amber-200',

                                    'shortlisted' =>
                                        'bg-blue-50 text-blue-700 border-blue-200',

                                    'interview' =>
                                        'bg-purple-50 text-purple-700 border-purple-200',

                                    'selected' =>
                                        'bg-emerald-50 text-emerald-700 border-emerald-200',

                                    'rejected' =>
                                        'bg-red-50 text-red-700 border-red-200',

                                    default =>
                                        'bg-slate-50 text-slate-700 border-slate-200',

                                };

                            @endphp


                            <span
                                class="inline-flex rounded-full border px-3 py-1.5 text-xs font-semibold capitalize {{ $statusClasses }}"
                            >
                                {{ $application->status }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection