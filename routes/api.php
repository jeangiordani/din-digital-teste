<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/user', [AuthController::class, 'user'])->name('user');
});

Route::post('/user', [UserController::class, 'create'])->name('user.create');

Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware('jwt.auth');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show')->middleware('jwt.auth');
Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware('jwt.auth');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update')->middleware('jwt.auth');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('jwt.auth');
