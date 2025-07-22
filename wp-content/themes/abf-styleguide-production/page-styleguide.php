<?php
/**
 * Template Name: Styleguide Page
 * 
 * Template für normale Seiten mit Header und Navigation
 * Standard-Template für alle Content-Seiten
 */

get_header(); ?>

<main id="main" class="site-main styleguide-main">
    <div class="container-content">
        <?php
        while (have_posts()) :
            the_post();
            
            // Page title
            if (get_the_title()) :
                ?>
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
                <?php
            endif;
            
            // Get the content
            the_content();
            
        endwhile;
        ?>
    </div>
</main>

<?php get_footer('styleguide'); ?> 