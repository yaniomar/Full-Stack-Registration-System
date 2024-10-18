<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

// Show signup form
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.form');

// Handle signup form submission
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');

// Verification notice route
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');

// Email verification handling
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed']); // Ensure the link is signed for security

// Resend verification notification
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->name('verification.resend');

// Protect routes with verified middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [App\Http\Controllers\User\HomeController::class, 'index'])->name('user.home');
});
