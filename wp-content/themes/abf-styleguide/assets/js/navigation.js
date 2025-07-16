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
    
    // Beim Laden: Aktive Submenu-Seiten automatisch öffnen
    function openActiveSubmenus() {
        // Erweiterte Suche nach aktiven Submenu-Items
        const activeSubmenus = document.querySelectorAll('.navigation__submenu .current-menu-item, .navigation__submenu .current-menu-ancestor, .navigation__submenu .current-page-parent, .navigation__submenu .current-page-ancestor');
        
        // Auch Top-Level aktive Items berücksichtigen
        const activeTopLevel = document.querySelectorAll('.navigation__menu > .current-menu-ancestor, .navigation__menu > .current-page-ancestor');
        
        activeSubmenus.forEach(function(activeItem) {
            const parentMenuItem = activeItem.closest('.navigation__menu-item');
            if (parentMenuItem) {
                parentMenuItem.classList.add('navigation__menu-item--open');
                parentMenuItem.classList.add('navigation__menu-item--active-child'); // Marker für aktive Submenu-Seite
            }
        });
        
        // Top-Level Items mit aktiven Kindern ebenfalls öffnen
        activeTopLevel.forEach(function(activeItem) {
            if (activeItem.querySelector('.navigation__submenu')) {
                activeItem.classList.add('navigation__menu-item--open');
                activeItem.classList.add('navigation__menu-item--active-child');
            }
        });
    }
    
    // Beim Laden ausführen - mit setTimeout für bessere Kompatibilität
    setTimeout(openActiveSubmenus, 100);
    
    // Zusätzlich bei window.load ausführen
    window.addEventListener('load', openActiveSubmenus);
    
    // Submenu-Toggle (Plus-Icon) mit Accordion-Verhalten
    document.querySelectorAll('.navigation__submenu-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const li = toggle.closest('li');
            if (li) {
                const isCurrentlyOpen = li.classList.contains('navigation__menu-item--open');
                const hasActiveChild = li.classList.contains('navigation__menu-item--active-child');
                
                // Geschwister-Elemente der gleichen Ebene schließen (Accordion-Verhalten pro Ebene)
                const parentContainer = li.parentNode; // ul oder .navigation__submenu
                if (parentContainer) {
                    // Alle direkten Kinder des gleichen Parents durchgehen (Geschwister)
                    const siblings = parentContainer.querySelectorAll(':scope > .navigation__menu-item');
                    siblings.forEach(function(sibling) {
                        if (sibling !== li && 
                            sibling.classList.contains('navigation__menu-item--open') &&
                            !sibling.classList.contains('navigation__menu-item--active-child')) {
                            sibling.classList.remove('navigation__menu-item--open');
                        }
                    });
                }
                
                // Aktuelles Submenu togglen - aber nur wenn es keine aktive Submenu-Seite enthält
                if (!hasActiveChild) {
                    li.classList.toggle('navigation__menu-item--open');
                } else {
                    // Aktive Submenu-Seite: Wenn geklickt, schließen erlauben, aber bei anderem Klick wieder öffnen
                    if (isCurrentlyOpen) {
                        li.classList.remove('navigation__menu-item--open');
                    } else {
                        li.classList.add('navigation__menu-item--open');
                    }
                }
            }
        });
    });
    
    // Submenu-Links: Navigation nicht schließen, aktiven Punkt anzeigen
    document.querySelectorAll('.navigation__submenu .navigation__menu-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Entferne aktive Klasse von allen anderen Submenu-Links
            document.querySelectorAll('.navigation__submenu .navigation__menu-link').forEach(function(otherLink) {
                otherLink.classList.remove('navigation__menu-link--active');
            });
            
            // Füge aktive Klasse zum geklickten Link hinzu
            this.classList.add('navigation__menu-link--active');
            
            // Parent Menu-Item als aktiv markieren
            const parentMenuItem = this.closest('.navigation__menu-item');
            if (parentMenuItem) {
                parentMenuItem.classList.add('navigation__menu-item--active-child');
                parentMenuItem.classList.add('navigation__menu-item--open');
            }
            
            // Navigation bleibt offen (kein closeNavigation() aufrufen)
            // Link-Funktion wird normal ausgeführt
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
        
        // Add navigation-active class to site content for mobile
        const siteContent = document.querySelector('.site-content');
        if (siteContent) {
            siteContent.classList.add('navigation-active');
        }
        // Header bleibt unverändert - keine navigation-active Klasse mehr
        
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
        
        // Remove navigation-active class from site content
        const siteContent = document.querySelector('.site-content');
        if (siteContent) {
            siteContent.classList.remove('navigation-active');
        }
        // Header bleibt unverändert - keine navigation-active Klasse mehr
        
        document.body.style.overflow = ''; // Restore background scroll
    }
}); 