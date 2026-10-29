
@extends('layouts.app')

@section('title', 'Verifikasi Produk - KopiTani')

@section('content')
    <section class="card">
        <h1>Verifikasi Produk Petani</h1>
        <p>Periksa produk sebelum ditampilkan di katalog KopiTani.</p>

        @if ($products->isEmpty())
            <p>Tidak ada produk yang menunggu verifikasi.</p>
        @else
            @foreach ($products as $product)
                <article class="card">
                    <h2>{{ $product->name }}</h2>

                    <p>Petani: {{ $product->farmer->name ?? '-' }}</p>
                    <p>Kategori: {{ $product->category->name ?? '-' }}</p>
                    <p>Harga: Rp{{ number_format($product->price, 0, ',', '.') }}/kg</p>
                    <p>Stok: {{ $product->stock }} kg</p>
                    <p>Harga grosir: Rp{{ number_format($product->wholesale_price, 0, ',', '.') }}/kg</p>
                    <p>Minimal grosir: {{ $product->wholesale_min_qty }} kg</p>
                    <p>Deskripsi: {{ $product->description ?? '-' }}</p>

                    <form action="{{ route('admin.products.approve', $product) }}" method="POST">
                        @csrf
                        <button type="submit">Setujui Produk</button>
                    </form>

                    <form action="{{ route('admin.products.reject', $product) }}" method="POST">
                        @csrf
                        <label for="reason-{{ $product->id }}">Alasan penolakan</label>
                        <textarea
                            id="reason-{{ $product->id }}"
                            name="rejection_reason"
                            required
                            maxlength="1000"
                        ></textarea>
                        <button type="submit">Tolak Produk</button>
                    </form>
                </article>
            @endforeach
        @endif
    </section>
@endsection
