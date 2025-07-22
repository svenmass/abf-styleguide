<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package ABF_Styleguide
 */

get_header();
?>

<main id="main" class="site-main styleguide-main">
    <div class="container-content">
        <?php
        if (have_posts()) :

            if (is_home() && !is_front_page()) :
                ?>
                <header class="page-header">
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                </header>
                <?php
            endif;

            ?>
            <div class="cards-grid cards-grid--3-cols">
                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content-card-vertical');
                endwhile;
                ?>
            </div>
            <?php

            the_posts_navigation();

        else :

            get_template_part('template-parts/content', 'none');

        endif;
        ?>
    </div>
</main>

<?php 
// Load appropriate footer based on page type
if (function_exists('abf_is_styleguide_page') && abf_is_styleguide_page()) {
    get_footer('styleguide');
} else {
    get_footer();
}
?>
