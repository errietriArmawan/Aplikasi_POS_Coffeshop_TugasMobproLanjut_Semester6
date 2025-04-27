<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        $products = Product::all(); // Mengambil semua produk
        return response()->json($products); // Mengembalikan daftar produk
    }

    // Menampilkan detail produk berdasarkan ID
    public function show($id)
    {
        $product = Product::findOrFail($id); // Mencari produk berdasarkan ID
        return response()->json($product);   // Mengembalikan data produk
    }

    // Menambahkan produk baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        // Menyimpan gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Membuat produk baru dan menyimpan ke database
        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully.',
            'data' => $product,
        ], 201);
    }

    // Mengupdate produk berdasarkan ID
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        $product = Product::findOrFail($id);

        // Jika ada gambar yang diupload, simpan gambar dan tambahkan ke data
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Update produk
        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => $product,
        ]);
    }

    // Menghapus produk berdasarkan ID
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
