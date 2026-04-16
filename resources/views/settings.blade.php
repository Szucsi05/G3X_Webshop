<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Settings - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="settings-page">
    @include('partials.site-sidebar')
    @include('partials.site-navbar')

    <div class="settings-shell">
        <div class="settings-container">
            <div class="settings-header">
                <h1 class="settings-title">Profile Settings</h1>
            </div>

            <div class="settings-tabs">
                <a href="{{ route('settings.show') }}" class="settings-tab active">Settings</a>
                <a href="{{ route('orders.index') }}" class="settings-tab">My Orders</a>
            </div>

            @if(session('success'))
                <div class="settings-flash success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="settings-flash error">
                    @foreach($errors->all() as $error)
                        <p class="settings-error">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}" class="settings-form">
                @csrf
                @method('PUT')

                <div class="settings-section">
                    <h2 class="settings-section-title">Personal Information</h2>

                    <div class="settings-field">
                        <label for="name" class="settings-label">Full Name</label>
                        <input id="name" type="text" name="name" class="settings-input" value="{{ old('name', auth()->user()->name) }}" placeholder="Example: John Smith">
                    </div>

                    <div class="settings-field">
                        <label for="email" class="settings-label">Email Address</label>
                        <input id="email" type="email" name="email" class="settings-input" value="{{ old('email', auth()->user()->email) }}" placeholder="example@email.com">
                    </div>
                </div>

                <div class="settings-section">
                    <h2 class="settings-section-title">Change Password</h2>

                    <div class="settings-field">
                        <label for="password" class="settings-label">New Password</label>
                        <input id="password" type="password" name="password" class="settings-input" placeholder="Leave empty if you do not want to change it">
                    </div>

                    <div class="settings-field">
                        <label for="password_confirmation" class="settings-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="settings-input" placeholder="Repeat the new password">
                    </div>
                </div>

                <button type="submit" class="settings-submit">Save Settings</button>
            </form>

            <a href="{{ route('home') }}" class="settings-back-link settings-back-link--bottom"><img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-18"> Back to Home</a>
        </div>
    </div>

    @include('partials.site-footer', ['footerVariant' => 'legal'])
    @include('partials.site-scripts')
</body>
</html>
