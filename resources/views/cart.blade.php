<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Kosár - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 20px 60px 20px;
            background: #0b1c2c;
            color: #fff;
        }

        .cart-item-card {
            background: rgba(60, 45, 92, 0.5);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #5c4d7c;
            transition: all 0.3s ease;
        }

        .cart-item-card:hover {
            background: rgba(60, 45, 92, 0.8);
            border-color: #00ff99;
            transform: translateY(-2px);
        }

        .qty-btn {
            transition: all 0.3s ease;
        }

        .qty-btn:hover {
            transform: scale(1.05);
        }

        /* Letiltja az input number spinner-t */
        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .qty-input[type=number] {
            -moz-appearance: textfield;
        }

        .empty-cart-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 500px;
            padding: 40px;
            background: linear-gradient(135deg, #2c1e4a, #3b2d5c);
            border-radius: 10px;
            border: 1px solid #5c4d7c;
        }

        .empty-cart-content {
            text-align: center;
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
            display: block;
            animation: none;
        }

        .btn-continue-shopping {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #00cc88, #009966);
            color: #000;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s ease;
            font-size: 1.05em;
        }

        .btn-continue-shopping:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 200, 136, 0.3);
        }

        @media (max-width: 768px) {
            .cart-container {
                padding: 120px 15px 40px 15px;
            }

            [style*="grid-template-columns: 1.5fr 1fr"] {
                grid-template-columns: 1fr !important;
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

    <div class="cart-container">
        <h1 style="color: #00ff99; margin-bottom: 30px;">Kosár</h1>
        @if(empty($cart))
            <div class="empty-cart-container">
                <div class="empty-cart-content">
                    <div class="empty-icon">📦</div>
                    <h2 style="color: #fff; margin-bottom: 10px;">Kosarad üres</h2>
                    <p style="color: #7d6b9f; margin-bottom: 30px; font-size: 1.1em;">Úgy tűnik, még nem választottál terméket</p>
                    <a href="{{ route('home') }}" class="btn-continue-shopping">← Vissza a főoldalra</a>
                </div>
            </div>
        @else
            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; margin-bottom: 30px;">
                <!-- Kosár tartalma -->
                <div>
                    <div style="background: linear-gradient(135deg, #2c1e4a, #3b2d5c); padding: 25px; border-radius: 10px; border: 1px solid #5c4d7c;">
                        <h3 style="color: #00cc88; margin-bottom: 20px; font-size: 1.3em;">Kosár tartalma</h3>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                            <div class="cart-item-card" data-item-id="{{ $id }}">
                                <div style="display: flex; gap: 15px;">
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 90px; height: 90px; border-radius: 8px; object-fit: cover; flex-shrink: 0;">
                                    <div style="flex: 1;">
                                        <h4 style="margin: 0; color: #00ff99; margin-bottom: 8px;">{{ $item['name'] }}</h4>
                                        <p style="color: #7d6b9f; margin: 5px 0; font-size: 0.95em;">Eladó: <span style="color: #fff;">{{ $item['seller'] ?? 'N/A' }}</span></p>
                                        <div style="display: flex; align-items: center; gap: 12px; margin-top: 12px;">
                                            <form class="update-form" method="POST" action="{{ route('cart.update', $id) }}" style="display: flex; align-items: center; gap: 8px;">
                                                @csrf
                                                @method('POST')
                                                <button type="button" class="qty-btn qty-minus" data-id="{{ $id }}" style="background: #ff4444; color: #fff; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 1.1em;">−</button>
                                                <input type="number" name="quantity" class="qty-input" value="{{ $item['quantity'] }}" min="1" style="width: 50px; padding: 6px; background: #1a0f2e; color: #fff; border: 1px solid #5c4d7c; border-radius: 4px; text-align: center; font-weight: bold;" data-price="{{ $item['price'] }}">
                                                <button type="button" class="qty-btn qty-plus" data-id="{{ $id }}" style="background: #00cc88; color: #000; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 1.1em;">+</button>
                                            </form>
                                            <span style="color: #00cc88; font-weight: bold; margin-left: auto; white-space: nowrap;">{{ number_format($subtotal, 0, ',', ' ') }} Ft</span>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('cart.remove', $id) }}" class="remove-form" style="margin-top: 12px;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" style="width: 100%; background: rgba(255, 68, 68, 0.2); color: #ff4444; border: 1px solid #ff4444; padding: 8px; border-radius: 4px; cursor: pointer; font-weight: bold; transition: all 0.3s ease;">🗑️ Eltávolít</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Összegzés és fizetés -->
                <div>
                    <div style="background: linear-gradient(135deg, #00cc88, #009966); padding: 25px; border-radius: 10px; box-shadow: 0 8px 32px rgba(0, 200, 136, 0.2);">
                        <h3 style="color: #000; margin-bottom: 20px; font-size: 1.3em;">Végösszeg</h3>
                        
                        <div style="background: rgba(0, 0, 0, 0.1); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: #000;">Termékek:</span>
                                <span style="color: #000; font-weight: bold;" id="subtotal-price">{{ number_format($total, 0, ',', ' ') }} Ft</span>
                            </div>
                            <div style="border-top: 1px solid rgba(0, 0, 0, 0.2); padding-top: 10px; display: flex; justify-content: space-between;">
                                <span style="color: #000; font-size: 1.1em; font-weight: bold;">Összesen:</span>
                                <span style="color: #000; font-size: 1.2em; font-weight: bold;" id="total-price">{{ number_format($total, 0, ',', ' ') }} Ft</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('checkout.details') }}">
                            @csrf
                            <button type="submit" style="width: 100%; padding: 14px; background: #000; color: #00cc88; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 1.1em; transition: all 0.3s ease;">Fizetéshez →</button>
                        </form>

                        <a href="{{ route('home') }}" style="display: block; margin-top: 12px; text-align: center; color: #000; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">← Vissza a vásárláshoz</a>
                    </div>
                </div>
            </div>
        @endif
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

    document.addEventListener('DOMContentLoaded', function() {
        setupUserDropdownDelay();
        // Plus and minus buttons for quantity
        const qtyPlusButtons = document.querySelectorAll('.qty-plus');
        const qtyMinusButtons = document.querySelectorAll('.qty-minus');

        qtyPlusButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = btn.closest('.update-form');
                const input = form.querySelector('.qty-input');
                input.value = parseInt(input.value) + 1;
                submitUpdateForm(form);
            });
        });

        qtyMinusButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = btn.closest('.update-form');
                const input = form.querySelector('.qty-input');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    submitUpdateForm(form);
                }
            });
        });

        // Remove form handler
        const removeForms = document.querySelectorAll('.remove-form');
        removeForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const itemElement = form.closest('.cart-item-card');
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove item from DOM with smooth animation
                        itemElement.style.opacity = '0';
                        itemElement.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => {
                            itemElement.remove();
                            updateCartBadge(data.cart_count);
                            updateTotalPrice(data.total);
                            // Refresh page if cart is empty
                            const remainingItems = document.querySelectorAll('.cart-item-card');
                            if (remainingItems.length === 0) {
                                setTimeout(() => location.reload(), 300);
                            }
                        }, 300);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });

    function submitUpdateForm(form) {
        const formData = new FormData(form);
        const input = form.querySelector('.qty-input');
        const price = parseFloat(input.dataset.price);
        const quantity = parseInt(input.value);
        const itemCard = form.closest('.cart-item-card');
        const priceSpan = itemCard.querySelector('span[style*="color: #00cc88"]');
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Frissítsd az egyedi tétel árát
                const subtotal = price * quantity;
                if (priceSpan) {
                    priceSpan.textContent = number_format(subtotal) + ' Ft';
                }
                
                // Frissítsd az összes ár
                updateCartBadge(data.cart_count);
                updateTotalPrice(data.total);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function number_format(num) {
        return Math.floor(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    function updateCartBadge(count) {
        const badge = document.getElementById('cart-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline';
                badge.style.animation = 'none';
                setTimeout(() => {
                    badge.style.animation = 'badge-pulse 0.3s ease-in-out';
                }, 10);
            } else {
                badge.style.display = 'none';
            }
        }
    }

    function updateTotalPrice(total) {
        const totalElement = document.getElementById('total-price');
        const subtotalElement = document.getElementById('subtotal-price');
        if (totalElement) {
            const formattedTotal = number_format(total) + ' Ft';
            totalElement.textContent = formattedTotal;
            if (subtotalElement) {
                subtotalElement.textContent = formattedTotal;
            }
            totalElement.style.animation = 'none';
            setTimeout(() => {
                totalElement.style.animation = 'badge-pulse 0.3s ease-in-out';
            }, 10);
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