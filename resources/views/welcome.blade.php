@extends('layouts.app')

@section('title', 'KopiTani - Pasar Kopi Langsung dari Petani')

@section('content')

<style>
    .kt-landing {
        --kt-green: #315c3e;
        --kt-dark: #38251c;
        --kt-cream: #fff8f4;
        --kt-peach: #fff0e9;
        --kt-muted: #746b65;
        color: var(--kt-dark);
        background: var(--kt-cream);
        margin: -24px;
        font-family: inherit;
    }

    .kt-landing * { box-sizing: border-box; }
    .kt-landing a { text-decoration: none; }

    .kt-container {
        width: min(1180px, calc(100% - 48px));
        margin: auto;
    }

    .kt-nav {
        height: 76px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #eee5df;
        padding: 0 max(24px, calc((100% - 1180px) / 2));
        background: rgba(255,248,244,.96);
        gap: 24px;
    }

    .kt-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--kt-green);
        font-size: 21px;
        font-weight: 800;
        white-space: nowrap;
    }

    .kt-logo-mark {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        background: #e5efd9;
        border-radius: 11px;
        font-size: 22px;
    }

    .kt-logo small {
        display: block;
        color: var(--kt-muted);
        font-size: 9px;
        font-weight: 500;
    }

    .kt-nav-links {
        display: flex;
        align-items: center;
        gap: 34px;
    }

    .kt-nav-links a {
        color: #62574f;
        font-size: 13px;
        font-weight: 600;
    }

    .kt-nav-links a:hover { color: var(--kt-green); }

    .kt-nav-actions {
        display: flex;
        align-items: center;
        gap: 18px;
        white-space: nowrap;
    }

    .kt-nav-actions a {
        color: var(--kt-dark);
        font-size: 13px;
        font-weight: 700;
    }

    .kt-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        padding: 13px 20px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        transition: .2s;
        border: 1px solid transparent;
    }

    .kt-btn:hover { transform: translateY(-2px); }

    .kt-btn-primary {
        background: var(--kt-dark);
        color: white !important;
    }

    .kt-btn-green {
        background: var(--kt-green);
        color: white !important;
    }

    .kt-btn-light {
        background: white;
        color: var(--kt-dark) !important;
        border-color: #eee5df;
    }

    .kt-hero {
        padding: 66px 0 30px;
    }

    .kt-hero-grid {
        display: grid;
        grid-template-columns: 1.2fr .9fr;
        align-items: center;
        gap: 48px;
    }

    .kt-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #e4edcf;
        color: #48653d;
        padding: 8px 13px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .kt-hero h1 {
        font-size: clamp(36px, 4.3vw, 57px);
        line-height: 1.08;
        letter-spacing: -2px;
        margin: 22px 0 17px;
        color: #30231c;
        font-weight: 850;
    }

    .kt-hero h1 span { color: var(--kt-green); }

    .kt-hero-description {
        color: var(--kt-muted);
        font-size: 14px;
        line-height: 1.9;
        max-width: 590px;
    }

    .kt-hero-buttons {
        display: flex;
        gap: 12px;
        margin-top: 25px;
        flex-wrap: wrap;
    }

    .kt-trust-points {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        margin-top: 30px;
        color: #675d53;
        font-size: 11px;
        font-weight: 700;
    }

    .kt-photo-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 190px 145px;
        gap: 12px;
        position: relative;
    }

    .kt-photo {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 7px 20px #38251c15;
        background: #e5ded3;
    }

    .kt-photo:first-child {
        grid-column: 1 / 3;
    }

    .kt-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s;
    }

    .kt-photo:hover img { transform: scale(1.04); }

    .kt-photo-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 30px 16px 14px;
        color: white;
        background: linear-gradient(transparent, #000a);
        font-size: 12px;
        font-weight: 800;
    }

    .kt-photo-caption small {
        display: block;
        font-size: 9px;
        margin-bottom: 5px;
        color: #e5eacb;
    }

    .kt-floating {
        position: absolute;
        left: -18px;
        bottom: -13px;
        background: white;
        padding: 12px 16px;
        border-radius: 12px;
        box-shadow: 0 7px 25px #0002;
        font-size: 11px;
        font-weight: 800;
        z-index: 2;
    }

    .kt-floating span {
        display: block;
        color: var(--kt-muted);
        font-size: 9px;
        font-weight: 500;
        margin-top: 3px;
    }

    .kt-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        background: white;
        border: 1px solid #f0e8e2;
        border-radius: 15px;
        margin-top: 48px;
        padding: 25px 10px;
        box-shadow: 0 5px 20px #38251c08;
    }

    .kt-stat {
        text-align: center;
        padding: 5px 12px;
        border-right: 1px solid #f0e9e4;
    }

    .kt-stat:last-child { border: 0; }

    .kt-stat strong {
        display: block;
        font-size: 32px;
        letter-spacing: -1px;
        color: #30231c;
    }

    .kt-stat span {
        display: block;
        font-size: 11px;
        margin-top: 3px;
        font-weight: 700;
    }

    .kt-stat small {
        display: block;
        color: var(--kt-muted);
        font-size: 9px;
        margin-top: 5px;
    }

    .kt-section { padding: 82px 0; }

    .kt-section-peach { background: var(--kt-peach); }

    .kt-section-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 32px;
    }

    .kt-section-heading h2 {
        font-size: clamp(27px, 3vw, 36px);
        margin: 9px 0;
        letter-spacing: -1px;
        color: #30231c;
    }

    .kt-section-heading p {
        color: var(--kt-muted);
        font-size: 13px;
        line-height: 1.7;
        max-width: 570px;
        margin: 0;
    }

    .kt-section-tag {
        color: #6b5143;
        background: #f4ded2;
        padding: 9px 14px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .kt-benefit-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .kt-benefit {
        padding: 25px;
        background: white;
        border: 1px solid #eee5df;
        border-radius: 15px;
        box-shadow: 0 5px 15px #38251c08;
    }

    .kt-icon {
        width: 45px;
        height: 45px;
        display: grid;
        place-items: center;
        border-radius: 11px;
        background: #fbe7dc;
        font-size: 21px;
        margin-bottom: 17px;
    }

    .kt-benefit:nth-child(2) .kt-icon { background: #e3edcf; }
    .kt-benefit:nth-child(3) .kt-icon { background: #f4ded2; }

    .kt-benefit h3 {
        font-size: 16px;
        margin: 0 0 10px;
    }

    .kt-benefit p {
        color: var(--kt-muted);
        font-size: 12px;
        line-height: 1.8;
        min-height: 65px;
        margin: 0;
    }

    .kt-benefit-footer {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        padding: 10px;
        margin-top: 20px;
        border-radius: 8px;
        background: #fff4ee;
        font-size: 10px;
    }

    .kt-benefit-footer strong { color: var(--kt-green); }

    .kt-region-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 17px;
    }

    .kt-region {
        overflow: hidden;
        border-radius: 14px;
        background: white;
        border: 1px solid #eee5df;
    }

    .kt-region img {
        width: 100%;
        height: 165px;
        object-fit: cover;
        display: block;
    }

    .kt-region-content { padding: 17px; }

    .kt-region-content small {
        color: var(--kt-green);
        font-size: 10px;
        font-weight: 800;
    }

    .kt-region-content h3 {
        font-size: 16px;
        margin: 7px 0;
    }

    .kt-region-content p {
        color: var(--kt-muted);
        font-size: 11px;
        line-height: 1.6;
        margin: 0;
    }

    .kt-steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 17px;
    }

    .kt-step {
        background: white;
        border: 1px solid #eee5df;
        border-radius: 14px;
        padding: 23px;
    }

    .kt-step-number {
        font-size: 28px;
        font-weight: 850;
        color: #c4d4ad;
    }

    .kt-step h3 { font-size: 15px; margin: 12px 0 8px; }

    .kt-step p {
        color: var(--kt-muted);
        font-size: 12px;
        line-height: 1.8;
        margin: 0;
    }

    .kt-testimonials {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .kt-quote {
        background: white;
        border: 1px solid #eee5df;
        padding: 27px;
        border-radius: 15px;
    }

    .kt-quote p {
        color: #594d44;
        font-size: 14px;
        line-height: 1.9;
    }

    .kt-quote strong { font-size: 12px; }
    .kt-quote small { display: block; color: var(--kt-muted); margin-top: 5px; }

    .kt-cta {
        background: #38251c;
        color: white;
        border-radius: 19px;
        padding: 48px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 30px;
    }

    .kt-cta h2 {
        font-size: clamp(25px, 3vw, 35px);
        margin: 0 0 12px;
    }

    .kt-cta p {
        color: #d8c8bc;
        font-size: 13px;
        line-height: 1.7;
        margin: 0;
    }

    .kt-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .kt-cta .kt-btn-light { background: #fff; }

    .kt-footer {
        background: #f7eee8;
        padding: 35px 0;
        margin-top: 75px;
    }

    .kt-footer-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .kt-footer p {
        color: var(--kt-muted);
        font-size: 11px;
        margin: 7px 0 0;
    }

    .kt-footer-links { display: flex; gap: 22px; }

    .kt-footer-links a {
        color: #62574f;
        font-size: 11px;
        font-weight: 700;
    }

    @media(max-width: 850px) {
        .kt-nav-links { gap: 15px; }
        .kt-hero-grid { grid-template-columns: 1fr; }
        .kt-photo-grid { max-width: 600px; width: 100%; margin: 15px auto 0; }
        .kt-region-grid { grid-template-columns: repeat(2, 1fr); }
        .kt-steps { grid-template-columns: repeat(2, 1fr); }
    }

    @media(max-width: 600px) {
        .kt-landing { margin: -16px; }
        .kt-nav { height: auto; min-height: 70px; flex-wrap: wrap; padding: 14px 20px; gap: 12px; }
        .kt-nav-links { order: 3; width: 100%; justify-content: center; gap: 20px; }
        .kt-nav-links a { font-size: 11px; }
        .kt-nav-actions { gap: 10px; }
        .kt-nav-actions a { font-size: 11px; }
        .kt-container { width: calc(100% - 36px); }
        .kt-hero { padding-top: 42px; }
        .kt-hero-grid { gap: 25px; }
        .kt-hero h1 { font-size: 38px; }
        .kt-hero-description { font-size: 12px; }
        .kt-photo-grid { grid-template-rows: 165px 120px; gap: 8px; }
        .kt-floating { left: 5px; }
        .kt-stats { grid-template-columns: repeat(2, 1fr); gap: 18px 0; }
        .kt-stat:nth-child(2) { border-right: 0; }
        .kt-stat strong { font-size: 25px; }
        .kt-section { padding: 58px 0; }
        .kt-section-heading { align-items: start; flex-direction: column; }
        .kt-benefit-grid, .kt-testimonials { grid-template-columns: 1fr; }
        .kt-region-grid { gap: 10px; }
        .kt-region img { height: 120px; }
        .kt-region-content { padding: 12px; }
        .kt-steps { gap: 10px; }
        .kt-step { padding: 15px; }
        .kt-cta { padding: 28px 22px; flex-direction: column; align-items: start; }
        .kt-footer-links { flex-wrap: wrap; }
    }
</style>

<div class="kt-landing">

    {{-- NAVBAR --}}
    <nav class="kt-nav">
        <a href="/" class="kt-logo">
            <span class="kt-logo-mark">🌱</span>
            <span>
                KopiTani
                <small>Pasar Kopi Langsung dari Petani</small>
            </span>
        </a>

        <div class="kt-nav-links">
            <a href="/">Beranda</a>
            <a href="#tentang">Tentang Kami</a>
            <a href="#wilayah">Katalog Kopi</a>
            <a href="#cara-kerja">Cara Kerja</a>
        </div>

        <div class="kt-nav-actions">
            <a href="/login">Masuk</a>
            <a href="{{ route('register') }}" class="kt-register">
                Daftar Akun
            </a>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="kt-hero">
        <div class="kt-container">
            <div class="kt-hero-grid">
                <div>
                    <span class="kt-eyebrow">🌿 Pasar Komoditas Kopi B2B Nusantara</span>

                    <h1>
                        Hubungkan Petani Kopi dengan
                        <span>Kedai Favorit Anda</span>
                    </h1>

                    <p class="kt-hero-description">
                        Platform B2B terpercaya yang memotong rantai pasok tengkulak.
                        Membantu petani kopi lokal memasarkan hasil panen dengan harga
                        berkeadilan, dan memudahkan pemilik kedai kopi memperoleh
                        green beans dan roasted beans berkualitas langsung dari
                        kebun terbaik di Nusantara.
                    </p>

                    <div class="kt-hero-buttons">
                        <a href="/login" class="kt-btn kt-btn-primary">
                            Jelajahi Katalog →
                        </a>
                        <a href="/register" class="kt-btn kt-btn-light">
                            Mulai Bergabung
                        </a>
                    </div>

                </div>

                <div class="kt-photo-grid">
                    <div class="kt-photo">
                        <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=1000&q=85" alt="Petani dan perkebunan kopi">
                        <div class="kt-photo-caption">
                            <small>☀ PANEN KOPI NUSANTARA</small>
                            Kopi Berkualitas Langsung dari Petani
                        </div>
                    </div>

                    <div class="kt-photo">
                        <img src="https://images.unsplash.com/photo-1559525839-b184a4d698c7?auto=format&fit=crop&w=600&q=85" alt="Biji kopi pilihan">
                    </div>

                    <div class="kt-photo">
                        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=85" alt="Aktivitas kedai kopi">
                    </div>

                    <div class="kt-floating">
                        🚚 Pasokan Lebih Mudah
                        <span>Terhubung langsung dengan petani</span>
                    </div>
                </div>
            </div>

    </section>

    {{-- KEUNGGULAN --}}
    <section class="kt-section kt-section-peach" id="tentang">
        <div class="kt-container">
            <div class="kt-section-heading">
                <div>
                    <span class="kt-eyebrow">Keunggulan Berkelanjutan</span>
                    <h2>Mengapa Memilih KopiTani?</h2>
                    <p>
                        Membangun ekosistem pasokan kopi Indonesia yang adil,
                        efisien, dan transparan dari perkebunan hingga secangkir espresso.
                    </p>
                </div>
                <span class="kt-section-tag">● Marketplace Kopi B2B</span>
            </div>

            <div class="kt-benefit-grid">
                <article class="kt-benefit">
                    <div class="kt-icon">🤝</div>
                    <h3>Langsung dari Petani</h3>
                    <p>
                        Tanpa perantara berlapis. Kami menghubungkan petani
                        dengan pemilik kedai sehingga transaksi lebih transparan
                        dan petani memperoleh kesempatan harga yang lebih baik.
                    </p>
                    <div class="kt-benefit-footer">
                        <span>Keuntungan Petani</span>
                        <strong>Rantai Pasok Pendek</strong>
                    </div>
                </article>

                <article class="kt-benefit">
                    <div class="kt-icon">🌿</div>
                    <h3>Produk Kopi Berkualitas</h3>
                    <p>
                        Temukan berbagai produk kopi dari petani lokal.
                        Informasi harga, stok, kategori, dan deskripsi produk
                        tersedia sebelum melakukan pemesanan.
                    </p>
                    <div class="kt-benefit-footer">
                        <span>Informasi Produk</span>
                        <strong>Lebih Transparan</strong>
                    </div>
                </article>

                <article class="kt-benefit">
                    <div class="kt-icon">📋</div>
                    <h3>Pemesanan Lebih Mudah</h3>
                    <p>
                        Kedai dapat melihat katalog, memilih jumlah pembelian,
                        melakukan checkout, dan memantau perubahan status
                        pesanan melalui satu platform.
                    </p>
                    <div class="kt-benefit-footer">
                        <span>Minimum Pembelian</span>
                        <strong>Sesuai Produk</strong>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- KOPI NUSANTARA --}}
    <section class="kt-section" id="wilayah">
        <div class="kt-container">
            <div class="kt-section-heading">
                <div>
                    <span class="kt-eyebrow">Jelajahi Kekayaan Nusantara</span>
                    <h2>Kenali Kopi dari Berbagai Daerah</h2>
                    <p>
                        Indonesia memiliki kekayaan kopi dengan karakter
                        dan cita rasa yang beragam.
                    </p>
                </div>
                <a href="/login" class="kt-btn kt-btn-light">Lihat Katalog →</a>
            </div>

            <div class="kt-region-grid">
                <article class="kt-region">
                    <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=600&q=80" alt="Kopi Sumatra">
                    <div class="kt-region-content">
                        <small>SUMATRA</small>
                        <h3>Kopi Gayo</h3>
                        <p>Dikenal dengan karakter rasa yang khas dan aroma yang kaya.</p>
                    </div>
                </article>

                <article class="kt-region">
                    <img src="https://images.unsplash.com/photo-1511537190424-bbbab87ac5eb?auto=format&fit=crop&w=600&q=80" alt="Kopi Jawa">
                    <div class="kt-region-content">
                        <small>JAWA</small>
                        <h3>Kopi Java</h3>
                        <p>Memiliki karakter rasa seimbang dengan aroma yang lembut.</p>
                    </div>
                </article>

                <article class="kt-region">
                    <img src="https://i.pinimg.com/1200x/05/41/d8/0541d866585bd80836c5e94ac74cabec.jpg" alt="Kopi Sulawesi">
                    <div class="kt-region-content">
                        <small>SULAWESI</small>
                        <h3>Kopi Toraja</h3>
                        <p>Salah satu kopi Nusantara yang dikenal di pasar internasional.</p>
                    </div>
                </article>

                <article class="kt-region">
                    <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=600&q=80" alt="Kopi Bali">
                    <div class="kt-region-content">
                        <small>BALI</small>
                        <h3>Kopi Kintamani</h3>
                        <p>Memiliki karakter rasa yang unik dari dataran tinggi Bali.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- CARA KERJA --}}
    <section class="kt-section kt-section-peach" id="cara-kerja">
        <div class="kt-container">
            <div class="kt-section-heading">
                <div>
                    <span class="kt-eyebrow">Proses yang Sederhana</span>
                    <h2>Bagaimana KopiTani Bekerja?</h2>
                    <p>
                        Proses transaksi yang mudah untuk menghubungkan
                        petani dengan pemilik kedai kopi.
                    </p>
                </div>
            </div>

            <div class="kt-steps">
                <article class="kt-step">
                    <div class="kt-step-number">01</div>
                    <h3>Buat Akun</h3>
                    <p>Daftar sebagai Petani atau Pemilik Kedai untuk mulai menggunakan KopiTani.</p>
                </article>
                <article class="kt-step">
                    <div class="kt-step-number">02</div>
                    <h3>Jelajahi Produk</h3>
                    <p>Petani menambahkan produk, sedangkan Kedai melihat katalog produk yang tersedia.</p>
                </article>
                <article class="kt-step">
                    <div class="kt-step-number">03</div>
                    <h3>Buat Pesanan</h3>
                    <p>Kedai memilih produk, menentukan jumlah pembelian, dan mengisi alamat pengiriman.</p>
                </article>
                <article class="kt-step">
                    <div class="kt-step-number">04</div>
                    <h3>Pantau Pesanan</h3>
                    <p>Petani memproses pesanan dan Kedai dapat memantau status hingga selesai.</p>
                </article>
            </div>
        </div>
    </section>

    {{-- TESTIMONI --}}
    <section class="kt-section">
        <div class="kt-container">
            <div class="kt-section-heading">
                <div>
                    <span class="kt-eyebrow">Cerita dari Pengguna</span>
                    <h2>Kolaborasi yang Lebih Mudah</h2>
                    <p>Ilustrasi manfaat yang ingin dihadirkan KopiTani untuk petani dan pemilik kedai.</p>
                </div>
            </div>

            <div class="kt-testimonials">
                <article class="kt-quote">
                    <div>⭐⭐⭐⭐⭐</div>
                    <p>
                        “Dengan marketplace seperti KopiTani, petani bisa
                        memperkenalkan produknya secara langsung dan memiliki
                        akses lebih luas kepada calon pembeli.”
                    </p>
                    <strong>Perspektif Petani</strong>
                    <small>Mitra penyedia kopi lokal</small>
                </article>

                <article class="kt-quote">
                    <div>⭐⭐⭐⭐⭐</div>
                    <p>
                        “Mencari produk kopi dan memantau pesanan menjadi
                        lebih praktis karena informasi produk dan status
                        pesanan tersedia dalam satu tempat.”
                    </p>
                    <strong>Perspektif Pemilik Kedai</strong>
                    <small>Mitra kedai kopi</small>
                </article>
            </div>
        </div>
    </section>


    {{-- FOOTER --}}
    <footer class="kt-footer">
        <div class="kt-container kt-footer-inner">
            <div>
                <a href="/" class="kt-logo">
                    <span class="kt-logo-mark">🌱</span>
                    <span>KopiTani</span>
                </a>
                <p>Pasar kopi yang menghubungkan petani dan pemilik kedai.</p>
                <p>© {{ date('Y') }} KopiTani. Semua hak dilindungi.</p>
            </div>

            <div class="kt-footer-links">
                <a href="#tentang">Tentang Kami</a>
                <a href="#wilayah">Katalog Kopi</a>
                <a href="#cara-kerja">Cara Kerja</a>
                <a href="/login">Masuk</a>
            </div>
        </div>
    </footer>

</div>
@endsection