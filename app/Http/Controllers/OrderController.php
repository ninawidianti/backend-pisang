<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Method to create a new order
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id', // Pastikan semua product_id valid
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1', // Pastikan semua quantity valid
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User  tidak ditemukan atau belum login'
            ], 401);
        }

        // Mulai pesanan
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 0,
            'status' => 'pending' // Status awal adalah pending
        ]);

        $totalPrice = 0;

        // Loop melalui produk yang dipilih
        foreach ($request->product_id as $index => $productId) {
            $product = Product::find($productId);
            $quantity = $request->quantity[$index];
            $price = $product->price;
            $totalPrice += $price * $quantity;

            // Buat detail pesanan
            DetailOrder::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price
            ]);
        }

        // Update total harga pesanan
        $order->update(['total_price' => $totalPrice]);

        return response()->json([
            'status' => 'success',
            'data' => $order->load('orderDetails.product') // Memuat detail pesanan dengan produk
        ], 201);
    }

    // Method to get order details by order ID
    public function show($id)
    {
        $order = Order::with('orderDetails.product')->find($id);

        if ($order) {
            return response()->json([
                'status' => 'success',
                'data' => $order
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }
    }

    // Method to update order status (contoh tambahan)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,completed,canceled'
        ]);

        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'data' => $order
        ], 200);
    }

    // Method to delete an order (contoh tambahan)
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // Hapus detail order terkait
        DetailOrder::where('order_id', $id)->delete();
        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Order deleted successfully'
        ], 200);
    }
}