<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Menyimpan pesanan baru.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'delivery_method' => 'required|string',
            'address' => 'nullable|string',
            'total_price' => 'required|numeric',
            'status' => 'in:pending,completed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat pesanan baru
        $order = Order::create($request->all());

        return response()->json($order, 201);
    }

    /**
     * Menampilkan detail pesanan berdasarkan ID.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    /**
     * Memperbarui pesanan yang ada.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'payment_method' => 'sometimes|required|string',
            'delivery_method' => 'sometimes|required|string',
            'address' => 'nullable|string',
            'total_price' => 'sometimes|required|numeric',
            'status' => 'sometimes|in:pending,completed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Perbarui pesanan
        $order->update($request->all());

        return response()->json($order);
    }

    /**
     * Menghapus pesanan berdasarkan ID.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Hapus pesanan
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}