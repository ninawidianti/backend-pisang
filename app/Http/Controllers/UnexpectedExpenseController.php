<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnexpectedExpense;

class UnexpectedExpenseController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'daily');
        $date = \Carbon\Carbon::now('Asia/Jakarta');

        $query = UnexpectedExpense::query();

        if ($filter === 'daily') {
            $query->whereDate('date', $date->toDateString());
        } elseif ($filter === 'weekly') {
            $startOfWeek = $date->startOfWeek()->toDateString();
            $endOfWeek = $date->endOfWeek()->toDateString();
            $query->whereBetween('date', [$startOfWeek, $endOfWeek]);
        } elseif ($filter === 'monthly') {
            $query->whereMonth('date', $date->month)->whereYear('date', $date->year);
        }

        return response()->json(['data' => $query->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $expense = UnexpectedExpense::create($validated);

        return response()->json(['message' => 'Biaya Tidak Terduga berhasil ditambahkan!', 'data' => $expense], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $expense = UnexpectedExpense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Biaya Tidak Terduga tidak ditemukan'], 404);
        }

        $expense->update($validated);

        return response()->json(['message' => 'Biaya Tidak Terduga berhasil diperbarui!', 'data' => $expense]);
    }

    public function destroy($id)
    {
        $expense = UnexpectedExpense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Biaya Tidak Terduga tidak ditemukan'], 404);
        }

        $expense->delete();

        return response()->json(['message' => 'Biaya Tidak Terduga berhasil dihapus!']);
    }
}