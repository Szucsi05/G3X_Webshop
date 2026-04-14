<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0b1c2c;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
        }

        .product-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 120px 30px 40px 30px;
        }

        .product-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            margin-bottom: 60px;
            background: linear-gradient(135deg, #142c45 0%, #1a1a3e 100%);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 255, 153, 0.1);
        }

        .product-image-section {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 255, 153, 0.2);
        }

        .product-info-section h1 {
            font-size: 42px;
            color: #00ff99;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .product-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .meta-item {
            background: rgba(0, 255, 153, 0.1);
            padding: 12px 20px;
            border-radius: 8px;
            border-left: 3px solid #00ff99;
        }

        .meta-label {
            color: #cccccc;
            font-size: 12px;
            text-transform: uppercase;
        }

        .meta-value {
            color: #00ff99;
            font-weight: bold;
            font-size: 18px;
        }

        .price-section {
            background: rgba(44, 30, 74, 0.5);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 2px solid #2c1e4a;
        }

        .current-price {
            font-size: 48px;
            color: #00ff99;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .sellers-table {
            width: 100%;
            background: #0b1c2c;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
        }

        .sellers-table thead {
            background: #2c1e4a;
        }

        .sellers-table th {
            padding: 15px;
            text-align: left;
            color: #00ff99;
            font-weight: bold;
            border-bottom: 2px solid #3b2d5c;
        }

        .sellers-table td {
            padding: 15px;
            border-bottom: 1px solid #3b2d5c;
        }

        .seller-badge {
            background: #2c1e4a;
            padding: 8px 15px;
            border-radius: 6px;
            color: #00ff99;
        }

        .rating-stars {
            color: #ffd700;
            font-size: 14px;
        }

        .btn-kosarba {
            background: #00ff99;
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-kosarba:hover {
            background: #00cc7a;
            transform: translateY(-2px);
        }

        .reviews-section {
            background: linear-gradient(135deg, #142c45 0%, #1a1a3e 100%);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 255, 153, 0.1);
        }

        .reviews-section h2 {
            color: #00ff99;
            font-size: 32px;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c1e4a;
            padding-bottom: 15px;
        }

        .review-item {
            background: rgba(0, 255, 153, 0.05);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            border-left: 4px solid #00ff99;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .review-author {
            color: #00ff99;
            font-weight: bold;
            font-size: 16px;
        }

        .review-rating {
            color: #ffd700;
            font-weight: bold;
        }

        .review-text {
            color: #cccccc;
            line-height: 1.6;
            margin-top: 10px;
        }

        .review-date {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            margin-bottom: 40px;
            color: #00ff99;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .back-link:hover {
            color: #00cc7a;
        }

        @media (max-width: 768px) {
            .product-header {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .product-info-section h1 {
                font-size: 28px;
            }

            .current-price {
                font-size: 36px;
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
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('register') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/registration.png') }}" alt="Regisztráció" style="width: 18px; height: 18px;"> Regisztráció</a>
                    <a href="{{ route('login') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/login.png') }}" alt="Bejelentkezés" style="width: 18px; height: 18px;"> Bejelentkezés</a>
                @endauth
                <a href="{{ route('cart.index') }}" class="nav-btn" style="display: flex; align-items: center; gap: 8px;" onclick="localStorage.removeItem('filterState');"><img src="{{ asset('icons/cart.png') }}" alt="Kosár" style="width: 18px; height: 18px;"> cart <span id="cart-badge" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; margin-left: 5px; display: {{ session('cart') ? 'inline' : 'none' }};">{{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : '' }}</span></a>
            </div>
        </div>
    </nav>

    <!-- OLDALSÁV -->
    <div id="sidebar" class="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">✖</button>
        <h3>Kategóriák</h3>
        <ul>
            <li><a href="{{ route('filter.show', 'pc-games') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/pc_category.png') }}" alt="PC játékok" style="width: 18px; height: 18px;"> PC játékok</a></li>
            <li><a href="{{ route('filter.show', 'console-games') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/console_category.png') }}" alt="Konzol játékok" style="width: 18px; height: 18px;"> Konzol Játékok</a></li>
            <li><a href="{{ route('filter.show', 'game-subscriptions') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/subcriptions_category.png') }}" alt="Játék előfizetések" style="width: 18px; height: 18px;"> Játék Előfizetések</a></li>
            <li><a href="{{ route('filter.show', 'software') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/software_category.png') }}" alt="Szoftver" style="width: 18px; height: 18px;"> Szoftver</a></li>
            <li><a href="{{ route('filter.show') }}" onclick="localStorage.removeItem('filterState');" style="display: flex; align-items: center; gap: 8px;"><img src="{{ asset('icons/all_category.png') }}" alt="Összes termék" style="width: 18px; height: 18px;"> Összes termék</a></li>
        </ul>
    </div>

    <div class="product-container">
        <a href="#" id="back-link" class="back-link">← Vissza</a>
        <script>
            // Prioritás sorrend: keresés > szűrés > főoldal
            const lastSearchUrl = localStorage.getItem('lastSearchUrl');
            const lastFilterUrl = localStorage.getItem('lastFilterUrl');
            const backLink = document.getElementById('back-link');
            
            if (lastSearchUrl) {
                backLink.href = lastSearchUrl;
                backLink.textContent = '← Vissza a kereséshez';
            } else if (lastFilterUrl) {
                backLink.href = lastFilterUrl;
                backLink.textContent = '← Vissza a szűréshez';
            } else {
                backLink.href = '{{ route('home') }}';
                backLink.textContent = '← Vissza a főoldalra';
            }
        </script>

        <!-- PRODUCT HEADER -->
        <div class="product-header">
            <!-- Kép -->
            @php
                $imagePath = $product->image && file_exists(public_path($product->image)) ? $product->image : 'images/default-product.svg';
            @endphp
            <div class="product-image-section">
                <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" style="background: #1a1a3e; padding: 20px;">
            </div>

            <!-- Termékinformáció -->
            <div class="product-info-section">
                <h1>{{ $product->name }}</h1>

                <div class="product-meta">
                    <div class="meta-item">
                        <div class="meta-label">Kategória</div>
                        <div class="meta-value">{{ $product->category?->name ?? 'Ismeretlen' }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Eladók</div>
                        <div class="meta-value">{{ $product->offers->count() }} db ajánlat</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Platformok</div>
                        <div class="meta-value">{{ $product->offers->pluck('platform.name')->unique()->count() }} db</div>
                    </div>
                </div>

                <p style="color: #cccccc; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">{{ $product->description }}</p>

                @php
                    $offers = $product->offers()->with(['vendor', 'platform'])->get();
                    $minPrice = $offers->min('price') ?? 0;
                @endphp

                <div class="price-section">
                    @if($offers->isEmpty())
                        <div class="current-price" style="color: #ffcc00;">Nincs elérhető ajánlat</div>
                        <p style="color: #cccccc; font-size: 14px;">Kérjük, keress másik terméket vagy ellenőrizd később.</p>
                    @elseif($minPrice > 0)
                        <div class="current-price">{{ number_format($minPrice, 0, ',', ' ') }} Ft</div>
                        <p style="color: #cccccc; font-size: 14px;">Legalacsonyabb ár az eladóktól</p>
                    @else
                        <div class="current-price" style="color: #00ff99;">INGYENES</div>
                        <p style="color: #cccccc; font-size: 14px;">Ingyen elérhető szoftver</p>
                    @endif
                </div>

                <h3 style="color: #00ff99; margin-bottom: 20px; font-size: 20px;">Elérhető Ajánlatok</h3>
                @if($offers->count() > 0)
                    <table class="sellers-table">
                        <thead>
                            <tr>
                                <th>Eladó</th>
                                <th>Platform</th>
                                <th>Ár</th>
                                <th>Értékelés</th>
                                <th style="text-align: center;">Akció</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offers->sortBy('price') as $offer)
                                <tr>
                                    <td><span class="seller-badge">{{ $offer->vendor->name }}</span></td>
                                    <td><span style="color: #00ff99; font-size: 13px;">{{ $offer->platform->name }}</span></td>
                                    <td style="font-weight: bold; color: #00ff99;">
                                        @if($offer->price > 0)
                                            {{ number_format($offer->price, 0, ',', ' ') }} Ft
                                        @else
                                            <span style="color: #00ff99;">INGYENES</span>
                                        @endif
                                    </td>
                                    <td><span class="rating-stars">{{ number_format($offer->vendor->rating, 1) }}/5 ⭐</span></td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn-kosarba" data-product-id="{{ $product->id }}" data-offer-id="{{ $offer->id }}" data-price="{{ $offer->price }}" data-vendor="{{ $offer->vendor->name }}" onclick="addToCart(event)">Kosárba</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="background: rgba(255, 107, 107, 0.1); padding: 20px; border-radius: 8px; border-left: 3px solid #ff6b6b; color: #ff6b6b;">
                        ℹ️ Jelenleg nincsenek elérhető ajánlatok ehhez a termékhez.
                    </div>
                @endif
            </div>
        </div>

        <!-- REVIEWS SECTION -->
        <div class="reviews-section">
            <h2>💬 Vásárlói Vélemények</h2>

            <!-- Minta vélemények -->
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">👤 Csaba K.</span>
                    <span class="review-rating">5/5 ⭐</span>
                </div>
                <p class="review-text">Kiváló termék! Gyors szállítás és még jobb minőség. Csak ajánlani tudom mindenkinek!</p>
                <div class="review-date">2025. január 12.</div>
            </div>

            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">👤 Petra M.</span>
                    <span class="review-rating">4/5 ⭐</span>
                </div>
                <p class="review-text">Nagyon jó ár-érték arány. Az egyetlen hiba, hogy a szállítás egy kicsit lassabb volt.</p>
                <div class="review-date">2025. január 11.</div>
            </div>

            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">👤 János T.</span>
                    <span class="review-rating">5/5 ⭐</span>
                </div>
                <p class="review-text">Megérte minden fillér! Fantasztikus élmény volt, és a támogatás csapata szuperül segített.</p>
                <div class="review-date">2025. január 10.</div>
            </div>

            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">👤 Anna B.</span>
                    <span class="review-rating">4/5 ⭐</span>
                </div>
                <p class="review-text">Jó termék, hasznos információk. Legközelebb újra vásárolni fogok.</p>
                <div class="review-date">2025. január 9.</div>
            </div>
        </div>
    </div>

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

    // Add to cart - per offer basis
    function addToCart(event) {
        event.preventDefault();
        const button = event.target.closest('button');
        const offerId = button.getAttribute('data-offer-id');

        fetch(`/cart/add/${offerId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
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