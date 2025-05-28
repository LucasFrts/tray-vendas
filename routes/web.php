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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::post('/sellers', [SellerController::class, 'store'])->name('users.store');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('/dashboard')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
            Route::get('/vendedores', [AdminDashboardController::class, 'vendedores'])->name('admin.dashboard.vendedores');
            Route::get('/vendas', [AdminDashboardController::class, 'vendas'])->name('admin.dashboard.vendas');
        });
    });
    Route::prefix('/dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/vendas', [DashboardController::class, 'vendas'])->name('dashboard.vendas');
    });
});


require __DIR__.'/auth.php';
