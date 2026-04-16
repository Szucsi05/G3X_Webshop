<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @include('partials.site-sidebar')
    @include('partials.site-navbar')

    <div class="auth-content">
        <div class="register-container">
            <div class="register-left">
                <h1>Join<br>G3X!</h1>
                <p>Register now and discover some of the best digital products in the world.</p>
            </div>

            <div class="register-right">
                <h2>Register</h2>
                <p class="subtitle">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
                @if($errors->any()) <div class="error-message">@foreach($errors->all() as $error) {{ $error }}<br> @endforeach</div> @endif
                <form method="POST" action="{{ route('register') }}">@csrf
                    <div class="form-group"><label for="first_name">First Name</label><input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="John" required></div>
                    <div class="form-group"><label for="last_name">Last Name</label><input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Smith" required></div>
                    <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required></div>
                    <div class="form-group"><label for="password">Password</label><input type="password" id="password" name="password" placeholder="••••••••" required></div>
                    <div class="form-group"><label for="password_confirmation">Confirm Password</label><input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required></div>
                    <button type="submit" class="btn-register">Register</button>
                </form>
                <div class="login-link auth-footer-link">Already have an account? <a href="{{ route('login') }}">Log in</a></div>
            </div>
        </div>
    </div>

    @include('partials.site-scripts')
</body>
</html>