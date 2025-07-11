<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php 
    // Output favicon and touch icon meta tags
    abf_output_favicon_meta_tags();
    
    wp_head(); 
    ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <!-- Header -->
    <header class="site-header show-mobile-header">
        <div class="header-container">
            <div class="header-logo">
                <?php
                if (abf_has_logo('mobile')) {
                    abf_output_logo('mobile', 'logo-mobile');
                } else {
                    abf_output_logo('desktop', 'logo-desktop');
                }
                ?>
            </div>
            
            <!-- Burger Menu (Mobile only) -->
            <button class="burger-menu-toggle" aria-label="Navigation öffnen" aria-expanded="false">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </button>
        </div>
    </header>

    <!-- Navigation -->
    <?php get_template_part('navigation'); ?>

    <?php
    // Prüfe, ob Fullscreen-Template aktiv ist
    if (is_page_template('page-fullscreen.php')) {
        echo '<div id="content" class="site-content fullscreen-content">';
    } else {
        echo '<div id="content" class="site-content">';
    }
    ?> 