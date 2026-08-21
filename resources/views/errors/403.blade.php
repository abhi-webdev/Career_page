@extends('layouts.app')

@section('title', '403 - Unauthorized Access')

@section('content')

<div class="min-h-[calc(100vh-200px)] flex flex-col items-center justify-center px-4 py-16 text-center">
    <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-red-500/10 text-3xl font-extrabold text-red-500">
        🔒
    </span>
    <h1 class="mt-6 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-4xl dark:text-white">
        Access Restricted
    </h1>
    <p class="mt-2 text-sm text-[#6B6B6B] dark:text-[#A1A1A1] max-w-md">
        You do not have permission to view or manage this administrative resource.
    </p>

    <div class="mt-8 flex items-center gap-3">
        <a
            href="{{ route('jobs.index') }}"
            class="rounded-xl bg-brand-500 px-5 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
        >
            Return to Career Portal →
        </a>
    </div>
</div>

@endsection
