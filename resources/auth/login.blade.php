<!DOCTYPE html>
<html>
<head>
    <title>Login Bank Sampah</title>
</head>
<body>

    <h2>Login Bank Sampah</h2>

    @if ($errors->any())
        <div style="color:red;">
            {{ $errors->first() }}
        </div>
    @endif

<form action="{{ route('login.post') }}" method="POST">
        @csrf

        <div>
            <label>Email</label><br>
            <input
                type="email"
                name="email"
                placeholder="Masukkan email"
                required
            >
        </div>

        <br>

        <div>
            <label>Password</label><br>
            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                required
            >
        </div>

        <br>

        <button type="submit">
            Login
        </button>
    </form>

</body>
</html>