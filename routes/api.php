<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StokbahanController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinancialController;


// Route untuk registrasi dan login
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


// Route yang memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {

    // Route untuk mendapatkan data user saat ini
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user.profile');




    // Route untuk produk
    Route::get('/products', [ProductController::class, 'index'])->name('index');;
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('show');;
    Route::post('/products/create', [ProductController::class, 'store'])->name('store');;
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('destroy');;
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('update');;

    // Route untuk order
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-items', OrderItemController::class);

    // Route untuk logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});

    // Route untuk stok bahan
    Route::get('/stokbahan', [StokbahanController::class, 'index']);
    Route::get('/stokbahan/{id}', [StokbahanController::class, 'show']);
    Route::post('/stokbahan/create', [StokbahanController::class, 'store']);
    Route::delete('/stokbahan/{id}', [StokbahanController::class, 'destroy']);
    Route::put('/stokbahan/{id}', [StokbahanController::class, 'update']);


    //route untu manajemen keuangan
    Route::get('/income', [FinancialController::class, 'getIncome']);
    Route::get('/expenses', [FinancialController::class, 'getExpenses']);
    // Route untuk menambah data pemasukan/pengeluaran
    Route::post('income', [FinancialController::class, 'addIncome']);
    Route::post('expenses', [FinancialController::class, 'addExpense']);
    //Route untuk hapus data
    Route::delete('income/{id}', [FinancialController::class, 'deleteIncome']);
    Route::delete('expenses/{id}', [FinancialController::class, 'deleteExpense']);



    // Route untuk user index
    Route::get('/users', [UserController::class, 'index']);
