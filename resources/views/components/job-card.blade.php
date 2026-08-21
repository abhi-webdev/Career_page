@php
    $skillsList = is_array($job->skills) ? $job->skills : [];
    $displayedSkills = array_slice($skillsList, 0, 3);
    $remainingCount = count($skillsList) - count($displayedSkills);
@endphp

<article class="group relative flex flex-col justify-between rounded-3xl border border-[#E5E5E5] bg-white p-7 transition-all duration-200 hover:-translate-y-1 hover:border-brand-500/80 hover:shadow-md dark:border-[#262626] dark:bg-[#141414] dark:hover:border-brand-500 space-y-6">

    <div class="space-y-3.5">
        {{-- Top Meta Row: Location • Experience • Job Type --}}
        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
            <span class="text-brand-500 font-bold">
                {{ $job->location ?? 'Bhopal' }}
            </span>
            
            @if($job->experience)
                <span class="text-[#A1A1A1]">•</span>
                <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">
                    {{ $job->experience }}
                </span>
            @endif

            @if($job->job_type)
                <span class="text-[#A1A1A1]">•</span>
                <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">
                    {{ $job->job_type }}
                </span>
            @endif
        </div>

        {{-- Job Title --}}
        <h3 class="text-xl font-bold tracking-tight text-brand-500 transition hover:text-brand-600 dark:text-brand-500">
            <a href="{{ route('jobs.show', $job) }}">
                {{ $job->title }}
            </a>
        </h3>

        {{-- Description Snippet (2 Lines) --}}
        <p class="text-xs leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] line-clamp-2">
            {{ $job->description ? Str::limit(strip_tags($job->description), 120) : 'We are seeking a skilled engineer to build, maintain, and innovate high-performance applications.' }}
        </p>

        {{-- Curated Skills Row with +X more --}}
        @if(count($displayedSkills) > 0)
            <div class="flex flex-wrap items-center gap-2 pt-1">
                @foreach($displayedSkills as $skill)
                    <span class="rounded-xl border border-[#E5E5E5] bg-white px-3 py-1 font-mono text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white shadow-2xs">
                        {{ $skill }}
                    </span>
                @endforeach

                @if($remainingCount > 0)
                    <span class="text-xs font-semibold text-[#6B6B6B] dark:text-[#A1A1A1]">
                        +{{ $remainingCount }} more
                    </span>
                @endif
            </div>
        @endif
    </div>

    {{-- Card Footer: Full-Width [ View Position → ] Button --}}
    <div class="pt-4 border-t border-[#E5E5E5] dark:border-[#262626]">
        <a
            href="{{ route('jobs.show', $job) }}"
            class="flex w-full items-center justify-center gap-1.5 rounded-2xl border border-[#E5E5E5] bg-white py-2.5 text-xs font-bold text-[#111111] transition hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white dark:hover:border-brand-500 dark:hover:text-brand-500 shadow-2xs"
        >
            <span>View Position</span>
            <span>→</span>
        </a>
    </div>

</article>