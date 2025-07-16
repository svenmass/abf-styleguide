<?php
/**
 * Template part for displaying vertical post cards
 *
 * @package ABF_Styleguide
 */

// Get post data
$post_id = get_the_ID();
$post_title = get_the_title();
$post_permalink = get_permalink();
$post_thumbnail = get_the_post_thumbnail_url($post_id, 'abf-medium');

// Default thumbnail if none exists
if (!$post_thumbnail) {
    $post_thumbnail = get_template_directory_uri() . '/assets/images/logo.svg';
}

// Arrow icon SVG
$arrow_icon = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M8.84706 0L7.50984 1.36468L14.3252 8.06523H0V9.93393H14.3252L7.50984 16.6599L8.84706 18L18 9.00127L8.84706 0Z" fill="#95B7A4"/>
</svg>';
?>

<article class="card card--vertical" id="post-<?php echo $post_id; ?>">
    <a href="<?php echo esc_url($post_permalink); ?>" class="card__link">
        
        <!-- Card Image -->
        <div class="card__image">
            <img src="<?php echo esc_url($post_thumbnail); ?>" 
                 alt="<?php echo esc_attr($post_title); ?>" 
                 loading="lazy">
        </div>
        
        <!-- Card Content -->
        <div class="card__content">
            <h3 class="card__title">
                <?php echo esc_html($post_title); ?>
                <span class="card__icon">
                    <?php echo $arrow_icon; ?>
                </span>
            </h3>
        </div>
        
    </a>
</article> 