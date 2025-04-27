<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Menambahkan transaksi baru
    public function store(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'products' => 'required|array', // Data produk harus berupa array
            'products.*.product_id' => 'required|exists:products,id', // ID produk harus ada di database
            'products.*.quantity' => 'required|integer|min:1', // Kuantitas harus lebih dari 0
            'status' => 'required|string', // Status transaksi harus ada
        ]);

        // Membuat transaksi baru
        $transaction = Transaction::create([
            'user_id' => Auth::user()->id, // Kasir yang sedang login
            'status' => $request->status,
            'notes' => $request->notes ?? '',
            'total' => 0, // Total akan dihitung berdasarkan produk yang dipilih
        ]);

        $total = 0; // Total harga transaksi
        foreach ($request->products as $productData) {
            $product = Product::find($productData['product_id']);
            if (!$product) {
                return response()->json(['error' => 'Product not found.'], 404);
            }

            // Menghitung total harga produk yang dibeli
            $productTotal = $product->price * $productData['quantity'];
            $total += $productTotal;

            // Menyimpan produk ke tabel pivot transaction_products
            $transaction->products()->attach($product->id, [
                'quantity' => $productData['quantity'],
                'price' => $product->price,
                'total' => $productTotal,  // Total harga produk
            ]);
        }

        // Memperbarui total transaksi
        $transaction->update(['total' => $total]);

        return response()->json($transaction, 201);
    }

    public function index()
    {
        // Untuk melihat transaksi beserta user dan produk terkait
        $transactions = Transaction::with(['user', 'products'])->get();
        return response()->json($transactions);
    }

    public function show($id)
    {
        try {
            // Cari transaksi dengan ID tertentu, termasuk produk terkait
            $transaction = Transaction::with(['user', 'products'])->findOrFail($id);
            
            // Jika transaksi ditemukan, kembalikan sebagai response
            return response()->json($transaction);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika transaksi tidak ditemukan, beri pesan error yang sesuai
            return response()->json(['error' => 'Transaction not found.'], 404);
        } catch (\Exception $e) {
            // Tangani error lainnya
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
    
    
}
