<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patron\HomeController;
use App\Http\Controllers\Patron\FeedbackController;
use App\Http\Controllers\Patron\ReservationController;
use App\Http\Controllers\Patron\PaymentController;

use App\Http\Controllers\Admin\AuthController;

Route::name('patron.')->group(function () {
    // Home page
    Route::get('/home', [HomeController::class, 'index'])->name('p_home');

    // Feedback
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Reservation form
    Route::get('/p_mreserve', [ReservationController::class, 'create'])->name('p_mreserve');
    Route::post('/p_mreserve', [ReservationController::class, 'store'])->name('p_mreserve.submit');

    // Payment (proof of receipt upload)
    Route::get('/p_payment', [PaymentController::class, 'index'])->name('p_payment');
    Route::post('/p_payment', [PaymentController::class, 'store'])->name('payment.store');

    // Placeholder views for other patron pages
    Route::view('/p_vreserve', 'patron.p_vreserve')->name('p_vreserve');
    Route::view('/faq', 'patron.faq')->name('faq');
    Route::view('/guidelines', 'patron.guidelines')->name('guidelines');
});



Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});