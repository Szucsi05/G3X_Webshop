<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Method - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout-payment-method.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @include('partials.site-navbar')

    <div class="payment-method-container">


        <form id="payment-form" method="POST" action="{{ route('checkout') }}">
            @csrf

            <div class="payment-methods-grid">
                <div class="payment-method-card" onclick="selectPaymentMethod('card', this)">
                    <div class="icon"><img src="{{ asset('icons/green_card.png') }}" alt="Bank card" class="icon-48 icon-contain"></div>
                    <div class="name">Bank Card</div>
                    <div class="desc">VISA, Mastercard, Maestro</div>
                </div>

                <div class="payment-method-card" onclick="selectPaymentMethod('paypal', this)">
                    <div class="icon"><img src="{{ asset('icons/paypal.png') }}" alt="PayPal" class="icon-48 icon-contain"></div>
                    <div class="name">PayPal</div>
                    <div class="desc">paypal</div>
                </div>

                <div class="payment-method-card payment-method-card--large-icon" onclick="selectPaymentMethod('google_pay', this)">
                    <div class="icon"><img src="{{ asset('icons/google_pay.png') }}" alt="Google Pay" class="icon-72 icon-contain"></div>
                    <div class="name">Google Pay</div>
                    <div class="desc">google pay</div>
                </div>

                <div class="payment-method-card payment-method-card--large-icon" onclick="selectPaymentMethod('apple_pay', this)">
                    <div class="icon"><img src="{{ asset('icons/apple_pay.png') }}" alt="Apple Pay" class="icon-72 icon-contain"></div>
                    <div class="name">Apple Pay</div>
                    <div class="desc">apple pay</div>
                </div>
            </div>

            <input type="hidden" id="payment_method" name="payment_method" value="">

            <button type="submit" class="btn-continue payment-method-submit" id="continue-btn" disabled>
                Continue to Payment
                <img src="{{ asset('icons/black_right_arrow.png') }}" alt="Continue" class="icon-22">
            </button>
        </form>

        <a href="{{ route('checkout.details') }}" class="back-link"><img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-22"> Back to Details</a>
    </div>

    @include('partials.site-sidebar')

    <!-- JS -->
    <script>
    function selectPaymentMethod(method, element) {
        // Remove active class from all cards
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Add active class to selected card
        element.classList.add('active');
        
        // Set hidden input
        document.getElementById('payment_method').value = method;
        
        // Enable button
        document.getElementById('continue-btn').disabled = false;
    }
    </script>
    @include('partials.site-footer')
    @include('partials.site-scripts')
</body>
</html>
