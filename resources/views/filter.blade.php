<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Összes Termék - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .filter-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 120px 20px 40px 20px;
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #5c4d7c;
        }

        .filter-header h1 {
            color: #00cc88;
            font-size: 2.2em;
            margin: 0;
        }

        .results-count {
            color: #aaa;
            font-size: 1.1em;
        }

        .category-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .category-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            background: rgba(92, 77, 124, 0.4);
            color: #ddd;
            border: 2px solid #5c4d7c;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1em;
            text-decoration: none;
        }

        .category-btn:hover {
            background: rgba(0, 204, 136, 0.2);
            border-color: #00cc88;
            color: #00cc88;
        }

        .category-btn.active {
            background: linear-gradient(135deg, #00cc88, #00aa66);
            color: #000;
            border-color: #00cc88;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 80px;
            margin-bottom: 20px;
            display: block;
            opacity: 0.6;
        }

        .empty-state h2 {
            color: #aaa;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
        }

        @media (max-width: 768px) {
            .filter-container {
                padding: 120px 15px 40px 15px;
            }

            .filter-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .filter-header h1 {
                font-size: 1.8em;
            }
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
                    <input type="text" name="q" placeholder="Keresés játékokra, ajándékkártyákra..." value="{{ request('q') }}" style="padding: 12px 16px; font-size: 1.1em; flex: 1; border: 1px solid #5c4d7c; background: #3b2d5c; color: #fff; border-radius: 6px;">
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
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
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

    <!-- OLDALSÁV -->
    <div id="sidebar" class="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">✖</button>
        <h3>Kategóriák</h3>
        <ul>
            <li><a href="{{ route('filter.show', 'pc_games') }}" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/pc_category.png') }}" alt="PC játékok" style="width: 18px; height: 18px;"> PC Játékok</a></li>
            <li><a href="{{ route('filter.show', 'console_games') }}" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/console_category.png') }}" alt="Konzol játékok" style="width: 18px; height: 18px;"> Konzol Játékok</a></li>
            <li><a href="{{ route('filter.show', 'game_subscriptions') }}" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/subcriptions_category.png') }}" alt="Játék előfizetések" style="width: 18px; height: 18px;"> Játék Előfizetések</a></li>
            <li><a href="{{ route('filter.show', 'software') }}" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/software_category.png') }}" alt="Szoftver" style="width: 18px; height: 18px;"> Szoftver</a></li>
            <li><a href="{{ route('filter.show') }}" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/all_category.png') }}" alt="Összes termék" style="width: 18px; height: 18px;"> Összes termék</a></li>
        </ul>
    </div>

    <div class="filter-container">
        <div class="filter-header">
            <h1>{{ $categoryLabel ?? 'Összes Termék' }}</h1>
            <span class="results-count">
                {{ count($products) }} termék
            </span>
        </div>

        <!-- Category Filter Buttons -->
        <div class="category-filter">
            <a href="{{ route('filter.show') }}" class="category-btn {{ !$category ? 'active' : '' }}">
                <img src="{{ asset('icons/all_category.png') }}" alt="Összes" style="width: 18px; height: 18px;"> Összes
            </a>
            <a href="{{ route('filter.show', 'pc_games') }}" class="category-btn {{ $category === 'pc_games' ? 'active' : '' }}">
                <img src="{{ asset('icons/pc_category.png') }}" alt="PC Játékok" style="width: 18px; height: 18px;"> PC Játékok
            </a>
            <a href="{{ route('filter.show', 'console_games') }}" class="category-btn {{ $category === 'console_games' ? 'active' : '' }}">
                <img src="{{ asset('icons/console_category.png') }}" alt="Konzol Játékok" style="width: 18px; height: 18px;"> Konzol Játékok
            </a>
            <a href="{{ route('filter.show', 'game_subscriptions') }}" class="category-btn {{ $category === 'game_subscriptions' ? 'active' : '' }}">
                <img src="{{ asset('icons/subcriptions_category.png') }}" alt="Előfizetések" style="width: 18px; height: 18px;"> Előfizetések
            </a>
            <a href="{{ route('filter.show', 'software') }}" class="category-btn {{ $category === 'software' ? 'active' : '' }}">
                <img src="{{ asset('icons/software_category.png') }}" alt="Szoftver" style="width: 18px; height: 18px;"> Szoftver
            </a>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" id="products-grid">
            @if(count($products) > 0)
                @foreach($products as $product)
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
                                <button type="button" class="btn-add-cart" onclick="event.stopPropagation(); goToProduct({{ $product->id }})" }}>Részletek</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <span class="empty-state-icon">🛍️</span>
                    <h2>Nincs találat</h2>
                    <p>Nincs termék ebben a kategóriában</p>
                </div>
            @endif
        </div>
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

        window.addEventListener('DOMContentLoaded', function() {
            setupUserDropdownDelay();
        });

        function goToProduct(productId) {
            localStorage.setItem('lastFilterUrl', window.location.href);
            localStorage.removeItem('lastSearchUrl');
            window.location.href = '/product/' + productId;
        }

        function addToCart(productId) {
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('cart-badge');
                    badge.textContent = data.cart_count;
                    badge.style.display = 'inline';
                    badge.style.animation = 'none';
                    setTimeout(() => {
                        badge.style.animation = 'badge-pulse 0.3s ease-in-out';
                    }, 50);
                }
            })
            .catch(error => console.error('Error:', error));
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
