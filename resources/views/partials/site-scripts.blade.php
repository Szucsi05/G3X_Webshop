<script>
let userDropdownTimeout;

function toggleSidebar(forceOpen) {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (!sidebar || !overlay) return;

    const shouldOpen = typeof forceOpen === 'boolean'
        ? forceOpen
        : !sidebar.classList.contains('active');

    sidebar.classList.toggle('active', shouldOpen);
    overlay.classList.toggle('active', shouldOpen);
    sidebar.setAttribute('aria-hidden', shouldOpen ? 'false' : 'true');
}

function closeSidebar() {
    toggleSidebar(false);
}

function setupUserDropdownDelay() {
    const userMenuContainer = document.querySelector('.user-menu-container');
    if (!userMenuContainer) return;

    userMenuContainer.addEventListener('mouseenter', function() {
        clearTimeout(userDropdownTimeout);
        const dropdown = this.querySelector('.user-dropdown');
        if (dropdown) dropdown.classList.add('active');
    });

    userMenuContainer.addEventListener('mouseleave', function() {
        const dropdown = this.querySelector('.user-dropdown');
        userDropdownTimeout = setTimeout(() => {
            if (dropdown) dropdown.classList.remove('active');
        }, 300);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setupUserDropdownDelay();

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });
});
</script>
