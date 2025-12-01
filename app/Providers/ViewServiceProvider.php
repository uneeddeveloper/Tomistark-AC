<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment; // <-- Import model Payment

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kirim data ke view 'admin.layout' setiap kali view itu di-render
        View::composer('admin.layout', function ($view) {

            // Kita cek dulu apakah user sudah login dan rolenya admin
            if (Auth::check() && Auth::user()->role == 'admin') {
                $pendingVerificationCount = Payment::where('status', 'pending_verification')->count();

                // [TAMBAHAN BARU] Hitung refund yang pending
                $pendingRefundCount = Payment::where('status', 'pending_refund')
                    ->where('refund_status', 'pending')
                    ->count();

                $view->with('pendingVerificationCount', $pendingVerificationCount);
                $view->with('pendingRefundCount', $pendingRefundCount); // Kirim ke view

            } else {
                // Jika bukan admin, kirim 0
                $view->with('pendingVerificationCount', 0);
                $view->with('pendingRefundCount', 0); // Kirim 0
            }
        });
    }
}
