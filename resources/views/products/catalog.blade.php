
@extends('layouts.app')

@section('title', 'Katalog Kopi - KopiTani')

@section('content')
    <section class="product-page">
        <div class="product-header">
            <div>
                <span class="eyebrow">KATALOG PETANI LOKAL</span>
                <h1>Katalog Kopi ☕</h1>
                <p>
                    Pilih biji kopi berkualitas dari petani lokal untuk kebutuhan kedai kamu.
                </p>
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">☕</div>
                <h3>Belum Ada Produk Tersedia</h3>
                <p>
                    Produk kopi yang tersedia akan muncul di halaman ini setelah disetujui admin.
                </p>
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

                            <span class="status-badge status-approved">
                                Tersedia
                            </span>
                        </div>

                        <div class="product-card-body">
                            <span class="eyebrow">
                                {{ $product->category->name ?? 'Tanpa kategori' }}
                            </span>

                            <h3>{{ $product->name }}</h3>

                            <p class="product-description">
                                {{ $product->description ?: 'Produk kopi lokal pilihan untuk kebutuhan kedai kamu.' }}
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
                                    <strong>
                                        {{ number_format($product->wholesale_min_qty, 0, ',', '.') }} kg
                                    </strong>
                                </div>
                            </div>

                            <div class="wholesale-info">
                                <span>Harga grosir</span>
                                <strong>
                                    Rp{{ number_format($product->wholesale_price, 0, ',', '.') }}/kg
                                </strong>
                            </div>

                            <a
                                href="{{ route('orders.checkout', $product) }}"
                                class="inline-block mt-4 w-full rounded-lg bg-green-700 px-4 py-3 text-center font-semibold text-white hover:bg-green-800"
                            >
                                Pesan Sekarang
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
