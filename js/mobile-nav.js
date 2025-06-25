// Modern Mobile Navigation
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const menuToggle = document.getElementById('modern-menu-toggle');
    const navDrawer = document.getElementById('modern-nav-drawer');
    const navOverlay = document.getElementById('modern-nav-overlay');
    const navLinks = document.querySelectorAll('.modern-nav-list .nav-link');
    
    // Toggle navigation
    function toggleNavigation() {
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
        
        if (!isExpanded) {
            // Opening the menu
            navDrawer.style.display = 'block';
            navOverlay.style.display = 'block';
            
            // Small delay to ensure display is set before adding active class
            setTimeout(() => {
                menuToggle.setAttribute('aria-expanded', 'true');
                menuToggle.classList.add('active');
                navDrawer.classList.add('active');
                navOverlay.classList.add('active');
                document.body.classList.add('no-scroll');
            }, 10);
        } else {
            // Closing the menu
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.classList.remove('active');
            navDrawer.classList.remove('active');
            navOverlay.classList.remove('active');
            document.body.classList.remove('no-scroll');
            
            // Hide after transition
            setTimeout(() => {
                if (!navDrawer.classList.contains('active')) {
                    navDrawer.style.display = 'none';
                    navOverlay.style.display = 'none';
                }
            }, 300);
        }
    }
    
    // Close navigation
    function closeNavigation() {
        if (navDrawer.classList.contains('active')) {
            toggleNavigation();
        }
    }
    
    // Initialize
    if (menuToggle && navDrawer && navOverlay) {
        // Set initial state
        navDrawer.style.display = 'none';
        navOverlay.style.display = 'none';
        
        // Toggle menu
        menuToggle.addEventListener('click', toggleNavigation);
        
        // Close when clicking overlay or nav links
        navOverlay.addEventListener('click', closeNavigation);
        navLinks.forEach(link => {
            if (!link.classList.contains('dropdown-toggle')) {
                link.addEventListener('click', closeNavigation);
            }
        });
        
        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navDrawer.classList.contains('active')) {
                closeNavigation();
            }
        });
    }
});

