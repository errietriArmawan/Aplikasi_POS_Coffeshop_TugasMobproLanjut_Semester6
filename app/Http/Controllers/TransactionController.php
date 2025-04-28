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
            'amount_paid' => 'required|numeric|min:0', // Jumlah yang dibayar
        ]);

        // Membuat transaksi baru
        $transaction = Transaction::create([
            'user_id' => Auth::user()->id, // Kasir yang sedang login
            'status' => $request->status,
            'notes' => $request->notes ?? '',
            'total' => 0, // Total akan dihitung berdasarkan produk yang dipilih
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . str_pad(Transaction::count() + 1, 4, '0', STR_PAD_LEFT), // Generate invoice number
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
                'total' => $productTotal,
            ]);

            // Mengurangi stok produk yang dibeli
            if ($product->stock >= $productData['quantity']) {
                $product->decrement('stock', $productData['quantity']); // Mengurangi stok produk
            } else {
                return response()->json(['error' => 'Not enough stock for product ' . $product->name], 400); // Error jika stok tidak cukup
            }
        }

        // Memperbarui total transaksi
        $transaction->update(['total' => $total]);

        // Menghitung kembalian jika jumlah yang dibayar lebih besar dari total
        $changeDue = 0;
        if ($request->amount_paid >= $total) {
            $changeDue = $request->amount_paid - $total;
        }

        // Update kembalian dan status pembayaran
        $transaction->update([
            'change_due' => $changeDue,
            'payment_status' => $changeDue > 0 ? 'paid' : 'unpaid', // Mengatur status pembayaran
            'amount_paid' => $request->amount_paid
        ]);

        // Mengambil data transaksi dengan produk terkait
        $transaction = $transaction->load('products');

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
