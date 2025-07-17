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

// Get new ACF fields
$headline_text = get_field('sp_headline_text') ?: '';
$headline_tag = get_field('sp_headline_tag') ?: 'h2';
$headline_size = get_field('sp_headline_size') ?: '36';
$headline_weight = get_field('sp_headline_weight') ?: '600';
$headline_color = get_field('sp_headline_color') ?: 'inherit';

$selection_mode = get_field('sp_selection_mode') ?: 'automatic';
$post_categories = get_field('sp_post_categories') ?: array();
$manual_posts = get_field('sp_manual_posts') ?: array();
$orderby = get_field('sp_orderby') ?: 'date';
$order = get_field('sp_order') ?: 'DESC';
$posts_per_page = get_field('sp_posts_per_page') ?: 6;
$columns = get_field('sp_columns') ?: 3;

// ACF true_false fields can return '1', '0', true, false, or null
// We need to check explicitly for truthy values
$show_excerpt = get_field('sp_show_excerpt');
$show_date = get_field('sp_show_date');
$show_category = get_field('sp_show_category');

// Convert ACF boolean values to actual booleans
$show_excerpt = ($show_excerpt === true || $show_excerpt === '1' || $show_excerpt === 1);
$show_date = ($show_date === true || $show_date === '1' || $show_date === 1);
$show_category = ($show_category === true || $show_category === '1' || $show_category === 1);

// DEBUG: Only for admins
if (current_user_can('manage_options')) {
    echo '<!-- DEBUG Styleguide Posts ACF Values:';
    echo ' sp_show_excerpt: ' . var_export(get_field('sp_show_excerpt'), true) . ' -> ' . var_export($show_excerpt, true);
    echo ' sp_show_date: ' . var_export(get_field('sp_show_date'), true) . ' -> ' . var_export($show_date, true);
    echo ' sp_show_category: ' . var_export(get_field('sp_show_category'), true) . ' -> ' . var_export($show_category, true);
    echo ' -->';
}

// Build query args based on selection mode
$posts_query = null;

if ($selection_mode === 'manual' && !empty($manual_posts)) {
    // Manual selection: use selected posts in the specified order
    $args = array(
        'post_type' => 'post',
        'post__in' => $manual_posts,
        'orderby' => 'post__in', // Preserve the manual order
        'posts_per_page' => -1, // Show all selected posts
        'post_status' => 'publish'
    );
} else {
    // Automatic selection: use category filter
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'orderby' => $orderby,
        'order' => $order,
        'post_status' => 'publish'
    );
    
    // Add category filter if categories are selected
    if (!empty($post_categories)) {
        $args['category__in'] = $post_categories;
    }
}

// Execute query
$posts_query = new WP_Query($args);

// Determine grid class
$grid_class = 'cards-grid--' . $columns . '-cols';

// Arrow icon SVG (from existing template-part)
$arrow_icon = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M8.84706 0L7.50984 1.36468L14.3252 8.06523H0V9.93393H14.3252L7.50984 16.6599L8.84706 18L18 9.00127L8.84706 0Z" fill="#95B7A4"/>
</svg>';
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class_name); ?>">
    
    <?php if ($headline_text): ?>
        <header class="styleguide-posts-header">
            <<?php echo $headline_tag; ?> class="styleguide-posts-headline" 
                style="font-weight: <?php echo esc_attr($headline_weight); ?>; font-size: <?php echo esc_attr($headline_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($headline_color)); ?>;">
                <?php echo wp_kses_post($headline_text); ?>
            </<?php echo $headline_tag; ?>>
        </header>
    <?php endif; ?>
    
    <?php if ($posts_query && $posts_query->have_posts()): ?>
        <div class="cards-grid <?php echo esc_attr($grid_class); ?>">
            <?php while ($posts_query->have_posts()): $posts_query->the_post(); ?>
                
                <?php
                // Get post data
                $post_id = get_the_ID();
                $post_title = get_the_title();
                $post_permalink = get_permalink();
                
                // Get post thumbnail with fallback logic
                $post_thumbnail = function_exists('abf_get_card_thumbnail_url') 
                    ? abf_get_card_thumbnail_url($post_id, 'abf-medium') 
                    : (has_post_thumbnail() ? get_the_post_thumbnail_url($post_id, 'medium') : '');
                $thumbnail_alt = function_exists('abf_get_card_thumbnail_alt') 
                    ? abf_get_card_thumbnail_alt($post_id) 
                    : get_post_meta($post_id, '_wp_attachment_image_alt', true);
                ?>
                
                <article class="card card--vertical" id="post-<?php echo $post_id; ?>">
                    <a href="<?php echo esc_url($post_permalink); ?>" class="card__link">
                        
                        <?php if ($post_thumbnail): ?>
                            <!-- Card Image -->
                            <div class="card__image">
                                <img src="<?php echo esc_url($post_thumbnail); ?>" 
                                     alt="<?php echo esc_attr($thumbnail_alt ?: $post_title); ?>" 
                                     loading="lazy">
                            </div>
                        <?php endif; ?>
                        
                        <!-- Card Content -->
                        <div class="card__content">
                            
                            <?php if ($show_category): ?>
                                <?php
                                $categories = get_the_category();
                                if ($categories): ?>
                                    <div class="card__category">
                                        <?php echo esc_html($categories[0]->name); ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <h3 class="card__title">
                                <?php echo esc_html($post_title); ?>
                                <span class="card__icon">
                                    <?php echo $arrow_icon; ?>
                                </span>
                            </h3>
                            
                            <?php if ($show_date): ?>
                                <div class="card__meta">
                                    <time class="card__date" datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($show_excerpt): ?>
                                <div class="card__excerpt">
                                    <?php 
                                    if (has_excerpt()) {
                                        the_excerpt();
                                    } else {
                                        echo wp_trim_words(get_the_content(), 20, '...');
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                        </div>
                        
                    </a>
                </article>
                
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