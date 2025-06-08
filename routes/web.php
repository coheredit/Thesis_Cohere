<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patron\HomeController;
use App\Http\Controllers\Patron\FeedbackController;
use App\Http\Controllers\Patron\ReservationController;

Route::name('patron.')->group(function () {
    // Home page
    Route::get('/home', [HomeController::class, 'index'])->name('p_home');

    // Feedback
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Reservation form
    Route::get('/p_mreserve', [ReservationController::class, 'create'])->name('p_mreserve');
    Route::post('/p_mreserve', [ReservationController::class, 'store'])->name('p_mreserve.submit');

    // Placeholder views for other patron pages
    Route::view('/p_vreserve', 'patron.p_vreserve')->name('p_vreserve');
    Route::view('/p_payment', 'patron.p_payment')->name('p_payment');
    Route::view('/faq', 'patron.faq')->name('faq');
});
