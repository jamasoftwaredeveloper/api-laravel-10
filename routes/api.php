<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\v1\SaleController;
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

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Rutas protegidas por autenticación



Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('user/profile', [AuthController::class, 'userProfile'])->name('userProfile');

// products
Route::middleware('auth:sanctum')->get('products', 'App\Http\Controllers\v1\ProductController@index')
    ->name('products.index');
Route::middleware('auth:sanctum')->get('products/{id}', 'App\Http\Controllers\v1\ProductController@show')
    ->name('products.show');
Route::middleware('auth:sanctum')->post('products', 'App\Http\Controllers\v1\ProductController@store')
    ->name('products.store');
Route::middleware('auth:sanctum')->put('products/{id}', 'App\Http\Controllers\v1\ProductController@update')
    ->name('products.update');
Route::middleware('auth:sanctum')->delete('products/{id}', 'App\Http\Controllers\v1\ProductController@destroy')
    ->name('products.destroy');

Route::middleware('auth:sanctum')->put('products/{product}/inactiveOrActivate', 'App\Http\Controllers\v1\ProductController@inactiveOrActivate')
    ->name('products.inactiveOrActivate');

//sales
Route::get('sales', 'App\Http\Controllers\v1\SaleController@index')
    ->name('sales.index');
Route::get('sales/{id}', 'App\Http\Controllers\v1\SaleController@show')
    ->name('sales.show');
Route::post('sales', 'App\Http\Controllers\v1\SaleController@store')
    ->name('sales.store');
Route::put('sales/{id}', 'App\Http\Controllers\v1\SaleController@update')
    ->name('sales.update');
Route::delete('sales/{id}', 'App\Http\Controllers\v1\SaleController@destroy')
    ->name('sales.destroy');

