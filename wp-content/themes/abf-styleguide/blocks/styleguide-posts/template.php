<?php
/**
 * Styleguide Posts Block Template
 *
 * @package ABF_Styleguide
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'styleguide-posts-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'styleguide-posts-block';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

// Get ACF fields
$posts_to_show = get_field('posts_to_show') ?: 6;
$columns = get_field('columns') ?: 3;
$post_types = get_field('post_types') ?: array('post');
$categories = get_field('categories');
$tags = get_field('tags');
$orderby = get_field('orderby') ?: 'date';
$order = get_field('order') ?: 'DESC';
$show_title = get_field('show_title') ?: false;
$block_title = get_field('block_title') ?: 'Aktuelle Beiträge';

// Build query args
$args = array(
    'post_type' => $post_types,
    'posts_per_page' => $posts_to_show,
    'orderby' => $orderby,
    'order' => $order,
    'post_status' => 'publish'
);

// Add category filter
if ($categories) {
    $args['cat'] = $categories;
}

// Add tag filter
if ($tags) {
    $args['tag__in'] = $tags;
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
            <p>Keine Beiträge gefunden.</p>
        </div>
    <?php endif; ?>
    
</div>

<?php
// Reset post data
wp_reset_postdata();
?> 