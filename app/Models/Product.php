<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan nama default
    protected $table = 'products';

    // Tentukan kolom yang bisa diisi massal
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
    ];

    // Tentukan tipe data kolom jika diperlukan
    protected $casts = [
        'price' => 'decimal:2',  // Untuk memastikan harga disimpan dengan 2 desimal
    ];
}
