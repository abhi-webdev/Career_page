@extends('layouts.app')

@section('title', 'Employee Portal')

@section('content')

<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">

    {{-- Welcome Header --}}
    <div class="border-b border-[#E5E5E5] pb-8 dark:border-[#262626]">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
                Staff Onboarding Portal
            </span>
            @if($employee)
                <span class="font-mono text-xs font-extrabold text-emerald-600 bg-emerald-500/10 px-2.5 py-0.5 rounded-lg border border-emerald-500/20">
                    Employee ID: {{ $employee->employee_code }}
                </span>
            @endif
        </div>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-4xl dark:text-white">
            Welcome to the Team, {{ $user->name }}!
        </h1>
        <p class="mt-2 text-sm text-[#6B6B6B] dark:text-[#A1A1A1]">
            Your official employment profile, joining schedule, and verified onboarding documents.
        </p>
    </div>

    @if($employee)
        {{-- Hired Staff Banner --}}
        <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-sm font-bold text-white shadow-xs">
                            ✓
                        </span>
                        <h2 class="text-lg font-extrabold text-emerald-800 dark:text-emerald-300">
                            Employment Confirmed • {{ $employee->application->job->company }}
                        </h2>
                    </div>

                    <p class="mt-2 text-xs text-emerald-700 dark:text-emerald-400">
                        Position: <strong class="text-emerald-900 dark:text-emerald-200">{{ $employee->application->job->title }}</strong>
                    </p>

                    <div class="mt-4 grid gap-3 sm:grid-cols-3 text-xs">
                        <div class="bg-white/80 dark:bg-[#141414] p-3.5 rounded-xl border border-emerald-500/20">
                            <span class="text-[10px] uppercase font-bold text-slate-500">Employee Code</span>
                            <p class="font-mono font-extrabold text-brand-500 text-sm mt-0.5">{{ $employee->employee_code }}</p>
                        </div>
                        <div class="bg-white/80 dark:bg-[#141414] p-3.5 rounded-xl border border-emerald-500/20">
                            <span class="text-[10px] uppercase font-bold text-slate-500">Scheduled Joining Date</span>
                            <p class="font-bold text-[#111111] dark:text-white text-sm mt-0.5">{{ $employee->joining_date->format('d M Y') }}</p>
                            <p class="text-[10px] text-brand-500 font-semibold">{{ $employee->joining_date->diffForHumans() }}</p>
                        </div>
                        <div class="bg-white/80 dark:bg-[#141414] p-3.5 rounded-xl border border-emerald-500/20">
                            <span class="text-[10px] uppercase font-bold text-slate-500">Onboarding Status</span>
                            <p class="font-bold capitalize text-emerald-600 dark:text-emerald-400 text-sm mt-0.5">{{ $employee->status }}</p>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 flex flex-col gap-2">
                    @if($employee->offer && $employee->offer->signed_offer_letter_path)
                        <a
                            href="{{ route('applications.offer.download-signed', $employee->application) }}"
                            class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition"
                        >
                            <span>📥 Download Signed Offer</span>
                        </a>
                    @endif
                    <a
                        href="{{ route('offers.current') }}"
                        class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-[#111111] border border-[#E5E5E5] hover:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white transition"
                    >
                        <span>View Offer History →</span>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="p-12 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1] border border-dashed rounded-2xl">
            Employee record is being initialized by human resources.
        </div>
    @endif

</div>

@endsection
