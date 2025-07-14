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

/**
 * Get color value from choice field
 */
if (!function_exists('abf_get_accordion_color_value')) {
    function abf_get_accordion_color_value($color_choice) {
        if (!$color_choice || $color_choice === 'inherit') {
            return 'inherit';
        }
        
        // Check if it's a predefined color from colors.json
        $colors_file = get_template_directory() . '/colors.json';
        if (file_exists($colors_file)) {
            $colors_json = file_get_contents($colors_file);
            $colors = json_decode($colors_json, true);
            
            if (isset($colors[$color_choice])) {
                return $colors[$color_choice];
            }
        }
        
        // If it's already a color value (hex, rgb, etc.), return as is
        if (strpos($color_choice, '#') === 0 || strpos($color_choice, 'rgb') === 0 || strpos($color_choice, 'hsl') === 0) {
            return $color_choice;
        }
        
        // Default fallback
        return 'inherit';
    }
}

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
                <?php
                $headline_styles = [];
                if ($headline_weight) $headline_styles[] = "font-weight: {$headline_weight}";
                if ($headline_size) $headline_styles[] = "font-size: {$headline_size}px";
                if ($headline_color) $headline_styles[] = "color: " . abf_get_accordion_color_value($headline_color);
                $headline_style_attr = !empty($headline_styles) ? ' style="' . implode('; ', $headline_styles) . '"' : '';
                ?>
                <<?php echo $headline_tag; ?> class="styleguide-akkordeon-headline"<?php echo $headline_style_attr; ?>>
                    <?php echo wp_kses_post($headline_text); ?>
                </<?php echo $headline_tag; ?>>
            <?php endif; ?>
            
            <!-- Text Content -->
            <?php if ($text_content): ?>
                <?php
                $text_styles = [];
                if ($text_weight) $text_styles[] = "font-weight: {$text_weight}";
                if ($text_size) $text_styles[] = "font-size: {$text_size}px";
                if ($text_color) $text_styles[] = "color: " . abf_get_accordion_color_value($text_color);
                $text_style_attr = !empty($text_styles) ? ' style="' . implode('; ', $text_styles) . '"' : '';
                ?>
                <<?php echo $text_tag; ?> class="styleguide-akkordeon-text"<?php echo $text_style_attr; ?>>
                    <?php echo wp_kses_post($text_content); ?>
                </<?php echo $text_tag; ?>>
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
                        
                        // Build styles
                        $title_styles = [];
                        if ($title_weight) $title_styles[] = "font-weight: {$title_weight}";
                        if ($title_size) $title_styles[] = "font-size: {$title_size}px";
                        if ($title_color) $title_styles[] = "color: " . abf_get_accordion_color_value($title_color);
                        $title_style_attr = !empty($title_styles) ? ' style="' . implode('; ', $title_styles) . '"' : '';
                        
                        $content_styles = [];
                        if ($content_weight) $content_styles[] = "font-weight: {$content_weight}";
                        if ($content_size) $content_styles[] = "font-size: {$content_size}px";
                        if ($content_color) $content_styles[] = "color: " . abf_get_accordion_color_value($content_color);
                        $content_style_attr = !empty($content_styles) ? ' style="' . implode('; ', $content_styles) . '"' : '';
                        ?>
                        
                        <div class="styleguide-akkordeon-item" data-accordion-item>
                            <<?php echo $accordion_h_tag; ?> class="styleguide-akkordeon-item-header">
                                <button 
                                    class="styleguide-akkordeon-item-trigger" 
                                    aria-expanded="false"
                                    aria-controls="<?php echo esc_attr($item_id); ?>"
                                    data-accordion-trigger
                                    <?php echo $title_style_attr; ?>
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
                                <div class="styleguide-akkordeon-item-content-inner"<?php echo $content_style_attr; ?>>
                                    <?php echo wp_kses_post($content); ?>
                                </div>
                            </div>
                        </div>
                        
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        </div>

</div> 