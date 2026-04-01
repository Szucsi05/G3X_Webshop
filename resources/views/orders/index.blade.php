<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Rendeléseim - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .orders-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
            flex: 1;
        }
        .order-card {
            background: #142c45;
            border: 1px solid #5c4d7c;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 25px;
            transition: all 0.3s;
            cursor: pointer;
            width: 100%;
        }
        .order-card:hover {
            border-color: #00ff99;
            box-shadow: 0 0 15px rgba(0, 255, 153, 0.2);
            transform: translateY(-2px);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .order-number {
            font-size: 18px;
            color: #00ff99;
            font-weight: bold;
        }
        .order-date {
            color: #cccccc;
            font-size: 16px;
        }
        .order-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #3b2d5c;
        }
        .order-info-item {
            color: #cccccc;
            font-size: 13px;
        }
        .order-info-label {
            color: #7d6b9f;
            font-size: 13px;
            text-transform: uppercase;
        }
        .order-amount {
            font-size: 22px;
            color: #00ff99;
            font-weight: bold;
        }
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #cccccc;
        }
        .nav-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        .nav-tab {
            padding: 10px 20px;
            background: rgba(0,255,153,0.1);
            color: #00ff99;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            border: 1px solid #00ff99;
        }
        .nav-tab.active {
            background: #00ff99;
            color: #0b1c2c;
        }
    </style>
</head>
<body style="min-height: 100vh; background: linear-gradient(135deg, #0b1c2c 0%, #1a3a52 100%); padding-top: 120px; padding-bottom: 0; display: flex; flex-direction: column;">
    <nav class="navbar">
        <div class="navbar-top">
            <a href="{{ route('home') }}" class="logo-link">
                <div class="animated-logo">G3X</div>
            </a>
            <div class="search-bar">
                <form method="GET" action="{{ route('search') }}" style="display: flex; width: 100%;">
                    <input type="text" name="q" placeholder="Keresés játékokra, ajándékkártyákra..." style="padding: 12px 16px; font-size: 1.1em; flex: 1; border: 1px solid #5c4d7c; background: #3b2d5c; color: #fff; border-radius: 6px;">
                </form>
            </div>
            <div class="nav-right">
                <a href="#" class="nav-btn" onclick="toggleSidebar()">Kategóriák</a>
                @auth
                    <div class="user-menu-container">
                        <button class="user-btn">👤 {{ Auth::user()->name }}</button>
                        <div class="user-dropdown" id="user-dropdown">
                            <a href="{{ route('settings.show') }}" class="user-dropdown-item">⚙️ Beállítások</a>
                            <a href="{{ route('orders.index') }}" class="user-dropdown-item">📋 Rendeléseim</a>
                            <a href="{{ route('logout') }}" class="user-dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Kijelentkezés</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('register') }}" class="nav-btn">Regisztráció</a>
                    <a href="{{ route('login') }}" class="nav-btn">Bejelentkezés</a>
                @endauth
                <a href="{{ route('cart.index') }}" class="nav-btn">🛒 Kosár <span id="cart-badge" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; margin-left: 5px; display: {{ session('cart') ? 'inline' : 'none' }};">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : '' }}</span></a>
            </div>
        </div>
    </nav>

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

    <div class="orders-container">
        <div style="margin-bottom: 30px;">
            <a href="{{ route('home') }}" style="color: #00ff99; text-decoration: none; font-weight: bold; margin-bottom: 20px; display: inline-block;">← Vissza a főoldalra</a>
            <h1 style="color: white; margin-top: 10px; font-size: 32px;">Rendeléseim</h1>
        </div>

        <!-- Navigáció -->
        <div class="nav-tabs">
            <a href="{{ route('settings.show') }}" class="nav-tab">⚙️ Beállítások</a>
            <a href="{{ route('orders.index') }}" class="nav-tab active">📋 Rendeléseim</a>
        </div>

        @if($orders->isEmpty())
            <div class="empty-message">
                <div style="font-size: 48px; margin-bottom: 15px;">📦</div>
                <h3 style="color: white;">Még nincsenek rendeléseid</h3>
                <p>Kezdj el vásárolni az alábbi gomb segítségével!</p>
                <a href="{{ route('home') }}" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #00ff99; color: #000; text-decoration: none; border-radius: 6px; font-weight: bold;">🛍️ Vásárlás</a>
            </div>
        @else
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order->id) }}" style="text-decoration: none;">
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-number">Rendelés #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                            <div class="order-date">{{ $order->created_at->format('Y. m. d. H:i') }}</div>
                        </div>

                        <div class="order-info">
                            <div>
                                <div class="order-info-label">E-mail</div>
                                <div class="order-info-item">{{ $order->email }}</div>
                            </div>
                            <div>
                                <div class="order-info-label">Fizetési mód</div>
                                <div class="order-info-item">
                                    @php
                                        $methods = [
                                            'card' => '💳 Bankkártya',
                                            'paypal' => '🅿️ PayPal',
                                            'google_pay' => '🔵 Google Pay',
                                            'apple_pay' => '🍎 Apple Pay'
                                        ];
                                    @endphp
                                    {{ $methods[$order->payment_method] ?? $order->payment_method }}
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div class="order-info-label">Termékek száma</div>
                                <div class="order-info-item">{{ count($order->items) ?? 0 }} db</div>
                            </div>
                            <div class="order-amount">{{ number_format($order->total_amount, 0, ',', ' ') }} Ft</div>
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-columns">
            <div>
                <h4>Kapcsolat</h4>
                <p>Email: info@g3x.hu</p>
                <p>Telefon: +36 30 123 4567</p>
            </div>
            <div>
                <h4>GYIK</h4>
                <p>Fizetés és szállítás</p>
                <p>Visszatérítés</p>
                <p>Fiók kezelése</p>
            </div>
            <div>
                <h4>Rólunk</h4>
                <p>Küldetésünk</p>
                <p>Karrier</p>
                <p>Blog</p>
            </div>
            <div>
                <h4>Jogi</h4>
                <p>Adatvédelmi irányelvek</p>
                <p>Felhasználási feltételek</p>
                <p>Sütik kezelése</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 G3X - Digitális Piactér. Minden jog fenntartva.</p>
        </div>
    </footer>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        let userDropdownTimeout;

        function setupUserDropdownDelay() {
            const userMenuContainer = document.querySelector('.user-menu-container');
            if (!userMenuContainer) return;

            userMenuContainer.addEventListener('mouseenter', function() {
                clearTimeout(userDropdownTimeout);
                const dropdown = this.querySelector('.user-dropdown');
                if (dropdown) dropdown.classList.add('active');
            });

            userMenuContainer.addEventListener('mouseleave', function() {
                const dropdown = this.querySelector('.user-dropdown');
                userDropdownTimeout = setTimeout(() => {
                    if (dropdown) dropdown.classList.remove('active');
                }, 300);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupUserDropdownDelay();
        });
    </script>
</body>
</html>
