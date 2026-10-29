
@extends('layouts.app')

@section('title', 'Tambah Produk - KopiTani')

@section('content')
    <section class="card">
        <h1>Tambah Produk Kopi 🌱</h1>

        <p>Masukkan informasi kopi yang ingin kamu jual.</p>

        @if ($errors->any())
            <div class="error">
                <p>Periksa kembali data yang kamu masukkan:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <label for="name">Nama Produk</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Contoh: Arabika Gayo"
                maxlength="255"
                required
            >

            <label for="category_id">Kategori Produk</label>
            <select id="category_id" name="category_id" required>
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <label for="description">Deskripsi</label>
            <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Ceritakan karakteristik kopi..."
            >{{ old('description') }}</textarea>

            <label for="price">Harga per kg (Rp)</label>
            <input
                id="price"
                type="number"
                name="price"
                value="{{ old('price') }}"
                min="1"
                step="0.01"
                required
            >

            <label for="stock">Stok (kg)</label>
            <input
                id="stock"
                type="number"
                name="stock"
                value="{{ old('stock', 0) }}"
                min="0"
                required
            >

            <label for="wholesale_min_qty">Minimal pembelian grosir (kg)</label>
            <input
                id="wholesale_min_qty"
                type="number"
                name="wholesale_min_qty"
                value="{{ old('wholesale_min_qty', 10) }}"
                min="1"
                required
            >

            <label for="wholesale_price">Harga grosir per kg (Rp)</label>
            <input
                id="wholesale_price"
                type="number"
                name="wholesale_price"
                value="{{ old('wholesale_price') }}"
                min="1"
                step="0.01"
                required
            >

            <button type="submit">Simpan Produk</button>
            <a class="btn" href="{{ route('products.index') }}">Kembali</a>
        </form>
    </section>
@endsection
