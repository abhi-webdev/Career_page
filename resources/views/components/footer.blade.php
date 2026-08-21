<footer class="mt-20 border-t border-[#E5E5E5] bg-white py-10 transition-colors duration-200 dark:border-[#262626] dark:bg-[#0A0A0A]">

    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 sm:flex-row sm:px-6 lg:px-8">

        {{-- Brand & Copyright --}}
        <div class="flex items-center gap-2.5">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-500 text-xs font-bold text-white">
                💼
            </span>
            <span class="text-sm font-bold tracking-tight text-[#111111] dark:text-white">
                Job<span class="text-brand-500">Portal</span>
            </span>
            <span class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                © {{ date('Y') }} All rights reserved.
            </span>
        </div>

        {{-- Meta & Links --}}
        <div class="flex items-center gap-6 text-xs font-medium text-[#6B6B6B] dark:text-[#A1A1A1]">
            <a href="{{ route('jobs.index') }}" class="transition hover:text-brand-500">
                Explore Roles
            </a>
            <span>•</span>
            <span>Developer-First Career Experience</span>
        </div>

    </div>

</footer>