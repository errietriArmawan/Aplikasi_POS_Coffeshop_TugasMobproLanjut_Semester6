<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'total', 'status', 'notes',
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
}
