<?php
/**
 * Styleguide Text Zweispaltig Block Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get block data with defensive checks
$headline_text = get_field('stz_headline_text') ?: '';
$headline_tag = get_field('stz_headline_tag') ?: 'h2';
$headline_color = get_field('stz_headline_color') ?: 'inherit';
$left_text = get_field('stz_left_text') ?: '';
$right_text = get_field('stz_right_text') ?: '';
$section_spacing = get_field('stz_section_spacing') ?: 'default';
$container_width = get_field('stz_container_width') ?: 'content';

// Don't render if no content
if (empty($left_text) && empty($right_text)) {
    return;
}

// Generate CSS classes
$classes = array('styleguide-text-zweispaltig');
$classes[] = 'spacing-' . esc_attr($section_spacing);

// Container class based on width setting
$container_class = '';
switch ($container_width) {
    case 'wide': $container_class = 'container-wide'; break;
    case 'full': $container_class = 'container-full'; break;
    default: $container_class = 'container-content'; break;
}

// Get headline color with fallback
$headline_style = '';
if ($headline_color && $headline_color !== 'inherit') {
    if (function_exists('abf_get_color_value')) {
        $color_value = abf_get_color_value($headline_color);
        if ($color_value) {
            $headline_style = 'style="color: ' . esc_attr($color_value) . '"';
        }
    }
}
?>
<section class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="<?php echo esc_attr($container_class); ?>">
        
        <?php if (!empty($headline_text)): ?>
            <<?php echo esc_html($headline_tag); ?> class="stz-headline" <?php echo $headline_style; ?>>
                <?php echo esc_html($headline_text); ?>
            </<?php echo esc_html($headline_tag); ?>>
        <?php endif; ?>
        
        <div class="stz-content-wrapper">
            <div class="stz-left-column">
                <?php if (!empty($left_text)): ?>
                    <div class="stz-text-content">
                        <?php echo wp_kses_post($left_text); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="stz-right-column">
                <?php if (!empty($right_text)): ?>
                    <div class="stz-text-content">
                        <?php echo wp_kses_post($right_text); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
</section> 