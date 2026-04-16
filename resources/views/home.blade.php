<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>G3X - Digital Marketplace</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @include('partials.site-navbar')
    @include('partials.site-sidebar')

    @php
        $categoryTranslations = [
            'Játék' => 'Games',
            'Szoftver' => 'Software',
            'Előfizetés' => 'Subscriptions',
        ];
    @endphp

    <!-- HERO -->
    <header class="hero home-hero">
        <div class="hero-orb-right"></div>
        <div class="hero-orb-left"></div>
        <div class="hero-content">
            <h1 class="hero-title">The Best Digital Marketplace</h1>
            <p class="hero-copy">Compare prices, find the best deal, and shop with confidence. 100+ offers available.</p>
            <div class="hero-actions">
                <a href="{{ route('filter.show') }}" class="hero-primary">Start Shopping</a>
                <a href="#popular" class="hero-secondary">Explore</a>
            </div>
        </div>
    </header>




    <!-- POPULAR GAMES -->
    <section class="cards-section" id="popular">
        <h2 class="home-section-title">Most Popular Games <img src="{{ asset('icons/popular_games.png') }}" alt="Most Popular Games" class="icon-48 icon-no-shrink"></h2>
        <div class="products-grid">
            @foreach($popular as $product)
                @php
                    $imagePath = $product->image && file_exists(public_path($product->image)) ? $product->image : 'images/default-product.svg';
                    $offers = $product->offers()->orderBy('price')->get();
                    $minPrice = $offers->first()?->price;
                @endphp
                <div class="product-card home-product-card" onclick="goToProduct({{ $product->id }})">
                    <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-image home-product-image">
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p class="product-description">{{ $product->offers->count() }} offers - {{ $categoryTranslations[$product->category?->name] ?? ($product->category?->name ?? 'Unknown') }}</p>
                        <div class="product-footer">
                            <span class="product-price">
                                @if($offers->isEmpty())
                                    No offers available
                                @elseif($minPrice > 0)
                                    {{ number_format($minPrice, 0, ',', '.') . ' Ft' }}
                                @else
                                    FREE
                                @endif
                            </span>
                            <button type="button" class="btn-add-cart" onclick="event.stopPropagation(); goToProduct({{ $product->id }})">Details</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- BEST SELLING -->
    <section class="cards-section">
        <h2 class="home-section-title"><img src="{{ asset('icons/best_selling_games.png') }}" alt="Best Selling" class="icon-48 icon-no-shrink"> Best Selling</h2>
        <div class="products-grid">
            @foreach($bestSelling as $product)
                @php
                    $imagePath = $product->image && file_exists(public_path($product->image)) ? $product->image : 'images/default-product.svg';
                    $offers = $product->offers()->orderBy('price')->get();
                    $minPrice = $offers->first()?->price;
                @endphp
                <div class="product-card home-product-card" onclick="goToProduct({{ $product->id }})">
                    <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-image home-product-image">
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p class="product-description">{{ $product->offers->count() }} offers - {{ $categoryTranslations[$product->category?->name] ?? ($product->category?->name ?? 'Unknown') }}</p>
                        <div class="product-footer">
                            <span class="product-price">
                                @if($offers->isEmpty())
                                    No offers available
                                @elseif($minPrice > 0)
                                    {{ number_format($minPrice, 0, ',', '.') . ' Ft' }}
                                @else
                                    FREE
                                @endif
                            </span>
                            <button type="button" class="btn-add-cart" onclick="event.stopPropagation(); goToProduct({{ $product->id }})">Details</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- CONSOLE GAMES -->
    <section class="cards-section" id="console-games">
        <h2 class="home-section-title">Console Games <img src="{{ asset('icons/console_games.png') }}" alt="Console Games" class="icon-48 icon-no-shrink"></h2>
        <div class="products-grid">
            @foreach($consoleGames as $product)
                @php
                    $imagePath = $product->image && file_exists(public_path($product->image)) ? $product->image : 'images/default-product.svg';
                    $offers = $product->offers()->orderBy('price')->get();
                    $minPrice = $offers->first()?->price;
                @endphp
                <div class="product-card home-product-card" onclick="goToProduct({{ $product->id }})">
                    <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-image home-product-image">
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p class="product-description">{{ $product->offers->count() }} offers - {{ $categoryTranslations[$product->category?->name] ?? ($product->category?->name ?? 'Unknown') }}</p>
                        <div class="product-footer">
                            <span class="product-price">
                                @if($offers->isEmpty())
                                    No offers available
                                @elseif($minPrice > 0)
                                    {{ number_format($minPrice, 0, ',', '.') . ' Ft' }}
                                @else
                                    FREE
                                @endif
                            </span>
                            <button type="button" class="btn-add-cart" onclick="event.stopPropagation(); goToProduct({{ $product->id }})">Details</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="view-all-wrap">
        <a href="{{ route('filter.show') }}" class="view-all-btn">
            View All Products ({{ \App\Models\Product::count() }})
        </a>
    </div>

    @include('partials.site-footer')

    <!-- JS -->
    <script>
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
    @include('partials.site-scripts')
</body>
</html>
