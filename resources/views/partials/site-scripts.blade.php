<script>
let userDropdownTimeout;

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
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

document.addEventListener('DOMContentLoaded', setupUserDropdownDelay);
</script>