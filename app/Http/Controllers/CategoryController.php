<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name', 'asc')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:categories,code'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Category::create([
            'name' => strip_tags($validated['name']),
            'code' => strtoupper(trim($validated['code'])),
            'description' => strip_tags($validated['description'] ?? ''),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:categories,code,' . $category->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $category->update([
            'name' => strip_tags($validated['name']),
            'code' => strtoupper(trim($validated['code'])),
            'description' => strip_tags($validated['description'] ?? ''),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki relasi produk aktif.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}

