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
            
            // Post title (wie page-title in styleguide.page)
            if (get_the_title()) :
                ?>
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    
                    <?php
                    // Post meta nur bei Posts anzeigen
                    if ('post' === get_post_type()) :
                        ?>
                        <div class="post-meta">
                            <?php
                            if (function_exists('abf_styleguide_posted_on')) {
                                abf_styleguide_posted_on();
                            }
                            if (function_exists('abf_styleguide_posted_by')) {
                                abf_styleguide_posted_by();
                            }
                            ?>
                        </div><!-- .post-meta -->
                        <?php
                    endif;
                    ?>
                </header>
                <?php
            endif;
            
            // Post thumbnail
            if (function_exists('abf_styleguide_post_thumbnail')) {
                abf_styleguide_post_thumbnail();
            }
            
            // Post content (gleiche Klassen wie styleguide.page)
            the_content();
            
            // Post navigation
            the_post_navigation(
                array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'abf-styleguide') . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'abf-styleguide') . '</span> <span class="nav-title">%title</span>',
                )
            );
            
            // Entry footer (categories, tags, etc.)
            if (function_exists('abf_styleguide_entry_footer')) :
                ?>
                <footer class="post-footer">
                    <?php abf_styleguide_entry_footer(); ?>
                </footer><!-- .post-footer -->
                <?php
            endif;
            
            // Comments
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?> 