<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.form');

Route::post('/signup', [AuthController::class, 'signup'])->name('signup');

Route::get('/', function () {
    return view('welcome');
});
