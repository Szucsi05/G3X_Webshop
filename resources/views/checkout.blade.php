<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Fizetés - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 20px 20px 20px;
            background: #0b1c2c;
            color: #fff;
            border-radius: 8px;
        }
        .checkout-content {
            display: flex;
            gap: 30px;
        }
        .checkout-left {
            flex: 1;
        }
        .checkout-right {
            flex: 1;
            background: #142c45;
            padding: 20px;
            border-radius: 8px;
            height: fit-content;
        }
        .order-summary h2 {
            color: #00ff99;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #3b2d5c;
            font-size: 14px;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item-name {
            color: #fff;
        }
        .order-item-qty {
            color: #cccccc;
        }
        .order-item-amount {
            color: #00ff99;
            font-weight: bold;
        }
        .order-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            margin-top: 15px;
            border-top: 2px solid #00ff99;
            font-size: 18px;
            font-weight: bold;
            color: #00ff99;
        }
        .payment-section h3 {
            color: #00ff99;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .payment-method-info {
            background: #0b1c2c;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            color: #cccccc;
            font-size: 14px;
        }
        .payment-form {
            background: #0b1c2c;
            padding: 20px;
            border-radius: 6px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            color: #00ff99;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            background: #3b2d5c;
            color: #fff;
            border: 1px solid #5c4d7c;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #00ff99;
            box-shadow: 0 0 5px rgba(0, 255, 153, 0.3);
        }
        .form-row {
            display: flex;
            gap: 15px;
        }
        .form-row .form-group {
            flex: 1;
        }
        .btn-complete {
            width: 100%;
            padding: 12px;
            background: #00ff99;
            color: #000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .btn-complete:hover {
            background: #00cc7a;
        }
        .btn-back {
            color: #00ff99;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .payment-badge {
            display: inline-block;
            background: #2c1e4a;
            color: #00ff99;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 15px;
        }
        .secure-badge {
            text-align: center;
            color: #00ff99;
            font-size: 12px;
            margin-top: 15px;
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
                <a href="{{ route('cart.index') }}" class="nav-btn">🛒 Kosár <span id="cart-badge" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; margin-left: 5px; display: {{ session('cart') ? 'inline' : 'none' }};">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : '' }}</span></a>
            </div>
        </div>
    </nav>

    <div class="checkout-container">
        <div style="margin-bottom: 30px;">
            <h1 style="color: #00ff99; margin: 0;">💳 Fizetés</h1>
        </div>

        <div class="checkout-content">
            <!-- Left side - Payment Form -->
            <div class="checkout-left">
                <div class="payment-form">
                    @php
                        $paymentMethods = [
                            'card' => 'Bankkártya',
                            'paypal' => 'PayPal',
                            'google_pay' => 'Google Pay',
                            'apple_pay' => 'Apple Pay'
                        ];
                        $methodName = $paymentMethods[$payment_method] ?? 'Ismeretlen';
                    @endphp
                    
                    <div class="payment-method-info">
                        <span class="payment-badge">{{ $methodName }}</span>
                    </div>

                    <h3 style="color: #00ff99;">Fizetési adatok</h3>

                    <form id="payment-form" method="POST" action="{{ route('checkout.process') }}">
                        @csrf

                        <div class="form-group">
                            <label>E-mail cím</label>
                            <input type="email" name="email" placeholder="your@email.com" required>
                        </div>

                        <input type="hidden" name="payment_method" value="{{ $payment_method }}">

                        @if($payment_method === 'card')
                            <!-- Bankkártya adatok -->
                            <div id="card-form" style="display: block;">
                                <div class="form-group">
                                    <label>Kártyaszám (16 szám)</label>
                                    <input type="text" name="card_number" placeholder="1234 5678 9012 3456" pattern="\d{4} \d{4} \d{4} \d{4}" maxlength="19" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Lejárat (HH/ÉÉ)</label>
                                        <input type="text" name="card_expiry" placeholder="MM/YY" pattern="\d{2}/\d{2}" maxlength="5" required>
                                    </div>
                                    <div class="form-group">
                                        <label>CVC (3 szám)</label>
                                        <input type="password" name="card_cvc" placeholder="•••" pattern="\d{3}" maxlength="3" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Kártyabirtokos neve</label>
                                    <input type="text" name="card_name" placeholder="John Doe" required>
                                </div>

                                <div class="form-group">
                                    <label>Ország</label>
                                    <select name="country" required>
                                        <option value="">Válassz országot</option>
                                        <option value="HU" selected>🇭🇺 Magyarország</option>
                                        <option value="GB">🇬🇧 Egyesült Királyság</option>
                                        <option value="DE">🇩🇪 Németország</option>
                                        <option value="AT">🇦🇹 Ausztria</option>
                                        <option value="FR">🇫🇷 Franciaország</option>
                                        <option value="US">🇺🇸 Amerikai Egyesült Államok</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Irányítószám (max 4 szám)</label>
                                    <input type="text" name="postal_code" placeholder="1234" pattern="\d{1,4}" maxlength="4" required>
                                </div>

                            </div>
                        @elseif($payment_method === 'paypal')
                            <div class="form-group">
                                <label>PayPal E-mail cím</label>
                                <input type="email" name="paypal_email" placeholder="your@email.com" required>
                            </div>
                            <p style="color: #cccccc; font-size: 13px; margin-top: 10px;">A PayPal-ra leszünk irányítva az ön adatainak megerősítéséhez.</p>
                        @elseif($payment_method === 'google_pay')
                            <div class="form-group">
                                <label>Google fiók e-mail</label>
                                <input type="email" name="google_email" placeholder="your@gmail.com" required>
                            </div>
                            <p style="color: #cccccc; font-size: 13px; margin-top: 10px;">A Google Pay fiókja lesz használva a fizetéshez.</p>
                        @elseif($payment_method === 'apple_pay')
                            <div class="form-group">
                                <label>Apple fiók e-mail</label>
                                <input type="email" name="apple_email" placeholder="your@icloud.com" required>
                            </div>
                            <p style="color: #cccccc; font-size: 13px; margin-top: 10px;">Az Apple Pay biztonsági módszere lesz használva a fizetéshez.</p>
                        @endif

                        <button type="submit" class="btn-complete">✅ Fizetés megerősítése</button>
                    </form>

                    <div class="secure-badge">🔒 100% Biztonságos & Védett Fizetés</div>
                </div>
            </div>

            <!-- Right side - Order Summary -->
            <div class="checkout-right">
                <div class="order-summary">
                    <h2>📋 Rendelés összefoglalása</h2>

                    @foreach($cart as $id => $item)
                        <div class="order-item">
                            <div>
                                <div class="order-item-name">{{ $item['name'] }}</div>
                                <div class="order-item-qty" style="font-size: 12px; margin-top: 3px;">
                                    Eladó: {{ $item['seller'] ?? 'N/A' }} | Mennyiség: {{ $item['quantity'] }}
                                </div>
                            </div>
                            <div class="order-item-amount">
                                {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} Ft
                            </div>
                        </div>
                    @endforeach

                    <div class="order-total">
                        <span>Összesen:</span>
                        <span>{{ number_format($total, 0, ',', ' ') }} Ft</span>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('checkout.payment') }}" class="btn-back">← Vissza a fizetési mód választáshoz</a>
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

    // Card number formatting
    document.addEventListener('DOMContentLoaded', function() {
        setupUserDropdownDelay();
        const cardInput = document.querySelector('input[name="card_number"]');
        if (cardInput) {
            cardInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s/g, '');
                // Max 16 digits
                value = value.substring(0, 16);
                let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
                e.target.value = formatted;
            });
        }

        // Expiry date formatting
        const expiryInput = document.querySelector('input[name="card_expiry"]');
        if (expiryInput) {
            expiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                // Max 4 digits (MMYY)
                value = value.substring(0, 4);
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                e.target.value = value;
            });
        }

        // CVC validation (max 3 digits)
        const cvcInput = document.querySelector('input[name="card_cvc"]');
        if (cvcInput) {
            cvcInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').substring(0, 3);
            });
        }

        // Postal code validation (max 4 digits)
        const postalInput = document.querySelector('input[name="postal_code"]');
        if (postalInput) {
            postalInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
            });
        }

        // Card source toggle (saved vs new)
        // Removed - no longer using saved cards
    });
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
