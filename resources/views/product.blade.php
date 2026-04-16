<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="product-page">
    @include('partials.site-navbar', ['cartLinkOnclick' => "localStorage.removeItem('filterState');"])
    @include('partials.site-sidebar')

    @php
        $categoryTranslations = [
            'Játék' => 'Games',
            'Szoftver' => 'Software',
            'Előfizetés' => 'Subscriptions',
        ];
    @endphp

    <div class="product-container">
        <a href="#" id="back-link" class="product-back-link"><img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-18"> <span class="back-link-label">Back</span></a>
        <script>
            const lastSearchUrl = localStorage.getItem('lastSearchUrl');
            const lastFilterUrl = localStorage.getItem('lastFilterUrl');
            const backLink = document.getElementById('back-link');

            if (lastSearchUrl) {
                backLink.href = lastSearchUrl;
                backLink.innerHTML = '<img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-18"> <span class="back-link-label">Back to Search</span>';
            } else if (lastFilterUrl) {
                backLink.href = lastFilterUrl;
                backLink.innerHTML = '<img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-18"> <span class="back-link-label">Back to Filter</span>';
            } else {
                backLink.href = '{{ route('home') }}';
                backLink.innerHTML = '<img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-18"> <span class="back-link-label">Back to Home</span>';
            }
        </script>

        <div class="product-header">
            @php
                $imagePath = $product->image && file_exists(public_path($product->image)) ? $product->image : 'images/default-product.svg';
            @endphp
            <div class="product-image-section">
                <div class="product-image-frame">
                    <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}">
                </div>
            </div>


            <div class="product-info-section">
                <h1>{{ $product->name }}</h1>

                <div class="product-meta">
                    <div class="meta-item">
                        <div class="meta-label">Category</div>
                        <div class="meta-value">{{ $categoryTranslations[$product->category?->name] ?? ($product->category?->name ?? 'Unknown') }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Sellers</div>
                        <div class="meta-value">{{ $product->offers->count() }} offers</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Platforms</div>
                        <div class="meta-value">{{ $product->offers->pluck('platform.name')->unique()->count() }}</div>
                    </div>
                </div>

                <p class="product-description-copy">{{ $product->description }}</p>

                @php
                    $offers = $product->offers()->with(['vendor', 'platform'])->get();
                    $minPrice = $offers->min('price') ?? 0;
                @endphp

                <div class="price-section">
                    @if($offers->isEmpty())
                        <div class="current-price price-warning">No offers available</div>
                        <p class="price-muted">Please search for another product or check back later.</p>
                    @elseif($minPrice > 0)
                        <div class="current-price">{{ number_format($minPrice, 0, ',', ' ') }} Ft</div>
                        <p class="price-muted">Lowest price from sellers</p>
                    @else
                        <div class="current-price">FREE</div>
                        <p class="price-muted">Free software available</p>
                    @endif
                </div>

                <h3 class="offers-title">Available Offers</h3>
                @if($offers->count() > 0)
                    <table class="sellers-table">
                        <thead>
                            <tr>
                                <th>Seller</th>
                                <th>Platform</th>
                                <th>Price</th>
                                <th>Rating</th>
                                <th class="product-action-header">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offers->sortBy('price') as $offer)
                                <tr>
                                    <td><span class="seller-badge">{{ $offer->vendor->name }}</span></td>
                                    <td><span class="product-platform">{{ $offer->platform->name }}</span></td>
                                    <td class="product-price-cell">
                                        @if($offer->price > 0)
                                            {{ number_format($offer->price, 0, ',', ' ') }} Ft
                                        @else
                                            <span>FREE</span>
                                        @endif
                                    </td>
                                    <td><span class="rating-stars">{{ number_format($offer->vendor->rating, 1) }}/5</span></td>
                                    <td class="product-action-cell">
                                        <button type="button" class="btn-kosarba" data-product-id="{{ $product->id }}" data-offer-id="{{ $offer->id }}" data-price="{{ $offer->price }}" data-vendor="{{ $offer->vendor->name }}" onclick="addToCart(event)">Add to Cart</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-offers-box">
                        There are currently no available offers for this product.
                    </div>
                @endif
            </div>
        </div>

        <div class="reviews-section">
            <h2>Customer Reviews</h2>

            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">Chris K.</span>
                    <span class="review-rating">5/5</span>
                </div>
                <p class="review-text">Excellent product. Fast delivery and even better quality. I can only recommend it.</p>
                <div class="review-date">January 12, 2025</div>
            </div>

            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">Petra M.</span>
                    <span class="review-rating">4/5</span>
                </div>
                <p class="review-text">Very good value for money. The only downside was that delivery was a little slower.</p>
                <div class="review-date">January 11, 2025</div>
            </div>

            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">John T.</span>
                    <span class="review-rating">5/5</span>
                </div>
                <p class="review-text">Worth every penny. It was a fantastic experience and the support team was extremely helpful.</p>
                <div class="review-date">January 10, 2025</div>
            </div>

            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">Anna B.</span>
                    <span class="review-rating">4/5</span>
                </div>
                <p class="review-text">Good product and useful information. I will buy here again next time.</p>
                <div class="review-date">January 9, 2025</div>
            </div>
        </div>
    </div>


    <script>
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
