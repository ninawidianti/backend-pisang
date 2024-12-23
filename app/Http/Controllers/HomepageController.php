<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class HomepageController extends Controller
{   
    public function getStats()
    {
        $totalProduk = Product::count();
        $totalCustomer = User::where('role', 'customer')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        return response()->json([
            'totalProduk' => $totalProduk,
            'totalCustomer' => $totalCustomer,
            'pendingOrders' => $pendingOrders
        ]);
    }
}
