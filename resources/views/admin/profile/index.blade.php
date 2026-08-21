@extends('layouts.admin')

@section('title', 'Admin Profile')
@section('header_title', 'System Administrator Profile')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-[#111111] dark:text-white">
            Administrator Profile
        </h1>
        <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Manage your account identity and review administrative privileges.
        </p>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3.5 text-xs font-bold text-emerald-700 dark:text-emerald-300">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-12">

        {{-- Left: Identity Card --}}
        <div class="md:col-span-4 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs text-center space-y-4">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-brand-500/10 text-3xl font-extrabold text-brand-500 border border-brand-500/20">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-base font-bold text-[#111111] dark:text-white">{{ $user->name }}</h2>
                <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $user->email }}</p>
                <span class="mt-2 inline-flex rounded-full bg-brand-500 px-3 py-0.5 text-[10px] font-bold uppercase text-white shadow-xs">
                    {{ $user->role }}
                </span>
            </div>
            <div class="pt-4 border-t border-[#E5E5E5] dark:border-[#262626] text-xs text-left space-y-2 text-[#6B6B6B] dark:text-[#A1A1A1]">
                <div class="flex justify-between">
                    <span>Member Since:</span>
                    <span class="font-bold text-[#111111] dark:text-white">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Permissions:</span>
                    <span class="font-bold text-brand-500">Super Admin / Full Access</span>
                </div>
            </div>
        </div>

        {{-- Right: Edit Details --}}
        <div class="md:col-span-8 rounded-2xl border border-[#E5E5E5] bg-white p-6 sm:p-8 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                Account Details
            </h2>

            <form action="{{ route('admin.profile.update') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Full Name *
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Email Address *
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-[#E5E5E5] dark:border-[#262626] flex justify-end">
                    <button
                        type="submit"
                        class="rounded-xl bg-brand-500 px-6 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-brand-600 transition"
                    >
                        Save Profile Changes →
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

@endsection
