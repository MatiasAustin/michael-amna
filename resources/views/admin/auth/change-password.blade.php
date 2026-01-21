@extends('admin.layout.structure')
@include('admin.layout.header')

<div class="admin-dashboard-content">
    <h1>Admin Panel</h1>
    <p>Michael & Amna</p>

    <div class="admin-dashboard-main">
        @include('admin.layout.sidebar')

        <div class="admin-dashboard-panel">
            <h3>Account Settings</h3>
            
            <div style="margin-bottom: 30px;">
                <p style="font-size: 14px; opacity: 0.8;">Current Email</p>
                <p style="font-size: 18px; font-weight: 500;">{{ Auth::user()->email }}</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('status') == 'otp_sent')
                <div class="card" style="max-width: 400px;">
                    <h3>Verify OTP</h3>
                    <p style="font-size:13px; margin-bottom:15px;">A verification code has been sent to your email.</p>

                    <form action="{{ route('admin.change-password.verify') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label for="otp" style="display:block; margin-bottom:5px;">Verification Code (OTP)</label>
                            <input type="text" name="otp" id="otp" class="form-control" autocomplete="off" placeholder="123456">
                            @error('otp')
                                <p style="color: var(--error-text); font-size: 12px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary">
                                Verify & Change Password
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 40px;">
                    
                    {{-- CHANGE EMAIL FORM --}}
                    <div class="card" style="max-width: 400px;">
                        <h4 style="margin-bottom: 20px;">Change Email</h4>
                        <form action="{{ route('admin.change-email.update') }}" method="POST">
                            @csrf
                            @method('PUT')
    
                            <div style="margin-bottom: 15px;">
                                <label for="email" style="display:block; margin-bottom:5px;">New Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                                @error('email')
                                    <p style="color: var(--error-text); font-size: 12px;">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div style="margin-bottom: 20px;">
                                <label for="current_password_email" style="display:block; margin-bottom:5px;">Current Password</label>
                                <div class="password-wrapper" style="position: relative;">
                                    <input type="password" name="current_password" id="current_password_email" class="form-control" required style="padding-right: 40px; margin: 0;">
                                    <button type="button" class="btn-toggle-password" onclick="togglePassword('current_password_email', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; opacity: 1; color: #555; padding: 0;">
                                        <!-- Eye Icon (Show) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1-12S-5-5-1 7c0 0 4 7 13 7s13-7 13-7-4-7-13-7-13 7-13 7z" transform="translate(0, 12)"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p style="color: var(--error-text); font-size: 12px;">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    Update Email
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- HELP EMAIL SETTINGS --}}
                    <div class="card" style="max-width: 400px;">
                        <h4 style="margin-bottom: 20px;">Help Email Settings</h4>
                        <form action="{{ route('admin.settings.help-email') }}" method="POST">
                            @csrf
                            @method('PUT')
    
                            <div style="margin-bottom: 20px;">
                                <label for="help_email" style="display:block; margin-bottom:5px;">Help Email Address</label>
                                <input type="email" name="help_email" id="help_email" 
                                    class="form-control" 
                                    placeholder="e.g. support@domain.com"
                                    value="{{ old('help_email', $venue->help_email ?? '') }}">
                                @error('help_email')
                                    <p style="color: var(--error-text); font-size: 12px;">{{ $message }}</p>
                                @enderror
                                <p style="font-size:12px; opacity:0.7; margin-top:5px;">
                                    This email will be used for the "Report a problem" button on the RSVP page.
                                </p>
                            </div>
    
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    Update Help Email
                                </button>
                            </div>
                        </form>
                    </div>
    
                    {{-- CHANGE PASSWORD FORM --}}
                    <div class="card" style="max-width: 400px;">
                        <h4 style="margin-bottom: 20px;">Change Password</h4>
                        <form action="{{ route('admin.change-password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
    
                            <div style="margin-bottom: 15px;">
                                <label for="current_password" style="display:block; margin-bottom:5px;">Current Password</label>
                                <div class="password-wrapper" style="position: relative;">
                                    <input type="password" name="current_password" id="current_password" class="form-control" style="padding-right: 40px; margin: 0;">
                                    <button type="button" class="btn-toggle-password" onclick="togglePassword('current_password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; opacity: 1; color: #555; padding: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p style="color: var(--error-text); font-size: 12px;">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div style="margin-bottom: 15px;">
                                <label for="new_password" style="display:block; margin-bottom:5px;">New Password</label>
                                <div class="password-wrapper" style="position: relative;">
                                    <input type="password" name="new_password" id="new_password" class="form-control" style="padding-right: 40px; margin: 0;">
                                    <button type="button" class="btn-toggle-password" onclick="togglePassword('new_password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; opacity: 1; color: #555; padding: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </div>
                                @error('new_password')
                                    <p style="color: var(--error-text); font-size: 12px;">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div style="margin-bottom: 20px;">
                                <label for="new_password_confirmation" style="display:block; margin-bottom:5px;">Confirm New Password</label>
                                <div class="password-wrapper" style="position: relative;">
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" style="padding-right: 40px; margin: 0;">
                                    <button type="button" class="btn-toggle-password" onclick="togglePassword('new_password_confirmation', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer; opacity: 1; color: #555; padding: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </div>
                            </div>
    
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@include('admin.layout.footer')

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const iconInfo = {
            show: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
            hide: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>'
        };

        if (input.type === "password") {
            input.type = "text";
            btn.innerHTML = iconInfo.hide;
            // Removed opacity toggle to keep visibility consistent
            // btn.style.opacity = "1"; 
        } else {
            input.type = "password";
            btn.innerHTML = iconInfo.show;
            // btn.style.opacity = "0.6";
        }
    }
</script>
</body>
</html>
