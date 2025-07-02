<?php
/**
 * Headline Block Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get block data
$headline_text = get_field('headline_text');
$headline_tag = get_field('headline_tag') ?: 'h2';
$headline_size = get_field('headline_size') ?: '24';
$headline_weight = get_field('headline_weight') ?: '400';
$headline_color = get_field('headline_color') ?: 'inherit';
$headline_padding = get_field('headline_padding') ?: 'md';

// Don't render if no text
if (!$headline_text) {
    return;
}

// Build CSS classes
$classes = array('block-headline');
if ($headline_padding && $headline_padding !== 'none') {
    $classes[] = 'p-' . $headline_padding;
}

// Build inline styles
$styles = array();
$styles[] = 'font-size: ' . $headline_size . 'px';
$styles[] = 'font-weight: ' . $headline_weight;
$styles[] = 'line-height: 1.4';

// Color handling
if ($headline_color === 'inherit') {
    $styles[] = 'color: inherit';
} elseif ($headline_color === 'primary' || $headline_color === 'secondary') {
    $styles[] = 'color: var(--color-' . $headline_color . ')';
} else {
    // Dynamic color from colors.json
    $styles[] = 'color: var(--color-' . sanitize_title($headline_color) . ')';
}

// Container class based on location
$container_class = 'container-content'; // 840px max-width
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="<?php echo esc_attr($container_class); ?>">
        <?php 
        printf(
            '<%1$s class="headline-text" style="%2$s">%3$s</%1$s>',
            esc_attr($headline_tag),
            esc_attr(implode('; ', $styles)),
            esc_html($headline_text)
        );
        ?>
    </div>
</div> 