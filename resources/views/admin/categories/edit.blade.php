
@extends('layouts.app')

@section('title', 'Edit Kategori - KopiTani')

@section('content')
    <section class="card">
        <h1>Edit Kategori Kopi</h1>

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('admin.categories.update', $category) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div>
                <label for="name">Nama Kategori</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}"
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
                >{{ old('description', $category->description) }}</textarea>
            </div>

            <button type="submit">Simpan Perubahan</button>

            <a href="{{ route('admin.categories.index') }}">Batal</a>
        </form>
    </section>
@endsection
