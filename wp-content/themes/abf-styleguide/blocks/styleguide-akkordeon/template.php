<?php
/**
 * Styleguide Akkordeon Block Template
 */

// Get the block ID
$block_id = 'styleguide-akkordeon-' . $block['id'];

// Get content fields
$headline_text = get_field('sa_headline_text') ?: '';
$headline_tag = get_field('sa_headline_tag') ?: 'h2';
$headline_size = get_field('sa_headline_size') ?: '24';
$headline_weight = get_field('sa_headline_weight') ?: '400';
$headline_color = get_field('sa_headline_color') ?: 'inherit';

$text_content = get_field('sa_text_content') ?: '';
$text_tag = get_field('sa_text_tag') ?: 'p';
$text_size = get_field('sa_text_size') ?: '18';
$text_weight = get_field('sa_text_weight') ?: '400';
$text_color = get_field('sa_text_color') ?: 'inherit';

$accordion_items = get_field('sa_accordion_items') ?: array();

// Convert color choices to actual values
function abf_get_accordion_color_value($color_choice) {
    if (!$color_choice || $color_choice === 'inherit') {
        return 'inherit';
    }
    
    // Handle primary and secondary colors
    if ($color_choice === 'primary') {
        return 'var(--color-primary)';
    } elseif ($color_choice === 'secondary') {
        return 'var(--color-secondary)';
    } elseif ($color_choice === 'white') {
        return '#ffffff';
    } elseif ($color_choice === 'black') {
        return '#000000';
    }
    
    // Try to get dynamic color from colors.json
    if (function_exists('abf_get_color_value')) {
        $color_value = abf_get_color_value($color_choice);
        if ($color_value) {
            return $color_value;
        }
    }
    
    // Fallback to CSS variable
    return "var(--color-" . sanitize_title($color_choice) . ")";
}

// Auto-generate accordion item H-tag based on main headline
function abf_get_accordion_h_tag($main_headline_tag) {
    $h_number = (int)filter_var($main_headline_tag, FILTER_SANITIZE_NUMBER_INT);
    
    if ($h_number >= 1 && $h_number <= 5) {
        return 'h' . ($h_number + 1);
    }
    
    // Fallback
    return 'h3';
}

$accordion_h_tag = abf_get_accordion_h_tag($headline_tag);

// Don't render if no content
if (!$headline_text && !$text_content && empty($accordion_items)) {
    return;
}

// Generate unique IDs for each accordion item
$unique_id_prefix = 'accordion-' . uniqid();
?>

<div class="block-styleguide-akkordeon" id="<?php echo esc_attr($block_id); ?>">
    <div class="container-content">
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
</div> 