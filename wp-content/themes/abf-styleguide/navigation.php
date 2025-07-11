<?php
/**
 * Navigation Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<nav class="navigation show-desktop-sidebar" id="site-navigation">
    <div class="navigation__container">
        <!-- Logo in Navigation -->
        <div class="navigation__logo">
            <?php abf_output_logo('desktop', 'navigation__logo-desktop'); ?>
        </div>
        
        <div class="navigation__header">
            <h2 class="navigation__title">Navigation</h2>
            <button class="navigation__close" aria-label="Navigation schließen">
                <span class="navigation__close-icon">×</span>
            </button>
        </div>
        
        <div class="navigation__content">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class' => 'navigation__menu',
                'container' => false,
                'fallback_cb' => 'abf_fallback_menu',
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'walker' => new ABF_Nav_Walker(),
            ));
            ?>
        </div>
        
        <div class="navigation__footer">
            <div class="navigation__contact">
                <p class="navigation__contact-text">Kontakt</p>
                <a href="mailto:info@example.com" class="navigation__contact-email">info@example.com</a>
            </div>
        </div>
    </div>
</nav>

<!-- Navigation Overlay (Mobile) -->
<div class="navigation__overlay" id="navigation-overlay"></div> 