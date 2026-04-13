<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Local styles for auth page to match screenshot */
        .auth-content { padding-top: 80px; width: 100%; display:flex; align-items:center; justify-content:center; min-height: calc(100vh - 80px); }
        .login-container { display:flex; gap:40px; max-width:1000px; width:100%; padding:20px; }
        .login-left { flex:1; color:#fff; display:flex; flex-direction:column; justify-content:center }
        .login-left h1 { font-size:48px; margin:0 0 20px 0; }
        .login-left p { color:#cccccc }
        .login-right { flex:1; background: linear-gradient(135deg,#2c1e4a 0%,#1a1a3e 100%); padding:40px; border-radius:12px; box-shadow:0 10px 40px rgba(0,0,0,0.3); }
        .login-right h2 { color:#00ff99; margin:0 0 10px 0; font-size:28px }
        .subtitle{ color:#cccccc; margin-bottom:30px }
        .form-group{ margin-bottom:15px }
        .form-group label{ display:block; color:#00ff99; margin-bottom:8px; font-weight:bold; font-size:13px }
        .form-group input{ width:100%; padding:12px; background:#3b2d5c; color:#fff; border:1px solid #5c4d7c; border-radius:6px }
        .btn-login{ width:100%; padding:12px; background:#00ff99; color:#000; border:none; border-radius:6px; font-weight:bold; font-size:16px }
        .error-message{ color:#ff4444; background:rgba(255,68,68,0.1); padding:12px; border-radius:6px; border-left:3px solid #ff4444 }
    </style>
</head>
<body>
    <!-- OLDALSÁV -->
    <div id="sidebar" class="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">✖</button>
        <h3>Kategóriák</h3>
        <ul>
            <li><a href="{{ route('filter.show', 'pc-games') }}" onclick="localStorage.removeItem('filterState');">🖥️ PC Játékok</a></li>
            <li><a href="{{ route('filter.show', 'console-games') }}" onclick="localStorage.removeItem('filterState');">🎮 Konzol Játékok</a></li>
            <li><a href="{{ route('filter.show', 'game-subscriptions') }}" onclick="localStorage.removeItem('filterState');">🎯 Játék Előfizetések</a></li>
            <li><a href="{{ route('filter.show', 'software') }}" onclick="localStorage.removeItem('filterState');">💻 Szoftver</a></li>
            <li><a href="{{ route('filter.show') }}" onclick="localStorage.removeItem('filterState');">✨ Összes termék</a></li>
        </ul>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-top">
            <a href="{{ route('home') }}" class="logo-link"><div class="animated-logo">G3X</div></a>
            <div class="search-bar"><form method="GET" action="{{ route('search') }}" style="display:flex;width:100%"><input type="text" name="q" placeholder="Keresés játékokra, ajándékkártyákra..." value="{{ request('q') }}"></form></div>
            <div class="nav-right">
                <a href="#" class="nav-btn" onclick="toggleSidebar()">Kategóriák</a>
                <a href="{{ route('register') }}" class="nav-btn">Regisztráció</a>
                <a href="{{ route('login') }}" class="nav-btn">Bejelentkezés</a>
                <a href="{{ route('cart.index') }}" class="nav-btn">🛒 Kosár <span id="cart-badge" style="background:red;color:white;border-radius:50%;padding:2px 6px;font-size:12px;margin-left:5px;display:{{ session('cart') ? 'inline':'none' }}">{{ session('cart') ? array_sum(array_column(session('cart'),'quantity')) : '' }}</span></a>
            </div>
        </div>
    </nav>

    <div class="auth-content">
        <div class="login-container">
            <div class="login-left">
                <h1>Üdv! <br> Jó újra látni.</h1>
            </div>

            <div class="login-right">
                <h2>Bejelentkezés</h2>
                <p class="subtitle">Új vagy? <a href="{{ route('register') }}" style="color:#00ff99">Hozz létre fiókot</a></p>

                @if($errors->any())
                    <div class="error-message">@foreach($errors->all() as $error) {{ $error }}<br> @endforeach</div>
                @endif

                @if(session('success'))
                    <div style="color:#00ff99;background:rgba(0,255,153,0.1);padding:12px;border-radius:6px;border-left:3px solid #00ff99;margin-bottom:15px">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">@csrf
                    <div class="form-group"><label for="email">E-mail</label><input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required></div>
                    <div class="form-group"><label for="password">Jelszó</label><input type="password" id="password" name="password" placeholder="••••••••" required></div>
                    <button type="submit" class="btn-login">Bejelentkezés</button>
                </form>

                <div class="register-link" style="text-align:center;margin-top:12px;color:#cccccc">Nincs fiókod? <a href="{{ route('register') }}" style="color:#00ff99">Regisztrálj most</a></div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar(){ const sidebar = document.getElementById('sidebar'); if(sidebar) sidebar.classList.toggle('active'); }
    </script>
</body>
</html>