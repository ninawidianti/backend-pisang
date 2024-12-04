<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\FinancialController;
use App\Http\Controllers\StokBahanController;

Route::get('/stokbahan/pdf', [StokBahanController::class, 'generatePDF'])->name('stokbahan.pdf');


Route::get('/financial/pdf', [FinancialController::class, 'generatePDF']);
