<?php

// Patron Controller Related
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patron\HomeController;
use App\Http\Controllers\Patron\FeedbackController;
use App\Http\Controllers\Patron\PaymentController;

// Unified
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Patron\CalendarController;

// Admin Controller Related
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\AdminActivityController;

// Patron Routes
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

// Admin Authentication Routes (NO MIDDLEWARE - accessible to non-authenticated users)
Route::prefix('admin')->name('admin.')->group(function () {
    // Signup
    Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');
    
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Protected Admin Routes (WITH MIDDLEWARE - requires authentication)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Admin Homepage
    Route::get('/home', [AdminHomeController::class, 'index'])->name('home');

    // Packages on Admin homepage
    Route::get('/packages', [PackageController::class, 'index']);
    Route::post('/packages', [PackageController::class, 'store']);
    Route::put('/packages/{package}', [PackageController::class, 'update']);
    Route::delete('/admin/packages/{package}', [PackageController::class, 'destroy'])->name('admin.packages.destroy');
    Route::post('/admin/packages', [PackageController::class, 'store'])->name('admin.packages.store');

    // Inquiry
    Route::get('/inquiry', [InquiryController::class, 'index'])->name('inquiry');
    Route::post('/inquiries/{id}/update-status', [InquiryController::class, 'updateStatusAjax']);
    Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');


    // Availability for the calendar on admin making inquiry/reservation
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [AvailabilityController::class, 'toggle'])->name('availability.toggle');

    // Admin creates reservation (inquiry form)
    Route::get('/reserve', [ReservationController::class, 'create'])->name('reserve.create');
    Route::post('/reserve', [ReservationController::class, 'store'])->name('reserve.store');

    // Activity Log for Admin Profile
    Route::get('/activities', [AdminActivityController::class, 'index'])->name('activities');

    Route::view('/report', 'admin.a_report')->name('report');
    Route::view('/profile', 'admin.a_profile')->name('profile');

    Route::post('/send-reply', [ReservationController::class, 'sendReply']);
});

// Logout Route (accessible to authenticated users)
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// For Calendar Routes 
Route::get('/calendar/availability', [CalendarController::class, 'getAvailability']);