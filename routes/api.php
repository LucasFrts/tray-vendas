<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::Apiresource('users', UserController::class)->except(['store']);
    Route::Apiresource('orders', OrderController::class);
});

Route::apiResource('sellers', SellerController::class)
    ->middleware(['auth:sanctum'])
    ->except(['store', 'update', 'destroy']);

Route::post('sellers',      [SellerController::class, 'store']);

Route::put('sellers/{seller}',  [SellerController::class, 'update'])
    ->middleware(['auth:sanctum', 'can:update,seller']);
Route::delete('sellers/{seller}', [SellerController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'can:delete,seller']);
