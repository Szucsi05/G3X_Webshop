<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing Details - G3X</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout-details.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @include('partials.site-navbar')

    <div class="checkout-details-container">
        <div class="checkout-details-header">
            <h1 class="checkout-details-title"><img src="{{ asset('icons/green_details.png') }}" alt="Billing details" class="icon-28 icon-contain"> Billing Details</h1>
        </div>

        <div class="checkout-details-content">
            <!-- Left side - Form -->
            <div class="checkout-details-left">
                <form id="details-form" method="POST" action="{{ route('checkout.details.store') }}">
                    @csrf

                    <div class="form-section">
                        <h3>Account Type</h3>
                        <div class="toggle-section">
                            <button type="button" class="toggle-btn{{ (session('checkout_details.account_type') ?? old('account_type', 'personal')) === 'personal' ? ' active' : '' }}" data-type="personal" onclick="selectType('personal')">Private Individual</button>
                            <button type="button" class="toggle-btn{{ (session('checkout_details.account_type') ?? old('account_type', 'personal')) === 'company' ? ' active' : '' }}" data-type="company" onclick="selectType('company')">Company</button>
                        </div>
                        <input type="hidden" id="account-type" name="account_type" value="{{ session('checkout_details.account_type', old('account_type', 'personal')) }}">
                    </div>

                    <div id="personal-billing" class="form-section hidden-section active">
                        <h3>Billing Details (Private Individual)</h3>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="billing_name_personal" id="billing_name_personal" placeholder="e.g. John Smith" value="{{ session('checkout_details.billing_name_personal', old('billing_name_personal')) }}">
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="billing_phone_personal" id="billing_phone_personal" placeholder="+36 70 252 3456" value="{{ session('checkout_details.billing_phone_personal', old('billing_phone_personal')) }}">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="billing_email_personal" id="billing_email_personal" placeholder="e.g. john@example.com" value="{{ session('checkout_details.billing_email_personal', old('billing_email_personal')) }}">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <select name="billing_country_personal" id="billing_country_personal">
                                <option value="">Select a country</option>
                                <option value="HU" {{ (session('checkout_details.billing_country_personal', old('billing_country_personal', 'HU')) === 'HU') ? 'selected' : '' }}>🇭🇺 Hungary</option>
                                <option value="GB" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'GB' ? 'selected' : '' }}>🇬🇧 United Kingdom</option>
                                <option value="DE" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'DE' ? 'selected' : '' }}>🇩🇪 Germany</option>
                                <option value="AT" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'AT' ? 'selected' : '' }}>🇦🇹 Austria</option>
                                <option value="FR" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'FR' ? 'selected' : '' }}>🇫🇷 France</option>
                                <option value="US" {{ session('checkout_details.billing_country_personal', old('billing_country_personal')) === 'US' ? 'selected' : '' }}>🇺🇸 United States</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="billing_city_personal" id="billing_city_personal" placeholder="e.g. Budapest" value="{{ session('checkout_details.billing_city_personal', old('billing_city_personal')) }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" name="billing_postal_personal" id="billing_postal_personal" placeholder="e.g. 1011" value="{{ session('checkout_details.billing_postal_personal', old('billing_postal_personal')) }}">
                            </div>
                            <div class="form-group">
                                <label>Street Address</label>
                                <input type="text" name="billing_street_personal" id="billing_street_personal" placeholder="e.g. Petofi Street 10" value="{{ session('checkout_details.billing_street_personal', old('billing_street_personal')) }}">
                            </div>
                        </div>
                    </div>

                    <div id="company-billing" class="form-section hidden-section">
                        <h3>Billing Details (Company)</h3>
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" name="billing_company_name" id="billing_company_name" placeholder="e.g. Example Ltd." value="{{ session('checkout_details.billing_company_name', old('billing_company_name')) }}">
                        </div>
                        <div class="form-group">
                            <label>Tax ID</label>
                            <input type="text" name="billing_tax_id" id="billing_tax_id" placeholder="e.g. HU12345678" value="{{ session('checkout_details.billing_tax_id', old('billing_tax_id')) }}">
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="billing_phone_company" id="billing_phone_company" placeholder="+36 70 252 3456" value="{{ session('checkout_details.billing_phone_company', old('billing_phone_company')) }}">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="billing_email_company" id="billing_email_company" placeholder="e.g. info@company.com" value="{{ session('checkout_details.billing_email_company', old('billing_email_company')) }}">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <select name="billing_country_company" id="billing_country_company">
                                <option value="">Select a country</option>
                                <option value="HU" {{ (session('checkout_details.billing_country_company', old('billing_country_company', 'HU')) === 'HU') ? 'selected' : '' }}>🇭🇺 Hungary</option>
                                <option value="GB" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'GB' ? 'selected' : '' }}>🇬🇧 United Kingdom</option>
                                <option value="DE" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'DE' ? 'selected' : '' }}>🇩🇪 Germany</option>
                                <option value="AT" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'AT' ? 'selected' : '' }}>🇦🇹 Austria</option>
                                <option value="FR" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'FR' ? 'selected' : '' }}>🇫🇷 France</option>
                                <option value="US" {{ session('checkout_details.billing_country_company', old('billing_country_company')) === 'US' ? 'selected' : '' }}>🇺🇸 United States</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="billing_city_company" id="billing_city_company" placeholder="e.g. Budapest" value="{{ session('checkout_details.billing_city_company', old('billing_city_company')) }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" name="billing_postal_company" id="billing_postal_company" placeholder="e.g. 1011" value="{{ session('checkout_details.billing_postal_company', old('billing_postal_company')) }}">
                            </div>
                            <div class="form-group">
                                <label>Street Address</label>
                                <input type="text" name="billing_street_company" id="billing_street_company" placeholder="e.g. Petofi Street 10" value="{{ session('checkout_details.billing_street_company', old('billing_street_company')) }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-complete checkout-details-submit">Continue to Payment <img src="{{ asset('icons/black_right_arrow.png') }}" alt="Continue" class="icon-18"></button>
                </form>

                <a href="{{ route('cart.index') }}" class="back-link"><img src="{{ asset('icons/green_left_arrow.png') }}" alt="Back" class="icon-18"> Back to Cart</a>
            </div>

            <div class="checkout-details-right">
                <div class="order-summary">
                    <h2>Order Summary</h2>

                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                        <div class="order-item">
                            <div>
                                <div class="order-item-name">{{ $item['name'] }}</div>
                                <div class="order-item-qty">
                                    Seller: {{ $item['seller'] ?? 'N/A' }} | Quantity: {{ $item['quantity'] }}
                                </div>
                            </div>
                            <div class="order-amount">
                                {{ number_format($subtotal, 0, ',', ' ') }} Ft
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
    </div>

    @include('partials.site-sidebar')

    <!-- JS -->
    <script>
    function selectType(type) {
        // Update hidden input
        document.getElementById('account-type').value = type;

        // Update button styles
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-type="${type}"]`).classList.add('active');

        // Show/hide sections
        if (type === 'personal') {
            document.getElementById('personal-billing').classList.add('active');
            document.getElementById('company-billing').classList.remove('active');
            
            // Clear company fields
            document.getElementById('billing_company_name').value = '';
            document.getElementById('billing_company_id').value = '';
            document.getElementById('billing_tax_id').value = '';
            document.getElementById('billing_contact_name').value = '';
            document.getElementById('billing_phone_company').value = '';
            document.getElementById('billing_email_company').value = '';
        } else {
            document.getElementById('personal-billing').classList.remove('active');
            document.getElementById('company-billing').classList.add('active');
            
            // Clear personal fields
            document.getElementById('billing_name_personal').value = '';
            document.getElementById('billing_phone_personal').value = '';
            document.getElementById('billing_email_personal').value = '';
        }
    }
    </script>
    @include('partials.site-footer')
    @include('partials.site-scripts')
</body>
</html>
