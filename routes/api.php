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

// Without authentication
Route::prefix('v1')->group(function () {
    // products
    Route::get('products', 'App\Http\Controllers\v1\ProductController@index')
        ->name('v1.products.index');
    Route::get('products/{id}', 'App\Http\Controllers\v1\ProductController@show')
        ->name('v1.products.show');
    Route::post('products', 'App\Http\Controllers\v1\ProductController@store')
        ->name('v1.products.store');
    Route::put('products/{id}', 'App\Http\Controllers\v1\ProductController@update')
        ->name('v1.products.update');
    Route::delete('products/{id}', 'App\Http\Controllers\v1\ProductController@destroy')
        ->name('v1.products.destroy');
    Route::put('products/{id}/inactiveOrActivate', 'App\Http\Controllers\v1\ProductController@inactiveOrActivate')
        ->name('v1.products.inactiveOrActivate');

    //sales
    Route::get('sales', 'App\Http\Controllers\v1\SaleController@index')
        ->name('v1.sales.index');
    Route::get('sales/{id}', 'App\Http\Controllers\v1\SaleController@show')
        ->name('v1.sales.show');
    Route::post('sales', 'App\Http\Controllers\v1\SaleController@store')
        ->name('v1.sales.store');
    Route::put('sales/{id}', 'App\Http\Controllers\v1\SaleController@update')
        ->name('v1.sales.update');
    Route::delete('sales/{id}', 'App\Http\Controllers\v1\SaleController@destroy')
        ->name('v1.sales.destroy');
});


//Authentication sanctum
Route::middleware(['auth:sanctum'])->prefix('v2')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('user/profile', [AuthController::class, 'userProfile'])->name('userProfile');
    // products
    Route::get('products', 'App\Http\Controllers\v1\ProductController@index')
        ->name('v2.products.index');
    Route::get('products/{id}', 'App\Http\Controllers\v1\ProductController@show')
        ->name('v2.products.show');
    Route::post('products', 'App\Http\Controllers\v1\ProductController@store')
        ->name('v2.products.store');
    Route::put('products/{id}', 'App\Http\Controllers\v1\ProductController@update')
        ->name('v2.products.update');
    Route::delete('products/{id}', 'App\Http\Controllers\v1\ProductController@destroy')
        ->name('v2.products.destroy');
    Route::put('products/{id}/inactiveOrActivate', 'App\Http\Controllers\v1\ProductController@inactiveOrActivate')
        ->name('v2.products.inactiveOrActivate');

    //sales
    Route::get('sales', 'App\Http\Controllers\v1\SaleController@index')
        ->name('v2.sales.index');
    Route::get('sales/{id}', 'App\Http\Controllers\v1\SaleController@show')
        ->name('v2.sales.show');
    Route::post('sales', 'App\Http\Controllers\v1\SaleController@store')
        ->name('v2.sales.store');
    Route::put('sales/{id}', 'App\Http\Controllers\v1\SaleController@update')
        ->name('v2.sales.update');
    Route::delete('sales/{id}', 'App\Http\Controllers\v1\SaleController@destroy')
        ->name('v2.sales.destroy');
    // Agrega aquí más rutas que desees proteger con el middleware auth:sanctum.
});
