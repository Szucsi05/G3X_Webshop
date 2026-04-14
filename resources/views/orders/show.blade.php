<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Rendelés Részletei - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .success-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
            color: #fff;
            border-radius: 8px;
        }
        .success-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        .success-header h1 {
            color: #00ff99;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .success-header p {
            color: #cccccc;
            font-size: 16px;
        }
        .success-info {
            background: #142c45;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #00ff99;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #3b2d5c;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #cccccc;
        }
        .info-value {
            color: #00ff99;
            font-weight: bold;
        }
        .licenses-section h2 {
            color: #00ff99;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .license-card {
            background: #142c45;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #3b2d5c;
        }
        .license-product {
            color: #00ff99;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .license-seller {
            color: #cccccc;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .license-key {
            background: #0b1c2c;
            padding: 12px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #00ff99;
            word-break: break-all;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .copy-btn {
            background: #00ff99;
            color: #000;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            margin-left: 10px;
        }
        .copy-btn:hover {
            background: #00cc7a;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }
        .btn-primary, .btn-secondary {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }
        .btn-primary {
            background: #00ff99;
            color: #000;
        }
        .btn-primary:hover {
            background: #00cc7a;
        }
        .btn-secondary {
            background: #3b2d5c;
            color: #00ff99;
            border: 1px solid #00ff99;
        }
        .btn-secondary:hover {
            background: #5c4d7c;
        }
        .email-note {
            background: #2c1e4a;
            padding: 15px;
            border-radius: 6px;
            color: #cccccc;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body style="min-height: 100vh; background: linear-gradient(135deg, #0b1c2c 0%, #1a3a52 100%); padding: 120px 20px 40px 20px;">
    <!-- NAVBAR -->
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
                <a href="#" class="nav-btn" style="display: flex; align-items: center; gap: 8px;" onclick="toggleSidebar()"><img src="{{ asset('icons/category.png') }}" alt="Kategóriák" style="width: 18px; height: 18px;"> Kategóriák</a>
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
                    <a href="{{ route('register') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/registration.png') }}" alt="Regisztráció" style="width: 18px; height: 18px;"> Regisztráció</a>
                    <a href="{{ route('login') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/login.png') }}" alt="Bejelentkezés" style="width: 18px; height: 18px;"> Bejelentkezés</a>
                @endauth
                <a href="{{ route('cart.index') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/cart.png') }}" alt="Kosár" style="width: 18px; height: 18px;"> cart <span id="cart-badge" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; margin-left: 5px; display: none;">0</span></a>
            </div>
        </div>
    </nav>

    <div class="success-container">
        <div style="margin-bottom: 30px;">
            <h2 style="color: #00ff99; margin: 0;">📦 Rendelés #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
        </div>
        <p style="color: #cccccc; margin-bottom: 30px;">Rendelés dátuma: {{ $order->created_at->format('Y. m. d. H:i') }}</p>

        <div class="success-info">
            <div class="info-row">
                <span class="info-label">📧 E-mail:</span>
                <span class="info-value">{{ $order->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">💰 Összesen:</span>
                <span class="info-value">{{ number_format($order->total_amount, 0, ',', ' ') }} Ft</span>
            </div>
            <div class="info-row">
                <span class="info-label">🎮 Termékek:</span>
                <span class="info-value">{{ $order->items()->count() }} db</span>
            </div>
            @php
                $paymentMethods = [
                    'card' => '💳 Bankkártya',
                    'paypal' => '🅿️ PayPal',
                    'google_pay' => '🔵 Google Pay',
                    'apple_pay' => '🍎 Apple Pay'
                ];
            @endphp
            <div class="info-row">
                <span class="info-label">Fizetési mód:</span>
                <span class="info-value">{{ $paymentMethods[$order->payment_method] ?? 'Ismeretlen' }}</span>
            </div>
        </div>

        <div class="licenses-section">
            <h2>� Rendelt Termékek</h2>
            
            @if($order->items && $order->items->count() > 0)
                @foreach($order->items as $item)
                    <div class="license-card">
                        <div class="license-product">🎮 {{ $item->productOffer->product->name }}</div>
                        <div class="license-seller">Eladó: {{ $item->productOffer->vendor->name }}</div>
                        <div class="info-row" style="margin-top: 10px; color: #cccccc;">
                            <span>Ár: <strong>{{ number_format($item->price_at_purchase, 0, ',', ' ') }} Ft</strong></span>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color: #cccccc;">Nincsenek termékek ebben a rendelésben.</p>
            @endif
        </div>

        <div class="licenses-section">
            <h2>🔑 Az aktiválási kulcsok</h2>
            
            @if($order->items && $order->items->count() > 0)
                @foreach($order->items as $item)
                    @if($item->license_key)
                        <div class="license-card">
                            <div class="license-product">🎮 {{ $item->productOffer->product->name }}</div>
                            <div class="license-seller">Eladó: {{ $item->productOffer->vendor->name }}</div>
                            <div class="license-key">
                                <span id="key-{{ $item->id }}">{{ $item->license_key }}</span>
                                <button type="button" class="copy-btn" onclick="copyToClipboard('key-{{ $item->id }}')">📋 Másolás</button>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <p style="color: #cccccc;">Nincsenek elérhető aktiválási kulcsok.</p>
            @endif
        </div>

        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn-primary">🏠 Vissza a főoldalra</a>
            <a href="{{ route('orders.index') }}" class="btn-secondary">📋 Vissza a rendeléseimhez</a>
        </div>
    </div>

    <!-- OLDALSÁV -->
    <div id="sidebar" class="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">✖</button>
        <h3>Kategóriák</h3>
        <ul>
            <li><a href="{{ route('filter.show', 'pc-games') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/pc_category.png') }}" alt="PC játékok" style="width: 18px; height: 18px;"> PC Játékok</a></li>
            <li><a href="{{ route('filter.show', 'console-games') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/console_category.png') }}" alt="Konzol játékok" style="width: 18px; height: 18px;"> Konzol Játékok</a></li>
            <li><a href="{{ route('filter.show', 'game-subscriptions') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/subcriptions_category.png') }}" alt="Játék előfizetések" style="width: 18px; height: 18px;"> Játék Előfizetések</a></li>
            <li><a href="{{ route('filter.show', 'software') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/software_category.png') }}" alt="Szoftver" style="width: 18px; height: 18px;"> Szoftver</a></li>
            <li><a href="{{ route('filter.show') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/all_category.png') }}" alt="Összes termék" style="width: 18px; height: 18px;"> Összes termék</a></li>
        </ul>
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
                <h4>Elérhetőségek</h4>
                <p>Budapest, Magyarország</p>
                <p>Nyitvatartás: H-P 9:00-17:00</p>
            </div>
        </div>
        <p class="footer-bottom">© 2025 G3X - Minden jog fenntartva.</p>
    </footer>

    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }

    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        const text = element.textContent;
        
        navigator.clipboard.writeText(text).then(() => {
            const btn = event.target;
            const originalText = btn.textContent;
            btn.textContent = '✅ Másolva!';
            btn.style.background = '#4CAF50';
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = '#00ff99';
            }, 2000);
        }).catch(err => {
            alert('Hiba a másolás során!');
        });
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

    document.addEventListener('DOMContentLoaded', setupUserDropdownDelay);
    </script>
</body>
</html>
