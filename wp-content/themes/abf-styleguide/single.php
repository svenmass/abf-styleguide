<?php
/**
 * The template for displaying all single posts
 *
 * @package ABF_Styleguide
 */

get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

        <?php
        while (have_posts()) :
            the_post();

            get_template_part('template-parts/content', get_post_type());

            the_post_navigation(
                array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'abf-styleguide') . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'abf-styleguide') . '</span> <span class="nav-title">%title</span>',
                )
            );

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer(); 