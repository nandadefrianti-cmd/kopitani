<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\OrderController;


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

// Halaman setelah login
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Produk Kopi dan Katalog
Route::middleware('auth')->group(function () {

    // Produk milik petani
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    // Katalog untuk pemilik kedai
    Route::get('/catalog', [ProductController::class, 'catalog'])
        ->name('products.catalog');

    // Checkout untuk pemilik kedai
    Route::get('/products/{product}/checkout', [OrderController::class, 'checkout'])
        ->name('orders.checkout');

    // Dashboard Admin
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');    
    
    //Admin
    Route::get('/admin/products', [AdminController::class, 'products'])
        ->name('admin.products');

    Route::post('/admin/products/{product}/approve', [AdminController::class, 'approveProduct'])
        ->name('admin.products.approve');

    Route::post('/admin/products/{product}/reject', [AdminController::class, 'rejectProduct'])
        ->name('admin.products.reject');
    
    // Manajemen Kategori
    Route::resource('/admin/categories', AdminCategoryController::class)
        ->except(['show'])
        ->names('admin.categories');
    
    // Manajemen Pengguna
    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users');

    // Menampilkan halaman edit berdasarkan ID produk
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit');

    // Menyimpan perubahan data produk yang diedit petani
    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    // Menghapus produk milik petani
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    // Pemesanan produk
    Route::post('/products/{product}/order', [OrderController::class, 'store'])
        ->name('orders.store');

    // Menampilkan daftar pesanan
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');
    
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');

});
