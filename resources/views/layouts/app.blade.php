<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'KopiTani')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav>
        <a href="{{ route('home') }}">KopiTani</a>

        @auth
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                <a href="{{ route('admin.products') }}">Verifikasi Produk</a>
                <a href="{{ route('admin.categories.index') }}">Manajemen Kategori</a>
                <a href="{{ route('admin.users') }}">Manajemen Pengguna</a>
            @else
                <a href="{{ route('dashboard') }}">Dashboard</a>
            @endif

            @if (Auth::user()->role === 'kedai')
                <a href="{{ route('products.catalog') }}">Katalog Kopi</a>
                <a href="{{ route('orders.index') }}">Pesanan Saya</a>
            @endif

            @if (Auth::user()->role === 'petani')
                <a href="{{ route('products.index') }}">Produk Saya</a>
                <a href="{{ route('products.create') }}">Tambah Produk</a>
                <a href="{{ route('orders.index') }}">Pesanan Masuk</a>
            @endif

            <form action="{{ route('logout') }}" method="POST"
                  style="display: inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Daftar</a>
        @endauth
    </nav>

    <main>
        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>