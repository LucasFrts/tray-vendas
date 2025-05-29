<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->middleware('auth.handle-redirects')->name('home');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::post('/sellers', [SellerController::class, 'store'])->name('sellers.store');


Route::prefix('admin')->middleware('auth:web')->group(function () {
    Route::prefix('/dashboard')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/vendedores', [AdminDashboardController::class, 'vendedores'])->name('admin.dashboard.vendedores');
        Route::get('/vendas', [AdminDashboardController::class, 'vendas'])->name('admin.dashboard.vendas');
    });
});
Route::prefix('/dashboard')->middleware('auth:seller')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/vendas', [DashboardController::class, 'vendas'])->name('dashboard.vendas');
});

require __DIR__ . '/auth.php';
