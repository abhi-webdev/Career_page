<article class="group relative flex flex-col justify-between rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 transition-all duration-200 hover:-translate-y-1 hover:border-brand-500 hover:shadow-xs dark:border-[#262626] dark:bg-[#141414] dark:hover:border-brand-500">

    <div>
        {{-- Top Row: Job Title & Job Type Badge --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-[#111111] transition group-hover:text-brand-500 dark:text-white">
                    <a href="{{ route('jobs.show', $job) }}">
                        {{ $job->title }}
                    </a>
                </h2>
                <p class="mt-1 text-sm font-semibold text-brand-500">
                    {{ $job->company }}
                </p>
            </div>

            @if($job->job_type)
                <span class="shrink-0 rounded-full border border-brand-500/30 bg-brand-500/10 px-3 py-1 text-xs font-bold text-brand-500">
                    {{ $job->job_type }}
                </span>
            @endif
        </div>

        {{-- Meta Badges (Location & Experience) --}}
        <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            @if($job->location)
                <span class="inline-flex items-center gap-1.5 rounded-lg border border-[#E5E5E5] bg-white px-2.5 py-1 font-medium dark:border-[#262626] dark:bg-[#1A1A1A]">
                    📍 {{ $job->location }}
                </span>
            @endif

            @if($job->experience)
                <span class="inline-flex items-center gap-1.5 rounded-lg border border-[#E5E5E5] bg-white px-2.5 py-1 font-medium dark:border-[#262626] dark:bg-[#1A1A1A]">
                    🎯 {{ $job->experience }}
                </span>
            @endif

            @if($job->application_deadline)
                <span class="inline-flex items-center gap-1.5 rounded-lg border border-[#E5E5E5] bg-white px-2.5 py-1 font-medium dark:border-[#262626] dark:bg-[#1A1A1A]">
                    ⏳ Deadline: {{ $job->application_deadline->format('d M') }}
                </span>
            @endif
        </div>

        {{-- Description Snippet --}}
        @if($job->description)
            <p class="mt-4 text-xs leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1] line-clamp-2">
                {{ Str::limit($job->description, 140) }}
            </p>
        @endif

        {{-- Skill Badges --}}
        @if($job->skills && count($job->skills))
            <div class="mt-5 flex flex-wrap gap-1.5">
                @foreach($job->skills as $skill)
                    <span class="rounded-lg border border-[#E5E5E5] bg-white px-2.5 py-1 font-mono text-[11px] font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white">
                        {{ $skill }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Card Footer: Posted timestamp + Action button --}}
    <div class="mt-6 flex items-center justify-between border-t border-[#E5E5E5] pt-4 dark:border-[#262626]">
        <span class="text-[11px] font-medium text-[#6B6B6B] dark:text-[#A1A1A1]">
            Posted {{ $job->created_at->diffForHumans() }}
        </span>

        <a
            href="{{ route('jobs.show', $job) }}"
            class="inline-flex items-center gap-1 rounded-xl bg-[#111111] px-4 py-2 text-xs font-bold text-white transition duration-150 hover:bg-brand-500 dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white"
        >
            <span>View Role</span>
            <span>→</span>
        </a>
    </div>

</article>