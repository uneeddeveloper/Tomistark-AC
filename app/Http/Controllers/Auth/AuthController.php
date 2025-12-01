<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    // --- REGISTRASI ---

    /**
     * Menampilkan form registrasi.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses data dari form registrasi.
     */
    public function register(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Buat user baru - default role: user
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', // default
        ]);

        // Login-kan user
        Auth::login($user);

        // Arahkan ke dashboard berdasarkan role
        return $this->redirectToDashboard($user, $request);
    }

    // --- LOGIN ---

    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses data dari form login.
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Email atau Username
        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field     => $loginInput,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            \Log::info('LOGIN SUCCESS', [
                'user_id'   => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
            ]);

            // Redirect sesuai role (hormati intended URL)
            return $this->redirectToDashboard($user, $request);
        }

        // Gagal
        return back()->withErrors([
            'login' => 'Kombinasi email/username dan password tidak cocok.',
        ])->onlyInput('login');
    }

    /**
     * Redirect ke dashboard berdasarkan role user
     */
    private function redirectToDashboard($user, Request $request)
    {
        \Log::info('REDIRECT TO DASHBOARD', [
            'user_id'   => $user->id,
            'user_role' => $user->role,
            'user_email' => $user->email,
        ]);

        // Tentukan target berdasarkan role
        $target = match ($user->role) {
            'technician'              => route('technician.dashboard'),
            'admin', 'superadmin'     => route('admin.dashboard'),
            default                   => route('user.dashboard'),
        };

        // Pakai intended agar kalau user akses halaman terlindungi,
        // setelah login diarahkan kembali ke sana.
        return redirect()->intended($target);
    }

    // --- LOGOUT ---

    /**
     * Memproses logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
