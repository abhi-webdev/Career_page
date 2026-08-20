@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">


        {{-- Header --}}

        <div>

            <p class="text-sm font-semibold text-indigo-600">
                Account
            </p>

            <h1
                class="mt-2 text-3xl font-bold
                       tracking-tight text-slate-900"
            >
                My Profile
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Manage your personal information and resumes.
            </p>

        </div>


        {{-- Success --}}

        @if(session('success'))

            <div
                class="mt-6 rounded-xl
                       border border-emerald-200
                       bg-emerald-50 px-4 py-3
                       text-sm font-medium
                       text-emerald-700"
            >
                ✓ {{ session('success') }}
            </div>

        @endif


        {{-- Errors --}}

        @if($errors->any())

            <div
                class="mt-6 rounded-xl
                       border border-red-200
                       bg-red-50 px-4 py-3"
            >

                <ul
                    class="space-y-1 text-sm
                           text-red-700"
                >

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- PERSONAL INFORMATION --}}
        {{-- ========================================================= --}}

        <div
            class="mt-8 rounded-2xl
                   border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <div>

                <p
                    class="text-xs font-semibold
                           uppercase tracking-wide
                           text-slate-400"
                >
                    Personal Information
                </p>

                <h2
                    class="mt-1 text-xl font-bold
                           text-slate-900"
                >
                    Profile Details
                </h2>

            </div>


            <form
                method="POST"
                action="{{ route('profile.update') }}"
                class="mt-6"
            >

                @csrf

                @method('PUT')


                <div
                    class="grid gap-5
                           sm:grid-cols-2"
                >

                    {{-- Name --}}

                    <div>

                        <label
                            class="text-sm font-medium
                                   text-slate-700"
                        >
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old(
                                'name',
                                $user->name
                            ) }}"
                            class="mt-2 w-full rounded-xl
                                   border border-slate-200
                                   px-4 py-3 text-sm
                                   outline-none
                                   focus:border-indigo-500
                                   focus:ring-2
                                   focus:ring-indigo-100"
                        >

                    </div>


                    {{-- Email --}}

                    <div>

                        <label
                            class="text-sm font-medium
                                   text-slate-700"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old(
                                'email',
                                $user->email
                            ) }}"
                            class="mt-2 w-full rounded-xl
                                   border border-slate-200
                                   px-4 py-3 text-sm
                                   outline-none
                                   focus:border-indigo-500
                                   focus:ring-2
                                   focus:ring-indigo-100"
                        >

                    </div>

                </div>


                <button
                    type="submit"
                    class="mt-6 rounded-xl
                           bg-indigo-600 px-5 py-3
                           text-sm font-semibold
                           text-white transition
                           hover:bg-indigo-700"
                >
                    Save Changes
                </button>

            </form>

        </div>


        {{-- ========================================================= --}}
        {{-- RESUME MANAGEMENT --}}
        {{-- ========================================================= --}}

        <div
            class="mt-6 rounded-2xl
                   border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <div>

                <p
                    class="text-xs font-semibold
                           uppercase tracking-wide
                           text-slate-400"
                >
                    Resume
                </p>

                <h2
                    class="mt-1 text-xl font-bold
                           text-slate-900"
                >
                    Resume Management
                </h2>

                <p
                    class="mt-1 text-sm text-slate-500"
                >
                    Upload your latest resume for job applications.
                </p>

            </div>


            {{-- Upload --}}

            <form
                method="POST"
                action="{{ route(
                    'profile.resume.upload'
                ) }}"
                enctype="multipart/form-data"
                class="mt-6"
            >

                @csrf

                <div
                    class="rounded-xl border-2
                           border-dashed
                           border-slate-200
                           p-6"
                >

                    <label
                        class="block text-sm
                               font-medium
                               text-slate-700"
                    >
                        Upload Resume
                    </label>

                    <input
                        type="file"
                        name="resume"
                        accept=".pdf,.doc,.docx"
                        class="mt-3 block w-full
                               text-sm text-slate-500
                               file:mr-4
                               file:rounded-lg
                               file:border-0
                               file:bg-indigo-50
                               file:px-4
                               file:py-2
                               file:text-sm
                               file:font-semibold
                               file:text-indigo-700
                               hover:file:bg-indigo-100"
                    >

                    <p
                        class="mt-2 text-xs
                               text-slate-400"
                    >
                        PDF, DOC or DOCX. Maximum size: 5MB.
                    </p>

                </div>


                <button
                    type="submit"
                    class="mt-4 rounded-xl
                           bg-slate-900 px-5 py-3
                           text-sm font-semibold
                           text-white transition
                           hover:bg-slate-700"
                >
                    Upload Resume
                </button>

            </form>


            {{-- Existing Resumes --}}

            @if($user->resumes->count())

                <div class="mt-8">

                    <p
                        class="text-sm font-semibold
                               text-slate-900"
                    >
                        Your Resumes
                    </p>


                    <div class="mt-4 space-y-3">

                        @foreach($user->resumes as $resume)

                            <div
                                class="flex flex-col gap-4
                                       rounded-xl
                                       border border-slate-200
                                       p-4 sm:flex-row
                                       sm:items-center
                                       sm:justify-between"
                            >

                                <div
                                    class="flex items-center
                                           gap-3"
                                >

                                    <div
                                        class="flex h-10 w-10
                                               items-center
                                               justify-center
                                               rounded-lg
                                               bg-red-50
                                               text-red-600"
                                    >
                                        PDF
                                    </div>

                                    <div>

                                        <p
                                            class="text-sm
                                                   font-semibold
                                                   text-slate-800"
                                        >
                                            {{ $resume->file_name }}
                                        </p>

                                        <p
                                            class="mt-1 text-xs
                                                   text-slate-400"
                                        >
                                            Uploaded
                                            {{ $resume->created_at->format('d M Y') }}
                                        </p>

                                    </div>

                                </div>


                                <div
                                    class="flex gap-2"
                                >

                                    <a
                                        href="{{ asset(
                                            'storage/' .
                                            $resume->file_path
                                        ) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="rounded-lg
                                               border
                                               border-slate-200
                                               px-3 py-2
                                               text-xs font-semibold
                                               text-slate-700
                                               hover:bg-slate-50"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="{{ asset(
                                            'storage/' .
                                            $resume->file_path
                                        ) }}"
                                        download
                                        class="rounded-lg
                                               border
                                               border-slate-200
                                               px-3 py-2
                                               text-xs font-semibold
                                               text-slate-700
                                               hover:bg-slate-50"
                                    >
                                        Download
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'profile.resume.delete',
                                            $resume
                                        ) }}"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm(
                                                'Delete this resume?'
                                            )"
                                            class="rounded-lg
                                                   border
                                                   border-red-200
                                                   px-3 py-2
                                                   text-xs
                                                   font-semibold
                                                   text-red-600
                                                   hover:bg-red-50"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @else

                <div
                    class="mt-6 rounded-xl
                           bg-slate-50 p-5"
                >

                    <p
                        class="text-sm font-medium
                               text-slate-700"
                    >
                        No resume uploaded yet.
                    </p>

                    <p
                        class="mt-1 text-xs
                               text-slate-500"
                    >
                        Upload your resume to use it
                        when applying for jobs.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection