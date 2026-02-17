<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LpController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('lps', \App\Http\Controllers\LpController::class);
