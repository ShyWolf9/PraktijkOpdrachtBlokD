<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LpController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuessNumberController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public LP routes - allow browsing without an account
Route::get('/lps', [LpController::class, 'index'])->name('lps.index');

Route::middleware('auth')->group(function () {
    Route::get('/switch-account', [AuthController::class, 'switcher'])->name('account.switcher');
    Route::post('/switch-account/{user}', [AuthController::class, 'switchAccount'])->name('account.switch');
});

// Guess The Number game (all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/games/guess-number', [GuessNumberController::class, 'index'])->name('games.guess-number');
    Route::post('/games/guess-number/start', [GuessNumberController::class, 'start'])->name('games.guess-number.start');
    Route::post('/games/guess-number/guess', [GuessNumberController::class, 'guess'])->name('games.guess-number.guess');
    Route::post('/games/guess-number/reset', [GuessNumberController::class, 'reset'])->name('games.guess-number.reset');
});

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


    // LP Creation/Editing - only for admin and seller (must come BEFORE {lp} routes)
    Route::middleware('role:admin,seller')->group(function () {
        Route::get('/my-lps', [LpController::class, 'myListings'])->name('lps.my-listings');
        Route::get('/lps/create', [LpController::class, 'create'])->name('lps.create');
        Route::post('/lps/create/step2', [LpController::class, 'createStep2'])->name('lps.create-step2');
        Route::post('/lps/create/step3', [LpController::class, 'createStep3'])->name('lps.create-step3');
        Route::post('/lps/create/step4', [LpController::class, 'createStep4'])->name('lps.create-step4');
        Route::post('/lps/create/step5', [LpController::class, 'createStep5'])->name('lps.create-step5');
        Route::post('/lps', [LpController::class, 'store'])->name('lps.store');
        Route::get('/lps/{lp}/edit', [LpController::class, 'edit'])->name('lps.edit');
        Route::put('/lps/{lp}', [LpController::class, 'update'])->name('lps.update');
        Route::delete('/lps/{lp}', [LpController::class, 'destroy'])->name('lps.destroy');
    });

    // Show single LP - route is public; creation/editing remains protected

    // Purchase route - users only
    Route::middleware('role:user')->group(function () {
        Route::post('/lps/{lp}/purchase', [LpController::class, 'purchase'])->name('lps.purchase');
    });
});

// Show single LP - public route must be after creation/editing routes
Route::get('/lps/{lp}', [LpController::class, 'show'])->name('lps.show');
