@extends('layouts.app')

@section('title', 'Developer Profile')

@section('content')

<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">

    {{-- Header --}}
    <div class="border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
            Account Settings
        </span>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
            Developer Profile & Resumes
        </h1>
        <p class="mt-1 text-sm text-[#6B6B6B] dark:text-[#A1A1A1]">
            Manage your personal identity, contact email, and uploaded application documents.
        </p>
    </div>

    {{-- Personal Info Card --}}
    <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 sm:p-8 dark:border-[#262626] dark:bg-[#141414]">
        <div class="border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
            <h2 class="text-base font-bold text-[#111111] dark:text-white">
                Personal Information
            </h2>
            <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                Used for candidate correspondence and hiring communications.
            </p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid gap-5 sm:grid-cols-2">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Full Name
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="mt-2 w-full rounded-xl border border-[#E5E5E5] bg-white px-4 py-3 text-sm text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Email Address
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="mt-2 w-full rounded-xl border border-[#E5E5E5] bg-white px-4 py-3 text-sm text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    class="rounded-xl bg-brand-500 px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Resume Library Card --}}
    <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 sm:p-8 dark:border-[#262626] dark:bg-[#141414]">
        <div class="border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
            <h2 class="text-base font-bold text-[#111111] dark:text-white">
                Resume Library
            </h2>
            <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                Upload and manage CVs to quickly attach when applying for jobs.
            </p>
        </div>

        {{-- Upload New Resume Form --}}
        <form method="POST" action="{{ route('profile.resume.upload') }}" enctype="multipart/form-data" class="mt-6">
            @csrf

            <div class="relative rounded-2xl border-2 border-dashed border-[#E5E5E5] bg-white p-6 text-center transition hover:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A]">
                <span class="text-3xl">📤</span>
                <p class="mt-2 text-xs font-bold text-[#111111] dark:text-white">
                    Click to select a new resume
                </p>
                <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                    PDF, DOC, DOCX up to 5MB
                </p>
                <p id="profile-upload-filename" class="mt-2 text-xs font-bold text-brand-500"></p>

                <input
                    type="file"
                    name="resume"
                    accept=".pdf,.doc,.docx"
                    required
                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                    onchange="if(this.files[0]) { document.getElementById('profile-upload-filename').textContent = 'Selected: ' + this.files[0].name + ' (' + (this.files[0].size/1024).toFixed(1) + ' KB)'; }"
                >
            </div>

            <div class="mt-4 flex justify-end">
                <button
                    type="submit"
                    class="rounded-xl bg-[#111111] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-brand-500 dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white"
                >
                    Upload Document →
                </button>
            </div>
        </form>

        {{-- Saved Resumes List --}}
        <div class="mt-8 border-t border-[#E5E5E5] pt-6 dark:border-[#262626]">
            <p class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1] mb-4">
                Saved Documents ({{ $user->resumes->count() }})
            </p>

            @if($user->resumes->count())
                <div class="space-y-3">
                    @foreach($user->resumes as $resume)
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-[#E5E5E5] bg-white p-4 transition dark:border-[#262626] dark:bg-[#1A1A1A]">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-500/10 text-sm font-bold text-red-500">
                                    PDF
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-[#111111] dark:text-white truncate">
                                        {{ $resume->file_name }}
                                    </p>
                                    <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                        Uploaded {{ $resume->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ asset('storage/' . $resume->file_path) }}"
                                    target="_blank"
                                    class="rounded-lg border border-[#E5E5E5] px-3 py-1.5 text-xs font-semibold text-[#111111] transition hover:border-brand-500 dark:border-[#262626] dark:text-white"
                                >
                                    View
                                </a>

                                <form method="POST" action="{{ route('profile.resume.delete', $resume) }}" onsubmit="return confirm('Are you sure you want to delete this resume?');">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-500/20 dark:text-red-400"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-dashed border-[#E5E5E5] bg-white p-6 text-center text-xs text-[#6B6B6B] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]">
                    📄 No resumes uploaded yet. Upload your first resume above to apply easily.
                </div>
            @endif
        </div>
    </div>

</div>

@endsection