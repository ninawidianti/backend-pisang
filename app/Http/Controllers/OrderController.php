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

     public function index()
     {
         $orders = Order::all(); // Mengambil semua pesanan
         return response()->json($orders);
     }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'delivery_method' => 'required|string',
            'address' => 'nullable|string',
            'total_price' => 'required|numeric',
            'status' => 'in:pending,process,completed,canceled',
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
            'status' => 'sometimes|in:pending,process,completed,canceled',
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

    public function updateStatus(Request $request, $id)
{
    // Validasi input, pastikan status dikirim dan valid
    $validator = Validator::make($request->all(), [
        'status' => 'required|in:pending,process,completed,canceled',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422); // Error jika validasi gagal
    }

    // Cari pesanan berdasarkan ID
    $order = Order::find($id);

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404); // Pesanan tidak ditemukan
    }

    // Update status pesanan
    $order->status = $request->status;
    $order->save(); // Simpan perubahan

    return response()->json([
        'message' => 'Order status updated successfully',
        'order' => $order
    ]); // Mengembalikan response sukses
}

public function getOrders(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id', // Validasi user_id
        'status' => 'nullable|in:pending,process,completed,canceled', // Validasi status (opsional)
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $userId = $request->input('user_id'); // Ambil user_id dari POST
    $status = $request->input('status');  // Ambil status jika ada

    // Query orders berdasarkan user_id dan status jika ada
    $query = Order::where('user_id', $userId);

    if ($status) {
        $query->where('status', $status);
    }

    $orders = $query->get();

    if ($orders->isEmpty()) {
        return response()->json(['message' => 'No orders found for this user'], 404);
    }

    return response()->json($orders, 200);
}



}