<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image_url',
        // Tambahkan atribut lain yang diperlukan
    ];

    /**
     * Mendapatkan item-item pesanan yang terkait dengan produk ini.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}