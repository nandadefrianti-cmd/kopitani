@extends('layouts.app')

@section('title', 'Produk Saya - KopiTani')

@section('content')
    <div class="product-page">
        <div class="product-header">
            <div>
                <span class="eyebrow">RUANG PETANI</span>
                <h1>Produk Kopi Saya <span>🌱</span></h1>
                <p>Kelola produk dan pantau status penjualan kopi kamu.</p>
            </div>

            <a href="{{ route('products.create') }}" class="btn-primary">
                <span>+</span> Tambah Produk
            </a>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="product-summary">
            <div>
                <span class="summary-label">Total Produk</span>
                <strong>{{ $products->count() }}</strong>
                <span class="summary-note">Produk terdaftar</span>
            </div>

            <div>
                <span class="summary-label">Menunggu Verifikasi</span>
                <strong>{{ $products->where('status', 'pending')->count() }}</strong>
                <span class="summary-note">Sedang ditinjau admin</span>
            </div>
        </div>

        <div class="section-heading">
            <div>
                <h2>Daftar Produk</h2>
                <p>Semua kopi yang kamu tambahkan.</p>
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">☕</div>
                <h3>Belum ada produk kopi</h3>
                <p>Mulai jual kopi lokalmu dengan menambahkan produk pertama.</p>

                <a href="{{ route('products.create') }}" class="btn-primary">
                    Tambah Produk Pertama
                </a>
            </div>
        @else
            <div class="product-grid">
                @foreach ($products as $product)
                    <article class="product-card">
                        <div class="product-visual">
                            @if ($product->image_path)
                                <img
                                    src="{{ asset('storage/' . $product->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="product-image"
                                >
                            @else
                                <span class="coffee-icon">☕</span>
                            @endif

                            <span class="status-badge status-{{ $product->status }}">
                                @if ($product->status === 'pending')
                                    Menunggu Verifikasi
                                @elseif ($product->status === 'approved')
                                    Disetujui
                                @else
                                    Ditolak
                                @endif
                            </span>
                        </div>

                        <div class="product-card-body">
                            <h3>{{ $product->name }}</h3>

                            <p class="product-description">
                                {{ $product->description ?: 'Belum ada deskripsi produk.' }}
                            </p>

                            <div class="product-price">
                                <strong>
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </strong>
                                <span>/ kg</span>
                            </div>

                            <div class="product-details">
                                <div>
                                    <span>Stok tersedia</span>
                                    <strong>
                                        {{ number_format($product->stock, 0, ',', '.') }} kg
                                    </strong>
                                </div>

                                <div>
                                    <span>Minimal grosir</span>
                                    <strong>{{ $product->wholesale_min_qty }} kg</strong>
                                </div>
                            </div>

                            <div class="wholesale-info">
                                <span>Harga grosir</span>
                                <strong>
                                    Rp{{ number_format($product->wholesale_price, 0, ',', '.') }}/kg
                                </strong>
                            </div>

                            <div class="product-actions">
                                <a
                                    href="{{ route('products.edit', $product) }}"
                                    class="btn-primary"
                                >
                                    Edit Produk
                                </a>

                                <form
                                    action="{{ route('products.destroy', $product) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
@endsection