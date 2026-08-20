<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Job Portal')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-slate-50 text-slate-900">

    {{-- Navbar --}}
    @include('components.navbar')

    


    {{-- Flash Message --}}

    @if(session('success'))

        <div class="mx-auto mt-4 max-w-7xl px-4">

            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">

                {{ session('success') }}

            </div>

        </div>

    @endif

    @if(session('error'))

    <div class="mx-auto mt-4 max-w-7xl px-4">

        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">

            {{ session('error') }}

        </div>

    </div>

@endif


    {{-- Main Content --}}

    <main>
        @yield('content')
    </main>


    {{-- Footer --}}

    @include('components.footer')

</body>

</html>