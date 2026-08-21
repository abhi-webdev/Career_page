@extends('layouts.app')

@section('title', 'Your Resume Match — ADV AIT Careers')

@section('content')

<div class="max-w-5xl mx-auto space-y-8 py-8 sm:py-12 px-4 sm:px-6">

    {{-- Top Action Bar --}}
    <div class="flex items-center justify-between text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('resume-analyzer.index') }}" class="inline-flex items-center gap-1.5 font-bold hover:text-brand-500 transition">
            <span>←</span>
            <span>Upload Another Resume</span>
        </a>
        <span>File: <strong class="text-[#111111] dark:text-white">{{ $analysis['fileName'] }}</strong></span>
    </div>

    {{-- Header Banner --}}
    <div class="text-center space-y-2 max-w-2xl mx-auto">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-600 dark:text-emerald-400">
            <span>✓ Resume Analysis Complete</span>
        </span>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#111111] sm:text-4xl dark:text-white">
            Your Resume Match
        </h1>
        <p class="text-xs sm:text-sm text-[#6B6B6B] dark:text-[#A1A1A1]">
            We compared your extracted technical skills against all open positions at Advait.
        </p>
    </div>

    {{-- Detected Skills Section --}}
    <div class="rounded-3xl border border-[#E5E5E5] bg-white p-6 sm:p-8 dark:border-[#262626] dark:bg-[#141414] shadow-xs space-y-3">
        <h2 class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
            Detected Skills ({{ count($analysis['detectedSkills']) }})
        </h2>

        @if(count($analysis['detectedSkills']) > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($analysis['detectedSkills'] as $skill)
                    <span class="rounded-xl border border-brand-500/30 bg-brand-500/10 px-3.5 py-1.5 text-xs font-bold text-brand-600 dark:text-brand-400">
                        ✓ {{ $skill }}
                    </span>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-400">
                No technical skills detected from the document text.
            </p>
        @endif
    </div>

    {{-- Recommended Jobs Section --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#111111] dark:text-white">
                    Recommended Jobs
                </h2>
                <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Open positions ranked by your skills match percentage.
                </p>
            </div>
            <span class="text-xs font-bold text-brand-500">
                {{ count($analysis['recommendations']) }} Openings Evaluated
            </span>
        </div>

        @if(count($analysis['recommendations']) > 0)
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($analysis['recommendations'] as $rec)
                    @php $job = $rec['job']; @endphp
                    <div class="rounded-3xl border {{ $rec['score'] >= 75 ? 'border-brand-500/50 shadow-md' : 'border-[#E5E5E5] dark:border-[#262626]' }} bg-white p-6 sm:p-7 dark:bg-[#141414] flex flex-col justify-between space-y-5 hover:border-brand-500 transition">
                        
                        <div class="space-y-4">
                            {{-- Top Row: Title, Company, Match Score Badge --}}
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="text-[11px] font-bold text-brand-500 uppercase tracking-wider">{{ $job->company }}</span>
                                    <h3 class="text-lg font-bold text-[#111111] dark:text-white mt-0.5">
                                        {{ $job->title }}
                                    </h3>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-extrabold {{ $rec['score'] >= 75 ? 'bg-brand-500 text-white shadow-xs' : ($rec['score'] >= 50 ? 'bg-amber-500/20 text-amber-600 dark:text-amber-400' : 'bg-slate-500/20 text-slate-600 dark:text-slate-400') }}">
                                        {{ $rec['score'] }}% Match
                                    </span>
                                </div>
                            </div>

                            {{-- Meta Badges: Location & Experience --}}
                            <div class="flex flex-wrap items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                                <span class="inline-flex items-center gap-1 rounded-lg bg-[#F7F7F7] px-2.5 py-1 text-[11px] font-semibold dark:bg-[#1A1A1A]">
                                    📍 {{ $job->location ?? 'Bhopal / Remote' }}
                                </span>
                                @if($job->experience)
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-[#F7F7F7] px-2.5 py-1 text-[11px] font-semibold dark:bg-[#1A1A1A]">
                                        🎯 {{ $job->experience }}
                                    </span>
                                @endif
                                @if($job->job_type)
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-[#F7F7F7] px-2.5 py-1 text-[11px] font-semibold dark:bg-[#1A1A1A]">
                                        💼 {{ $job->job_type }}
                                    </span>
                                @endif
                            </div>

                            {{-- Matched Skills List --}}
                            <div class="space-y-1.5 text-xs">
                                <span class="font-bold text-emerald-600 dark:text-emerald-400 block text-[11px] uppercase tracking-wider">
                                    Matched Skills:
                                </span>
                                @if(count($rec['matched_skills']) > 0)
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($rec['matched_skills'] as $skill)
                                            <span class="rounded-lg bg-emerald-500/10 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-300">
                                                ✓ {{ $skill }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[11px] text-slate-400">None detected</p>
                                @endif
                            </div>

                            {{-- Missing Skills List --}}
                            @if(count($rec['missing_skills']) > 0)
                                <div class="space-y-1.5 text-xs">
                                    <span class="font-bold text-amber-600 dark:text-amber-400 block text-[11px] uppercase tracking-wider">
                                        Missing Skills:
                                    </span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($rec['missing_skills'] as $skill)
                                            <span class="rounded-lg bg-amber-500/10 px-2 py-0.5 text-[11px] font-medium text-amber-800 dark:text-amber-300">
                                                - {{ $skill }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Action Buttons: [View Job] [Apply Now] --}}
                        <div class="pt-4 border-t border-[#E5E5E5] dark:border-[#262626] grid grid-cols-2 gap-3">
                            <a
                                href="{{ route('jobs.show', $job) }}"
                                class="rounded-xl border border-[#E5E5E5] bg-white py-2.5 text-center text-xs font-bold text-[#111111] hover:bg-[#F7F7F7] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white transition"
                            >
                                View Job
                            </a>
                            <a
                                href="{{ route('applications.create', $job) }}"
                                class="rounded-xl bg-brand-500 py-2.5 text-center text-xs font-bold text-white shadow-xs hover:bg-brand-600 transition"
                            >
                                Apply Now →
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-[#E5E5E5] p-12 text-center text-xs text-[#6B6B6B] dark:border-[#262626] dark:text-[#A1A1A1]">
                ✨ We couldn't find active job openings right now. Check back soon for new opportunities at Advait!
            </div>
        @endif
    </div>

</div>

@endsection
