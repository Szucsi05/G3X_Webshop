<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @include('partials.site-navbar')
    @include('partials.site-sidebar')

    <div class="cart-container">
        <h1 class="cart-title"><img src="{{ asset('icons/green_cart.png') }}" alt="Cart" class="icon-32"> Cart</h1>
        @if(empty($cart))
            <div class="empty-cart-container">
                <div class="empty-cart-content">
                    <img src="{{ asset('icons/empty_cart.png') }}" alt="Empty cart" class="icon-80 mb-20">
                    <h2 class="empty-cart-title">Your cart is empty</h2>
                    <p class="empty-cart-copy">It looks like you have not selected any products yet</p>
                    <a href="{{ route('home') }}" class="btn-continue-shopping"><img src="{{ asset('icons/black_left_arrow.png') }}" alt="Back" class="icon-18"> Back to Home</a>
                </div>
            </div>
        @else
            <div class="cart-grid">
                <div>
                    <div class="cart-panel">
                        <h3><img src="{{ asset('icons/green_orders.png') }}" alt="Cart items" class="icon-24"> Cart Items</h3>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                            <div class="cart-item-card" data-item-id="{{ $id }}">
                                <div class="cart-item-layout">
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="cart-item-image">
                                    <div class="cart-item-info">
                                        <h4 class="cart-item-name">{{ $item['name'] }}</h4>
                                        <p class="cart-item-meta">Seller: <span>{{ $item['seller'] ?? 'N/A' }}</span></p>
                                        <div class="cart-item-actions">
                                            <form class="update-form" method="POST" action="{{ route('cart.update', $id) }}">
                                                @csrf
                                                @method('POST')
                                                <button type="button" class="qty-btn qty-minus" data-id="{{ $id }}">-</button>
                                                <input type="number" name="quantity" class="qty-input" value="{{ $item['quantity'] }}" min="1" data-price="{{ $item['price'] }}">
                                                <button type="button" class="qty-btn qty-plus" data-id="{{ $id }}">+</button>
                                            </form>
                                            <span class="cart-item-price">{{ number_format($subtotal, 0, ',', ' ') }} Ft</span>
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('cart.remove', $id) }}" class="remove-form">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="remove-btn">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="cart-summary">
                        <h3><img src="{{ asset('icons/black_card.png') }}" alt="Order total" class="icon-24"> Order Total</h3>

                        <div class="cart-summary-box">
                            <div class="cart-summary-row">
                                <span class="cart-summary-text">Products:</span>
                                <span id="subtotal-price"><strong>{{ number_format($total, 0, ',', ' ') }} Ft</strong></span>
                            </div>
                            <div class="cart-summary-total">
                                <span><strong>Total:</strong></span>
                                <span id="total-price"><strong>{{ number_format($total, 0, ',', ' ') }} Ft</strong></span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('checkout.details') }}">
                            @csrf
                            <button type="submit" class="checkout-btn"><span>Proceed to Checkout</span><img src="{{ asset('icons/green_right_arrow.png') }}" alt="Continue" class="icon-18"></button>
                        </form>

                        <a href="{{ route('home') }}" class="cart-back-link"><img src="{{ asset('icons/black_left_arrow.png') }}" alt="Back" class="icon-18"> Continue Shopping</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
                        itemElement.style.opacity = '0';
                        itemElement.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => {
                            itemElement.remove();
                            updateCartBadge(data.cart_count);
                            updateTotalPrice(data.total);
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
        const priceSpan = itemCard.querySelector('.cart-item-price');

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
                const subtotal = price * quantity;
                if (priceSpan) {
                    priceSpan.textContent = number_format(subtotal) + ' Ft';
                }

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
    @include('partials.site-footer')
    @include('partials.site-scripts')
</body>
</html>
