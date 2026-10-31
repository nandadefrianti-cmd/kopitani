<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Registrasi
Route::get('/register', [AuthController::class, 'showRegister'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest')
    ->name('register.store');

// Login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.store');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Semua fitur yang membutuhkan login
Route::middleware('auth')->group(function () {

    // Notifikasi
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');

    // Produk milik Petani
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    // Katalog Kedai
    Route::get('/catalog', [ProductController::class, 'catalog'])
        ->name('products.catalog');

    // Checkout dan pembuatan pesanan
    Route::get('/products/{product}/checkout', [OrderController::class, 'checkout'])
        ->name('orders.checkout');

    Route::post('/products/{product}/order', [OrderController::class, 'store'])
        ->name('orders.store');

    // Riwayat dan daftar pesanan
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    // Pembayaran oleh Kedai
    Route::post('/orders/{order}/payment-proof', [OrderController::class, 'uploadPaymentProof'])
        ->name('orders.paymentProof');

    // Konfirmasi pembayaran oleh Petani
    Route::post('/orders/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])
        ->name('orders.confirmPayment');

    Route::post('/orders/{order}/reject-payment', [OrderController::class, 'rejectPayment'])
        ->name('orders.rejectPayment');

    // Pengiriman oleh Petani
    Route::post('/orders/{order}/ship', [OrderController::class, 'shipOrder'])
        ->name('orders.ship');

    // Konfirmasi penerimaan oleh Kedai
    Route::post('/orders/{order}/confirm-received', [OrderController::class, 'confirmReceived'])
        ->name('orders.confirmReceived');

    // Melihat atau mengunduh bukti pembayaran
    Route::get('/orders/{order}/payment-proof', [OrderController::class, 'showPaymentProof'])
        ->name('orders.showPaymentProof');

    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Manajemen produk Admin
    Route::get('/admin/products', [AdminController::class, 'products'])
        ->name('admin.products');

    Route::post('/admin/products/{product}/approve', [AdminController::class, 'approveProduct'])
        ->name('admin.products.approve');

    Route::post('/admin/products/{product}/reject', [AdminController::class, 'rejectProduct'])
        ->name('admin.products.reject');

    // Manajemen kategori
    Route::resource('/admin/categories', AdminCategoryController::class)
        ->except(['show'])
        ->names('admin.categories');

    // Manajemen pengguna
    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users');
});
