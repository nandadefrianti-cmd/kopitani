@extends('layouts.app')

@section('title', 'Dashboard - KopiTani')

@section('content')
    <section class="card">
        <h1>Halo, {{ $user->name }}! 🌱</h1>

        <p>Selamat datang di dashboard KopiTani.</p>

        <p>
            Peran akun:
            <strong>
                {{ $user->role === 'petani' ? 'Petani Kopi' : ($user->role === 'kedai' ? 'Pemilik Kedai' : 'Admin') }}
            </strong>
        </p>

        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if ($user->role === 'petani')
            <p>Di sini nantinya kamu bisa mengelola produk kopi yang dijual.</p>
        @elseif ($user->role === 'kedai')
            <p>Di sini nantinya kamu bisa mencari kopi dan melihat pesanan.</p>
        @else
            <p>Di sini nantinya kamu bisa mengelola kategori dan memverifikasi produk.</p>
        @endif

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    </section>
@endsection
