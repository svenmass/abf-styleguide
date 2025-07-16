<?php
/**
 * Styleguide Akkordeon Block Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Extract block data
$fields = get_fields();

if (!$fields) {
    return;
}

// Block ID für einzigartige IDs
$unique_id_prefix = 'akkordeon-' . uniqid();

// Color function is now centralized in functions.php

/**
 * Get correct H-tag for accordion items based on main headline
 */
if (!function_exists('abf_get_accordion_h_tag')) {
    function abf_get_accordion_h_tag($main_headline_tag) {
        // Map headline tags to next level
        $tag_map = [
            'h1' => 'h2',
            'h2' => 'h3', 
            'h3' => 'h4',
            'h4' => 'h5',
            'h5' => 'h6',
            'h6' => 'h6', // Can't go lower than h6
        ];
        
        return isset($tag_map[$main_headline_tag]) ? $tag_map[$main_headline_tag] : 'h3';
    }
}

// Get content fields
$headline_text = $fields['sa_headline_text'] ?? '';
$headline_tag = $fields['sa_headline_tag'] ?? 'h2';
$headline_size = $fields['sa_headline_size'] ?? '24';
$headline_weight = $fields['sa_headline_weight'] ?? '400';
$headline_color = $fields['sa_headline_color'] ?? 'inherit';

$text_content = $fields['sa_text_content'] ?? '';
$text_tag = $fields['sa_text_tag'] ?? 'p';
$text_size = $fields['sa_text_size'] ?? '18';
$text_weight = $fields['sa_text_weight'] ?? '400';
$text_color = $fields['sa_text_color'] ?? 'inherit';

$accordion_items = $fields['sa_accordion_items'] ?? array();

// Don't render if no content
if (!$headline_text && !$text_content && empty($accordion_items)) {
    return;
}

// Auto-generate accordion item H-tag based on main headline
$accordion_h_tag = abf_get_accordion_h_tag($headline_tag);
?>

<div class="block-styleguide-akkordeon" id="<?php echo esc_attr($block_id); ?>">
        <div class="styleguide-akkordeon-container">
            
            <!-- Headline -->
            <?php if ($headline_text): ?>
                <<?php echo $headline_tag; ?> class="styleguide-akkordeon-headline" 
                    style="font-weight: <?php echo esc_attr($headline_weight); ?>; font-size: <?php echo esc_attr($headline_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($headline_color)); ?>;">
                    <?php echo wp_kses_post($headline_text); ?>
                </<?php echo $headline_tag; ?>>
            <?php endif; ?>
            
            <!-- Text Content -->
            <?php if ($text_content): ?>
                <div class="styleguide-akkordeon-text" 
                    style="font-weight: <?php echo esc_attr($text_weight); ?>; font-size: <?php echo esc_attr($text_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($text_color)); ?>;">
                    <?php echo wp_kses_post($text_content); ?>
                </div>
            <?php endif; ?>
            
            <!-- Accordion Items -->
            <?php if (!empty($accordion_items)): ?>
                <div class="styleguide-akkordeon-items">
                    <?php foreach ($accordion_items as $index => $item): ?>
                        <?php
                        $item_id = $unique_id_prefix . '-' . $index;
                        $title = $item['accordion_title'] ?? '';
                        $content = $item['accordion_content'] ?? '';
                        
                        // Title styling
                        $title_size = $item['accordion_title_size'] ?? '18';
                        $title_weight = $item['accordion_title_weight'] ?? '600';
                        $title_color = $item['accordion_title_color'] ?? 'inherit';
                        
                        // Content styling
                        $content_size = $item['accordion_content_size'] ?? '18';
                        $content_weight = $item['accordion_content_weight'] ?? '400';
                        $content_color = $item['accordion_content_color'] ?? 'inherit';
                        
                        if (!$title || !$content) continue;
                        ?>
                        
                        <div class="styleguide-akkordeon-item" data-accordion-item>
                            <<?php echo $accordion_h_tag; ?> class="styleguide-akkordeon-item-header">
                                <button 
                                    class="styleguide-akkordeon-item-trigger" 
                                    aria-expanded="false"
                                    aria-controls="<?php echo esc_attr($item_id); ?>"
                                    data-accordion-trigger
                                    style="font-weight: <?php echo esc_attr($title_weight); ?>; font-size: <?php echo esc_attr($title_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($title_color)); ?>;"
                                >
                                    <span class="styleguide-akkordeon-item-title">
                                        <?php echo esc_html($title); ?>
                                    </span>
                                    <span class="styleguide-akkordeon-item-icon" aria-hidden="true">
                                        <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <line x1="15" y1="7.6066" x2="-4.37113e-08" y2="7.6066" stroke="#74A68E"/>
                                            <line x1="7.5" y1="15.1066" x2="7.5" y2="0.106598" stroke="#74A68E"/>
                                        </svg>
                                    </span>
                                </button>
                            </<?php echo $accordion_h_tag; ?>>
                            
                            <div 
                                class="styleguide-akkordeon-item-content" 
                                id="<?php echo esc_attr($item_id); ?>"
                                aria-hidden="true"
                                data-accordion-content
                            >
                                <div class="styleguide-akkordeon-item-content-inner" 
                                    style="font-weight: <?php echo esc_attr($content_weight); ?>; font-size: <?php echo esc_attr($content_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($content_color)); ?>;">
                                    <?php echo wp_kses_post($content); ?>
                                </div>
                            </div>
                        </div>
                        
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Downloads Section -->
            <?php 
            $downloads = get_field('sa_downloads');
            if ($downloads && is_array($downloads) && count($downloads) > 0): 
            ?>
                <div class="styleguide-akkordeon-downloads">
                    <?php foreach ($downloads as $download): ?>
                        <?php if ($download['download_title'] && $download['download_link']): ?>
                            <a href="<?php echo esc_url($download['download_link']['url']); ?>" download>
                                <?php echo esc_html($download['download_title']); ?>
                                <?php echo abf_get_file_meta($download['download_link']); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        </div>

</div> 