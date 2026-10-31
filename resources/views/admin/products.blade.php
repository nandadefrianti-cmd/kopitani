
@extends('layouts.app')

@section('title', 'Verifikasi Produk - KopiTani')

@section('content')
<div class="kt-verification-page">

    <header class="kt-verification-heading">
        <div>
            <span class="kt-verification-badge">
                <span class="kt-verification-dot"></span>
                Panel Admin
            </span>

            <h1>Verifikasi Produk Petani</h1>
            <p>
                Periksa informasi dan foto produk sebelum ditampilkan
                di katalog KopiTani.
            </p>
        </div>

        <div class="kt-verification-count">
            <span>Menunggu verifikasi</span>
            <strong>{{ $products->count() }}</strong>
        </div>
    </header>

    @if (session('success'))
        <div class="kt-verification-alert kt-verification-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="kt-verification-alert kt-verification-alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if ($products->isEmpty())
        <div class="kt-verification-empty">
            <div class="kt-verification-empty-icon">✓</div>
            <h2>Semua produk sudah diperiksa</h2>
            <p>
                Saat ini tidak ada produk yang menunggu verifikasi.
            </p>
        </div>
    @else

        <div class="kt-verification-list">
            @foreach ($products as $product)

                <article class="kt-verification-card">

                    <div class="kt-verification-card-header">
                        <span class="kt-verification-status">
                            Menunggu verifikasi
                        </span>

                        <span class="kt-verification-product-id">
                            Produk #{{ $product->id }}
                        </span>
                    </div>

                    <div class="kt-verification-product">

                        {{-- Foto produk --}}
                        <div class="kt-verification-image">
                            @if ($product->image_path)
                                <img
                                    src="{{ asset('storage/' . $product->image_path) }}"
                                    alt="Foto {{ $product->name }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="kt-verification-no-image">
                                    <span>☕</span>
                                    <p>Foto produk belum tersedia</p>
                                </div>
                            @endif
                        </div>

                        {{-- Detail produk --}}
                        <div class="kt-verification-details">

                            <div class="kt-verification-title">
                                <span>INFORMASI PRODUK</span>
                                <h2>{{ $product->name }}</h2>
                                <p>
                                    Petani:
                                    <strong>
                                        {{ $product->farmer->name ?? '-' }}
                                    </strong>
                                </p>
                            </div>

                            <div class="kt-verification-info">

                                <div class="kt-verification-info-row">
                                    <span>Kategori</span>
                                    <strong>
                                        {{ $product->category->name ?? '-' }}
                                    </strong>
                                </div>

                                <div class="kt-verification-info-row">
                                    <span>Harga</span>
                                    <strong class="kt-verification-price">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}/kg
                                    </strong>
                                </div>

                                <div class="kt-verification-info-row">
                                    <span>Stok tersedia</span>
                                    <strong>{{ $product->stock }} kg</strong>
                                </div>

                                <div class="kt-verification-info-row">
                                    <span>Harga grosir</span>
                                    <strong>
                                        Rp{{ number_format($product->wholesale_price, 0, ',', '.') }}/kg
                                    </strong>
                                </div>

                                <div class="kt-verification-info-row">
                                    <span>Minimal grosir</span>
                                    <strong>
                                        {{ $product->wholesale_min_qty }} kg
                                    </strong>
                                </div>

                            </div>

                            <div class="kt-verification-description">
                                <span>Deskripsi produk</span>
                                <p>
                                    {{ $product->description ?: 'Tidak ada deskripsi produk.' }}
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- Aksi verifikasi --}}
                    <div class="kt-verification-actions-area">

                        <form
                            action="{{ route('admin.products.reject', $product) }}"
                            method="POST"
                            class="kt-verification-reject-form"
                        >
                            @csrf

                            <label for="reason-{{ $product->id }}">
                                Alasan penolakan
                                <span>(wajib jika produk ditolak)</span>
                            </label>

                            <textarea
                                id="reason-{{ $product->id }}"
                                name="rejection_reason"
                                rows="3"
                                required
                                maxlength="1000"
                                placeholder="Tuliskan alasan jika produk tidak memenuhi ketentuan..."
                            >{{ old('rejection_reason') }}</textarea>

                            @error('rejection_reason')
                                <p class="kt-verification-error">
                                    {{ $message }}
                                </p>
                            @enderror

                            <button
                                type="submit"
                                class="kt-verification-button kt-verification-button-reject"
                                onclick="return confirm('Yakin ingin menolak produk ini?')"
                            >
                                Tolak Produk
                            </button>
                        </form>

                        <form
                            action="{{ route('admin.products.approve', $product) }}"
                            method="POST"
                            class="kt-verification-approve-form"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="kt-verification-button kt-verification-button-approve"
                                onclick="return confirm('Setujui produk ini?')"
                            >
                                <span>✓</span>
                                Setujui Produk
                            </button>
                        </form>

                    </div>

                </article>

            @endforeach
        </div>

    @endif

</div>
@endsection
