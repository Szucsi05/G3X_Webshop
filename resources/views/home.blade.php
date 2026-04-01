<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>G3X - Digitális Piactér</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <input type="text" name="q" placeholder="Keresés játékokra, ajándékkártyákra..." value="{{ request('q') }}" style="padding: 12px 16px; font-size: 1.1em; flex: 1; border: 1px solid #5c4d7c; background: #3b2d5c; color: #fff; border-radius: 6px;">
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
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
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
            <li><a href="{{ route('filter.show', 'pc-games') }}" onclick="clearFilters()">🖥️ PC játékok</a></li>
            <li><a href="{{ route('filter.show', 'console-games') }}" onclick="clearFilters()">🎮 Konzol Játékok</a></li>
            <li><a href="{{ route('filter.show', 'game-subscriptions') }}" onclick="clearFilters()">🎯 Játék Előfizetések</a></li>
            <li><a href="{{ route('filter.show', 'software') }}" onclick="clearFilters()">💻 Szoftver</a></li>
            <li><a href="{{ route('filter.show') }}" onclick="clearFilters()">✨ Összes termék</a></li>
        </ul>
    </div>

    <!-- HERO -->
    <header class="hero" style="background: linear-gradient(135deg, #0a1428 0%, #1a1a3e 50%, #2c1e4a 100%); position: relative; overflow: hidden; padding: 120px 30px;">
        <div style="position: absolute; top: -50%; right: -10%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(0, 255, 153, 0.15) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
        <div style="position: absolute; bottom: -30%; left: -5%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(44, 30, 74, 0.3) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
        <div style="position: relative; z-index: 1; max-width: 900px; margin: 0 auto;">
            <h1 style="font-size: 3.5em; font-weight: 800; margin-bottom: 20px; background: linear-gradient(135deg, #00ff99 0%, #00ccff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">A Legjobb Digitális Piactér</h1>
            <p style="font-size: 1.4em; color: #cccccc; margin-bottom: 30px; line-height: 1.6;">Hasonlítsd össze az árakat, keress a legjobbat, és vásárolj garantáltan biztonságosan. 100+ ajánlat.</p>
            <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: center;">
                <a href="{{ route('filter.show') }}" style="display: inline-block; padding: 12px 30px; font-size: 1.1em; background: linear-gradient(135deg, #00ff99, #00cc88); color: #000; text-decoration: none; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0, 255, 153, 0.3);">🔍 Vásárlás Megkezdése</a>
                <a href="#featured" style="display: inline-block; padding: 12px 30px; font-size: 1.1em; background: transparent; color: #00ff99; text-decoration: none; border-radius: 8px; border: 2px solid #00ff99; cursor: pointer; font-weight: bold; transition: all 0.3s ease;">⬇️ Felfedezés</a>
            </div>
        </div>
    </header>




    <!-- KÁRTYÁK -->
    <section class="cards-section" id="featured">
        <h2 style="font-size: 2.5em; margin-bottom: 20px; text-align: center;">🎮 Kiemelt Termékek</h2>
        <div class="products-grid">
            @foreach($featured as $product)
                @php
                    $imagePath = $product->image && file_exists(public_path($product->image)) ? $product->image : 'images/default-product.svg';
                    $offers = $product->offers()->orderBy('price')->get();
                    $minPrice = $offers->first()?->price;
                @endphp
                <div class="product-card" onclick="goToProduct({{ $product->id }})" style="cursor: pointer;">
                    <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-image" style="background: #1a1a3e;">
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p class="product-description">{{ $product->offers->count() }} ajánlat - {{ $product->category?->name ?? 'Ismeretlen' }}</p>
                        <div class="product-footer">
                            <span class="product-price">
                                @if($offers->isEmpty())
                                    Nincs elérhető ajánlat
                                @elseif($minPrice > 0)
                                    {{ number_format($minPrice, 0, ',', '.') . ' Ft' }}
                                @else
                                    INGYENES
                                @endif
                            </span>
                            <button type="button" class="btn-add-cart" onclick="event.stopPropagation(); goToProduct({{ $product->id }})">Részletek</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 50px;">
            <a href="{{ route('filter.show') }}" class="view-all-btn" style="display: inline-block; padding: 15px 40px; font-size: 1.1em; background: linear-gradient(135deg, #00cc88, #00aa66); color: white; text-decoration: none; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 204, 136, 0.3);">
                ➤ Összes Terméket Megtekintése ({{ \App\Models\Product::count() }})
            </a>
        </div>
    </section>

    <!-- CTA SZEKCIÓ -->
    <section style="background: linear-gradient(135deg, #2c1e4a 0%, #1a1a3e 100%); padding: 80px 30px; text-align: center; margin-top: 20px;">
        <div style="max-width: 800px; margin: 0 auto;">
            <h2 style="font-size: 2.2em; color: #00ff99; margin-bottom: 20px;">Készen Állsz a Vásárlásra?</h2>
            <p style="color: #cccccc; font-size: 1.1em; margin-bottom: 30px; line-height: 1.6;">Fedezd fel a legjobb ajánlatokat, hasonlítsd össze az árakat és vásárolj mit hiszel a legjobb.</p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('filter.show') }}" style="display: inline-block; padding: 15px 40px; font-size: 1.1em; background: linear-gradient(135deg, #00ff99, #00cc88); color: #000; text-decoration: none; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0, 255, 153, 0.3);">🛍️ Vásárolj Most</a>
                <a href="{{ route('register') }}" style="display: inline-block; padding: 15px 40px; font-size: 1.1em; background: transparent; color: #00ff99; text-decoration: none; border-radius: 8px; border: 2px solid #00ff99; cursor: pointer; font-weight: bold; transition: all 0.3s ease;">👤 Regisztrálj Ingyenesen</a>
            </div>
        </div>
    </section>

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

    <!-- JS -->
    <script>
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

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }

    function clearFilters() {
        localStorage.removeItem('filterState');
        toggleSidebar();
    }

    function goToProduct(productId) {
        localStorage.removeItem('lastSearchUrl');
        localStorage.removeItem('lastFilterUrl');
        window.location.href = '/product/' + productId;
    }

    // Add to cart - simple and direct
    function addToCart(productId) {
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update badge
                const badge = document.getElementById('cart-badge');
                badge.textContent = data.cart_count;
                badge.style.display = 'inline';
                
                // Pulse animation
                badge.style.animation = 'none';
                setTimeout(() => {
                    badge.style.animation = 'badge-pulse 0.3s ease-in-out';
                }, 50);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
</body>
</html>
