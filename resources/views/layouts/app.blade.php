
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'KopiTani')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    {{-- Navbar khusus halaman selain Landing Page --}}
    @if (!request()->routeIs('home'))
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

                {{-- Notifikasi --}}
                <div style="display: inline-block; position: relative;">
                    <details>
                        <summary style="cursor: pointer; display: inline-block; list-style: none;">
                            🔔
                            @if (Auth::user()->unreadNotifications()->count() > 0)
                                <span style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px;">
                                    {{ Auth::user()->unreadNotifications()->count() }}
                                </span>
                            @endif
                        </summary>

                        <div style="position: absolute; right: 0; top: 30px; width: 320px; max-height: 400px; overflow-y: auto; background: white; border: 1px solid #ddd; padding: 12px; z-index: 1000; color: #222; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <strong>Notifikasi</strong>

                                @if (Auth::user()->unreadNotifications()->exists())
                                    <form action="{{ route('notifications.readAll') }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" style="font-size: 12px;">
                                            Baca semua
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <hr>

                            @forelse (Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="margin-bottom: 10px;">
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" style="width: 100%; text-align: left; padding: 8px; border: none; cursor: pointer; background: {{ $notification->read_at ? '#fff' : '#f0f7ff' }}; color: #222;">
                                        <strong>
                                            {{ $notification->data['title'] ?? 'Notifikasi' }}
                                        </strong>

                                        @if (!$notification->read_at)
                                            <span style="color: blue; font-size: 11px;">• Baru</span>
                                        @endif

                                        <div style="font-size: 13px; margin-top: 4px;">
                                            {{ $notification->data['message'] ?? '' }}
                                        </div>

                                        <small style="color: #777;">
                                            {{ $notification->created_at->locale('id')->diffForHumans() }}
                                        </small>
                                    </button>
                                </form>
                            @empty
                                <p style="font-size: 13px; color: #777;">
                                    Belum ada notifikasi.
                                </p>
                            @endforelse
                        </div>
                    </details>
                </div>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </nav>
    @endif

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
