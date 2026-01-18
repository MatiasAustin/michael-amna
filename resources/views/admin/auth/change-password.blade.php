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
                                <input type="password" name="current_password" id="current_password_email" class="form-control" required>
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
                                <input type="password" name="current_password" id="current_password" class="form-control">
                                @error('current_password')
                                    <p style="color: var(--error-text); font-size: 12px;">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div style="margin-bottom: 15px;">
                                <label for="new_password" style="display:block; margin-bottom:5px;">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control">
                                @error('new_password')
                                    <p style="color: var(--error-text); font-size: 12px;">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div style="margin-bottom: 20px;">
                                <label for="new_password_confirmation" style="display:block; margin-bottom:5px;">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
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
</body>
</html>
