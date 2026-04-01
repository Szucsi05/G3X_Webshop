<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Fizetési Mód - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .payment-method-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 120px 20px 20px 20px;
            background: #0b1c2c;
            color: #fff;
            border-radius: 8px;
        }
        .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .payment-method-card {
            background: #142c45;
            border: 2px solid #5c4d7c;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .payment-method-card:hover {
            border-color: #00ff99;
            box-shadow: 0 0 15px rgba(0, 255, 153, 0.2);
        }
        .payment-method-card.active {
            border-color: #00ff99;
            background: #1a3a52;
        }
        .payment-method-card .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .payment-method-card .name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #00ff99;
        }
        .payment-method-card .desc {
            font-size: 12px;
            color: #cccccc;
        }
        .btn-continue {
            width: 100%;
            padding: 12px;
            background: #00ff99;
            color: #000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            margin-top: 30px;
            transition: background 0.3s;
        }
        .btn-continue:hover {
            background: #00cc7a;
        }
        .btn-continue:disabled {
            background: #666;
            cursor: not-allowed;
        }
        .back-link {
            color: #00ff99;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
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
                <a href="{{ route('cart.index') }}" class="nav-btn">🛒 Kosár <span id="cart-badge" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; margin-left: 5px; display: none;">0</span></a>
            </div>
        </div>
    </nav>

    <div class="payment-method-container">
        <h1 style="color: #00ff99; margin-bottom: 30px;">💳 Válassz Fizetési Módot</h1>

        <form id="payment-form" method="POST" action="{{ route('checkout') }}">
            @csrf

            <div class="payment-methods-grid">
                <div class="payment-method-card" onclick="selectPaymentMethod('card', this)">
                    <div class="icon">💳</div>
                    <div class="name">Bankkártya</div>
                    <div class="desc">VISA, Mastercard, Maestro</div>
                </div>

                <div class="payment-method-card" onclick="selectPaymentMethod('paypal', this)">
                    <div class="icon">🅿️</div>
                    <div class="name">PayPal</div>
                    <div class="desc">Gyors és biztonságos</div>
                </div>

                <div class="payment-method-card" onclick="selectPaymentMethod('google_pay', this)">
                    <div class="icon">🔵</div>
                    <div class="name">Google Pay</div>
                    <div class="desc">Google fiók szükséges</div>
                </div>

                <div class="payment-method-card" onclick="selectPaymentMethod('apple_pay', this)">
                    <div class="icon">🍎</div>
                    <div class="name">Apple Pay</div>
                    <div class="desc">Apple fiók szükséges</div>
                </div>
            </div>

            <input type="hidden" id="payment_method" name="payment_method" value="">

            <button type="submit" class="btn-continue" id="continue-btn" disabled>➡️ Tovább a Fizetéshez</button>
        </form>

        <a href="{{ route('checkout.details') }}" class="back-link">← Vissza az Adatokhoz</a>
    </div>

    <!-- SIDEBAR -->
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

    <!-- JS -->
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

    document.addEventListener('DOMContentLoaded', setupUserDropdownDelay);

    function selectPaymentMethod(method, element) {
        // Remove active class from all cards
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Add active class to selected card
        element.classList.add('active');
        
        // Set hidden input
        document.getElementById('payment_method').value = method;
        
        // Enable button
        document.getElementById('continue-btn').disabled = false;
    }
    </script>

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
</body>
</html>
