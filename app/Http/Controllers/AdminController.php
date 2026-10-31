<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Notifications\KopiTaniNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Menampilkan daftar produk yang menunggu verifikasi Admin
    public function products()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $products = Product::with(['farmer', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.products', compact('products'));
    }

    // Menyetujui produk dan mengirim notifikasi kepada Petani
    public function approveProduct(Product $product)
    {
        abort_unless(Auth::user()->role === 'admin', 403);
        abort_unless($product->status === 'pending', 404);

        $product->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        $product->farmer->notify(new KopiTaniNotification(
            'Produk Disetujui',
            'Produk ' . $product->name . ' telah disetujui dan sekarang tampil di katalog.',
            url('/products'),
            'success'
        ));

        return redirect()
            ->route('admin.products')
            ->with('success', 'Produk berhasil disetujui.');
    }

    // Menolak produk dan mengirim alasan penolakan kepada Petani
    public function rejectProduct(Request $request, Product $product)
    {
        abort_unless(Auth::user()->role === 'admin', 403);
        abort_unless($product->status === 'pending', 404);

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $product->farmer->notify(new KopiTaniNotification(
            'Produk Ditolak',
            'Produk ' . $product->name . ' ditolak. Alasan: ' . $validated['rejection_reason'],
            url('/products'),
            'danger'
        ));

        return redirect()
            ->route('admin.products')
            ->with('success', 'Produk berhasil ditolak.');
    }

    // Menampilkan daftar akun Petani dan Kedai
    public function users()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $users = User::whereIn('role', ['petani', 'kedai'])
            ->latest()
            ->get();

        return view('admin.users', compact('users'));
    }
}
