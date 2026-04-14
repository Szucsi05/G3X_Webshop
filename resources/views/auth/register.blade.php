<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Regisztráció - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .auth-content { padding-top: 80px; width: 100%; display:flex; align-items:center; justify-content:center; min-height: calc(100vh - 80px); }
        .register-container { display:flex; gap:40px; max-width:1000px; width:100%; padding:20px }
        .register-left { flex:1; color:#fff; display:flex; flex-direction:column; justify-content:center }
        .register-left h1{ font-size:48px }
        .register-right { flex:1; background:linear-gradient(135deg,#2c1e4a 0%,#1a1a3e 100%); padding:40px; border-radius:12px; box-shadow:0 10px 40px rgba(0,0,0,0.3) }
        .form-group{ margin-bottom:15px }
        .form-group label{ display:block; color:#00ff99; margin-bottom:8px }
        .form-group input{ width:100%; padding:12px; background:#3b2d5c; color:#fff; border:1px solid #5c4d7c; border-radius:6px }
        .btn-register{ width:100%; padding:12px; background:#00ff99; color:#000; border:none; border-radius:6px; font-weight:bold }
    </style>
</head>
<body>
    <!-- OLDALSÁV -->
    <div id="sidebar" class="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">✖</button>
        <h3>Kategóriák</h3>
        <ul>
            <li><a href="{{ route('filter.show', 'pc-games') }}" onclick="localStorage.removeItem('filterState');"><img src="{{ asset('icons/pc_category.png') }}" alt="PC játékok" style="width: 18px; height: 18px;"> PC Játékok</a></li>
            <li><a href="{{ route('filter.show', 'console-games') }}" onclick="localStorage.removeItem('filterState');"><img src="{{ asset('icons/console_category.png') }}" alt="Konzol játékok" style="width: 18px; height: 18px;"> Konzol Játékok</a></li>
            <li><a href="{{ route('filter.show', 'game-subscriptions') }}" onclick="localStorage.removeItem('filterState');"><img src="{{ asset('icons/subcriptions_category.png') }}" alt="Játék előfizetések" style="width: 18px; height: 18px;"> Játék Előfizetések</a></li>
            <li><a href="{{ route('filter.show', 'software') }}" onclick="localStorage.removeItem('filterState');"><img src="{{ asset('icons/software_category.png') }}" alt="Szoftver" style="width: 18px; height: 18px;"> Szoftver</a></li>
            <li><a href="{{ route('filter.show') }}" onclick="localStorage.removeItem('filterState');"><img src="{{ asset('icons/all_category.png') }}" alt="Összes termék" style="width: 18px; height: 18px;"> Összes termék</a></li>
        </ul>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-top">
            <a href="{{ route('home') }}" class="logo-link"><div class="animated-logo">G3X</div></a>
            <div class="search-bar"><form method="GET" action="{{ route('search') }}" style="display:flex;width:100%"><input type="text" name="q" placeholder="Keresés játékokra, ajándékkártyákra..." value="{{ request('q') }}"></form></div>
            <div class="nav-right">
                <a href="#" class="nav-btn" style="display: flex; align-items: center; gap: 8px;" onclick="toggleSidebar()"><img src="{{ asset('icons/category.png') }}" alt="Kategóriák" style="width: 18px; height: 18px;"> Kategóriák</a>
                <a href="{{ route('register') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/registration.png') }}" alt="Regisztráció" style="width: 18px; height: 18px;"> Regisztráció</a>
                <a href="{{ route('login') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/login.png') }}" alt="Bejelentkezés" style="width: 18px; height: 18px;"> Bejelentkezés</a>
                <a href="{{ route('cart.index') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/cart.png') }}" alt="Kosár" style="width: 18px; height: 18px;"> cart <span id="cart-badge" style="background:red;color:white;border-radius:50%;padding:2px 6px;font-size:12px;margin-left:5px;display:{{ session('cart') ? 'inline':'none' }}">{{ session('cart') ? array_sum(array_column(session('cart'),'quantity')) : '' }}</span></a>
            </div>
        </div>
    </nav>

    <div class="auth-content">
        <div class="register-container">
            <div class="register-left">
                <h1>Csatlakozz<br>a G3X-hez!</h1>
                <p>Regisztrálj most és fedezd fel a világ legjobb digitális termékeit.</p>
            </div>

            <div class="register-right">
                <h2>Regisztráció</h2>
                <p class="subtitle">Már van fiókod? <a href="{{ route('login') }}" style="color:#00ff99">Jelentkezz be</a></p>
                @if($errors->any()) <div class="error-message">@foreach($errors->all() as $error) {{ $error }}<br> @endforeach</div> @endif
                <form method="POST" action="{{ route('register') }}">@csrf
                    <div class="form-group"><label for="first_name">Keresztnév</label><input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="János" required></div>
                    <div class="form-group"><label for="last_name">Vezetéknév</label><input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Kovács" required></div>
                    <div class="form-group"><label for="email">E-mail</label><input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required></div>
                    <div class="form-group"><label for="password">Jelszó</label><input type="password" id="password" name="password" placeholder="••••••••" required></div>
                    <div class="form-group"><label for="password_confirmation">Jelszó megerősítése</label><input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required></div>
                    <button type="submit" class="btn-register">Regisztráció</button>
                </form>
                <div class="login-link" style="text-align:center;margin-top:12px;color:#cccccc">Már van fiókod? <a href="{{ route('login') }}" style="color:#00ff99">Jelentkezz be</a></div>
            </div>
        </div>
    </div>

    <script>function toggleSidebar(){ const sidebar=document.getElementById('sidebar'); if(sidebar) sidebar.classList.toggle('active'); }</script>
</body>
</html>