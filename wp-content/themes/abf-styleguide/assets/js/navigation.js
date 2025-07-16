/**
 * Navigation JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    const burgerToggle = document.querySelector('.burger-menu-toggle');
    const navigation = document.querySelector('.navigation');
const navigationOverlay = document.querySelector('.navigation__overlay');
const navigationClose = document.querySelector('.navigation__close');
    
    // Toggle navigation on burger click
    if (burgerToggle) {
        burgerToggle.addEventListener('click', function() {
            const isActive = navigation.classList.contains('navigation--active');
            
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
    
    // Submenu-Toggle (Plus-Icon)
    document.querySelectorAll('.navigation__submenu-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const li = toggle.closest('li');
            if (li) {
                li.classList.toggle('navigation__menu-item--open');
                // Submenu wird jetzt vollständig über CSS gesteuert
            }
        });
    });
    
    function openNavigation() {
        if (navigation) {
            navigation.classList.add('navigation--active');
        }
        if (navigationOverlay) {
            navigationOverlay.classList.add('navigation__overlay--active');
        }
        if (burgerToggle) {
            burgerToggle.classList.add('active');
            burgerToggle.setAttribute('aria-expanded', 'true');
        }
        
        // Add navigation-active class to site content and header for mobile
        const siteContent = document.querySelector('.site-content');
        const siteHeader = document.querySelector('.site-header');
        if (siteContent) {
            siteContent.classList.add('navigation-active');
        }
        if (siteHeader) {
            siteHeader.classList.add('navigation-active');
        }
        
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }
    
    function closeNavigation() {
        if (navigation) {
            navigation.classList.remove('navigation--active');
        }
        if (navigationOverlay) {
            navigationOverlay.classList.remove('navigation__overlay--active');
        }
        if (burgerToggle) {
            burgerToggle.classList.remove('active');
            burgerToggle.setAttribute('aria-expanded', 'false');
        }
        
        // Remove navigation-active class from site content and header
        const siteContent = document.querySelector('.site-content');
        const siteHeader = document.querySelector('.site-header');
        if (siteContent) {
            siteContent.classList.remove('navigation-active');
        }
        if (siteHeader) {
            siteHeader.classList.remove('navigation-active');
        }
        
        document.body.style.overflow = ''; // Restore background scroll
    }
}); 