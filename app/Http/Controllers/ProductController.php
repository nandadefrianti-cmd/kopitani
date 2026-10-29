<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Menampilkan produk milik petani yang sedang login
    public function index()
    {
        abort_unless(Auth::user()->role === 'petani', 403);

        $products = Product::where('farmer_id', Auth::id())
            ->latest()
            ->get();

        return view('products.index', compact('products'));
    }

    // Menampilkan formulir tambah produk
    public function create()
    {
        abort_unless(Auth::user()->role === 'petani', 403);

        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    // Menyimpan produk baru beserta foto
    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'petani', 403);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'wholesale_min_qty' => ['required', 'integer', 'min:1'],
            'wholesale_price' => ['required', 'numeric', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        Product::create([
            'farmer_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::lower(Str::random(6)),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'wholesale_min_qty' => $validated['wholesale_min_qty'],
            'wholesale_price' => $validated['wholesale_price'],
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan dan menunggu verifikasi admin.');
    }

    // Menampilkan formulir edit produk
    public function edit(Product $product)
    {
        abort_unless(Auth::user()->role === 'petani', 403);
        abort_unless($product->farmer_id === Auth::id(), 403);

        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    // Memperbarui data produk dan foto jika diunggah
    public function update(Request $request, Product $product)
    {
        abort_unless(Auth::user()->role === 'petani', 403);
        abort_unless($product->farmer_id === Auth::id(), 403);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'wholesale_min_qty' => ['required', 'integer', 'min:1'],
            'wholesale_price' => ['required', 'numeric', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::lower(Str::random(6)),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'wholesale_min_qty' => $validated['wholesale_min_qty'],
            'wholesale_price' => $validated['wholesale_price'],
            'status' => 'pending',
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');

            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $data['image_path'] = $imagePath;
        }

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui dan menunggu verifikasi admin.');
    }

    // Menghapus produk dan foto miliknya
    public function destroy(Product $product)
    {
        abort_unless(Auth::user()->role === 'petani', 403);
        abort_unless($product->farmer_id === Auth::id(), 403);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    // Menampilkan katalog produk yang disetujui untuk kedai
    public function catalog()
    {
        abort_unless(Auth::user()->role === 'kedai', 403);

        $products = Product::with('category')
            ->where('status', 'approved')
            ->where('stock', '>', 0)
            ->latest()
            ->get();

        return view('products.catalog', compact('products'));
    }
}
