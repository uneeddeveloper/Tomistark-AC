<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;

// Jika controller teknisi kamu ada di root Controller:
// use App\Http\Controllers\TechnicianDashboardController;
// (pakai import di atas)
use App\Http\Controllers\TechnicianDashboardController;

/*
|--------------------------------------------------------------------------
| Halaman Depan (Guest)
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('welcome');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Setelah Login (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Redirect universal ke dashboard berdasar role
    Route::get('/home', function () {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($role === 'technician') {
            return redirect()->route('technician.dashboard');
        }
        // default user
        return redirect()->route('user.dashboard');
    })->name('home');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    | Catatan: kita gunakan alias 'admin' (wrapper) sesuai bootstrap/app.php
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            // Dashboard
            Route::get('/dashboard', 'dashboard')->name('dashboard');

            // Users
            Route::get('/users', 'users')->name('users.index');
            Route::get('/users/create', 'createUser')->name('users.create');
            Route::post('/users', 'storeUser')->name('users.store');
            Route::get('/users/{user}/edit', 'editUser')->name('users.edit');
            Route::put('/users/{user}', 'updateUser')->name('users.update');
            Route::delete('/users/{user}', 'deleteUser')->name('users.delete');
            Route::get('/users/{user}', 'showUser')->name('users.show');

            // Bookings
            Route::get('/bookings', 'bookings')->name('bookings.index');
            Route::get('/bookings/{id}', 'showBooking')->name('bookings.show');
            Route::post('/bookings/{id}/status', 'updateBookingStatus')->name('bookings.updateStatus');
            Route::post('/bookings/{id}/approve', 'approvePendingBooking')->name('bookings.approve');
            Route::post('/bookings/{id}/reject', 'rejectPendingBooking')->name('bookings.reject');

            // Services
            Route::get('/services', 'services')->name('services.index');
            Route::get('/services/create', 'createService')->name('services.create');
            Route::post('/services', 'storeService')->name('services.store');
            Route::get('/services/{service}/edit', 'editService')->name('services.edit');
            Route::put('/services/{service}', 'updateService')->name('services.update');
            Route::delete('/services/{service}', 'deleteService')->name('services.delete');

            // Payments
            Route::get('/payments', 'payments')->name('payments.index');
            Route::get('/payments/verification', 'paymentVerification')->name('payments.verification');
            Route::post('/payments/{payment}/verify', 'verifyPayment')->name('payments.verify');

            // Refunds
            Route::get('/refunds', 'refundRequests')->name('refunds.index');
            Route::post('/refunds/{payment}/approve', 'approveRefund')->name('refunds.approve');
            Route::post('/refunds/{payment}/reject', 'rejectRefund')->name('refunds.reject');

            // Reports
            Route::get('/reports', 'reports')->name('reports.index');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | USER
    | Tetap pakai 'role:user' (tidak kita buat alias wrapper 'user')
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->name('user.')->middleware(['role:user'])->group(function () {
        Route::controller(UserController::class)->group(function () {
            // Dashboard
            Route::get('/dashboard', 'dashboard')->name('dashboard');

            // Profile
            Route::get('/profile', 'profile')->name('profile');
            Route::put('/profile', 'updateProfile')->name('profile.update');

            // Bookings
            Route::get('/bookings', 'bookings')->name('bookings');
            Route::get('/bookings/create', 'createBooking')->name('bookings.create');
            Route::post('/bookings', 'storeBooking')->name('bookings.store');
            Route::get('/bookings/{id}', 'showBooking')->name('bookings.show');
            Route::post('/bookings/{id}/cancel', 'cancelBooking')->name('bookings.cancel');
            Route::post('/bookings/{id}/rate', 'submitRating')->name('bookings.rate');

            // Payments
            Route::get('/payments', 'payments')->name('payments');
            Route::get('/payments/{id}', 'showPayment')->name('payments.show');

            // Request Refund
            Route::post('/payments/{payment}/request-refund', 'requestRefund')->name('payments.requestRefund');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | TECHNICIAN
    | Penting: SEKARANG diproteksi middleware(['auth','technician'])
    |--------------------------------------------------------------------------
    */
    Route::prefix('technician')->name('technician.')->middleware(['technician'])->group(function () {
        Route::get('/dashboard', [TechnicianDashboardController::class, 'index'])->name('dashboard');

        // COD Management
        Route::get('/cod', [TechnicianDashboardController::class, 'cod'])->name('cod.index');
        Route::post('/cod/{payment}/collect', [TechnicianDashboardController::class, 'collectCod'])->name('cod.collect');

        // (opsional) daftar pekerjaan teknisi, update status pengerjaan, dll.
        // Route::get('/jobs', [...])->name('jobs.index');
        Route::post('/jobs/{booking}/status', [\App\Http\Controllers\TechnicianDashboardController::class, 'updateStatus'])->name('jobs.updateStatus');
    });
});

/*
|--------------------------------------------------------------------------
| Fallback 404
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
