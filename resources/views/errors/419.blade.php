@extends('layouts.app')

@section('title', '419 - Session Expired')

@section('content')

<div class="min-h-[calc(100vh-200px)] flex flex-col items-center justify-center px-4 py-16 text-center">
    <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-500/10 text-3xl font-extrabold text-amber-500">
        ⏳
    </span>
    <h1 class="mt-6 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-4xl dark:text-white">
        Session Expired
    </h1>
    <p class="mt-2 text-sm text-[#6B6B6B] dark:text-[#A1A1A1] max-w-md">
        Your security session has expired. Please refresh the page and submit your request again.
    </p>

    <div class="mt-8 flex items-center gap-3">
        <a
            href="{{ url()->previous() ?: route('jobs.index') }}"
            class="rounded-xl bg-brand-500 px-5 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
        >
            Reload Previous Page →
        </a>
    </div>
</div>

@endsection
