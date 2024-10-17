<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/Register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::middleware([EnsureFrontendRequestsAreStateful::class])->group(function () {
//     // Daftar route API-mu di sini

//     // Route::post('/Register', [AuthController::class,'register']);
//
// });

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products/create', [ProductController::class, 'store']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

Route::get('/users', [UserController::class,'index']);
