# KopiTani

**Marketplace B2B Bahan Baku Kopi Lokal**

KopiTani merupakan aplikasi marketplace berbasis web yang menghubungkan petani kopi lokal dengan pemilik kedai kopi. Melalui aplikasi ini, petani dapat menawarkan dan mengelola produk kopi, sedangkan pemilik kedai dapat melihat katalog, melakukan pemesanan, dan memantau status pesanan.

## 1. Teknologi yang Digunakan

- **Backend:** PHP 8.3 dan Laravel 13
- **Frontend:** Blade, Tailwind CSS, dan Vite
- **Database:** MySQL
- **ORM:** Laravel Eloquent ORM
- **Development Environment:** Laragon
- **Version Control:** Git dan GitHub

## 2. Fitur Aplikasi

### Admin
- Melihat dashboard admin.
- Mengelola kategori produk.
- Melihat daftar pengguna.
- Meninjau produk yang diajukan petani.
- Menyetujui atau menolak produk.

### Petani
- Melihat dashboard petani.
- Menambahkan produk kopi.
- Melihat daftar produk milik sendiri.
- Mengubah informasi produk.
- Menghapus produk milik sendiri.
- Melihat pesanan yang masuk.
- Memperbarui status pesanan menjadi dikirim.

### Kedai
- Melihat katalog produk yang telah disetujui admin.
- Melihat detail produk dan harga.
- Melakukan checkout dan pemesanan.
- Melihat daftar pesanan sendiri.
- Memperbarui status pesanan menjadi dibayar.
- Menyelesaikan pesanan.

### Autentikasi
- Registrasi pengguna.
- Login dan logout.
- Pengelolaan session.
- Pembatasan akses berdasarkan role pengguna.

## 3. Persyaratan Instalasi

Pastikan perangkat sudah memiliki:

- PHP minimal 8.3.
- Composer.
- Node.js dan npm.
- MySQL.
- Laragon atau web server lokal lainnya.
- Git.

## 4. Cara Instalasi dan Menjalankan Project

### Langkah 1. Clone Repository

Buka terminal, kemudian jalankan:

```bash
git clone https://github.com/nandadefrianti-cmd/kopitani.git
```

Masuk ke direktori project:

```bash
cd kopitani
```

### Langkah 2. Install Dependensi

Install dependensi Laravel:

```bash
composer install
```

Install dependensi frontend:

```bash
npm install
```

### Langkah 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

Pada Windows Command Prompt:

```bash
copy .env.example .env
```

Buka file `.env`, kemudian sesuaikan konfigurasi database:

```env
APP_NAME=KopiTani
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kopitani
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password database dengan konfigurasi MySQL pada perangkat masing-masing.

### Langkah 4. Membuat Database

1. Jalankan Laragon dan aktifkan Apache serta MySQL.
2. Buka phpMyAdmin atau MySQL client.
3. Buat database dengan nama `kopitani`.
4. Pastikan konfigurasi database pada file `.env` sudah sesuai.

### Langkah 5. Generate Application Key

Jalankan perintah:

```bash
php artisan key:generate
```

### Langkah 6. Menjalankan Migration

Jalankan migration untuk membuat tabel database:

```bash
php artisan migrate
```

### Langkah 7. Membuat Storage Link

Jalankan perintah:

```bash
php artisan storage:link
```

### Langkah 8. Menjalankan Frontend

Jalankan proses build frontend:

```bash
npm run build
```

### Langkah 9. Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://localhost:8000
```

## 5. Informasi Akses Login Pengguna

Gunakan akun demo berikut untuk menguji aplikasi berdasarkan masing-masing role.

| Role | Email | Password |
|---|---|---|
| Admin | admin@kopitani.test | KopiTaniAdmin2026! |
| Petani | petani@kopitest | 0987654321 |
| Kedai | kedai@test | 1234567890 |

**Catatan:** Akun demo digunakan untuk keperluan pengujian dan demonstrasi aplikasi.

## 6. Struktur Database

KopiTani menggunakan database relasional MySQL dengan tabel utama:

| Tabel | Keterangan |
|---|---|
| users | Menyimpan data pengguna dan role |
| categories | Menyimpan kategori produk |
| products | Menyimpan data produk kopi |
| orders | Menyimpan data pesanan |
| order_items | Menyimpan detail produk dalam pesanan |

Relasi database menggunakan foreign key untuk menjaga konsistensi antar data.

## 7. Keamanan Aplikasi

Beberapa mekanisme keamanan yang diterapkan:

- Validasi input pada sisi server.
- Password disimpan menggunakan hashing.
- Proteksi CSRF melalui Laravel.
- Proteksi query database menggunakan Eloquent ORM.
- Pembatasan akses berdasarkan role pengguna.
- Pemeriksaan kepemilikan data sebelum mengubah atau menghapus produk.
- Pengelolaan session saat login dan logout.

## 8. Repository

Source code aplikasi:

https://github.com/nandadefrianti-cmd/kopitani

## 9. Identitas Project

- **Nama Aplikasi:** KopiTani
- **Jenis Aplikasi:** Marketplace B2B
- **Mata Kuliah:** Web Application Development
- **Universitas:** Universitas Cakrawala
- **Nama Pengembang:** [Nanda Deprianti]
- **NIM:** [25110300006]

10. Informasi Deployment

URL aplikasi: [Isi URL aplikasi setelah deployment]