<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="orders-page">
    @include('partials.site-navbar')

    @include('partials.site-sidebar')

    <div class="orders-container">
        @if(!$orders->isEmpty())
            <div class="orders-header">
                <h1 class="orders-title">My Orders</h1>
            </div>

            <div class="orders-tabs">
                <a href="{{ route('settings.show') }}" class="orders-tab">Settings</a>
                <a href="{{ route('orders.index') }}" class="orders-tab active">My Orders</a>
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="orders-empty">
                <img src="{{ asset('icons/empty_cart.png') }}" alt="No orders" class="orders-empty-icon">
                <h3 class="orders-empty-title">You do not have any orders yet</h3>
                <a href="{{ route('home') }}" class="orders-empty-link"><img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-18"> Back to Home</a>
            </div>
        @else
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order->id) }}" class="orders-link">
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-number">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>

                        <div class="order-info">
                            <div>
                                <div class="order-info-label">Email</div>
                                <div class="order-info-item">{{ $order->email }}</div>
                            </div>
                            <div>
                                <div class="order-info-label">Payment Method</div>
                                <div class="order-info-item">
                                    @php
                                        $methods = [
                                            'card' => 'Bank Card',
                                            'paypal' => 'PayPal',
                                            'google_pay' => 'Google Pay',
                                            'apple_pay' => 'Apple Pay'
                                        ];
                                    @endphp
                                    {{ $methods[$order->payment_method] ?? $order->payment_method }}
                                </div>
                            </div>
                        </div>

                        <div class="order-summary-row">
                            <div>
                                <div class="order-info-label">Products</div>
                                <div class="order-info-item">{{ count($order->items) ?? 0 }}</div>
                            </div>
                            <div class="order-amount">{{ number_format($order->total_amount, 0, ',', ' ') }} Ft</div>
                        </div>
                    </div>
                </a>
            @endforeach

            <a href="{{ route('home') }}" class="orders-back-link orders-back-link--bottom"><img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-18"> Back to Home</a>
        @endif
    </div>

    @include('partials.site-footer', ['footerVariant' => 'legal'])
    @include('partials.site-scripts')
</body>
</html>
