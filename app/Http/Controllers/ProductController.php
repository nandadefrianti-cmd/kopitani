<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->role === 'petani', 403);
        
        $products = Product::where('farmer_id', Auth::id())
            ->latest()
            ->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        abort_unless(Auth::user()->role === 'petani', 403);

        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

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
        ]);

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
            'status' => 'pending',
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 
            'Produk berhasil ditambahkan dan menunggu verifikasi admin.');
    }
    
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
