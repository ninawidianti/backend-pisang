<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Stokbahan;
use App\Models\UnexpectedExpense;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinancialController extends Controller
{
    // Fungsi untuk mengambil data pemasukan
    public function getIncome(Request $request)
    {
        $filter = $request->query('filter', 'daily');

        // Pastikan menggunakan zona waktu Asia/Jakarta
        $date = Carbon::now('Asia/Jakarta'); // Gunakan waktu lokal

        // Query untuk mengambil data pesanan yang statusnya 'completed'
        $ordersQuery = Order::where('status', 'completed');

        // Filter berdasarkan periode waktu yang diminta
        if ($filter === 'daily') {
            $ordersQuery->whereDate('created_at', $date->toDateString());
        } elseif ($filter === 'weekly') {
            // Jika weekly, hitung minggu yang dimulai dari hari Senin
            $startOfWeek = $date->startOfWeek(Carbon::MONDAY)->startOfDay(); // Mulai minggu pada hari Senin
            $endOfWeek = $date->endOfWeek(Carbon::SUNDAY)->endOfDay(); // Akhir minggu pada hari Minggu

            // Debugging output untuk memastikan tanggal yang dihitung
            Log::info("Start of Week (Monday): " . $startOfWeek);
            Log::info("End of Week (Sunday): " . $endOfWeek);

            // Ambil data pesanan dalam rentang minggu (Senin - Minggu)
            $ordersQuery->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        } elseif ($filter === 'monthly') {
            $ordersQuery->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year);
        }

        // Ambil data pesanan berdasarkan filter
        $orders = $ordersQuery->get();

        // Format data untuk respons
        $incomeData = $orders->map(function ($order) {
            return [
                'date' => $order->created_at->toDateString(),
                'amount' => $order->total_price,
                'description' => 'Order ID: ' . $order->id,
            ];
        });

        return response()->json(['data' => $incomeData]);
    }

    // Fungsi untuk mengambil data pengeluaran
    public function getExpenses(Request $request)
    {
        $filter = $request->query('filter', 'daily');

        // Pastikan menggunakan zona waktu Asia/Jakarta
        $date = Carbon::now('Asia/Jakarta'); // Gunakan waktu lokal

        // Query untuk mengambil data stok bahan
        $stokbahansQuery = Stokbahan::query();

        // Filter berdasarkan periode waktu yang diminta
        if ($filter === 'daily') {
            $stokbahansQuery->whereDate('created_at', $date->toDateString());
        } elseif ($filter === 'weekly') {
            // Jika weekly, hitung minggu yang dimulai dari hari Senin
            $startOfWeek = $date->startOfWeek(Carbon::SUNDAY)->startOfDay(); // Mulai minggu pada hari Senin
            $endOfWeek = $date->endOfWeek(Carbon::MONDAY)->endOfDay(); // Akhir minggu pada hari Minggu

            // Debugging output untuk memastikan tanggal yang dihitung
            Log::info("Start of Week (Sunday): " . $startOfWeek);
            Log::info("End of Week (Monday): " . $endOfWeek);

            // Ambil data pengeluaran dalam rentang minggu (Senin - Minggu)
            $stokbahansQuery->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        } elseif ($filter === 'monthly') {
            $stokbahansQuery->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year);
        }

        // Ambil data pengeluaran berdasarkan filter
        $expenses = $stokbahansQuery->get();

        // Format data untuk respons
        $expenseData = $expenses->map(function ($expense) {
            return [
                'date' => $expense->created_at->toDateString(),
                'amount' => $expense->purchase_price, // Menggunakan 'purchase_price' sebagai jumlah pengeluaran
                'description' => 'Item: ' . $expense->name . ' from ' . $expense->supplier,
            ];
        });

        return response()->json(['data' => $expenseData]);
    }

    //fungsi untuk addincome dan expense
    public function addIncome(Request $request)
{
    $validated = $request->validate([
        'total_price' => 'required|numeric',
        'user_id' => 'required|exists:users,id',
        'payment_method' => 'required|string',
        'delivery_method' => 'required|string',
        'status' => 'required|in:pending,process,completed,canceled',
        'address' => 'nullable|string',
    ]);

    $order = Order::create($validated);

    return response()->json(['message' => 'Pemasukan berhasil ditambahkan!', 'data' => $order], 201);
}


    public function addExpense(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'stock_quantity' => 'required|numeric',
        'unit' => 'required|string',
        'purchase_price' => 'required|numeric',
        'supplier' => 'required|string',
    ]);

    $stokbahan = Stokbahan::create($validated);

    return response()->json(['message' => 'Pengeluaran berhasil ditambahkan!', 'data' => $stokbahan], 201);
}

public function updateIncome(Request $request, $id)
{
    $validated = $request->validate([
        'total_price' => 'required|numeric',
        'user_id' => 'required|exists:users,id',
        'payment_method' => 'required|string',
        'delivery_method' => 'required|string',
        'status' => 'required|in:pending,process,completed,canceled',
        'address' => 'nullable|string',
    ]);

    $order = Order::find($id);
    if (!$order) {
        return response()->json(['message' => 'Pemasukan tidak ditemukan'], 404);
    }

    $order->update($validated);

    return response()->json(['message' => 'Pemasukan berhasil diperbarui!', 'data' => $order]);
}

public function updateExpense(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'stock_quantity' => 'required|numeric',
        'unit' => 'required|string',
        'purchase_price' => 'required|numeric',
        'supplier' => 'required|string',
    ]);

    $stokbahan = Stokbahan::find($id);
    if (!$stokbahan) {
        return response()->json(['message' => 'Pengeluaran tidak ditemukan'], 404);
    }

    $stokbahan->update($validated);

    return response()->json(['message' => 'Pengeluaran berhasil diperbarui!', 'data' => $stokbahan]);
}

    //fungsi untuk delete

    public function deleteIncome($id)
{
    $order = Order::find($id);
    if (!$order) {
        return response()->json(['message' => 'Pemasukan tidak ditemukan'], 404);
    }

    $order->delete();

    return response()->json(['message' => 'Pemasukan berhasil dihapus!']);
}


public function deleteExpense($id)
{
    $stokbahan = Stokbahan::find($id);
    if (!$stokbahan) {
        return response()->json(['message' => 'Pengeluaran tidak ditemukan'], 404);
    }

    $stokbahan->delete();

    return response()->json(['message' => 'Pengeluaran berhasil dihapus!']);
}

public function generatePDF(Request $request)
    {
        // Ambil bulan dan tahun dari query parameter, jika tidak ada, gunakan bulan dan tahun saat ini
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        // Mengambil data pemasukan berdasarkan bulan dan tahun
        $incomeData = Order::where('status', 'completed')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get()
            ->map(function ($order) {
                return [
                    'date' => $order->created_at->toDateString(),
                    'amount' => $order->total_price,
                    'description' => 'Order ID: ' . $order->id,
                ];
            });

        // Mengambil data pengeluaran berdasarkan bulan dan tahun
        $stokbahans = Stokbahan::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get()
            ->map(function ($stokbahan) {
                return [
                    'date' => $stokbahan->created_at->toDateString(),
                    'amount' => $stokbahan->purchase_price,
                    'description' => 'Item: ' . $stokbahan->name . ' from ' . $stokbahan->supplier,
                ];
            });

        $otherExpenses = UnexpectedExpense::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get()
            ->map(function ($expense) {
                return [
                    'date' => $expense->created_at->toDateString(),
                    'amount' => $expense->amount,
                    'description' => $expense->description,
                ];
            });

        // Gabungkan data pemasukan dan pengeluaran
        $expenses = $stokbahans->merge($otherExpenses)->sortByDesc('date')->values();

        // Pastikan data yang dikirim ke view sudah benar
        $data = [
            'income' => $incomeData,
            'expenses' => $expenses,
            'month' => $month, // Tambahkan bulan ke data
            'year' => $year,   // Tambahkan tahun ke data
        ];

        // Menggunakan view untuk membuat PDF
        $pdf = PDF::loadView('reports.financial', $data);
        return $pdf->download('laporan_keuangan_' . $month . '_' . $year . '.pdf');
    }

}