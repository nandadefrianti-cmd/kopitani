
@extends('layouts.app')

@section('title', 'Katalog Kopi - KopiTani')

@section('content')
    <section class="card">
        <h1>Katalog Kopi</h1>
        <p>Pilih biji kopi dari petani lokal untuk kebutuhan kedai kamu.</p>

        @if ($products->isEmpty())
            <p>Belum ada produk kopi yang tersedia.</p>
        @else
            <div class="product-grid">
                @foreach ($products as $product)
                    <article class="card">
                        <h2>{{ $product->name }}</h2>

                        <p>
                            Kategori:
                            {{ $product->category->name ?? 'Tanpa kategori' }}
                        </p>

                        <p>
                            Harga: Rp{{ number_format($product->price, 0, ',', '.') }}/kg
                        </p>

                        <p>Stok: {{ $product->stock }} kg</p>

                        <p>
                            Minimal grosir: {{ $product->wholesale_min_qty }} kg
                        </p>

                        <p>
                            Harga grosir:
                            Rp{{ number_format($product->wholesale_price, 0, ',', '.') }}/kg
                        </p>

                        <p>{{ $product->description }}</p>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
