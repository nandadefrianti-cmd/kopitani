<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - KopiTani</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main>
        <section class="card">
            <h1>Masuk ke KopiTani ☕</h1>
            <p>Masuk untuk melanjutkan aktivitasmu.</p>

            @if (session('success'))
                <p>{{ session('success') }}</p>
            @endif

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >

                <button type="submit">Masuk</button>
            </form>

            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
            <p><a href="{{ route('home') }}">Kembali ke beranda</a></p>
        </section>
    </main>
</body>
</html>