<?php
/**
 * Navigation Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<nav class="site-navigation" id="site-navigation">
    <div class="navigation-container">
        <div class="navigation-header">
            <h2 class="navigation-title">Navigation</h2>
            <button class="navigation-close" aria-label="Navigation schließen">
                <span class="close-icon">×</span>
            </button>
        </div>
        
        <div class="navigation-content">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class' => 'navigation-menu',
                'container' => false,
                'fallback_cb' => 'abf_fallback_menu',
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            ));
            ?>
        </div>
        
        <div class="navigation-footer">
            <div class="navigation-contact">
                <p class="contact-text">Kontakt</p>
                <a href="mailto:info@example.com" class="contact-email">info@example.com</a>
            </div>
        </div>
    </div>
</nav>

<!-- Navigation Overlay (Mobile) -->
<div class="navigation-overlay" id="navigation-overlay"></div> 