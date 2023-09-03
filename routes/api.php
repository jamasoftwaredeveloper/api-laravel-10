<?php

use App\Http\Controllers\AuthController;
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


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('user/profile', [AuthController::class, 'userProfile'])->name('userProfile');
    Route::get('products', 'App\Http\Controllers\v1\ProductController@index')
        ->name('products.index');
    Route::get('products/{id}', 'App\Http\Controllers\v1\ProductController@show')
        ->name('products.show');
    Route::post('products', 'App\Http\Controllers\v1\ProductController@store')
        ->name('products.store');
    Route::put('products/{id}', 'App\Http\Controllers\v1\ProductController@update')
        ->name('products.update');
    Route::delete('products/{id}', 'App\Http\Controllers\v1\ProductController@destroy')
        ->name('products.destroy');
    Route::resource('sales', SaleController::class);

    Route::put('products/{product}/inactiveOrActivate', 'App\Http\Controllers\v1\ProductController@inactiveOrActivate')
        ->name('products.inactiveOrActivate');
});
