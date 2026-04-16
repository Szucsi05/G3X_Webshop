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
            <h4>FAQ</h4>
            <p>Payment and Delivery</p>
            <p>Refunds</p>
            <p>Account Management</p>
        </div>
        <div>
            <h4>About Us</h4>
            <p>Our Mission</p>
            <p>Careers</p>
            <p>Blog</p>
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
                <p>Opening Hours: Mon-Fri 9:00-17:00</p>
            </div>
        @endif
    </div>
    @if($footerVariant === 'legal')
        <div class="footer-bottom">
            <p>&copy; 2024 G3X - Digital Marketplace. All rights reserved.</p>
        </div>
    @else
        <p class="footer-bottom">© 2025 G3X - All rights reserved.</p>
    @endif
</footer>