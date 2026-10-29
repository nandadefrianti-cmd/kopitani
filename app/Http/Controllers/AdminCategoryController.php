<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:categories,name,' . $category->id,
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        abort_unless(Auth::user()->role === 'admin', 403);

        if ($category->products()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan produk.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
