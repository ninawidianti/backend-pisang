<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        
    ];

    /**
     * Mendapatkan pesanan yang memiliki item ini.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    
    protected static function booted()
    {
        static::creating(function ($order) {
            // Membuat batch_id berdasarkan user_id dan timestamp saat ini
            $order->batch_id = $order->user_id . '-' . now()->timestamp;
        });
    }
    
}