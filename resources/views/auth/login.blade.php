<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In - G3X</title>
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
        <div class="login-container">
            <div class="login-left">
                <h1>Welcome back. <br> Good to see you again.</h1>
            </div>

            <div class="login-right">
                <h2>Log In</h2>
                <p class="subtitle">New here? <a href="{{ route('register') }}">Create an account</a></p>

                @if($errors->any())
                    <div class="error-message">@foreach($errors->all() as $error) {{ $error }}<br> @endforeach</div>
                @endif

                @if(session('success'))
                    <div class="success-message">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">@csrf
                    <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required></div>
                    <div class="form-group"><label for="password">Password</label><input type="password" id="password" name="password" placeholder="••••••••" required></div>
                    <button type="submit" class="btn-login">Log In</button>
                </form>

                <div class="register-link auth-footer-link">Do not have an account? <a href="{{ route('register') }}">Register now</a></div>
            </div>
        </div>
    </div>

    @include('partials.site-scripts')
</body>
</html>
