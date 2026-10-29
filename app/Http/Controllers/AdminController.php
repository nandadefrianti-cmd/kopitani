<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function products()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $products = Product::with(['farmer', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.products', compact('products'));
    }

    public function approveProduct(Product $product)
    {
        abort_unless(Auth::user()->role === 'admin', 403);
        abort_unless($product->status === 'pending', 404);

        $product->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.products')
            ->with('success', 'Produk berhasil disetujui.');
    }

    public function rejectProduct(Request $request, Product $product)
    {
        abort_unless(Auth::user()->role === 'admin', 403);
        abort_unless($product->status === 'pending', 404);

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $product->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('admin.products')
            ->with('success', 'Produk berhasil ditolak.');
    }
    public function users()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $users = \App\Models\User::whereIn('role', ['petani', 'kedai'])
            ->latest()
            ->get();

        return view('admin.users', compact('users'));
    }
}
