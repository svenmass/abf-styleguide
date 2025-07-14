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
            

            
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?> 