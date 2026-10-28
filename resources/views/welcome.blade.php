
@extends('layouts.app')

@section('title', 'Beranda - KopiTani')

@section('content')
    <section class="card">
        <h1>Selamat Datang di KopiTani 🌱</h1>

        <p>
            Marketplace kopi yang menghubungkan petani kopi
            langsung dengan pemilik kedai kopi.
        </p>

        <p>
            Temukan biji kopi berkualitas dengan harga grosir
            langsung dari petani terpercaya.
        </p>

        <div>
            <a href="/login" class="btn">Masuk</a>
            <a href="/register" class="btn">Daftar Sekarang</a>
        </div>
    </section>
@endsection
