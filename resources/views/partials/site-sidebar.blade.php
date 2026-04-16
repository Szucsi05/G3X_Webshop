@php
    $sidebarOnclick = $sidebarOnclick ?? "localStorage.removeItem('filterState');";
    $sidebarSlugStyle = $sidebarSlugStyle ?? 'hyphen';
    $pcSlug = $sidebarSlugStyle === 'underscore' ? 'pc_games' : 'pc-games';
    $consoleSlug = $sidebarSlugStyle === 'underscore' ? 'console_games' : 'console-games';
    $subscriptionsSlug = $sidebarSlugStyle === 'underscore' ? 'game_subscriptions' : 'game-subscriptions';
@endphp

<div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

<div id="sidebar" class="sidebar" aria-hidden="true">
    <button class="close-btn" type="button" aria-label="Close categories" onclick="closeSidebar()">
        <img src="{{ asset('icons/x.png') }}" alt="Close" class="close-btn-icon">
    </button>
    <h3>Categories</h3>
    <ul>
        <li><a href="{{ route('filter.show', $pcSlug) }}" onclick="{{ $sidebarOnclick }}"><img src="{{ asset('icons/pc_category.png') }}" alt="PC Games" class="icon-18"> PC Games</a></li>
        <li><a href="{{ route('filter.show', $consoleSlug) }}" onclick="{{ $sidebarOnclick }}"><img src="{{ asset('icons/console_category.png') }}" alt="Console Games" class="icon-18"> Console Games</a></li>
        <li><a href="{{ route('filter.show', $subscriptionsSlug) }}" onclick="{{ $sidebarOnclick }}"><img src="{{ asset('icons/subcriptions_category.png') }}" alt="Subscriptions" class="icon-18"> Subscriptions</a></li>
        <li><a href="{{ route('filter.show', 'software') }}" onclick="{{ $sidebarOnclick }}"><img src="{{ asset('icons/software_category.png') }}" alt="Software" class="icon-18"> Software</a></li>
        <li><a href="{{ route('filter.show') }}" onclick="{{ $sidebarOnclick }}"><img src="{{ asset('icons/all_category.png') }}" alt="All Products" class="icon-18"> All Products</a></li>
    </ul>
</div>