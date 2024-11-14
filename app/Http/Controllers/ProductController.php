<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Method index untuk mendapatkan semua produk
    public function index() {
        $products = Product::all(); //eloquent
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    // Method show untuk mendapatkan produk berdasarkan id
    public function show($id) {
        $product = Product::find($id);

        if ($product) {
            return response()->json([
                'status' => 'success',
                'data' => $product
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
    }

    // Method store untuk menambahkan produk baru
    public function store(Request $request) {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image_url' => 'required|url'
        ]);

        // Buat produk baru
        $product = new Product();
        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->image_url = $validatedData['image_url'];
        
        // Simpan produk ke database
        $product->save();

        // Kembalikan response sukses
        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 201); // 201: Created
    }

    // Method destroy untuk menghapus produk berdasarkan id
    public function destroy($id) {
        $product = Product::find($id);

        if ($product) {
            $product->delete(); // Menghapus produk dari database
            return response()->json([
                'status' => 'success',
                'message' => 'Product successfully deleted'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
    }

    // Method update untuk mengubah produk berdasarkan id
public function update(Request $request, $id) {
    // Validasi input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'image_url' => 'required|url'
    ]);

    // Cari produk berdasarkan id
    $product = Product::find($id);

    if ($product) {
        // Update atribut produk dengan data yang divalidasi
        $product->name = $validatedData['name'];
        $product->description = $validatedData['description'];
        $product->price = $validatedData['price'];
        $product->image_url = $validatedData['image_url'];

        // Simpan perubahan ke database
        $product->save();

        // Kembalikan response sukses
        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 200); // 200: OK
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Product not found'
        ], 404);
    }
}

}
