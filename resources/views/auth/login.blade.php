@extends('layouts.app')

@section('title', 'Sign In')

@section('content')

<div class="min-h-[calc(100vh-160px)] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-500/10 text-2xl font-bold text-brand-500 dark:bg-brand-500/20">
            ⚡
        </span>
        <h1 class="mt-4 text-2xl font-bold tracking-tight text-[#111111] dark:text-white">
            Welcome Back
        </h1>
        <p class="mt-2 text-sm text-[#6B6B6B] dark:text-[#A1A1A1]">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-brand-500 hover:text-brand-600">
                Create one now
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-8 shadow-xs dark:border-[#262626] dark:bg-[#141414]">

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-500/20 bg-red-500/10 p-4">
                    <ul class="list-disc pl-5 text-xs font-semibold text-red-600 dark:text-red-400 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Email Address
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="developer@example.com"
                        class="mt-2 w-full rounded-xl border border-[#E5E5E5] bg-white px-4 py-3 text-sm text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="mt-2 w-full rounded-xl border border-[#E5E5E5] bg-white px-4 py-3 text-sm text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                {{-- Submit CTA --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-brand-500 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
                    >
                        Sign In →
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection