<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'total', 'status', 'notes', 'invoice_number', 'amount_paid', 'change_due', 'payment_status', // Menambahkan kolom baru
    ];

    // Relasi ke User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Produk via pivot table
    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_products')
                    ->withPivot('quantity', 'price', 'total')  // Menyertakan kolom pivot
                    ->withTimestamps();
    }

    // Membuat Invoice Number secara otomatis
    public static function generateInvoiceNumber()
    {
        // Format INV-YYYYMMDD-XXXX
        return 'INV-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    // Menambahkan method untuk menghitung kembalian
    public function calculateChange()
    {
        // Menghitung kembalian jika ada pembayaran yang lebih
        return $this->amount_paid >= $this->total ? $this->amount_paid - $this->total : 0;
    }
}
