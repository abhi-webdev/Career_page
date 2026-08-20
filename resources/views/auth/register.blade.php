<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Register - Job Portal</title>
</head>

<body>

    <h1>Create Account</h1>

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
        action="{{ route('register.store') }}"
    >

        @csrf

        <div>
            <label>Name</label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </div>

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
            Register
        </button>

    </form>

    <p>
        Already have an account?

        <a href="{{ route('login') }}">
            Login
        </a>
    </p>

</body>

</html>