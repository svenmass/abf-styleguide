<?php
/**
 * The template for displaying archive pages
 *
 * @package ABF_Styleguide
 */

get_header();
?>

<main id="main" class="site-main styleguide-main">
    <div class="container-content">
        <?php if (have_posts()) : ?>
            
            <header class="page-header">
                <?php
                the_archive_title('<h1 class="page-title">', '</h1>');
                the_archive_description('<div class="archive-description">', '</div>');
                ?>
            </header>

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