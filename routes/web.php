<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('home');
});

// Admin route group
Route::prefix('admin')->name('admin.')->group(function () {
    // GET: Show login form
    Route::get('/login', function () {
        return view('admin.login');  // corresponds to login.blade.php
    })->name('login');

    // GET: Admin dashboard
    Route::get('/home', function () {
        return view('admin.a_home');  // corresponds to a_home.blade.php
    })->name('home');

    // GET: Inquiries page
    Route::get('/inquiry', function () {
        return view('admin.a_inquiry');  // corresponds to a_inquiry.blade.php
    })->name('inquiry');

    // GET: Reports page
    Route::get('/report', function () {
        return view('admin.a_report');  // Not uploaded, assumed present
    })->name('report');

    // GET: Show signup form
    Route::get('/signup', function () {
        return view('admin.signup');  // corresponds to signup.blade.php
    })->name('signup.form');

    // GET: Show make reservation form
    Route::get('/reserve', function () {
        return view('admin.a_reserve');  // corresponds to a_reserve.blade.php
    })->name('reserve.form');

    Route::get('/profile', function () {
    return view('admin.a_profile');
    })->name('profile'); // ✅


    // POST: Handle signup submission
    Route::post('/signup', function (Request $request) {
        // Handle signup logic here
    })->name('signup.submit');

    // POST: Handle reservation submission
    Route::post('/reserve', function (Request $request) {
        // Handle reservation logic here
    })->name('reserve');

     // POST: Handle login submission
    Route::post('/login', function (Request $request) {
        // Handle login logic here
    })->name('login.submit');
});


// Patron route group
Route::prefix('patron')->name('patron.')->group(function () {
    // GET: Patron home
    Route::get('/home', function () {
        return view('patron.p_home'); // corresponds to p_home.blade.php
    })->name('home');

    // GET: FAQ page
    Route::get('/faq', function () {
        return view('patron.faq'); // corresponds to faq.blade.php
    })->name('faq');

    // GET: Show feedback form
    Route::get('/feedback', function () {
        return view('patron.feedback'); // corresponds to feedback.blade.php
    })->name('feedback.form');

    // GET: Show manual reservation form
    Route::get('/mreserve', function () {
        return view('patron.p_mreserve'); // corresponds to p_mreserve.blade.php
    })->name('reserve.form');

    // GET: Payment form (client-side only)
    Route::get('/payment', function () {
        return view('patron.p_payment'); // corresponds to p_payment.blade.php
    })->name('payment');

    // GET: View reservation form
    Route::get('/vreserve', function () {
        return view('patron.p_vreserve'); // corresponds to p_vreserve.blade.php
    })->name('view.form');

    // POST: Process view reservation
    Route::post('/vreserve', function (Request $request) {
        // Handle view reservation logic here
    })->name('view.submit');

    // POST: Handle feedback submission
    Route::post('/feedback', function (Request $request) {
        // Handle feedback logic here
    })->name('feedback.submit');

    // POST: Handle manual reservation submission
    Route::post('/mreserve', function (Request $request) {
        // Handle reservation logic here
    })->name('reserve.submit');
});
