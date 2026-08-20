<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - Job Portal</title>
</head>

<body>

    <h1>Login</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form
        method="POST"
        action="{{ route('login.store') }}"
    >

        @csrf

        <div>
            <label>Email</label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <div>
            <label>Password</label>

            <input
                type="password"
                name="password"
                required
            >
        </div>

        <button type="submit">
            Login
        </button>

    </form>

    <p>
        Don't have an account?

        <a href="{{ route('register') }}">
            Register
        </a>
    </p>

</body>

</html>