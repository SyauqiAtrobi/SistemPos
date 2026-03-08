<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Menampilkan halaman utama katalog parfum.
     */
    public function index(Request $request)
    {
        // 1. Ambil semua data kategori untuk ditampilkan sebagai tombol filter di atas
        $categories = Category::all();

        // 2. Siapkan query utama untuk mengambil produk
        // Menggunakan 'with' (Eager Loading) agar relasi kategori ikut ditarik, 
        // mencegah masalah N+1 Query yang bikin web lambat.
        $query = Product::with('category');

        // 3. Cek apakah pengguna mengklik tombol filter kategori tertentu (parameter ?category=slug)
        if ($request->has('category') && $request->category != '') {
            $categorySlug = $request->category;

            // Filter produk yang memiliki kategori dengan slug yang sesuai
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // 4. Jika ada query pencarian ('q'), filter pada nama/description
        if ($request->has('q') && trim($request->q) !== '') {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // 5. Ambil data produk (diurutkan dari yang terbaru ditambahkan)
        $products = $query->latest()->get();

        // Jika permintaan JSON (AJAX), kembalikan data produk dalam format terstruktur
        if ($request->wantsJson() || $request->ajax()) {
            $data = $products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->price,
                    'price_text' => 'Rp ' . number_format($p->price, 0, ',', '.'),
                    'stock' => $p->stock,
                    'description' => $p->description,
                    'category' => $p->category?->name,
                    'image' => $p->image ? asset('storage/'.$p->image) : 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=400&q=80'
                ];
            });
            return response()->json(['products' => $data]);
        }

        // 5. Kirim data ke tampilan (View) katalog.blade.php
        return view('katalog', compact('categories', 'products'));
    }

    /**
     * Menampilkan halaman Manajemen Produk untuk Admin
     */
    public function manage(Request $request)
    {
        $perPage = (int) $request->query('perPage', 10);
        if (!in_array($perPage, [10,25,50,100])) $perPage = 10;

        $products = Product::with('category')->latest()->paginate($perPage)->withQueryString();
        $categories = Category::all();

        return view('productmanagement', compact('products', 'categories'));
    }

    /**
     * Menyimpan data produk baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . time();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Memperbarui data produk
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->name !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . time();
        }

        // Hapus gambar lama jika ada gambar baru yang diunggah
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return back()->with('success', 'Data produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}
