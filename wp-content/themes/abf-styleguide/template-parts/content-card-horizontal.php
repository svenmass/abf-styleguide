<?php
/**
 * Template part for displaying horizontal post cards
 *
 * @package ABF_Styleguide
 */

// Get post data
$post_id = get_the_ID();
$post_title = get_the_title();
$post_permalink = get_permalink();
$post_thumbnail = get_the_post_thumbnail_url($post_id, 'abf-medium');
$post_excerpt = get_the_excerpt();

// Default thumbnail if none exists
if (!$post_thumbnail) {
    $post_thumbnail = get_template_directory_uri() . '/assets/images/logo.svg';
}

// Get categories
$categories = get_the_category();
$category_names = array();
if ($categories) {
    foreach ($categories as $category) {
        $category_names[] = $category->name;
    }
}
$category_string = implode(', ', $category_names);

// Arrow icon SVG
$arrow_icon = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M8.84706 0L7.50984 1.36468L14.3252 8.06523H0V9.93393H14.3252L7.50984 16.6599L8.84706 18L18 9.00127L8.84706 0Z" fill="#95B7A4"/>
</svg>';
?>

<article class="card card--horizontal" id="post-<?php echo $post_id; ?>">
    
    <!-- Card Image -->
    <div class="card__image">
        <img src="<?php echo esc_url($post_thumbnail); ?>" 
             alt="<?php echo esc_attr($post_title); ?>" 
             loading="lazy">
    </div>
    
    <!-- Card Content -->
    <div class="card__content">
        
        <!-- Title -->
        <h3 class="card__title">
            <a href="<?php echo esc_url($post_permalink); ?>">
                <?php echo esc_html($post_title); ?>
            </a>
        </h3>
        
        <!-- Excerpt -->
        <?php if ($post_excerpt): ?>
            <div class="card__excerpt">
                <?php echo esc_html($post_excerpt); ?>
            </div>
        <?php endif; ?>
        
        <!-- Category -->
        <?php if ($category_string): ?>
            <div class="card__category">
                <?php echo esc_html($category_string); ?>
            </div>
        <?php endif; ?>
        
        <!-- Read More Button -->
        <a href="<?php echo esc_url($post_permalink); ?>" class="card__button">
            Weiterlesen
            <span class="card__icon">
                <?php echo $arrow_icon; ?>
            </span>
        </a>
        
    </div>
    
</article> 