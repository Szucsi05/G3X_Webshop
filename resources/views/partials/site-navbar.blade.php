@php
    $searchValue = $searchValue ?? request('q');
    $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : '';
    $cartBadgeClass = session('cart') ? 'card-badge-visible' : 'card-badge-hidden';
    $cartLinkOnclick = $cartLinkOnclick ?? null;
@endphp

<nav class="navbar">
    <div class="navbar-top">
        <a href="{{ route('home') }}" class="logo-link">
            <div class="animated-logo">G3X</div>
        </a>
        <div class="search-bar">
            <form method="GET" action="{{ route('search') }}" class="search-form">
                <input type="text" name="q" placeholder="Search for games, gift cards..." value="{{ $searchValue }}" class="search-input">
            </form>
        </div>
        <div class="nav-right {{ auth()->check() ? 'nav-right-auth' : 'nav-right-guest' }}">
            <a href="#" class="nav-btn nav-btn-inline category-nav-link" onclick="toggleSidebar(); return false;"><img src="{{ asset('icons/category.png') }}" alt="Categories" class="icon-18"> Categories</a>
            @auth
                <div class="user-menu-container">
                    <button class="user-btn"><img src="{{ asset('icons/login.png') }}" alt="Account" class="icon-18"><span class="user-btn-label">{{ Auth::user()->name }}</span></button>
                    <div class="user-dropdown" id="user-dropdown">
                        <a href="{{ route('settings.show') }}" class="user-dropdown-item dropdown-item-inline"><img src="{{ asset('icons/settings.png') }}" alt="Settings" class="icon-18"> Settings</a>
                        <a href="{{ route('orders.index') }}" class="user-dropdown-item dropdown-item-inline"><img src="{{ asset('icons/orders.png') }}" alt="My Orders" class="icon-18"> My Orders</a>
                        <a href="{{ route('logout') }}" class="user-dropdown-item dropdown-item-inline" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img src="{{ asset('icons/log_out.png') }}" alt="Log Out" class="icon-18"> Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('register') }}" class="nav-btn nav-btn-inline"><img src="{{ asset('icons/registration.png') }}" alt="Register" class="icon-18"> Register</a>
                <a href="{{ route('login') }}" class="nav-btn nav-btn-inline"><img src="{{ asset('icons/login.png') }}" alt="Log In" class="icon-18"> Log In</a>
            @endauth
            <a href="{{ route('cart.index') }}" class="nav-btn nav-btn-inline cart-nav-link" @if($cartLinkOnclick) onclick="{{ $cartLinkOnclick }}" @endif><img src="{{ asset('icons/cart.png') }}" alt="Cart" class="icon-18"> Cart <span id="cart-badge" class="badge-alert {{ $cartBadgeClass }}">{{ $cartCount }}</span></a>
        </div>
    </div>
</nav>
