<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @include('partials.site-navbar', ['searchValue' => $query ?? ''])
    @include('partials.site-sidebar')

    @php
        $categoryTranslations = [
            'Játék' => 'Games',
            'Szoftver' => 'Software',
            'Előfizetés' => 'Subscriptions',
        ];
    @endphp

    <header class="hero search-page-hero">
        <h1>Search Results</h1>
        @if($query)
            <p>Search: "{{ $query }}"</p>
        @else
            <p>All products</p>
        @endif
        @if($products->total() > 0)
            <p>{{ $products->total() }} products</p>
        @endif
    </header>

    <section class="cards-section">
        @if($products->isEmpty())
            <p>No results found for your search.</p>
        @else
            <div class="products-grid">
                @foreach($products as $product)
                    @php
                        $imagePath = $product->image && file_exists(public_path($product->image)) ? $product->image : 'images/default-product.svg';
                        $minPrice = $product->offers_min_price;
                    @endphp
                    <div class="product-card search-results-card" onclick="localStorage.setItem('lastSearchUrl', window.location.href); localStorage.removeItem('lastFilterUrl'); window.location.href='{{ route('product.show', $product->id) }}'">
                        <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-image search-results-image">
                        <div class="product-info">
                            <h3 class="search-results-title">{{ $product->name }}</h3>
                            <p class="product-description">{{ $product->offers_count }} offers - {{ $categoryTranslations[$product->category?->name] ?? ($product->category?->name ?? 'Unknown') }}</p>
                            <div class="product-footer">
                                <span class="product-price">
                                    @if($product->offers_count === 0)
                                        No offers available
                                    @elseif($minPrice > 0)
                                        {{ number_format($minPrice, 0, ',', ' ') . ' Ft from' }}
                                    @else
                                        FREE
                                    @endif
                                </span>
                                <button type="button" onclick="event.stopPropagation();" class="btn-add-cart">Details</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('partials.pagination-controls', ['paginator' => $products])
        @endif
    </section>

    @include('partials.site-footer')

    <!-- JS -->
    <script>
    function addToCart(productId) {
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
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

    @if(session('success'))
        window.addEventListener('load', () => {
            alert("{{ session('success') }}");
        });
    @endif
    @if(session('error'))
        window.addEventListener('load', () => {
            alert("{{ session('error') }}");
        });
    @endif
    </script>
    @include('partials.site-scripts')
</body>
</html>