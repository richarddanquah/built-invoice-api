<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemStockController;
use App\Http\Controllers\InvoiceController;
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


// public routes
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

// protected routes
Route::group(['middleware' => ['auth:sanctum']],function() {
    Route::resource('customers', CustomerController::class);
    Route::resource('items', ItemController::class);
    Route::post('item_stocks', [ItemStockController::class,'store']);
    Route::resource('invoices', InvoiceController::class);
    Route::post('/logout', [AuthController::class,'logout']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
