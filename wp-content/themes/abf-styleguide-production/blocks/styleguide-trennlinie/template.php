<?php
/**
 * Styleguide Trennlinie Block Template
 */

// Get ACF fields
$thickness = get_field('stl_thickness') ?: '2px';
$color = get_field('stl_color') ?: 'primary';
$width = get_field('stl_width') ?: '100%';
$spacing_top = get_field('stl_spacing_top') ?: 'md';
$spacing_bottom = get_field('stl_spacing_bottom') ?: 'md';

// Generate unique ID for this block
$block_id = 'styleguide-trennlinie-' . uniqid();

// Build CSS classes
$classes = array(
    'styleguide-trennlinie',
    'styleguide-trennlinie--' . str_replace('px', '', $thickness),
    'styleguide-trennlinie--' . $color,
    'styleguide-trennlinie--' . str_replace('%', '', $width),
);

// Add spacing classes
if ($spacing_top !== 'none') {
    $classes[] = 'styleguide-trennlinie--spacing-top-' . $spacing_top;
}
if ($spacing_bottom !== 'none') {
    $classes[] = 'styleguide-trennlinie--spacing-bottom-' . $spacing_bottom;
}

// Get theme color for inline styles
$line_color = '#007cba'; // Fallback
if (function_exists('abf_get_color_value')) {
    $line_color = abf_get_color_value($color);
}
?>

<div class="<?php echo implode(' ', $classes); ?>" id="<?php echo $block_id; ?>">
    <div class="styleguide-trennlinie__line" 
         style="height: <?php echo $thickness; ?>; 
                background-color: <?php echo $line_color; ?>; 
                width: <?php echo $width; ?>;">
    </div>
</div>

<style>
    /* Kein inline CSS mehr nötig - SCSS übernimmt das Styling */
</style> 