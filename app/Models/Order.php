<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method',
        'delivery_method',
        'address',
        'total_price',
        'status',
    ];

    /**
     * Mendapatkan item-item pesanan untuk pesanan ini.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}