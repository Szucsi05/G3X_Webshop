<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @include('partials.site-navbar')

    <div class="checkout-container">
        <div class="checkout-header">
            <h1 class="page-heading">Payment Details</h1>
        </div>

        <div class="checkout-content">
            <div class="checkout-left">
                <div class="payment-form">
                    <form id="payment-form" method="POST" action="{{ route('checkout.process') }}">
                        @csrf

                        <input type="hidden" name="payment_method" value="{{ $payment_method }}">

                        @if($payment_method === 'card')
                            <div id="card-form" class="checkout-card-form">
                                <div class="form-group">
                                    <label>Cardholder Name</label>
                                    <input type="text" name="card_name" placeholder="John Doe" required>
                                </div>

                                <div class="form-group">
                                    <label>Card Number (16 digits)</label>
                                    <input type="text" name="card_number" placeholder="1234 5678 9012 3456" pattern="\d{4} \d{4} \d{4} \d{4}" maxlength="19" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Expiry Date (MM/YY)</label>
                                        <input type="text" name="card_expiry" placeholder="MM/YY" pattern="\d{2}/\d{2}" maxlength="5" required>
                                    </div>
                                    <div class="form-group">
                                        <label>CVC (3 digits)</label>
                                        <input type="password" name="card_cvc" placeholder="•••" pattern="\d{3}" maxlength="3" required>
                                    </div>
                                </div>
                            </div>
                        @elseif($payment_method === 'paypal')
                            <div class="form-group">
                                <label>PayPal Email Address</label>
                                <input type="email" name="paypal_email" placeholder="your@email.com" required>
                            </div>
                            <div class="form-group">
                                <label>PayPal Password</label>
                                <input type="password" name="paypal_password" placeholder="Password" required>
                            </div>
                            <p class="checkout-note">You will be redirected to PayPal to confirm your details.</p>
                        @elseif($payment_method === 'google_pay')
                            <div class="form-group">
                                <label>Google Account Email</label>
                                <input type="email" name="google_email" placeholder="your@gmail.com" required>
                            </div>
                            <div class="form-group">
                                <label>Google Account Password</label>
                                <input type="password" name="google_password" placeholder="Password" required>
                            </div>
                            <p class="checkout-note">Your Google Pay account will be used for payment.</p>
                        @elseif($payment_method === 'apple_pay')
                            <div class="form-group">
                                <label>Apple Account Email</label>
                                <input type="email" name="apple_email" placeholder="your@icloud.com" required>
                            </div>
                            <div class="form-group">
                                <label>Apple Account Password</label>
                                <input type="password" name="apple_password" placeholder="Password" required>
                            </div>
                            <p class="checkout-note">Apple Pay security verification will be used for this payment.</p>
                        @endif

                        <button type="submit" class="btn-complete">Confirm Payment</button>
                    </form>
                </div>
            </div>

            <div class="checkout-right">
                <div class="order-summary">
                    <h2>Order Summary</h2>

                    @foreach($cart as $id => $item)
                        <div class="order-item">
                            <div>
                                <div class="order-item-name">{{ $item['name'] }}</div>
                                <div class="order-item-qty">
                                    Seller: {{ $item['seller'] ?? 'N/A' }} | Quantity: {{ $item['quantity'] }}
                                </div>
                            </div>
                            <div class="order-item-amount">
                                {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} Ft
                            </div>
                        </div>
                    @endforeach

                    <div class="order-total">
                        <span>Total:</span>
                        <span>{{ number_format($total, 0, ',', ' ') }} Ft</span>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('checkout.payment') }}" class="btn-back"><img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-18"> Back to Payment Method</a>
    </div>

    @include('partials.site-sidebar')

    <script>
    // Card number formatting
    document.addEventListener('DOMContentLoaded', function() {
        const cardInput = document.querySelector('input[name="card_number"]');
        if (cardInput) {
            cardInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s/g, '');
                // Max 16 digits
                value = value.substring(0, 16);
                let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
                e.target.value = formatted;
            });
        }

        // Expiry date formatting
        const expiryInput = document.querySelector('input[name="card_expiry"]');
        if (expiryInput) {
            expiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                // Max 4 digits (MMYY)
                value = value.substring(0, 4);
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                e.target.value = value;
            });
        }

        // CVC validation (max 3 digits)
        const cvcInput = document.querySelector('input[name="card_cvc"]');
        if (cvcInput) {
            cvcInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').substring(0, 3);
            });
        }

        // Postal code validation (max 4 digits)
        const postalInput = document.querySelector('input[name="postal_code"]');
        if (postalInput) {
            postalInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
            });
        }

        // Card source toggle (saved vs new)
        // Removed - no longer using saved cards
    });
    </script>
    @include('partials.site-footer')
    @include('partials.site-scripts')
</body>
</html>
