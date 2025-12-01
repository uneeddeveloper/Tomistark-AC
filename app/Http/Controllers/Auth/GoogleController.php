<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- [PERBAIKAN] Tambahkan ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Exception; // <-- [PERBAIKAN] Tambahkan ini
use Illuminate\Support\Facades\Log; // <-- [PERBAIKAN] Tambahkan ini

class GoogleController extends Controller
{
    /**
     * Redirect to Google for authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    // [PERBAIKAN] Tambahkan (Request $request)
    public function handleGoogleCallback(Request $request)
    {
        try {

            // [PERBAIKAN PENTING]
            // Cek jika user SUDAH LOGIN, langsung redirect.
            // Ini memperbaiki error loop/404 yang Anda alami.
            if (Auth::check()) {
                return $this->redirectToDashboard(Auth::user());
            }

            $googleUser = Socialite::driver('google')->user();

            // [PERBAIKAN] Logika dibalik: Cari berdasarkan ID dulu, ini lebih aman.
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // User sudah ada dengan google_id, langsung login
                Auth::login($user, true);
                return $this->redirectToDashboard($user);
            }

            // Jika tidak ada google_id, baru cari berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Email ada (akun manual), kita tautkan google_id-nya
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Buat user baru jika tidak ada sama sekali
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)), // Password random
                    'role' => 'user', // Default role untuk login Google
                    'email_verified_at' => now(), // Email sudah terverifikasi oleh Google
                ]);
            }

            // Login user
            Auth::login($user, true);

            // Redirect ke dashboard berdasarkan role
            return $this->redirectToDashboard($user);
        } catch (Exception $e) { // <-- [PERBAIKAN] Tangkap semua jenis Exception

            // [PERBAIKAN] Gunakan Log::error yang sudah di-import
            Log::error('Google login error: ' . $e->getMessage());

            // Jika error terjadi (misal user refresh halaman callback)
            // Arahkan kembali ke login dengan pesan error
            return redirect('/login')->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }

    /**
     * Redirect ke dashboard berdasarkan role user
     * (Fungsi Anda sudah bagus, tidak perlu diubah)
     */
    private function redirectToDashboard($user)
    {
        // Cek jika user tidak ada (safety check)
        if (!$user) {
            return redirect('/login');
        }

        // Logika match Anda sudah benar
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            // 'technician' => redirect()->route('technician.dashboard'), // Uncomment jika Anda punya
            'user' => redirect()->route('user.dashboard'),
            default => redirect()->route('home') // Gunakan route 'home' sebagai default
        };
    }
}
