<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // added for logging

class AuthController extends Controller
{
      public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Option A — quick dump and stop (temporary):
        dd(Auth::attempt($credentials));

        // Option B — log result and continue (less intrusive):
        $attempt = Auth::attempt($credentials);
        Log::info('Auth attempt', [
            'email' => $request->input('email'),
            'attempt' => $attempt ? 'success' : 'failed',
        ]);

        if ($attempt) {
            $request->session()->regenerate();
            return redirect()->intended('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

}
