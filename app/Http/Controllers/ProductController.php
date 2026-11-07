<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk & menu
     */
    public function index(Request $request)
    {
        $query = Product::with('variants');

        // Fitur Pencarian Nama atau Kategori
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Ambil data dengan pagination (10 item per halaman)
        $products = $query->latest()->paginate(10);

        // Statistik Ringkas
        $totalProducts    = Product::count();
        $activeProducts   = Product::where('is_active', true)->count();
        $inactiveProducts = Product::where('is_active', false)->count();
        $categoryCount   = Product::distinct('category')->count('category');

        return view('products.index', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'categoryCount'
        ));
    }

    /**
     * Simpan Produk Baru & Varian Harga
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'category'         => 'required|string|max:100',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'        => 'required|boolean',
            'prices'           => 'required|array',
            'prices.Small'     => 'required|numeric|min:0',
            'prices.Medium'    => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle Upload Gambar (Jika ada)
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            // 2. Buat Produk Utama
            $product = Product::create([
                'name'      => $request->name,
                'category'  => $request->category,
                // 'image'     => $imagePath,
                'is_active' => $request->is_active,
            ]);

            // 3. Simpan Varian Ukuran & Harga (Small & Medium)
            foreach ($request->prices as $cupSize => $price) {
                if (!is_null($price) && $price !== '') {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'cup_size'   => $cupSize,
                        'price'      => $price,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus foto jika database gagal disimpan
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()->back()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    /**
     * Update Produk & Varian Harga
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|string|max:100',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'      => 'required|boolean',
            'prices'         => 'required|array',
            'prices.Small'   => 'required|numeric|min:0',
            'prices.Medium'  => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle Upload Gambar Baru (Jika Diubah)
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->image = $request->file('image')->store('products', 'public');
            }

            // 2. Update Informasi Utama Produk
            $product->update([
                'name'      => $request->name,
                'category'  => $request->category,
                'is_active' => $request->is_active,
                // 'image'     => $product->image,
            ]);

            // 3. Update Varian Harga (Small & Medium)
            foreach ($request->prices as $cupSize => $price) {
                if (!is_null($price) && $price !== '') {
                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'cup_size'   => $cupSize,
                        ],
                        [
                            'price' => $price,
                        ]
                    );
                } else {
                    // Jika harga Medium dikosongkan saat edit, hapus varian Medium tersebut
                    ProductVariant::where('product_id', $product->id)
                        ->where('cup_size', $cupSize)
                        ->delete();
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal mempebarui produk: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Produk beserta Gambar & Varian
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        try {
            // Hapus file gambar dari storage
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Hapus record produk (varian akan otomatis terhapus jika migration menggunakan onDelete('cascade'))
            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}