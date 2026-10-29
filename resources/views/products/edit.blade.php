
@extends('layouts.app')

@section('title', 'Edit Produk - KopiTani')

@section('content')
    <div class="product-page">
        <div class="product-header">
            <div>
                <span class="eyebrow">RUANG PETANI</span>
                <h1>Edit Produk Kopi 🌱</h1>
                <p>Perbarui informasi produk kopi yang kamu jual.</p>
            </div>

            <a href="{{ route('products.index') }}" class="btn-primary">
                Kembali
            </a>
        </div>

        <div class="product-form-card">
            <form action="{{ route('products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        required
                    >
                    @error('name')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id', $product->category_id) == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Produk</label>
                    <textarea id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Harga per kg (Rp)</label>
                    <input
                        type="number"
                        id="price"
                        name="price"
                        value="{{ old('price', $product->price) }}"
                        min="1"
                        required
                    >
                    @error('price')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stock">Stok (kg)</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        value="{{ old('stock', $product->stock) }}"
                        min="0"
                        required
                    >
                    @error('stock')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="wholesale_min_qty">Minimal Grosir (kg)</label>
                    <input
                        type="number"
                        id="wholesale_min_qty"
                        name="wholesale_min_qty"
                        value="{{ old('wholesale_min_qty', $product->wholesale_min_qty) }}"
                        min="1"
                        required
                    >
                    @error('wholesale_min_qty')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="wholesale_price">Harga Grosir per kg (Rp)</label>
                    <input
                        type="number"
                        id="wholesale_price"
                        name="wholesale_price"
                        value="{{ old('wholesale_price', $product->wholesale_price) }}"
                        min="1"
                        required
                    >
                    @error('wholesale_price')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                @if ($errors->any())
                    <div class="alert-success">
                        Periksa kembali data yang kamu masukkan.
                    </div>
                @endif

                <button type="submit" class="btn-primary">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection
