<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Ava & Mateo — Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  <link rel="icon" href="{{ asset('media/anm-logo.png') }}" type="image/png">

    <style>
    * {
        text-transform: none !important;
    }
    </style>

</head>
<body style="background-color: #7E2625; color: #F3ECDC;">
    <div class="header" style="padding: 20px; text-align: center;">

          <div class="brand">
            <img src="{{ asset('media/anm-logo.png') }}" alt="">
          </div>

    </div>

    <form action="{{ route('login.submit') }}" method="POST" style="display: flex; flex-direction: column; gap: 10px; min-width: 320px; max-width: 320px; width: 320px;">
    @csrf

        <input
            type="email"
            name="email"
            placeholder="Email"
            value="{{ old('email') }}"
            required
            style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;"
        >

        <div style="position: relative; display: flex; align-items: center;">
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Password"
                required
                style="padding: 10px; border: 1px solid #ccc; border-radius: 4px; width: 100%; padding-right: 82px;"
            >
            <button
                type="button"
                id="togglePassword"
                style="position: absolute; right: 8px; background: transparent; border: 1px solid #7E2625; color: #7E2625; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;"
            >
                Show
            </button>
        </div>

        <label style="font-size: 13px; display: flex; align-items: center; gap: 6px; margin-top: 4px; width:100%; justify-content: flex-start;">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} style="width: auto;"> Remember Me
        </label>

        @if ($errors->any())
            <div style="color: red; font-size: 12px;">
                {{ $errors->first() }}
            </div>
        @endif

        <button
            type="submit"
            style="background-color: #F3ECDC; color: #7E2625; padding: 10px; border: none; border-radius: 4px; cursor: pointer;"
        >
            Login
        </button>
    </form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            togglePassword.textContent = isHidden ? 'Hide' : 'Show';
        });
    }
});
</script>

</body>
</html>
