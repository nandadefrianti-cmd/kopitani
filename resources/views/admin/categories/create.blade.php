
@extends('layouts.app')

@section('title', 'Tambah Kategori - KopiTani')

@section('content')
    <section class="card">
        <h1>Tambah Kategori Kopi</h1>
        <p>Tambahkan kategori baru untuk produk petani.</p>

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div>
                <label for="name">Nama Kategori</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    maxlength="100"
                    required
                >
            </div>

            <div>
                <label for="description">Deskripsi</label>
                <textarea
                    id="description"
                    name="description"
                    maxlength="1000"
                >{{ old('description') }}</textarea>
            </div>

            <button type="submit">Simpan Kategori</button>

            <a href="{{ route('admin.categories.index') }}">Batal</a>
        </form>
    </section>
@endsection
