<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Venue;
// use Illuminate\Support\Facades\Session;
// use Illuminate\Routing\Controllers\HasMiddleware;
// use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\AdminVerificationCode;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login (termasuk dari remember-me), langsung ke dashboard
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username/password wrong!',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showChangePasswordForm()
    {
        $venue = Venue::first();
        return view('admin.auth.change-password', compact('venue'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Generate 6-digit OTP
        $otp = (string) rand(100000, 999999);
        $user = Auth::user();

        // Cache OTP and new password (hashed) for 10 minutes
        $cacheKey = 'admin_password_change_' . $user->id;
        Cache::put($cacheKey, [
            'otp' => $otp,
            'new_password' => Hash::make($request->new_password),
        ], 600); // 600 seconds = 10 minutes

        // Send Email
        Mail::to($user->email)->send(new AdminVerificationCode($otp));

        return back()->with('status', 'otp_sent')->with('message', 'Verification code sent to your email.');
    }

    public function verifyPasswordChangeOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        $user = Auth::user();
        $cacheKey = 'admin_password_change_' . $user->id;
        $cachedData = Cache::get($cacheKey);

        if (!$cachedData || $cachedData['otp'] !== $request->otp) {
            return back()->with('status', 'otp_sent')->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        // Update Password
        $user->password = $cachedData['new_password'];
        $user->save();

        // Clear Cache
        Cache::forget($cacheKey);

        return redirect()->route('admin.change-password')->with('success', 'Password successfully changed!');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore(Auth::id())],
            'current_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Email successfully updated!');
    }

    public function updateHelpEmail(Request $request)
    {
        $data = $request->validate([
            'help_email' => ['nullable', 'email', 'max:255'],
        ]);

        if (isset($data['help_email'])) {
            $data['help_email'] = trim($data['help_email']);
        }

        $venue = Venue::first();
        $venue ? $venue->update($data) : Venue::create($data);

        return back()->with('success', 'Help email updated.');
    }


}
