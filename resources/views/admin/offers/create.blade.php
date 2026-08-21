@extends('layouts.admin')

@section('title', 'Draft Employment Offer: ' . $application->user->name)
@section('header_title', 'Offers Management')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('admin.applications.show', $application) }}" class="hover:text-brand-500 transition">
            ← Back to Candidate Review
        </a>
    </div>

    <div>
        <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
            Employment Proposal
        </span>
        <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
            Create Official Offer Draft
        </h1>
        <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Structure compensation and joining timeline for {{ $application->user->name }}.
        </p>
    </div>

    {{-- Candidate Information Card --}}
    <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p class="text-xs font-bold text-[#111111] dark:text-white">
                    Candidate: {{ $application->user->name }}
                </p>
                <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                    {{ $application->user->email }}
                </p>
            </div>
            <div class="sm:text-right">
                <p class="text-xs font-bold text-brand-500">
                    {{ $application->job->title }}
                </p>
                <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                    {{ $application->job->company }}
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
    <form action="{{ route('admin.applications.offer.store', $application) }}" method="POST" class="rounded-2xl border border-[#E5E5E5] bg-white p-6 sm:p-8 dark:border-[#262626] dark:bg-[#141414] shadow-xs space-y-5">
        @csrf

        {{-- Salary CTC --}}
        <div>
            <label for="salary" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                Annual Compensation CTC (INR ₹) *
            </label>
            <div class="relative mt-1.5">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-[#6B6B6B] dark:text-[#A1A1A1]">
                    ₹
                </span>
                <input
                    id="salary"
                    name="salary"
                    type="number"
                    min="0"
                    step="0.01"
                    value="{{ old('salary', optional($application->offer)->salary) }}"
                    placeholder="850000"
                    required
                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2.5 pl-8 pr-4 text-xs font-bold text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
            </div>
            <p class="mt-1 text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                Total annual gross cost-to-company.
            </p>
        </div>

        {{-- Dates --}}
        <div class="grid gap-4 sm:grid-cols-2">
            {{-- Joining Date --}}
            <div>
                <label for="joining_date" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                    Joining Date *
                </label>
                <input
                    id="joining_date"
                    name="joining_date"
                    type="date"
                    value="{{ old('joining_date', optional($application->offer)->joining_date?->format('Y-m-d')) }}"
                    required
                    class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
            </div>

            {{-- Expiry Date --}}
            <div>
                <label for="offer_expiry_date" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                    Offer Expiry Date (Optional)
                </label>
                <input
                    id="offer_expiry_date"
                    name="offer_expiry_date"
                    type="date"
                    value="{{ old('offer_expiry_date', optional($application->offer)->offer_expiry_date?->format('Y-m-d')) }}"
                    class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
            </div>
        </div>

        {{-- Notes --}}
        <div>
            <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                Offer Terms / Additional Details
            </label>
            <textarea
                id="notes"
                name="notes"
                rows="5"
                placeholder="e.g. Stock equity details, probation terms, health insurance benefits..."
                class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
            >{{ old('notes', optional($application->offer)->notes) }}</textarea>
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
                class="rounded-xl bg-brand-500 px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
            >
                Save Offer Draft →
            </button>
        </div>
    </form>

</div>

@endsection