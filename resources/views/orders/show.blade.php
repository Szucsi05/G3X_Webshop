<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout-success.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="order-detail-page">
    @include('partials.site-navbar')

    <div class="order-detail-container">
        <div class="order-detail-title-block">
            <h2 class="order-detail-title">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
        </div>
        <p class="order-detail-subtitle">Order date: {{ $order->created_at->format('Y. m. d. H:i') }}</p>

        <div class="success-info">
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $order->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total:</span>
                <span class="info-value">{{ number_format($order->total_amount, 0, ',', ' ') }} Ft</span>
            </div>
            <div class="info-row">
                <span class="info-label">Products:</span>
                <span class="info-value">{{ $order->items()->count() }}</span>
            </div>
            @php
                $paymentMethods = [
                    'card' => 'Bank Card',
                    'paypal' => 'PayPal',
                    'google_pay' => 'Google Pay',
                    'apple_pay' => 'Apple Pay'
                ];
            @endphp
            <div class="info-row">
                <span class="info-label">Payment Method:</span>
                <span class="info-value">{{ $paymentMethods[$order->payment_method] ?? 'Unknown' }}</span>
            </div>
        </div>

        <div class="licenses-section">
            <h2 class="order-detail-section-title">Ordered Products</h2>
            
            @if($order->items && $order->items->count() > 0)
                @foreach($order->items as $item)
                    <div class="license-card">
                        <div class="license-product">{{ $item->productOffer->product->name }}</div>
                        <div class="license-seller">Seller: {{ $item->productOffer->vendor->name }}</div>
                        <div class="info-row order-detail-product-meta">
                            <span>Price: <strong>{{ number_format($item->price_at_purchase, 0, ',', ' ') }} Ft</strong></span>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="order-detail-empty-text">There are no products in this order.</p>
            @endif
        </div>

        <div class="licenses-section">
            <h2 class="order-detail-section-title">Activation Keys</h2>
            
            @if($order->items && $order->items->count() > 0)
                @foreach($order->items as $item)
                    @if($item->license_key)
                        <div class="license-card">
                            <div class="license-product">{{ $item->productOffer->product->name }}</div>
                            <div class="license-seller">Seller: {{ $item->productOffer->vendor->name }}</div>
                            <div class="license-key">
                                <span id="key-{{ $item->id }}">{{ $item->license_key }}</span>
                                <button type="button" class="copy-btn" onclick="copyToClipboard('key-{{ $item->id }}', event)">Copy</button>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <p class="order-detail-empty-text">No activation keys are available.</p>
            @endif
        </div>

        <div class="order-detail-actions">
            <a href="{{ route('home') }}" class="order-detail-primary">Back to Home</a>
            <a href="{{ route('orders.index') }}" class="order-detail-secondary">Back to My Orders</a>
        </div>
    </div>

    @include('partials.site-sidebar')

    @include('partials.site-footer')

    <script>
    function fallbackCopyText(text) {
        const helper = document.createElement('textarea');
        helper.value = text;
        helper.setAttribute('readonly', '');
        helper.style.position = 'fixed';
        helper.style.opacity = '0';
        helper.style.pointerEvents = 'none';
        document.body.appendChild(helper);
        helper.focus();
        helper.select();

        let copied = false;

        try {
            copied = document.execCommand('copy');
        } finally {
            document.body.removeChild(helper);
        }

        return copied;
    }

    function showCopySuccess(button) {
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('copied');

        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('copied');
        }, 2000);
    }

    function copyToClipboard(elementId, event) {
        const element = document.getElementById(elementId);
        const button = event.currentTarget;

        if (!element) {
            alert('Copy failed.');
            return;
        }

        const text = element.textContent.trim();

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showCopySuccess(button);
            }).catch(() => {
                if (fallbackCopyText(text)) {
                    showCopySuccess(button);
                    return;
                }

                alert('Copy failed.');
            });
            return;
        }

        if (fallbackCopyText(text)) {
            showCopySuccess(button);
            return;
        }

        alert('Copy failed.');
    }

    </script>
    @include('partials.site-scripts')
</body>
</html>
