@extends('layouts.app')

@section('title', 'Dashboard Admin - KopiTani')

@section('content')
<style>
    .admin-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 0;
        color: #263b2d;
    }

    .admin-dashboard * {
        box-sizing: border-box;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 30px;
    }

    .admin-header h1 {
        margin: 0 0 8px;
        font-size: clamp(25px, 4vw, 32px);
        font-weight: 750;
        color: #244b35;
    }

    .admin-header p {
        margin: 0;
        color: #718075;
        font-size: 15px;
    }

    .admin-label {
        display: inline-block;
        padding: 8px 13px;
        border-radius: 30px;
        background: #e5efe4;
        color: #315b3c;
        font-size: 12px;
        font-weight: 700;
    }

    .admin-welcome {
        padding: 28px;
        margin-bottom: 26px;
        border-radius: 16px;
        background: linear-gradient(120deg, #244b35, #3d6a49);
        color: #fff;
    }

    .admin-welcome h2 {
        margin: 0 0 10px;
        font-size: 23px;
        font-weight: 700;
    }

    .admin-welcome p {
        max-width: 650px;
        margin: 0;
        color: #e0eadd;
        line-height: 1.7;
        font-size: 14px;
    }

    .admin-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 30px;
    }

    .admin-stat {
        padding: 23px;
        background: #fff;
        border: 1px solid #e8ece7;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(35, 60, 40, 0.04);
    }

    .admin-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .admin-stat-title {
        color: #718075;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.5;
    }

    .admin-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border-radius: 12px;
        font-size: 20px;
    }

    .icon-users {
        background: #e8efff;
    }

    .icon-pending {
        background: #fff1d7;
    }

    .icon-approved {
        background: #e1f4e6;
    }

    .icon-rejected {
        background: #fde7e5;
    }

    .admin-stat-number {
        margin: 0;
        color: #244b35;
        font-size: 34px;
        line-height: 1.2;
        font-weight: 750;
    }

    .admin-stat-note {
        margin: 8px 0 0;
        color: #8a958c;
        font-size: 12px;
    }

    .admin-section {
        padding: 25px;
        background: #fff;
        border: 1px solid #e8ece7;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(35, 60, 40, 0.04);
    }

    .admin-section h2 {
        margin: 0 0 8px;
        color: #244b35;
        font-size: 20px;
        font-weight: 700;
    }

    .admin-section-description {
        margin: 0 0 22px;
        color: #718075;
        font-size: 14px;
        line-height: 1.6;
    }

    .admin-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
    }

    .admin-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px;
        border: 1px solid #e5ebe3;
        border-radius: 12px;
        text-decoration: none;
        color: #244b35;
        transition: 0.2s ease;
    }

    .admin-action:hover {
        background: #f6f9f4;
        border-color: #8eae91;
        transform: translateY(-2px);
    }

    .admin-action strong {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .admin-action span {
        display: block;
        color: #718075;
        font-size: 12px;
        line-height: 1.5;
    }

    .admin-action-arrow {
        color: #47744e;
        font-size: 22px;
        flex-shrink: 0;
    }

    @media (max-width: 900px) {
        .admin-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 560px) {
        .admin-dashboard {
            padding: 16px 0;
        }

        .admin-welcome {
            padding: 22px;
        }

        .admin-stats,
        .admin-actions {
            grid-template-columns: 1fr;
        }

        .admin-stat {
            padding: 20px;
        }

        .admin-section {
            padding: 20px;
        }
    }
</style>

<div class="admin-dashboard">

    <header class="admin-header">
        <div>
            <h1>Dashboard Admin</h1>
            <p>Kelola marketplace KopiTani dari satu tempat.</p>
        </div>

        <span class="admin-label">ADMIN PANEL</span>
    </header>

    <section class="admin-welcome">
        <h2>Selamat datang di KopiTani 👋</h2>
        <p>
            Pantau aktivitas marketplace, verifikasi produk petani,
            dan kelola kategori bahan baku kopi agar proses jual beli
            berjalan dengan baik.
        </p>
    </section>

    <section class="admin-stats">

        <article class="admin-stat">
            <div class="admin-stat-top">
                <span class="admin-stat-title">Total Pengguna</span>
                <span class="admin-stat-icon icon-users">👥</span>
            </div>
            <p class="admin-stat-number">{{ $totalUsers }}</p>
            <p class="admin-stat-note">Seluruh akun terdaftar</p>
        </article>

        <article class="admin-stat">
            <div class="admin-stat-top">
                <span class="admin-stat-title">Menunggu Verifikasi</span>
                <span class="admin-stat-icon icon-pending">⏳</span>
            </div>
            <p class="admin-stat-number">{{ $pendingProducts }}</p>
            <p class="admin-stat-note">Produk perlu diperiksa</p>
        </article>

        <article class="admin-stat">
            <div class="admin-stat-top">
                <span class="admin-stat-title">Produk Disetujui</span>
                <span class="admin-stat-icon icon-approved">✓</span>
            </div>
            <p class="admin-stat-number">{{ $approvedProducts }}</p>
            <p class="admin-stat-note">Produk lolos verifikasi</p>
        </article>

        <article class="admin-stat">
            <div class="admin-stat-top">
                <span class="admin-stat-title">Produk Ditolak</span>
                <span class="admin-stat-icon icon-rejected">✕</span>
            </div>
            <p class="admin-stat-number">{{ $rejectedProducts }}</p>
            <p class="admin-stat-note">Produk tidak lolos verifikasi</p>
        </article>

    </section>

    <section class="admin-section">
        <h2>Akses Cepat</h2>
        <p class="admin-section-description">
            Pilih menu untuk mengelola kebutuhan marketplace.
        </p>

        <div class="admin-actions">

            <a href="{{ route('admin.products') }}" class="admin-action">
                <div>
                    <strong>Verifikasi Produk</strong>
                    <span>Periksa pengajuan produk dari petani.</span>
                </div>
                <span class="admin-action-arrow">→</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="admin-action">
                <div>
                    <strong>Manajemen Kategori</strong>
                    <span>Tambah, edit, dan hapus kategori produk.</span>
                </div>
                <span class="admin-action-arrow">→</span>
            </a>

            <a href="{{ route('admin.users') }}" class="admin-action">
                <div>
                    <strong>Manajemen Pengguna</strong>
                    <span>Lihat daftar akun petani dan kedai.</span>
                </div>
                <span class="admin-action-arrow">→</span>
            </a>

        </div>
    </section>

</div>
@endsection