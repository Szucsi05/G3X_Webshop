<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Számlázási Adatok - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .checkout-details-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 20px 20px 20px;
            background: #0b1c2c;
            color: #fff;
            border-radius: 8px;
        }
        .checkout-details-content {
            display: flex;
            gap: 30px;
        }
        .checkout-details-left {
            flex: 1;
        }
        .checkout-details-right {
            flex: 1;
            background: #142c45;
            padding: 20px;
            border-radius: 8px;
            height: fit-content;
        }
        .form-section {
            background: #142c45;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-section h3 {
            color: #00ff99;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
        }
        .toggle-section {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .toggle-btn {
            flex: 1;
            padding: 10px;
            border: 2px solid #5c4d7c;
            background: transparent;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .toggle-btn.active {
            background: #00ff99;
            color: #000;
            border-color: #00ff99;
        }
        .toggle-btn:hover {
            border-color: #00ff99;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #cccccc;
            font-size: 13px;
            font-weight: bold;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            background: #0b1c2c;
            color: #fff;
            border: 1px solid #5c4d7c;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
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
        .hidden-section {
            display: none;
        }
        .hidden-section.active {
            display: block;
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
        .btn-complete:disabled {
            background: #666;
            cursor: not-allowed;
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
            color: #00ff99;
            font-size: 12px;
        }
        .order-amount {
            color: #00ff99;
            font-weight: bold;
        }
        .order-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-top: 2px solid #3b2d5c;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #00ff99;
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
                <a href="{{ route('cart.index') }}" class="nav-btn">🛒 Kosár <span id="cart-badge" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; margin-left: 5px; display: {{ session('cart') ? 'inline' : 'none' }};">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : '' }}</span></a>
            </div>
        </div>
    </nav>

    <div class="checkout-details-container">
        <div style="margin-bottom: 30px;">
            <h1 style="color: #00ff99; margin: 0;">📋 Számlázási Adatok</h1>
        </div>

        <div class="checkout-details-content">
            <!-- Left side - Form -->
            <div class="checkout-details-left">
                <form id="details-form" method="POST" action="{{ route('checkout.details.store') }}">
                    @csrf

                    <!-- Fiók típusa -->
                    <div class="form-section">
                        <h3>Fiók típusa</h3>
                        <div class="toggle-section">
                            <button type="button" class="toggle-btn{{ (session('checkout_details.account_type') ?? old('account_type', 'personal')) === 'personal' ? ' active' : '' }}" data-type="personal" onclick="selectType('personal')">👤 Magánszemély</button>
                            <button type="button" class="toggle-btn{{ (session('checkout_details.account_type') ?? old('account_type', 'personal')) === 'company' ? ' active' : '' }}" data-type="company" onclick="selectType('company')">🏢 Cég</button>
                        </div>
                        <input type="hidden" id="account-type" name="account_type" value="{{ session('checkout_details.account_type', old('account_type', 'personal')) }}">
                    </div>

                    <!-- Számlázási adatok - Magánszemély -->
                    <div id="personal-billing" class="form-section hidden-section active">
                        <h3>💳 Számlázási adatok (Magánszemély)</h3>
                        
                        <div class="form-group">
                            <label>Teljes név</label>
                            <input type="text" name="billing_name_personal" id="billing_name_personal" placeholder="pl. Kiss János" value="{{ session('checkout_details.billing_name_personal', old('billing_name_personal')) }}">
                        </div>

                        <div class="form-group">
                            <label>Telefonszám</label>
                            <input type="tel" name="billing_phone_personal" id="billing_phone_personal" placeholder="+36 70 252 3456" value="{{ session('checkout_details.billing_phone_personal', old('billing_phone_personal')) }}">
                        </div>

                        <div class="form-group">
                            <label>E-mail cím</label>
                            <input type="email" name="billing_email_personal" id="billing_email_personal" placeholder="pl. janos@example.com" value="{{ session('checkout_details.billing_email_personal', old('billing_email_personal')) }}">
                        </div>

                        <div class="form-group">
                            <label>Ország</label>
                            <select name="billing_country_personal" id="billing_country_personal">
                                <option value="">Válassz országot</option>
                                <option value="HU" {{ (session('checkout_details.billing_country_personal', old('billing_country_personal', 'HU')) === 'HU') ? 'selected' : '' }}>🇭🇺 Magyarország</option>
                                <option value="GB" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'GB' ? 'selected' : '' }}>🇬🇧 Egyesült Királyság</option>
                                <option value="DE" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'DE' ? 'selected' : '' }}>🇩🇪 Németország</option>
                                <option value="AT" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'AT' ? 'selected' : '' }}>🇦🇹 Ausztria</option>
                                <option value="FR" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'FR' ? 'selected' : '' }}>🇫🇷 Franciaország</option>
                                <option value="US" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'US' ? 'selected' : '' }}>🇺🇸 Amerikai Egyesült Államok</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Város</label>
                            <input type="text" name="billing_city_personal" id="billing_city_personal" placeholder="pl. Budapest" value="{{ session('checkout_details.billing_city_personal', old('billing_city_personal')) }}">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Irányítószám</label>
                                <input type="text" name="billing_postal_personal" id="billing_postal_personal" placeholder="pl. 1011" value="{{ session('checkout_details.billing_postal_personal', old('billing_postal_personal')) }}">
                            </div>
                            <div class="form-group">
                                <label>Házszám/Utca</label>
                                <input type="text" name="billing_street_personal" id="billing_street_personal" placeholder="pl. Petőfi utca 10" value="{{ session('checkout_details.billing_street_personal', old('billing_street_personal')) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Számlázási adatok - Cég -->
                    <div id="company-billing" class="form-section hidden-section">
                        <h3>💳 Számlázási adatok (Cég)</h3>
                        
                        <div class="form-group">
                            <label>Cégnév</label>
                            <input type="text" name="billing_company_name" id="billing_company_name" placeholder="pl. Cég Kft." value="{{ session('checkout_details.billing_company_name', old('billing_company_name')) }}">
                        </div>

                        <div class="form-group">
                            <label>Adószám</label>
                            <input type="text" name="billing_tax_id" id="billing_tax_id" placeholder="pl. HU12345678" value="{{ session('checkout_details.billing_tax_id', old('billing_tax_id')) }}">
                        </div>

                        <div class="form-group">
                            <label>Telefonszám</label>
                            <input type="tel" name="billing_phone_company" id="billing_phone_company" placeholder="+36 70 252 3456" value="{{ session('checkout_details.billing_phone_company', old('billing_phone_company')) }}">
                        </div>

                        <div class="form-group">
                            <label>E-mail cím</label>
                            <input type="email" name="billing_email_company" id="billing_email_company" placeholder="pl. info@ceg.hu" value="{{ session('checkout_details.billing_email_company', old('billing_email_company')) }}">
                        </div>

                        <div class="form-group">
                            <label>Ország</label>
                            <select name="billing_country_company" id="billing_country_company">
                                <option value="">Válassz országot</option>
                                <option value="HU" {{ (session('checkout_details.billing_country_company', old('billing_country_company', 'HU')) === 'HU') ? 'selected' : '' }}>🇭🇺 Magyarország</option>
                                <option value="GB" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'GB' ? 'selected' : '' }}>🇬🇧 Egyesült Királyság</option>
                                <option value="DE" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'DE' ? 'selected' : '' }}>🇩🇪 Németország</option>
                                <option value="AT" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'AT' ? 'selected' : '' }}>🇦🇹 Ausztria</option>
                                <option value="FR" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'FR' ? 'selected' : '' }}>🇫🇷 Franciaország</option>
                                <option value="US" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'US' ? 'selected' : '' }}>🇺🇸 Amerikai Egyesült Államok</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Város</label>
                            <input type="text" name="billing_city_company" id="billing_city_company" placeholder="pl. Budapest" value="{{ session('checkout_details.billing_city_company', old('billing_city_company')) }}">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Irányítószám</label>
                                <input type="text" name="billing_postal_company" id="billing_postal_company" placeholder="pl. 1011" value="{{ session('checkout_details.billing_postal_company', old('billing_postal_company')) }}">
                            </div>
                            <div class="form-group">
                                <label>Házszám/Utca</label>
                                <input type="text" name="billing_street_company" id="billing_street_company" placeholder="pl. Petőfi utca 10" value="{{ session('checkout_details.billing_street_company', old('billing_street_company')) }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-complete">➡️ Tovább a fizetéshez</button>
                </form>

                <a href="{{ route('cart.index') }}" class="back-link">← Vissza a kosárhoz</a>
            </div>

            <!-- Right side - Order Summary -->
            <div class="checkout-details-right">
                <div class="order-summary">
                    <h2>📋 Rendelés összefoglalása</h2>

                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                        <div class="order-item">
                            <div>
                                <div class="order-item-name">{{ $item['name'] }}</div>
                                <div class="order-item-qty">
                                    Eladó: {{ $item['seller'] ?? 'N/A' }} | Mennyiség: {{ $item['quantity'] }}
                                </div>
                            </div>
                            <div class="order-amount">
                                {{ number_format($subtotal, 0, ',', ' ') }} Ft
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

    function selectType(type) {
        // Update hidden input
        document.getElementById('account-type').value = type;

        // Update button styles
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-type="${type}"]`).classList.add('active');

        // Show/hide sections
        if (type === 'personal') {
            document.getElementById('personal-billing').classList.add('active');
            document.getElementById('company-billing').classList.remove('active');
            
            // Clear company fields
            document.getElementById('billing_company_name').value = '';
            document.getElementById('billing_company_id').value = '';
            document.getElementById('billing_tax_id').value = '';
            document.getElementById('billing_contact_name').value = '';
            document.getElementById('billing_phone_company').value = '';
            document.getElementById('billing_email_company').value = '';
        } else {
            document.getElementById('personal-billing').classList.remove('active');
            document.getElementById('company-billing').classList.add('active');
            
            // Clear personal fields
            document.getElementById('billing_name_personal').value = '';
            document.getElementById('billing_phone_personal').value = '';
            document.getElementById('billing_email_personal').value = '';
        }
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
