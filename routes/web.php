<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SellerAuthController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware('auth.handle-redirects')->name('home');
Route::post('/sellers', [SellerController::class, 'store'])->name('sellers.store');


Route::prefix('admin')->group(function () {
    Route::middleware('auth:web')->group(function () {
        Route::prefix('/dashboard')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
            Route::get('/vendedores', [AdminDashboardController::class, 'vendedores'])->name('admin.dashboard.vendedores');
            Route::get('/vendas', [AdminDashboardController::class, 'vendas'])->name('admin.dashboard.vendas');
        });
        Route::get('/api-key', [AdminDashboardController::class, 'getApiKey']);
        Route::post('/api-key', [AdminDashboardController::class, 'generateApiKey']);
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
    });
    Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::prefix('/dashboard')->middleware('auth:seller')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth.multi:web,seller')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
    Route::get('/sellers', [SellerController::class, 'index']);
});

Route::post('/sellers/{seller_id}/daily-report', [ReportController::class, 'dailyReport'])
    ->middleware('auth:web');


Route::get('/login', [SellerAuthController::class, 'loginView']);
Route::post('/login', [SellerAuthController::class, 'login']);
Route::any('/logout', [AuthController::class, 'logout']);
