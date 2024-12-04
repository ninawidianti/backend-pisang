<?php

namespace App\Http\Controllers;

use App\Models\Stokbahan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StokbahanController extends Controller
{
    public function index()
    {
        // Mengambil semua produk dari database
        $stokbahans = Stokbahan::all();
        
        // Mengembalikan produk dalam bentuk JSON
        return response()->json([
            'status' => 'success',
            'data' => $stokbahans
        ], 200);
    }

    public function show($id)
    {
        // Mencari produk berdasarkan id
        $stokbahan = Stokbahan::find($id);

        if ($stokbahan) {
            return response()->json([
                'status' => 'success',
                'data' => $stokbahan
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok bahan not found'
            ], 404);
        }
    }

    public function store(Request $request) {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'stock_quantity' => 'required|numeric|min:0',
            'unit' => 'required|in:kg,liter,pcs',
            'purchase_price' => 'required|numeric|min:0',
            'supplier' => 'required|string|max:255',
        ]);

        // Buat produk baru
        $stokbahan = new Stokbahan();
        $stokbahan->name = $validatedData['name'];
        $stokbahan->stock_quantity = $validatedData['stock_quantity'];
        $stokbahan->unit = $validatedData['unit'];
        $stokbahan->purchase_price = $validatedData['purchase_price'];
        $stokbahan->supplier = $validatedData['supplier'];
        
        // Simpan produk ke database
        $stokbahan->save();

        // Kembalikan response sukses
        return response()->json([
            'status' => 'success',
            'data' => $stokbahan
        ], 201); // 201: Created
    }

    public function destroy($id) {
        $stokbahan = Stokbahan::find($id);

        if ($stokbahan) {
            $stokbahan->delete(); // Menghapus produk dari database
            return response()->json([
                'status' => 'success',
                'message' => 'Material Stock successfully deleted'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Material Stock not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
{
    // Validasi input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'stock_quantity' => 'required|numeric|min:0',
        'unit' => 'required|in:kg,liter,pcs',
        'purchase_price' => 'required|numeric|min:0',
        'supplier' => 'required|string|max:255',
    ]);

    // Temukan stokbahan berdasarkan id
    $stokbahan = Stokbahan::find($id);

    if ($stokbahan) {
        // Update stokbahan dengan data baru
        $stokbahan->name = $validatedData['name'];
        $stokbahan->stock_quantity = $validatedData['stock_quantity'];
        $stokbahan->unit = $validatedData['unit'];
        $stokbahan->purchase_price = $validatedData['purchase_price'];
        $stokbahan->supplier = $validatedData['supplier'];
        
        // Simpan perubahan ke database
        $stokbahan->save();

        // Kembalikan response sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Material Stock successfully updated',
            'data' => $stokbahan
        ], 200);
    } else {
        // Jika stokbahan tidak ditemukan
        return response()->json([
            'status' => 'error',
            'message' => 'Material Stock not found'
        ], 404);
    }
}

public function generatePDF()
    {
        // Logic to generate PDF using Stokbahan data
        $stokbahans = Stokbahan::all();
        $pdf = Pdf::loadView('reports.stokbahan', ['stokbahans' => $stokbahans]);
        return $pdf->download('stokbahans.pdf');
    }



}
