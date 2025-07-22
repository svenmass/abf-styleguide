<?php
/**
 * Styleguide Similar Posts Block Template
 *
 * @package ABF_Styleguide
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'styleguide-similar-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'styleguide-similar-block';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

// Get ACF fields
$posts_to_show = get_field('posts_to_show') ?: 3;
$columns = get_field('columns') ?: 3;
$show_title = get_field('show_title') ?: true;
$block_title = get_field('block_title') ?: 'Ähnliche Beiträge';
$base_post_id = get_field('base_post') ?: get_the_ID();

// Get the base post to find similar posts
$base_post = get_post($base_post_id);
if (!$base_post) {
    return;
}

// Get categories of the base post
$categories = get_the_category($base_post_id);
$category_ids = array();
if ($categories) {
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
}

// Get tags of the base post
$tags = get_the_tags($base_post_id);
$tag_ids = array();
if ($tags) {
    foreach ($tags as $tag) {
        $tag_ids[] = $tag->term_id;
    }
}

// Build query args for similar posts
$args = array(
    'post_type' => $base_post->post_type,
    'posts_per_page' => $posts_to_show,
    'post__not_in' => array($base_post_id),
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish'
);

// Add category or tag filter (prefer categories)
if (!empty($category_ids)) {
    $args['category__in'] = $category_ids;
} elseif (!empty($tag_ids)) {
    $args['tag__in'] = $tag_ids;
}

// Execute query
$posts_query = new WP_Query($args);

// Determine grid class
$grid_class = 'cards-grid--' . $columns . '-cols';
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class_name); ?>">
    
    <?php if ($show_title && $block_title): ?>
        <header class="block-header">
            <h2 class="block-title"><?php echo esc_html($block_title); ?></h2>
        </header>
    <?php endif; ?>
    
    <?php if ($posts_query->have_posts()): ?>
        <div class="cards-grid <?php echo esc_attr($grid_class); ?>">
            <?php while ($posts_query->have_posts()): $posts_query->the_post(); ?>
                <?php get_template_part('template-parts/content-card-vertical'); ?>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="no-posts-message">
            <p>Keine ähnlichen Beiträge gefunden.</p>
        </div>
    <?php endif; ?>
    
</div>

<?php
// Reset post data
wp_reset_postdata();
?> 