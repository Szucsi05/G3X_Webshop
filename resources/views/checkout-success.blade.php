<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Successful Purchase - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout-success.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="checkout-success-page">
    @include('partials.site-navbar')

    <div class="success-container">
        <div class="success-title-block">
            <h2 class="success-title">Purchase Successful!</h2>
        </div>
        <p class="success-subtitle">Thank you for your order. You can find your activation keys below.</p>

        <div class="success-info">
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Price:</span>
                <span class="info-value">{{ number_format($total, 0, ',', ' ') }} Ft</span>
            </div>
            <div class="info-row">
                <span class="info-label">Number of Products:</span>
                <span class="info-value">{{ count($licenses) }}</span>
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
                <span class="info-value">{{ $paymentMethods[$payment_method] ?? 'Unknown' }}</span>
            </div>
        </div>

        <div class="licenses-section">
            <h2 class="licenses-section-title">Your Activation Keys</h2>
            
            @foreach($licenses as $index => $license)
                <div class="license-card">
                    <div class="license-product">{{ $license['name'] }}</div>
                    <div class="license-seller">Seller: {{ $license['seller'] }}</div>
                    <div class="license-key">
                        <span id="key-{{ $index }}">{{ $license['key'] }}</span>
                        <button type="button" class="copy-btn" onclick="copyToClipboard('key-{{ $index }}', event)">Copy</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="email-note">
            You receive the activation keys here immediately, and they are also saved to your order so you can open them again later in your order history.
        </div>

        <div class="action-buttons">
            <a href="{{ route('home') }}" class="success-action-primary">Back to Home</a>
            <a href="{{ route('cart.index') }}" class="success-action-secondary">Back to Cart</a>
        </div>
    </div>

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
    @include('partials.site-sidebar')
    @include('partials.site-scripts')

</body>
</html>
