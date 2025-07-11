/**
 * Navigation JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    const burgerToggle = document.querySelector('.burger-menu-toggle');
    const navigation = document.querySelector('.site-navigation');
    const navigationOverlay = document.querySelector('.navigation-overlay');
    const navigationClose = document.querySelector('.navigation-close');
    
    // Toggle navigation on burger click
    if (burgerToggle) {
        burgerToggle.addEventListener('click', function() {
            const isActive = navigation.classList.contains('active');
            
            if (isActive) {
                closeNavigation();
            } else {
                openNavigation();
            }
        });
    }
    
    // Close navigation on overlay click
    if (navigationOverlay) {
        navigationOverlay.addEventListener('click', function() {
            closeNavigation();
        });
    }
    
    // Close navigation on close button click
    if (navigationClose) {
        navigationClose.addEventListener('click', function() {
            closeNavigation();
        });
    }
    
    // Close navigation on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeNavigation();
        }
    });
    
    // Close navigation on window resize (if switching to desktop)
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) { // $breakpoint-desktop-small
            closeNavigation();
        }
    });
    
    function openNavigation() {
        navigation.classList.add('active');
        navigationOverlay.classList.add('active');
        burgerToggle.classList.add('active');
        burgerToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }
    
    function closeNavigation() {
        navigation.classList.remove('active');
        navigationOverlay.classList.remove('active');
        burgerToggle.classList.remove('active');
        burgerToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = ''; // Restore background scroll
    }
}); 