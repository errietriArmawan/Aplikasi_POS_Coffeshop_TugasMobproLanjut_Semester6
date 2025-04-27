<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    protected $fillable = [
        'transaction_id', 'product_id', 'quantity', 'price', 'total'
    ];

    // Relasi dengan Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi dengan Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
