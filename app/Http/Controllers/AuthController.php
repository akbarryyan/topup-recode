<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $remember = $request->has('remember');

        // Simulasi delay untuk loading animation (2 detik)
        sleep(2);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Check if user is admin
            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Login berhasil! Selamat datang ' . Auth::user()->name);
            } else {
                Auth::logout();
                return redirect()->route('admin.login')
                    ->with('error', 'Anda tidak memiliki akses ke halaman admin');
            }
        }

        return redirect()->back()
            ->with('error', 'Email atau password salah')
            ->withInput();
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil logout');
    }
}
