<article
    class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg"
>

    <div class="flex items-start justify-between gap-4">

        <div>

            <h2 class="text-xl font-bold text-slate-900">

                <a
                    href="{{ route('jobs.show', $job) }}"
                    class="transition hover:text-indigo-600"
                >
                    {{ $job->title }}
                </a>

            </h2>


            <p class="mt-1 text-sm font-medium text-slate-500">

                {{ $job->company }}

            </p>

        </div>


        <div class="rounded-xl bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-600">

            {{ $job->job_type ?? 'Job' }}

        </div>

    </div>


    <div class="mt-5 space-y-2 text-sm text-slate-500">

        @if($job->location)

            <p>
                📍 {{ $job->location }}
            </p>

        @endif


        @if($job->experience)

            <p>
                💼 {{ $job->experience }}
            </p>

        @endif

    </div>


    @if($job->skills)

        <div class="mt-5 flex flex-wrap gap-2">

            @foreach($job->skills as $skill)

                <span
                    class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600"
                >
                    {{ $skill }}
                </span>

            @endforeach

        </div>

    @endif


    <div class="mt-6 flex items-center justify-between">

        <span class="text-xs text-slate-400">
            Posted {{ $job->created_at->diffForHumans() }}
        </span>


        <a
            href="{{ route('jobs.show', $job) }}"
            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
        >
            View Job
        </a>

    </div>

</article>