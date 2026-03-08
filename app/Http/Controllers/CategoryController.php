<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Kategori
     */
    public function manage(Request $request)
    {
        $perPage = (int) $request->query('perPage', 10);
        if (!in_array($perPage, [10,25,50,100])) $perPage = 10;

        // Menggunakan withCount('products') untuk menghitung jumlah parfum di tiap kategori
        $categories = Category::withCount('products')->latest()->paginate($perPage)->withQueryString();
        
        return view('categorymanagement', compact('categories'));
    }

    /**
     * Menyimpan data kategori baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        // Generate slug URL secara otomatis dari nama kategori
        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
            ]);
        }

        return back()->with('success', 'Kategori aroma berhasil ditambahkan!');
    }

    /**
     * Memperbarui data kategori
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        // Perbarui slug jika nama kategori diubah
        if ($request->name !== $category->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return back()->with('success', 'Kategori aroma berhasil diperbarui!');
    }

    /**
     * Menghapus kategori
     */
    public function destroy(Category $category)
    {
        $categoryName = $category->name;
        $category->delete();

        return back()->with('success', "Kategori {$categoryName} (beserta semua produk di dalamnya) berhasil dihapus!");
    }
}