<footer class="mt-20 border-t border-[#E5E5E5] bg-white py-12 transition-colors duration-200 dark:border-[#262626] dark:bg-[#0A0A0A]">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">

        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-[#E5E5E5] dark:border-[#262626]">
            {{-- Brand --}}
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-500 font-extrabold text-white shadow-xs">
                    A
                </div>
                <div>
                    <span class="text-base font-extrabold tracking-tight text-[#111111] dark:text-white block">
                        ADV <span class="text-brand-500">AIT</span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#6B6B6B] dark:text-[#A1A1A1]">
                        Official Career Page
                    </span>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="flex flex-wrap items-center justify-center gap-6 text-xs font-semibold text-[#6B6B6B] dark:text-[#A1A1A1]">
                <a href="{{ route('jobs.index') }}" class="hover:text-brand-500 transition">Jobs</a>
                <a href="{{ route('resume-analyzer.index') }}" class="hover:text-brand-500 transition">Resume Analyzer</a>
                <a href="{{ route('home') }}#why-advait" class="hover:text-brand-500 transition">Why Advait</a>
                @guest
                    <a href="{{ route('login') }}" class="hover:text-brand-500 transition">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-brand-500 transition">Register</a>
                @endguest
            </div>
        </div>

        {{-- Bottom Copyright & Tagline --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            <p>© {{ date('Y') }} Advait (ADV AIT). All rights reserved.</p>
            <p class="text-[11px]">Building technology solutions with passion and engineering craftsmanship.</p>
        </div>

    </div>

</footer>