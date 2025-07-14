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
    <div class="container-content">
        <div class="styleguide-trennlinie__line" 
             style="height: <?php echo $thickness; ?>; 
                    background-color: <?php echo $line_color; ?>; 
                    width: <?php echo $width; ?>;">
        </div>
    </div>
</div>

<style>
    #<?php echo $block_id; ?> {
        width: 100%;
        max-width: 100%;
    }
    
    #<?php echo $block_id; ?> .container-content {
        max-width: 960px;
        margin: 0 auto;
        padding: 0 32px;
    }
    
    #<?php echo $block_id; ?> .styleguide-trennlinie__line {
        margin: 0 auto;
        display: block;
    }
    
    /* Spacing Top */
    #<?php echo $block_id; ?>.styleguide-trennlinie--spacing-top-xs {
        padding-top: 12px;
    }
    #<?php echo $block_id; ?>.styleguide-trennlinie--spacing-top-sm {
        padding-top: 16px;
    }
    #<?php echo $block_id; ?>.styleguide-trennlinie--spacing-top-md {
        padding-top: 24px;
    }
    #<?php echo $block_id; ?>.styleguide-trennlinie--spacing-top-lg {
        padding-top: 32px;
    }
    
    /* Spacing Bottom */
    #<?php echo $block_id; ?>.styleguide-trennlinie--spacing-bottom-xs {
        padding-bottom: 12px;
    }
    #<?php echo $block_id; ?>.styleguide-trennlinie--spacing-bottom-sm {
        padding-bottom: 16px;
    }
    #<?php echo $block_id; ?>.styleguide-trennlinie--spacing-bottom-md {
        padding-bottom: 24px;
    }
    #<?php echo $block_id; ?>.styleguide-trennlinie--spacing-bottom-lg {
        padding-bottom: 32px;
    }
</style> 