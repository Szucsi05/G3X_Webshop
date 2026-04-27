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
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <div id="password-requirements" style="font-size: 0.9em; margin-top: 8px; display: none;">
                            <div id="req-length" style="color: red;">✗ Minimum 8 characters</div>
                            <div id="req-uppercase" style="color: red;">✗ At least 1 uppercase letter (A-Z)</div>
                            <div id="req-number" style="color: red;">✗ At least 1 number (0-9)</div>
                            <div id="req-special" style="color: red;">✗ At least 1 special character (!@#$%^&*)</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                        <div id="password-match" style="font-size: 0.9em; margin-top: 8px; color: red; display: none;">✗ Passwords do not match</div>
                    </div>
                    <button type="submit" class="btn-register" id="register-btn" disabled>Register</button>
                </form>
                <div class="login-link auth-footer-link">Already have an account? <a href="{{ route('login') }}">Log in</a></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const registerBtn = document.getElementById('register-btn');
            const requirementsDiv = document.getElementById('password-requirements');
            const matchDiv = document.getElementById('password-match');

            function validatePassword() {
                const password = passwordInput.value;
                const confirm = confirmInput.value;
                
                // Show requirements if password has any value
                if (password.length > 0) {
                    requirementsDiv.style.display = 'block';
                } else {
                    requirementsDiv.style.display = 'none';
                }

                // Check each requirement
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[!@#$%^&*()\-_=+\[\]{};:'"<>,.?\\\|`~]/.test(password);
                
                // Update requirement indicators
                updateRequirement('req-length', hasLength);
                updateRequirement('req-uppercase', hasUppercase);
                updateRequirement('req-number', hasNumber);
                updateRequirement('req-special', hasSpecial);

                // Check password match
                const passwordsMatch = password === confirm && password.length > 0 && confirm.length > 0;
                
                if (password.length > 0 && confirm.length > 0) {
                    if (passwordsMatch) {
                        matchDiv.style.display = 'none';
                    } else {
                        matchDiv.style.display = 'block';
                    }
                } else {
                    matchDiv.style.display = 'none';
                }

                // Enable button only if all requirements met and passwords match
                const allValid = hasLength && hasUppercase && hasNumber && hasSpecial && passwordsMatch;
                registerBtn.disabled = !allValid;
            }

            function updateRequirement(id, met) {
                const element = document.getElementById(id);
                if (met) {
                    element.style.color = 'green';
                    element.textContent = element.textContent.replace('✗', '✓');
                    if (!element.textContent.includes('✓')) {
                        element.innerHTML = element.innerHTML.replace('✗', '✓');
                    }
                } else {
                    element.style.color = 'red';
                    element.innerHTML = element.innerHTML.replace('✓', '✗');
                    if (!element.textContent.includes('✗')) {
                        element.textContent = element.textContent.replace('✓', '✗');
                    }
                }
            }

            passwordInput.addEventListener('input', validatePassword);
            confirmInput.addEventListener('input', validatePassword);
        });
    </script>

    @include('partials.site-scripts')
</body>
</html>
