<?php

namespace App\Models;

use App\Models\User;
use App\Models\DetailOrder; // Pastikan Anda mengimpor model DetailOrder
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_price', 'status'];

    // Relationship to the user (customer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to order details
    public function orderDetails()
    {
        return $this->hasMany(DetailOrder::class);
    }
}