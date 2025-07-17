<?php
/**
 * Styleguide Grid Block Template
 *
 * @package ABF_Styleguide
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'styleguide-grid-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'styleguide-grid-block';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

// Get ACF fields
$headline_text = get_field('sg_headline_text');
$headline_tag = get_field('sg_headline_tag') ?: 'h2';
$headline_size = get_field('sg_headline_size') ?: 'h2';
$headline_weight = get_field('sg_headline_weight') ?: 'regular';
$headline_color = get_field('sg_headline_color') ?: 'default';
$grid_items = get_field('sg_grid_items');

// Early return if no grid items
if (!$grid_items || !is_array($grid_items)) {
    echo '<div class="styleguide-grid-placeholder"><p>Bitte fügen Sie Grid-Items hinzu.</p></div>';
    return;
}

// Prepare headline classes
$headline_classes = array();
$headline_classes[] = 'styleguide-grid-headline';
$headline_classes[] = 'font-size-' . $headline_size;
$headline_classes[] = 'font-weight-' . $headline_weight;
if ($headline_color !== 'default') {
    $headline_classes[] = 'color-' . $headline_color;
}
$headline_class_string = implode(' ', $headline_classes);
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class_name); ?>">
    
    <?php if ($headline_text): ?>
        <<?php echo esc_attr($headline_tag); ?> class="<?php echo esc_attr($headline_class_string); ?>">
            <?php echo esc_html($headline_text); ?>
        </<?php echo esc_attr($headline_tag); ?>>
    <?php endif; ?>
    
    <div class="styleguide-grid-container">
        <?php foreach ($grid_items as $index => $item): ?>
            <?php
            $icon_svg = $item['icon_svg'];
            $icon_name = $item['icon_name'];
            $show_download = $item['show_download'];
            $download_text = $item['download_text'] ?: 'Download';
            $download_file = $item['download_file'];
            
            // Convert ACF boolean values to actual booleans
            $show_download = ($show_download == 1 || $show_download === true || $show_download === '1');
            
            // Determine download URL (custom file or original SVG)
            $download_url = '';
            $download_filename = '';
            if ($show_download) {
                if ($download_file && is_array($download_file)) {
                    $download_url = $download_file['url'];
                    $download_filename = $download_file['filename'];
                } elseif ($icon_svg && is_array($icon_svg)) {
                    $download_url = $icon_svg['url'];
                    $download_filename = $icon_svg['filename'];
                }
            }
            
            // Skip if no SVG
            if (!$icon_svg || !is_array($icon_svg)) {
                continue;
            }
            ?>
            
            <div class="styleguide-grid-item">
                
                <!-- SVG Icon -->
                <div class="styleguide-grid-icon">
                    <img src="<?php echo esc_url($icon_svg['url']); ?>" 
                         alt="<?php echo esc_attr($icon_name); ?>" 
                         title="<?php echo esc_attr($icon_name); ?>"
                         loading="lazy">
                </div>
                
                <!-- Icon Name -->
                <div class="styleguide-grid-name">
                    <?php echo esc_html($icon_name); ?>
                </div>
                
                <!-- Download Link -->
                <?php if ($show_download && $download_url): ?>
                    <div class="styleguide-grid-download">
                        <a href="<?php echo esc_url($download_url); ?>" 
                           download="<?php echo esc_attr($download_filename); ?>" 
                           target="_blank" 
                           rel="noopener"
                           class="styleguide-grid-download-link">
                            <?php echo esc_html($download_text); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
            </div>
            
        <?php endforeach; ?>
    </div>
    
</div>

<?php
// Debug output for administrators
if (current_user_can('administrator') && defined('WP_DEBUG') && WP_DEBUG) {
    echo '<!-- DEBUG: Styleguide Grid Block -->';
    echo '<!-- Grid Items: ' . count($grid_items) . ' -->';
    echo '<!-- Headline: ' . ($headline_text ? $headline_text : 'None') . ' -->';
    echo '<!-- END DEBUG -->';
}
?> 