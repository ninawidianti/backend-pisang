<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Store a new payment for an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $orderId)
    {
        // Validasi input
        $request->validate([
            'payment_method' => 'required|string',
            'delivery_method' => 'required|string',
            'address' => 'nullable|string',
            'total_price' => 'required|numeric',
        ]);

        // Temukan pesanan berdasarkan ID
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // Buat pembayaran baru
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'delivery_method' => $request->delivery_method,
            'address' => $request->address,
            'total_price' => $request->total_price,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $payment
        ], 201);
    }

    /**
     * Get payment details by payment ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $payment = Payment::with('order')->find($id);

        if ($payment) {
            return response()->json([
                'status' => 'success',
                'data' => $payment
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }
    }
}