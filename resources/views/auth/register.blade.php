<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - KopiTani</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main>
        <section class="card">
            <h1>Daftar KopiTani 🌱</h1>
            <p>Buat akun untuk mulai menggunakan KopiTani.</p>

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <label for="name">Nama lengkap</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                >

                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >

                <label for="role">Daftar sebagai</label>
                <select id="role" name="role" required>
                    <option value="">Pilih peran</option>
                    <option value="petani" {{ old('role') === 'petani' ? 'selected' : '' }}>
                        Petani Kopi
                    </option>
                    <option value="kedai" {{ old('role') === 'kedai' ? 'selected' : '' }}>
                        Pemilik Kedai
                    </option>
                </select>

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >

                <label for="password_confirmation">Konfirmasi password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                >

                <button type="submit">Daftar Sekarang</button>
            </form>

            <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
            <p><a href="{{ route('home') }}">Kembali ke beranda</a></p>
        </section>
    </main>
</body>
</html>