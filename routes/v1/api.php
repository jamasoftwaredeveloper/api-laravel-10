<?php

use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('products', ProductController::class);
Route::resource('sales', SaleController::class);
Route::put('products/{product}/inactiveOrActivate', 'App\Http\Controllers\v1\ProductController@inactiveOrActivate')
    ->name('products.inactiveOrActivate');
