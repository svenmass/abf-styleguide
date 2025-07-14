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

            /* Start the Loop */
            while (have_posts()) :
                the_post();
                
                // Post article with styleguide.page styling
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('styleguide-post'); ?>>
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;

                        if ('post' === get_post_type()) :
                            ?>
                            <div class="entry-meta">
                                <?php
                                if (function_exists('abf_styleguide_posted_on')) {
                                    abf_styleguide_posted_on();
                                }
                                if (function_exists('abf_styleguide_posted_by')) {
                                    abf_styleguide_posted_by();
                                }
                                ?>
                            </div><!-- .entry-meta -->
                        <?php endif; ?>
                    </header><!-- .entry-header -->

                    <?php 
                    if (function_exists('abf_styleguide_post_thumbnail')) {
                        abf_styleguide_post_thumbnail(); 
                    }
                    ?>

                    <div class="entry-content">
                        <?php
                        if (is_singular()) :
                            the_content();
                        else :
                            the_excerpt();
                        endif;
                        ?>
                    </div><!-- .entry-content -->

                    <footer class="entry-footer">
                        <?php 
                        if (function_exists('abf_styleguide_entry_footer')) {
                            abf_styleguide_entry_footer(); 
                        }
                        ?>
                    </footer><!-- .entry-footer -->
                </article><!-- #post-<?php the_ID(); ?> -->
                <?php

            endwhile;

            the_posts_navigation();

        else :

            get_template_part('template-parts/content', 'none');

        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>
