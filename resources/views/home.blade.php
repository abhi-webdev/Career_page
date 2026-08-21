@extends('layouts.app')

@section('title', 'Advait Careers — Build What\'s Next')

@section('content')

<div class="space-y-24 sm:space-y-32 py-6 sm:py-10">

    {{-- SECTION 1: HERO SECTION --}}
    <section class="relative overflow-hidden rounded-3xl border border-[#E5E5E5] bg-gradient-to-b from-white via-[#FAF5FF] to-white p-8 sm:p-14 lg:p-16 dark:border-[#262626] dark:from-[#141414] dark:via-[#1A1325] dark:to-[#0A0A0A] shadow-xs">
        <div class="grid gap-12 lg:grid-cols-12 lg:items-center">
            
            {{-- Left Column: Hero Content --}}
            <div class="lg:col-span-7 space-y-6 text-left">
                
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-500/20 bg-brand-500/10 px-3.5 py-1 text-xs font-bold text-brand-500 dark:text-brand-400">
                    <span class="flex h-2 w-2 rounded-full bg-brand-500 animate-pulse"></span>
                    <span>Official Advait Careers</span>
                    <span>•</span>
                    <span>{{ $totalOpenings }} Active Roles</span>
                </div>

                <div class="space-y-2">
                    <span class="block text-xs sm:text-sm font-extrabold uppercase tracking-widest text-brand-500">
                        Build What's Next.
                    </span>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#111111] sm:text-5xl lg:text-6xl dark:text-white leading-[1.1]">
                        Your skills. Your ideas. Your <span class="text-brand-500">next opportunity.</span>
                    </h1>
                </div>

                <p class="text-sm sm:text-base leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] max-w-xl">
                    Join Advait and work on meaningful technology while growing with a team that values curiosity, ownership, and continuous learning.
                </p>

                {{-- Action CTAs --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                    <a
                        href="#latest-jobs"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-500 px-8 py-3.5 text-xs font-bold text-white shadow-md hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50 transition transform hover:-translate-y-0.5"
                    >
                        <span>Explore Open Roles</span>
                        <span>↓</span>
                    </a>

                    <a
                        href="{{ route('resume-analyzer.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-[#E5E5E5] bg-white px-8 py-3.5 text-xs font-bold text-[#111111] hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white transition shadow-xs transform hover:-translate-y-0.5"
                    >
                        <span>✨ Analyze My Resume</span>
                        <span>→</span>
                    </a>
                </div>

            </div>

            {{-- Right Column: Interactive Technology & Career Badge Visual --}}
            <div class="lg:col-span-5">
                <div class="relative rounded-3xl border border-[#E5E5E5] bg-white/80 p-6 sm:p-8 backdrop-blur-md dark:border-[#262626] dark:bg-[#141414]/90 shadow-sm space-y-5">
                    
                    <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-brand-500 text-xs font-extrabold text-white">
                                A
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#111111] dark:text-white">Advait Tech Stack</p>
                                <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">Modern Web & Cloud Systems</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                            ● Actively Hiring
                        </span>
                    </div>

                    {{-- Floating Skill Badges --}}
                    <div class="flex flex-wrap gap-2 pt-1">
                        <span class="rounded-xl border border-brand-500/30 bg-brand-500/10 px-3 py-1.5 font-mono text-xs font-bold text-brand-600 dark:text-brand-400 shadow-2xs">
                            PHP / Laravel
                        </span>
                        <span class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3 py-1.5 font-mono text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white">
                            React.js
                        </span>
                        <span class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3 py-1.5 font-mono text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white">
                            Node.js
                        </span>
                        <span class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3 py-1.5 font-mono text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white">
                            MySQL & Redis
                        </span>
                        <span class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3 py-1.5 font-mono text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white">
                            Docker & Cloud
                        </span>
                        <span class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3 py-1.5 font-mono text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white">
                            REST APIs
                        </span>
                    </div>

                    {{-- Mini highlight snippet --}}
                    <div class="rounded-2xl border border-brand-500/20 bg-orange-500/5 p-4 dark:bg-[#1A1A1A] space-y-1.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-[#111111] dark:text-white">Engineering First</span>
                            <span class="text-brand-500 font-bold">100% Quality</span>
                        </div>
                        <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1] leading-relaxed">
                            Clean code, peer reviews, automated CI testing, and direct impact on real products.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </section>


    {{-- SECTION 2: LATEST OPPORTUNITIES (MATCHING HERO SECTION WIDTH & CONTAINER) --}}
    <section id="latest-jobs" class="rounded-3xl border border-[#E5E5E5] bg-white p-8 sm:p-14 dark:border-[#262626] dark:bg-[#141414] shadow-xs space-y-8 scroll-mt-24">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 border-b border-[#E5E5E5] pb-5 dark:border-[#262626]">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-brand-500">
                    Latest Opportunities
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white mt-1">
                    Find where your skills can make an impact.
                </h2>
            </div>
            <a
                href="{{ route('jobs.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-500 hover:text-brand-600 transition"
            >
                <span>View All Positions ({{ $totalOpenings }})</span>
                <span>→</span>
            </a>
        </div>

        @if($latestJobs->count() > 0)
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($latestJobs as $job)
                    @php
                        $skillsList = is_array($job->skills) ? $job->skills : [];
                        $displayedSkills = array_slice($skillsList, 0, 3);
                        $remainingCount = count($skillsList) - count($displayedSkills);
                    @endphp
                    <div class="group rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 dark:border-[#262626] dark:bg-[#1A1A1A] shadow-xs flex flex-col justify-between hover:border-brand-500/60 hover:shadow-md transition duration-200 space-y-5">
                        
                        <div class="space-y-3">
                            {{-- Header Meta: Location & Experience --}}
                            <div class="flex items-center justify-between text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                                <span class="font-semibold text-brand-500">
                                    {{ $job->location ?? 'Bhopal' }}
                                </span>
                                @if($job->experience)
                                    <span>•</span>
                                    <span>{{ $job->experience }}</span>
                                @endif
                                @if($job->job_type)
                                    <span>•</span>
                                    <span class="font-medium">{{ $job->job_type }}</span>
                                @endif
                            </div>

                            {{-- Job Title --}}
                            <h3 class="text-lg font-bold tracking-tight text-[#111111] dark:text-white group-hover:text-brand-500 transition">
                                <a href="{{ route('jobs.show', $job) }}">
                                    {{ $job->title }}
                                </a>
                            </h3>

                            {{-- Short Snippet Description --}}
                            <p class="text-xs leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] line-clamp-2">
                                {{ $job->description ? \Illuminate\Support\Str::limit(strip_tags($job->description), 100) : 'Build scalable web applications and distributed systems.' }}
                            </p>

                            {{-- Curated 2-3 Skills (Uncluttered) --}}
                            @if(count($displayedSkills) > 0)
                                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                    @foreach($displayedSkills as $skill)
                                        <span class="rounded-lg bg-white px-2.5 py-1 font-mono text-[10px] font-semibold text-[#111111] dark:bg-[#141414] dark:text-white border border-[#E5E5E5] dark:border-[#262626]">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                    @if($remainingCount > 0)
                                        <span class="text-[10px] font-bold text-[#6B6B6B] dark:text-[#A1A1A1] pl-0.5">
                                            +{{ $remainingCount }} more
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Action Button --}}
                        <div class="pt-3 border-t border-[#E5E5E5] dark:border-[#262626]">
                            <a
                                href="{{ route('jobs.show', $job) }}"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-white py-2 text-xs font-bold text-[#111111] hover:bg-brand-500 hover:text-white dark:bg-[#141414] dark:text-white dark:hover:bg-brand-500 transition border border-[#E5E5E5] dark:border-[#262626] shadow-2xs"
                            >
                                <span>View Position</span>
                                <span>→</span>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="text-center pt-2">
                <a
                    href="{{ route('jobs.index') }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] px-8 py-3.5 text-xs font-bold text-[#111111] hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white transition shadow-xs"
                >
                    <span>Explore All Open Positions ({{ $totalOpenings }})</span>
                    <span>→</span>
                </a>
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-[#E5E5E5] p-10 text-center text-xs text-[#6B6B6B] dark:border-[#262626] dark:text-[#A1A1A1]">
                ✨ No open opportunities listed at the moment. Check back soon!
            </div>
        @endif
    </section>


    {{-- SECTION 3: RESUME ANALYZER (PROMINENT INTERACTIVE SECTION) --}}
    <section class="rounded-3xl border border-[#262626] bg-[#111111] p-8 sm:p-14 text-white shadow-xl">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center">
            
            {{-- Left Side: Value Prop & Live Matching Preview --}}
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-500/40 bg-brand-500/20 px-3.5 py-1 text-xs font-bold text-brand-400">
                    <span>✨ AI-Ready Skill Match Engine</span>
                </div>
                
                <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Not Sure Which Role Fits You?
                </h2>
                
                <p class="text-xs sm:text-sm text-neutral-400 leading-relaxed max-w-lg">
                    Let your resume do the searching. Upload your resume and discover which current Advait opportunities match your skills with instant match scores.
                </p>

                {{-- Visual Match Score Preview Card --}}
                <div class="rounded-2xl border border-[#262626] bg-[#1A1A1A] p-5 space-y-3 max-w-md">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-white">Backend Developer Match</span>
                        <span class="font-extrabold text-brand-400 text-sm">92%</span>
                    </div>
                    
                    {{-- Progress Bar --}}
                    <div class="h-2 w-full rounded-full bg-[#262626] overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-brand-500 to-amber-400 rounded-full" style="width: 92%;"></div>
                    </div>

                    <div class="flex items-center gap-2 text-[11px] text-neutral-400 pt-1">
                        <span class="font-bold text-emerald-400">✓ Matched:</span>
                        <span>Node.js, MongoDB, Express.js, Git</span>
                    </div>
                </div>
            </div>

            {{-- Right Side: Direct Interactive Upload Box --}}
            <div class="lg:col-span-6">
                <div class="rounded-3xl border border-[#262626] bg-[#161616] p-6 sm:p-8 space-y-5">
                    
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">
                        Upload Your Resume
                    </h3>

                    <form
                        action="{{ route('resume-analyzer.analyze') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-4"
                        onsubmit="document.getElementById('home-analyze-btn').textContent = 'Analyzing Resume...'; document.getElementById('home-analyze-btn').disabled = true;"
                    >
                        @csrf

                        <div class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-[#333333] bg-[#1F1F1F] p-8 text-center transition hover:border-brand-500 hover:bg-[#242424]">
                            <span class="text-3xl">📄</span>
                            <p class="mt-2 text-xs font-bold text-white">
                                Drop your resume here, or browse
                            </p>
                            <p class="mt-0.5 text-[10px] text-neutral-400">
                                PDF, DOCX, TXT (Max 5MB)
                            </p>
                            <p id="home-file-chosen" class="mt-2 text-xs font-bold text-brand-400 hidden"></p>

                            <input
                                type="file"
                                name="resume"
                                accept=".pdf,.doc,.docx,.txt"
                                required
                                class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                onchange="if(this.files && this.files[0]) { const display = document.getElementById('home-file-chosen'); display.textContent = '✓ ' + this.files[0].name; display.classList.remove('hidden'); }"
                            >
                        </div>

                        <button
                            id="home-analyze-btn"
                            type="submit"
                            class="w-full rounded-2xl bg-brand-500 py-3.5 text-xs font-bold text-white shadow-md hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50 transition flex items-center justify-center gap-2"
                        >
                            <span>🔍 Analyze My Resume</span>
                            <span>→</span>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </section>


    {{-- SECTION 4: ABOUT ADVAIT --}}
    <section class="rounded-3xl border border-[#E5E5E5] bg-white p-8 sm:p-14 dark:border-[#262626] dark:bg-[#141414] shadow-xs space-y-8">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-center">
            
            <div class="lg:col-span-8 space-y-4">
                <span class="text-xs font-extrabold uppercase tracking-widest text-brand-500">
                    About Advait
                </span>
                
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white leading-tight">
                    Technology is only part of what we build.<br class="hidden sm:block"> We also build opportunities.
                </h2>
                
                <p class="text-sm sm:text-base leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Advait is focused on creating practical digital solutions while giving talented people an environment where they can learn, contribute, and grow.
                </p>

                <div class="grid gap-4 sm:grid-cols-3 pt-4">
                    <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-1">
                        <span class="text-base">🚀</span>
                        <h4 class="text-xs font-bold text-[#111111] dark:text-white">Practical Solutions</h4>
                        <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Delivering robust software that solves real-world challenges.</p>
                    </div>

                    <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-1">
                        <span class="text-base">💡</span>
                        <h4 class="text-xs font-bold text-[#111111] dark:text-white">Continuous Learning</h4>
                        <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Experimenting with modern patterns and architectures.</p>
                    </div>

                    <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-1">
                        <span class="text-base">📈</span>
                        <h4 class="text-xs font-bold text-[#111111] dark:text-white">Merit-Based Growth</h4>
                        <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Clear pathways for ownership and technical leadership.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 text-center">
                <div class="rounded-3xl border border-brand-500/30 bg-orange-500/5 p-8 dark:bg-[#1A1A1A] space-y-3">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500 text-2xl font-extrabold text-white shadow-md">
                        A
                    </div>
                    <h3 class="text-base font-bold text-[#111111] dark:text-white">Engineering Craft</h3>
                    <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                        Focused on writing maintainable, tested, and developer-first applications.
                    </p>
                </div>
            </div>

        </div>
    </section>


    {{-- SECTION 5: WHY ADVAIT? (4 INTERACTIVE PILLARS) --}}
    <section id="why-advait" class="rounded-3xl border border-[#E5E5E5] bg-white p-8 sm:p-14 dark:border-[#262626] dark:bg-[#141414] shadow-xs space-y-10 scroll-mt-24">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-brand-500">
                Why Advait?
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                Four Pillars of Your Growth
            </h2>
            <p class="text-xs sm:text-sm text-[#6B6B6B] dark:text-[#A1A1A1]">
                A career at Advait is designed to give you continuous momentum and technical depth.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            
            {{-- 01: LEARN --}}
            <div class="group rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 dark:border-[#262626] dark:bg-[#1A1A1A] shadow-xs space-y-4 hover:border-brand-500 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500/10 text-xs font-extrabold text-brand-500">
                        01
                    </span>
                    <span class="text-lg">🌱</span>
                </div>
                <h3 class="text-base font-bold text-[#111111] dark:text-white group-hover:text-brand-500 transition">
                    LEARN
                </h3>
                <p class="text-xs leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Continuous upskilling, peer knowledge sharing, and exploring modern architectural patterns with mentorship.
                </p>
            </div>

            {{-- 02: BUILD --}}
            <div class="group rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 dark:border-[#262626] dark:bg-[#1A1A1A] shadow-xs space-y-4 hover:border-purple-600 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-600/10 text-xs font-extrabold text-purple-600 dark:text-purple-400">
                        02
                    </span>
                    <span class="text-lg">⚡</span>
                </div>
                <h3 class="text-base font-bold text-[#111111] dark:text-white group-hover:text-purple-600 transition">
                    BUILD
                </h3>
                <p class="text-xs leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Ship reliable, high-quality code and production systems that solve tangible problems for real users.
                </p>
            </div>

            {{-- 03: COLLABORATE --}}
            <div class="group rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 dark:border-[#262626] dark:bg-[#1A1A1A] shadow-xs space-y-4 hover:border-blue-600 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600/10 text-xs font-extrabold text-blue-600 dark:text-blue-400">
                        03
                    </span>
                    <span class="text-lg">🤝</span>
                </div>
                <h3 class="text-base font-bold text-[#111111] dark:text-white group-hover:text-blue-600 transition">
                    COLLABORATE
                </h3>
                <p class="text-xs leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Work cross-functionally with open communication, peer code reviews, constructive feedback, and mutual trust.
                </p>
            </div>

            {{-- 04: GROW --}}
            <div class="group rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 dark:border-[#262626] dark:bg-[#1A1A1A] shadow-xs space-y-4 hover:border-emerald-600 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600/10 text-xs font-extrabold text-emerald-600 dark:text-emerald-400">
                        04
                    </span>
                    <span class="text-lg">📈</span>
                </div>
                <h3 class="text-base font-bold text-[#111111] dark:text-white group-hover:text-emerald-600 transition">
                    GROW
                </h3>
                <p class="text-xs leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Build a long-term career with clear ownership, technical leadership pathways, and measurable impact.
                </p>
            </div>

        </div>
    </section>


    {{-- SECTION 6: HOW WE HIRE (HORIZONTAL TIMELINE ON DESKTOP) --}}
    <section class="rounded-3xl border border-[#E5E5E5] bg-white p-8 sm:p-14 dark:border-[#262626] dark:bg-[#141414] shadow-xs space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-xs font-extrabold uppercase tracking-widest text-brand-500">
                Recruitment Flow
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                How We Hire
            </h2>
            <p class="text-xs sm:text-sm text-[#6B6B6B] dark:text-[#A1A1A1]">
                A structured, transparent 6-step recruitment journey from application to joining.
            </p>
        </div>

        {{-- Desktop Horizontal & Mobile Vertical Timeline --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-6 relative">
            
            {{-- 01 --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-2 flex flex-col justify-between">
                <div>
                    <span class="font-mono text-xs font-bold text-brand-500 uppercase">01</span>
                    <h3 class="text-sm font-bold text-[#111111] dark:text-white mt-1">Apply</h3>
                    <p class="text-[11px] leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] mt-1">
                        Submit profile & resume. First-time candidates receive instant credentials.
                    </p>
                </div>
            </div>

            {{-- 02 --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-2 flex flex-col justify-between">
                <div>
                    <span class="font-mono text-xs font-bold text-purple-600 uppercase">02</span>
                    <h3 class="text-sm font-bold text-[#111111] dark:text-white mt-1">HR Interview</h3>
                    <p class="text-[11px] leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] mt-1">
                        Video screening on background, values, and alignment.
                    </p>
                </div>
            </div>

            {{-- 03 --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-2 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-xs font-bold text-blue-600 uppercase">03</span>
                        <span class="rounded bg-blue-500/10 px-1.5 py-0.5 text-[9px] font-bold text-blue-600 dark:text-blue-400">Optional</span>
                    </div>
                    <h3 class="text-sm font-bold text-[#111111] dark:text-white mt-1">Tech Round</h3>
                    <p class="text-[11px] leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] mt-1">
                        Technical assessment (Optional depending on role).
                    </p>
                </div>
            </div>

            {{-- 04 --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-2 flex flex-col justify-between">
                <div>
                    <span class="font-mono text-xs font-bold text-amber-600 uppercase">04</span>
                    <h3 class="text-sm font-bold text-[#111111] dark:text-white mt-1">Final Decision</h3>
                    <p class="text-[11px] leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] mt-1">
                        Leadership review of evaluation scorecards and feedback.
                    </p>
                </div>
            </div>

            {{-- 05 --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-2 flex flex-col justify-between">
                <div>
                    <span class="font-mono text-xs font-bold text-emerald-600 uppercase">05</span>
                    <h3 class="text-sm font-bold text-[#111111] dark:text-white mt-1">Offer Letter</h3>
                    <p class="text-[11px] leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] mt-1">
                        Instant PDF offer generation and signed document upload.
                    </p>
                </div>
            </div>

            {{-- 06 --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-2 flex flex-col justify-between">
                <div>
                    <span class="font-mono text-xs font-bold text-brand-500 uppercase">06</span>
                    <h3 class="text-sm font-bold text-[#111111] dark:text-white mt-1">Join Advait</h3>
                    <p class="text-[11px] leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] mt-1">
                        Automatic employee onboarding and team induction.
                    </p>
                </div>
            </div>

        </div>
    </section>


    {{-- SECTION 7: FINAL CAREER CTA --}}
    <section class="rounded-3xl border border-[#E5E5E5] bg-gradient-to-b from-[#FAF5FF] via-white to-white p-8 sm:p-16 dark:border-[#262626] dark:from-[#1A1325] dark:via-[#141414] dark:to-[#141414] shadow-sm text-center space-y-6">
        <div class="max-w-2xl mx-auto space-y-3">
            <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-[#111111] dark:text-white">
                Ready to Build What's Next?
            </h2>
            <p class="text-xs sm:text-sm text-[#6B6B6B] dark:text-[#A1A1A1] leading-relaxed">
                Explore opportunities at Advait and find the role where your skills can make an impact.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
            <a
                href="{{ route('jobs.index') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-500 px-8 py-4 text-xs font-bold text-white shadow-md hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50 transition"
            >
                <span>Explore Open Roles</span>
                <span>→</span>
            </a>

            <a
                href="{{ route('resume-analyzer.index') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-[#E5E5E5] bg-white px-8 py-4 text-xs font-bold text-[#111111] hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white transition shadow-xs"
            >
                <span>Analyze Resume</span>
                <span>↗</span>
            </a>
        </div>
    </section>

</div>

@endsection
