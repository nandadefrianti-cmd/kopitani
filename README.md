# KopiTani

**Marketplace B2B Bahan Baku Kopi Lokal**

KopiTani merupakan aplikasi marketplace berbasis web yang menghubungkan petani kopi lokal dengan pemilik kedai kopi. Melalui aplikasi ini, petani dapat menawarkan dan mengelola produk kopi, sedangkan pemilik kedai dapat melihat katalog, melakukan pemesanan dalam jumlah grosir, mengunggah bukti pembayaran, dan memantau status pesanan. Admin memverifikasi setiap produk sebelum tampil di katalog.

**Aplikasi online:** https://kopitani.ethiodeploy.app/

## 1. Teknologi yang Digunakan

- **Backend:** PHP 8.3 dan Laravel 13
- **Frontend:** Blade, Tailwind CSS, dan Vite
- **Database:** MySQL
- **ORM:** Laravel Eloquent ORM
- **Development Environment:** Laragon
- **Version Control:** Git dan GitHub
- **Deployment:** EthioDeploy

## 2. Fitur Aplikasi

### Admin
- Melihat dashboard admin.
- Mengelola kategori produk (tambah, ubah, hapus).
- Melihat daftar pengguna.
- Meninjau produk yang diajukan petani.
- Menyetujui atau menolak produk (dengan alasan penolakan).

### Petani
- Melihat dashboard petani.
- Menambahkan produk kopi (foto, harga, stok, harga dan jumlah minimum grosir).
- Melihat daftar produk milik sendiri.
- Mengubah informasi produk (perubahan diajukan ulang untuk diverifikasi admin).
- Menghapus produk milik sendiri.
- Melihat pesanan yang masuk.
- Mengonfirmasi atau menolak bukti pembayaran dari kedai.
- Memperbarui status pesanan menjadi dikirim.

### Kedai
- Melihat katalog produk yang telah disetujui admin.
- Melihat detail produk dan harga.
- Melakukan checkout dan pemesanan (stok dan minimum jumlah grosir divalidasi).
- Mengunggah bukti pembayaran (dapat diunggah ulang jika ditolak).
- Melihat daftar pesanan sendiri beserta riwayatnya.
- Mengonfirmasi pesanan diterima sehingga pesanan selesai.

### Autentikasi dan Fitur Pendukung
- Registrasi pengguna (role petani atau kedai).
- Login dan logout.
- Pengelolaan session.
- Pembatasan akses berdasarkan role pengguna.
- Notifikasi untuk kejadian penting dan riwayat aktivitas pesanan.

### Alur Status Pesanan

`menunggu_pembayaran` -> `dibayar` -> `dikirim` -> `selesai`

1. Kedai membuat pesanan (status `menunggu_pembayaran`) lalu mengunggah bukti pembayaran.
2. Petani memeriksa bukti: jika valid status menjadi `dibayar`, jika tidak bukti ditolak dan kedai mengunggah ulang.
3. Petani mengirim pesanan (status `dikirim`).
4. Kedai mengonfirmasi pesanan diterima (status `selesai`).

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

Pada macOS, Linux, atau Git Bash:

```bash
cp .env.example .env
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

### Langkah 7. Mengisi Akun Demo (Seeder)

Jalankan seeder untuk membuat akun demo Admin, Petani, dan Kedai (lihat bagian 5):

```bash
php artisan db:seed
```

Alternatif: database contoh beserta data dummy tersedia pada file `kopitani.sql` di root repository dan dapat diimpor lewat phpMyAdmin sebagai pengganti langkah 6 dan 7.

### Langkah 8. Membuat Storage Link

Jalankan perintah:

```bash
php artisan storage:link
```

### Langkah 9. Menjalankan Frontend

Jalankan proses build frontend:

```bash
npm run build
```

Untuk pengembangan, gunakan `npm run dev` agar perubahan tampilan langsung termuat.

### Langkah 10. Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://localhost:8000
```

## 5. Informasi Akses Login Pengguna

KopiTani memiliki tiga role: Admin, Petani, dan Kedai. Akun **Admin** disiapkan oleh sistem (seeder) dan tidak bisa dibuat lewat registrasi, sedangkan akun **Petani** dan **Kedai** dibuat sendiri lewat halaman registrasi.

### a. Aplikasi online (https://kopitani.ethiodeploy.app/)

| Role | Cara mendapatkan akun | Email | Password |
|---|---|---|---|
| Admin | Sudah tersedia | admin@kopitani.test | adminkopi7890 |
| Petani | Daftar di `/register`, pilih role **Petani** | petani@kopitest | 0987654321 |
| Kedai | Daftar di `/register`, pilih role **Kedai** | kedai@test | 1234567890 |

Penguji dapat langsung login sebagai Admin. Untuk mencoba alur Petani dan Kedai, buka halaman registrasi, isi nama, email, password (minimal 8 karakter), lalu pilih role. Setelah mendaftar, pengguna otomatis masuk ke dashboard sesuai role. Email dan password Petani dan Kedai pada tabel di atas adalah akun demo yang sudah didaftarkan pada aplikasi online, sehingga dapat langsung dipakai tanpa mendaftar lagi.

### b. Instalasi lokal

Jalankan `php artisan db:seed` (atau impor `kopitani.sql`) agar ketiga akun pada tabel di atas otomatis tersedia di database lokal.

**Catatan:** Akun demo digunakan untuk keperluan pengujian dan demonstrasi aplikasi.

## 6. Struktur Database

KopiTani menggunakan database relasional MySQL dengan tabel utama:

| Tabel | Keterangan |
|---|---|
| users | Menyimpan data pengguna dan role (admin, petani, kedai) |
| categories | Menyimpan kategori produk |
| products | Menyimpan data produk kopi, status verifikasi, dan harga grosir |
| orders | Menyimpan data pesanan, status pesanan, dan status pembayaran |
| order_items | Menyimpan detail produk dalam pesanan (jumlah, harga satuan, subtotal) |
| order_histories | Menyimpan riwayat aktivitas pada setiap pesanan |
| notifications | Menyimpan notifikasi untuk pengguna |

Tabel bawaan Laravel seperti `sessions`, `cache`, dan `jobs` juga dibuat oleh migration.

Relasi database menggunakan foreign key untuk menjaga konsistensi antar data:

- `categories` ke `products` (one-to-many).
- `users` ke `products` melalui `farmer_id` (one-to-many).
- `users` ke `orders` melalui `buyer_id` dan `farmer_id` (one-to-many).
- `orders` ke `order_items` ke `products` (many-to-many melalui `order_items`).
- `orders` ke `order_histories` (one-to-many).

## 7. Keamanan Aplikasi

Beberapa mekanisme keamanan yang diterapkan:

- Validasi input pada sisi server, termasuk validasi tipe dan ukuran file unggahan.
- Password disimpan menggunakan hashing (bcrypt).
- Proteksi CSRF melalui Laravel.
- Pencegahan XSS: seluruh output di view Blade di-escape dengan `{{ }}`.
- Pencegahan SQL Injection: akses database memakai Eloquent ORM (prepared statement).
- Pembatasan akses berdasarkan role pengguna.
- Pemeriksaan kepemilikan data sebelum mengubah atau menghapus data.
- Pengelolaan session saat login dan logout (session ID diperbarui saat login).
- Bukti pembayaran disimpan di storage privat dan hanya dapat dibuka oleh pihak yang terkait dengan pesanan.

## 8. Repository

Source code aplikasi:

https://github.com/nandadefrianti-cmd/kopitani

## 9. Identitas Project

- **Nama Aplikasi:** KopiTani
- **Jenis Aplikasi:** Marketplace B2B
- **Mata Kuliah:** Web Application Development
- **Universitas:** Universitas Cakrawala
- **Nama Pengembang:** Nanda Deprianti
- **NIM:** 25110300006

## 10. Informasi Deployment

**URL aplikasi:** https://kopitani.ethiodeploy.app/

Aplikasi dideploy menggunakan EthioDeploy yang terhubung dengan repository GitHub (branch `main`).

Langkah deployment:

1. Hubungkan repository GitHub dengan project di EthioDeploy.
2. Aktifkan MySQL Addon sebagai database production. Koneksi dibaca dari variabel `DATABASE_URL` melalui `config/database.php`.
3. Isi Environment Variables di dashboard EthioDeploy: `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, `DB_CONNECTION`, `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION`, dan `FILESYSTEM_DISK`.
4. Atur proses build: instalasi dependensi dengan Composer dan build frontend dengan `npm run build`.
5. Jalankan migration dan seeder akun Admin saat aplikasi dimulai.
6. Uji aplikasi: periksa log server, lalu coba login sebagai Admin, Petani, dan Kedai.

File `.env` tidak diunggah ke GitHub; seluruh konfigurasi sensitif di production hanya disimpan sebagai Environment Variables.