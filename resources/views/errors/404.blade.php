@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')

<div class="min-h-[calc(100vh-200px)] flex flex-col items-center justify-center px-4 py-16 text-center">
    <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-500/10 text-3xl font-extrabold text-brand-500 dark:bg-brand-500/20">
        404
    </span>
    <h1 class="mt-6 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-4xl dark:text-white">
        Position or Page Not Found
    </h1>
    <p class="mt-2 text-sm text-[#6B6B6B] dark:text-[#A1A1A1] max-w-md">
        The link you followed may have expired, or the requested resource does not exist.
    </p>

    <div class="mt-8 flex items-center gap-3">
        <a
            href="{{ route('jobs.index') }}"
            class="rounded-xl bg-brand-500 px-5 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
        >
            Explore Open Roles →
        </a>
    </div>
</div>

@endsection
