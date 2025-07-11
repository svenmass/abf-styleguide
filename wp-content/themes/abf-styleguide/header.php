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
    <header class="site-header">
        <div class="header-container">
            <div class="header-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link">
                    <?php abf_output_logo('desktop', 'logo-desktop'); ?>
                    <?php abf_output_logo('mobile', 'logo-mobile'); ?>
                </a>
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

    <div id="content" class="site-content"> 