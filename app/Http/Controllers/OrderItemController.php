<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderItemController extends Controller
{
    /**
     * Menyimpan item pesanan baru.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Membuat batch_id secara otomatis berdasarkan order_id dan waktu saat ini
        $batchId = $request->order_id . '-' . now()->timestamp;

        // Menambahkan batch_id ke data input
        $request->merge(['batch_id' => $batchId]);

        // Buat item pesanan baru
        $orderItem = OrderItem::create($request->all());

        return response()->json($orderItem, 201);
    }

    /**
     * Menampilkan semua item pesanan.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderId = $request->query('order_id'); // Ambil order_id dari parameter query
    
        if ($orderId) {
            // Jika order_id diberikan, filter item pesanan berdasarkan order_id
            $orders = OrderItem::where('order_id', $orderId)->get();
        } else {
            // Jika order_id tidak diberikan, kembalikan semua item pesanan
            $orders = OrderItem::all();
        }
    
        return response()->json($orders);
    }
    

    /**
     * Menampilkan detail item pesanan berdasarkan ID.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orderItem = OrderItem::find($id);

        if (!$orderItem) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        return response()->json($orderItem);
    }

    /**
     * Memperbarui item pesanan yang ada.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'quantity' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $orderItem = OrderItem::find($id);

        if (!$orderItem) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        // Jika batch_id tidak ada dalam permintaan, jangan ubah batch_id
        if ($request->has('batch_id')) {
            $orderItem->batch_id = $request->batch_id;
        }

        // Perbarui item pesanan
        $orderItem->update($request->all());

        return response()->json($orderItem);
    }

    /**
     * Menghapus item pesanan berdasarkan ID.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orderItem = OrderItem::find($id);

        if (!$orderItem) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        // Hapus item pesanan
        $orderItem->delete();

        return response()->json(['message' => 'Order item deleted successfully']);
    }
}
