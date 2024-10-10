<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.form');

Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/signup', [RegisteredUserController::class, 'store'])->name('signup');
Route::get('/', function () {
    return view('welcome'); // Adjust this if your welcome view is located elsewhere
})->name('welcome');
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // Create a view for this
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
