<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Profil beállítások - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
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
                <a href="{{ route('cart.index') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/cart.png') }}" alt="Kosár" style="width: 18px; height: 18px;"> cart <span id="cart-badge" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; margin-left: 5px; display: {{ session('cart') ? 'inline' : 'none' }};">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : '' }}</span></a>
            </div>
        </div>
    </nav>

    <!-- TARTALOM -->
    <div style="min-height: 100vh; background: linear-gradient(135deg, #0b1c2c 0%, #1a3a52 100%); padding: 120px 20px 40px 20px;">
        <div style="max-width: 600px; margin: 0 auto;">
        <!-- Fejléc -->
        <div style="margin-bottom: 30px;">
            <a href="{{ route('home') }}" style="color: #00ff99; text-decoration: none; font-weight: bold; margin-bottom: 20px; display: inline-block;">← Vissza a főoldalra</a>
            <h1 style="color: white; margin-top: 10px; font-size: 32px;">Profil beállítások</h1>
        </div>

        <!-- Navigáció -->
        <div style="display: flex; gap: 10px; margin-bottom: 30px;">
            <a href="{{ route('settings.show') }}" style="padding: 10px 20px; background: #00ff99; color: #0b1c2c; text-decoration: none; border-radius: 6px; font-weight: bold; border: none;">⚙️ Beállítások</a>
            <a href="{{ route('orders.index') }}" style="padding: 10px 20px; background: rgba(0,255,153,0.1); color: #00ff99; text-decoration: none; border-radius: 6px; font-weight: bold; border: 1px solid #00ff99;">📋 Rendeléseim</a>
        </div>

        <!-- Sikerüzenet -->
        @if(session('success'))
            <div style="background: #00ff99; color: #0b1c2c; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Hibauzenetek -->
        @if($errors->any())
            <div style="background: #ff4444; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                @foreach($errors->all() as $error)
                    <p style="margin: 5px 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Beállítások formanyomtatvány -->
        <form method="POST" action="{{ route('settings.update') }}" style="background: rgba(255,255,255,0.05); padding: 30px; border-radius: 12px; border: 1px solid rgba(0,255,153,0.2);">
            @csrf
            @method('PUT')

            <!-- Személyes adatok szekció -->
            <div style="margin-bottom: 30px;">
                <h2 style="color: #00ff99; font-size: 18px; margin-bottom: 20px; border-bottom: 2px solid #00ff99; padding-bottom: 10px;">Személyes adatok</h2>

                <!-- Név -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #e0e0e0; margin-bottom: 8px; font-weight: 500;">Teljes név</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                        style="width: 100%; padding: 12px; background: rgba(0,255,153,0.05); border: 1px solid #00ff99; color: white; border-radius: 6px; font-size: 14px; font-family: inherit;" 
                        placeholder="Pl: Kiss János">
                </div>

                <!-- Email -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #e0e0e0; margin-bottom: 8px; font-weight: 500;">E-mail cím</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                        style="width: 100%; padding: 12px; background: rgba(0,255,153,0.05); border: 1px solid #00ff99; color: white; border-radius: 6px; font-size: 14px; font-family: inherit;" 
                        placeholder="példa@email.com">
                </div>

                <!-- Jelszó módosítása -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #e0e0e0; margin-bottom: 8px; font-weight: 500;">Új jelszó (hagyja üresen ha nem szeretné módosítani)</label>
                    <input type="password" name="password" 
                        style="width: 100%; padding: 12px; background: rgba(0,255,153,0.05); border: 1px solid #00ff99; color: white; border-radius: 6px; font-size: 14px; font-family: inherit;" 
                        placeholder="••••••••">
                </div>

                <!-- Jelszó megerősítés -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #e0e0e0; margin-bottom: 8px; font-weight: 500;">Jelszó megerősítés</label>
                    <input type="password" name="password_confirmation" 
                        style="width: 100%; padding: 12px; background: rgba(0,255,153,0.05); border: 1px solid #00ff99; color: white; border-radius: 6px; font-size: 14px; font-family: inherit;" 
                        placeholder="••••••••">
                </div>
            </div>



            <!-- Ment gomb -->
            <button type="submit" 
                style="width: 100%; padding: 14px; background: linear-gradient(135deg, #00ff99 0%, #00dd88 100%); color: #0b1c2c; border: none; border-radius: 6px; font-weight: bold; font-size: 16px; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase;">
                💾 Beállítások mentése
            </button>

            <button type="button" onclick="window.history.back()" 
                style="width: 100%; padding: 12px; background: transparent; color: #00ff99; border: 1px solid #00ff99; border-radius: 6px; font-weight: bold; font-size: 14px; cursor: pointer; margin-top: 10px; transition: all 0.3s ease;">
                Mégse
            </button>
        </form>
        </div>
    </div>

<style>
    input:focus {
        outline: none;
        border-color: #00ff99 !important;
        box-shadow: 0 0 10px rgba(0, 255, 153, 0.3) !important;
        background: rgba(0,255,153,0.1) !important;
    }

    button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 255, 153, 0.3);
    }

    button[type="button"]:hover {
        border-color: #00ff99;
        background: rgba(0, 255, 153, 0.1);
    }
</style>

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
            if (sidebar) {
                sidebar.classList.toggle('active');
            }
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
