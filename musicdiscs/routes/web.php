<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LpController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    });
    
    // Seller Only Routes
    Route::middleware('role:seller')->group(function () {
        Route::get('/dashboard/seller', [DashboardController::class, 'seller'])->name('dashboard.seller');
    });
    
    // User Only Routes
    Route::middleware('role:user')->group(function () {
        Route::get('/dashboard/user', [DashboardController::class, 'user'])->name('dashboard.user');
    });
    
    // LP Routes - accessible to all authenticated users
    Route::get('/lps', [LpController::class, 'index'])->name('lps.index');
    Route::get('/lps/{lp}', [LpController::class, 'show'])->name('lps.show');
    
    // LP Creation/Editing - only for admin and seller
    Route::middleware('role:admin,seller')->group(function () {
        Route::get('/lps/create', [LpController::class, 'create'])->name('lps.create');
        Route::post('/lps', [LpController::class, 'store'])->name('lps.store');
        Route::get('/lps/{lp}/edit', [LpController::class, 'edit'])->name('lps.edit');
        Route::put('/lps/{lp}', [LpController::class, 'update'])->name('lps.update');
    });
    
    // LP Deletion - only for admin
    Route::middleware('role:admin')->group(function () {
        Route::delete('/lps/{lp}', [LpController::class, 'destroy'])->name('lps.destroy');
    });
});
