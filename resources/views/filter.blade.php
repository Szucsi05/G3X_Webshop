<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/filter.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @include('partials.site-navbar')
    @include('partials.site-sidebar', ['sidebarSlugStyle' => 'underscore'])

    @php
        $categoryTranslations = [
            'Játék' => 'Games',
            'Szoftver' => 'Software',
            'Előfizetés' => 'Subscriptions',
        ];

        $translatedCategoryLabel = $categoryLabel ?? 'All Products';
        foreach ($categoryTranslations as $source => $target) {
            $translatedCategoryLabel = str_replace($source, $target, $translatedCategoryLabel);
        }
    @endphp

    <div class="filter-container">
        <div class="filter-header">
            <h1>{{ $translatedCategoryLabel }}</h1>
            <span class="results-count">
                {{ count($products) }} products
            </span>
        </div>

        <!-- Category Filter Buttons -->
        <div class="category-filter">
            <a href="{{ route('filter.show') }}" class="category-btn {{ !$category ? 'active' : '' }}">
                <img src="{{ asset('icons/all_category.png') }}" alt="All" class="icon-18"> All
            </a>
            <a href="{{ route('filter.show', 'pc_games') }}" class="category-btn {{ $category === 'pc_games' ? 'active' : '' }}">
                <img src="{{ asset('icons/pc_category.png') }}" alt="PC Games" class="icon-18"> PC Games
            </a>
            <a href="{{ route('filter.show', 'console_games') }}" class="category-btn {{ $category === 'console_games' ? 'active' : '' }}">
                <img src="{{ asset('icons/console_category.png') }}" alt="Console Games" class="icon-18"> Console Games
            </a>
            <a href="{{ route('filter.show', 'game_subscriptions') }}" class="category-btn {{ $category === 'game_subscriptions' ? 'active' : '' }}">
                <img src="{{ asset('icons/subcriptions_category.png') }}" alt="Subscriptions" class="icon-18"> Subscriptions
            </a>
            <a href="{{ route('filter.show', 'software') }}" class="category-btn {{ $category === 'software' ? 'active' : '' }}">
                <img src="{{ asset('icons/software_category.png') }}" alt="Software" class="icon-18"> Software
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
                    <div class="product-card filter-product-card" onclick="goToProduct({{ $product->id }})">
                        <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-image filter-product-image">
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
            @else
                <div class="empty-state">
                    <img src="{{ asset('icons/empty_cart.png') }}" alt="No results" class="empty-state-icon">
                    <h2>No Results</h2>
                    <p>There are no products in this category</p>
                </div>
            @endif
        </div>
    </div>

    <script>
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
    @include('partials.site-footer')
    @include('partials.site-scripts')
</body>
</html>
