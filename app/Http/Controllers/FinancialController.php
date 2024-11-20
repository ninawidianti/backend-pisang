<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Stokbahan;
use Carbon\Carbon;

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
            \Log::info("Start of Week (Monday): " . $startOfWeek);
            \Log::info("End of Week (Sunday): " . $endOfWeek);

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
            \Log::info("Start of Week (Sunday): " . $startOfWeek);
            \Log::info("End of Week (Monday): " . $endOfWeek);

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
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'date' => 'required|date',
        ]);

        $income = new Income();
        $income->amount = $validated['amount'];
        $income->description = $validated['description'];
        $income->date = $validated['date'];
        $income->save();

        return response()->json(['message' => 'Pemasukan berhasil ditambahkan!'], 201);
    }

    public function addExpense(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'date' => 'required|date',
        ]);

        $expense = new Expense();
        $expense->amount = $validated['amount'];
        $expense->description = $validated['description'];
        $expense->date = $validated['date'];
        $expense->save();

        return response()->json(['message' => 'Pengeluaran berhasil ditambahkan!'], 201);
    }
    //fungsi untuk delete

    public function deleteIncome($id)
    {
        $income = Income::find($id);
        if (!$income) {
            return response()->json(['message' => 'Pemasukan tidak ditemukan'], 404);
        }

        $income->delete();

        return response()->json(['message' => 'Pemasukan berhasil dihapus!']);
    }

    public function deleteExpense($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Pengeluaran tidak ditemukan'], 404);
        }

        $expense->delete();

        return response()->json(['message' => 'Pengeluaran berhasil dihapus!']);
    }


}
