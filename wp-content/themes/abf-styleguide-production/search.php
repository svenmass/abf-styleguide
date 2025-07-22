<?php
/**
 * The template for displaying search results pages
 *
 * @package ABF_Styleguide
 */

get_header();
?>

<main id="main" class="site-main styleguide-main">
    <div class="container-content">
        <?php if (have_posts()) : ?>
            
            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    /* translators: %s: search query. */
                    printf(esc_html__('Suchergebnisse für: %s', 'abf-styleguide'), '<span>' . get_search_query() . '</span>');
                    ?>
                </h1>
            </header>

            <div class="cards-list">
                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content-card-horizontal');
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