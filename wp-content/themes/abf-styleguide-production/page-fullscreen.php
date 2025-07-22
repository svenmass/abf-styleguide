<?php
/**
 * Template Name: Fullscreen
 * 
 * Template für Vollbild-Seiten ohne Header und Navigation
 * Ideal für Homepage und Landing Pages
 */

get_header('fullscreen'); ?>

<main id="main" class="site-main fullscreen-main">
    <?php
    while (have_posts()) :
        the_post();
        
        // Get the content
        the_content();
        
    endwhile;
    ?>
</main>

<?php get_footer('fullscreen'); ?> 