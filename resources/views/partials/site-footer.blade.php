@php
    $footerVariant = $footerVariant ?? 'contact';
@endphp

<footer class="footer">
    <div class="footer-columns">
        <div>
            <h4>Contact</h4>
            <p>Email: info@g3x.hu</p>
            <p>Phone: +36 30 123 4567</p>
        </div>
        <div>
            <h4>Payment Options</h4>
            <div class="footer-icon-list footer-payment-list">
                <img src="{{ asset('icons/paypal.png') }}" alt="PayPal" class="footer-brand-icon">
                <img src="{{ asset('icons/green_card.png') }}" alt="Card" class="footer-brand-icon">
                <img src="{{ asset('icons/apple_pay.png') }}" alt="Apple Pay" class="footer-brand-icon footer-brand-icon-wide">
                <img src="{{ asset('icons/google_pay.png') }}" alt="Google Pay" class="footer-brand-icon footer-brand-icon-wide">
            </div>
        </div>
        <div>
            <h4>Follow Us</h4>
            <div class="footer-icon-list footer-social-list">
                <a href="https://www.facebook.com/" class="footer-icon-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <img src="{{ asset('icons/facebook.png') }}" alt="Facebook" class="footer-brand-icon">
                </a>
                <a href="https://www.instagram.com/" class="footer-icon-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <img src="{{ asset('icons/instagram.png') }}" alt="Instagram" class="footer-brand-icon">
                </a>
                <a href="https://discord.com/" class="footer-icon-link" target="_blank" rel="noopener noreferrer" aria-label="Discord">
                    <img src="{{ asset('icons/discord.png') }}" alt="Discord" class="footer-brand-icon">
                </a>
                <a href="https://x.com/" class="footer-icon-link" target="_blank" rel="noopener noreferrer" aria-label="X">
                    <img src="{{ asset('icons/green_x.png') }}" alt="X" class="footer-brand-icon">
                </a>
            </div>
        </div>
        @if($footerVariant === 'legal')
            <div>
                <h4>Legal</h4>
                <p>Privacy Policy</p>
                <p>Terms of Use</p>
                <p>Cookie Preferences</p>
            </div>
        @else
            <div>
                <h4>Location</h4>
                <p>Budapest, Hungary</p>
                <p>Open 24/7</p>
            </div>
        @endif
    </div>
    @if($footerVariant === 'legal')
        <div class="footer-bottom">
            <p>&copy; 2026 G3X - Digital Marketplace. All rights reserved.</p>
        </div>
    @else
        <p class="footer-bottom">© 2026 G3X - All rights reserved.</p>
    @endif
</footer>
