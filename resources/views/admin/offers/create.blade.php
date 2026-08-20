@extends('layouts.app')

@section('title', 'Create Offer')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Back --}}

        <a
            href="{{ route('admin.applications.show', $application) }}"
            class="text-sm font-medium text-slate-500 transition hover:text-indigo-600"
        >
            ← Back to Application
        </a>


        {{-- Header --}}

        <div class="mt-8">

            <p class="text-sm font-semibold text-indigo-600">
                Offer Management
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                Create Job Offer
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Create an employment offer for the selected candidate.
            </p>

        </div>


        {{-- Candidate Information --}}

        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Candidate
                    </p>

                    <h2 class="mt-1 text-xl font-bold text-slate-900">
                        {{ $application->user->name }}
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $application->user->email }}
                    </p>

                </div>


                <div class="rounded-xl bg-emerald-50 px-4 py-3">

                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                        Application Status
                    </p>

                    <p class="mt-1 text-sm font-bold capitalize text-emerald-700">
                        {{ $application->status }}
                    </p>

                </div>

            </div>


            <div class="mt-5 border-t border-slate-100 pt-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Position
                </p>

                <p class="mt-1 text-lg font-bold text-slate-900">
                    {{ $application->job->title }}
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $application->job->company }}
                </p>

            </div>

        </div>


        {{-- Errors --}}

        @if($errors->any())

            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4">

                <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Offer Form --}}

        <form
            action="{{ route('admin.applications.offer.store', $application) }}"
            method="POST"
            class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8"
        >

            @csrf


            {{-- Salary --}}

            <div>

                <label
                    for="salary"
                    class="text-sm font-semibold text-slate-700"
                >
                    Annual Salary
                </label>

                <div class="relative mt-2">

                    <span
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400"
                    >
                        ₹
                    </span>

                    <input
                        id="salary"
                        name="salary"
                        type="number"
                        min="0"
                        step="0.01"
                        value="{{ old('salary', optional($application->offer)->salary) }}"
                        placeholder="600000"
                        required
                        class="w-full rounded-xl border border-slate-200 py-3 pl-9 pr-4 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                </div>

                <p class="mt-2 text-xs text-slate-400">
                    Enter the annual CTC offered to the candidate.
                </p>

            </div>


            {{-- Dates --}}

            <div class="mt-6 grid gap-6 sm:grid-cols-2">

                {{-- Joining Date --}}

                <div>

                    <label
                        for="joining_date"
                        class="text-sm font-semibold text-slate-700"
                    >
                        Joining Date
                    </label>

                    <input
                        id="joining_date"
                        name="joining_date"
                        type="date"
                        value="{{ old('joining_date', optional($application->offer)->joining_date?->format('Y-m-d')) }}"
                        required
                        class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                </div>


                {{-- Expiry Date --}}

                <div>

                    <label
                        for="offer_expiry_date"
                        class="text-sm font-semibold text-slate-700"
                    >
                        Offer Expiry Date
                    </label>

                    <input
                        id="offer_expiry_date"
                        name="offer_expiry_date"
                        type="date"
                        value="{{ old('offer_expiry_date', optional($application->offer)->offer_expiry_date?->format('Y-m-d')) }}"
                        class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >

                    <p class="mt-2 text-xs text-slate-400">
                        Optional. The date by which the candidate must respond.
                    </p>

                </div>

            </div>


            {{-- Notes --}}

            <div class="mt-6">

                <label
                    for="notes"
                    class="text-sm font-semibold text-slate-700"
                >
                    Offer Notes
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="6"
                    placeholder="Add additional information about the offer..."
                    class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('notes', optional($application->offer)->notes) }}</textarea>

            </div>


            {{-- Actions --}}

            <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('admin.applications.show', $application) }}"
                    class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Save Offer Draft
                </button>

            </div>

        </form>

    </div>

</div>

@endsection