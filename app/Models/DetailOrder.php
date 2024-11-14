<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    // Relationship to the order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship to the product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}