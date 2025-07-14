<?php
/**
 * The template for displaying all single posts
 *
 * @package ABF_Styleguide
 */

get_header();
?>

<main id="main" class="site-main styleguide-main">
    <div class="container-content">
        <?php
        while (have_posts()) :
            the_post();
            
            // Post title
            if (get_the_title()) :
                ?>
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
                <?php
            endif;
            
            // Post meta (date, author, etc.)
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
                <?php
            endif;
            
            // Post thumbnail
            if (function_exists('abf_styleguide_post_thumbnail')) {
                abf_styleguide_post_thumbnail();
            }
            
            // Post content
            the_content();
            
            // Post navigation
            the_post_navigation(
                array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'abf-styleguide') . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'abf-styleguide') . '</span> <span class="nav-title">%title</span>',
                )
            );
            
            // Entry footer (categories, tags, etc.)
            ?>
            <footer class="entry-footer">
                <?php 
                if (function_exists('abf_styleguide_entry_footer')) {
                    abf_styleguide_entry_footer(); 
                }
                ?>
            </footer><!-- .entry-footer -->
            <?php
            
            // Comments
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?> 