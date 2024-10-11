<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.form');

Route::post('/signup', [AuthController::class, 'signup'])->name('signup');

Route::get('/', function () {
    return view('welcome');
});
Auth::routes([
    'verify'=>true
]);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/send-test-email', function () {
    Mail::raw('This is a test email.', function ($message) {
        $message->to('oabdelfattah943@gmail.com')
                ->subject('Test Email');
    });
    return 'Email sent successfully';
});