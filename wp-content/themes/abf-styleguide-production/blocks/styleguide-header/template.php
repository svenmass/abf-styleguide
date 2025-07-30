<?php
/**
 * Styleguide Header Block Template
 */

// Get the block ID
$block_id = 'styleguide-header-' . $block['id'];

// Layout settings - fixed 40/60 split
$image_position = get_field('sh_image_position') ?: 'right';

// Image settings
$image = get_field('sh_image');
$image_fit = get_field('sh_image_fit') ?: 'cover';

// Content fields
$headline_text = get_field('sh_headline_text') ?: '';
$headline_tag = get_field('sh_headline_tag') ?: 'h2';
$headline_size = get_field('sh_headline_size') ?: '24';
$headline_weight = get_field('sh_headline_weight') ?: '400';
$headline_color = get_field('sh_headline_color') ?: 'inherit';

$text_content = get_field('sh_text_content') ?: '';
$text_tag = get_field('sh_text_tag') ?: 'p';

$show_button = get_field('sh_show_button') ?: false;
$button_text = get_field('sh_button_text') ?: '';
$button_url_field = get_field('sh_button_url') ?: '';
$button_url = '';
$button_target = '';
if (is_array($button_url_field) && !empty($button_url_field['url'])) {
    $button_url = $button_url_field['url'];
    $button_target = !empty($button_url_field['target']) ? $button_url_field['target'] : '_self';
} elseif (is_string($button_url_field) && !empty($button_url_field)) {
    // Fallback für alte string-Werte
    $button_url = $button_url_field;
    $button_target = '_self';
}
$button_bg_color = get_field('sh_button_bg_color') ?: 'primary';
$button_text_color = get_field('sh_button_text_color') ?: 'white';
$button_hover_bg_color = get_field('sh_button_hover_bg_color') ?: 'secondary';
$button_hover_text_color = get_field('sh_button_hover_text_color') ?: 'white';

// Hintergrundfarbe (nur für Text-Bereich)
$background_color = get_field('sh_background_color') ?: '';

// Handle button URLs with modal triggers
$href_attr = '#';
$onclick_attr = '';
if ($button_url) {
    // Check for modal triggers
    if (in_array($button_url, ['#register-modal', '#login-modal', '#modal'])) {
        $href_attr = 'javascript:void(0)';
        if ($button_url === '#register-modal') {
            $onclick_attr = ' onClick="abfOpenModal(\'register\')"';
        } elseif ($button_url === '#login-modal') {
            $onclick_attr = ' onClick="abfOpenModal(\'login\')"';
        } elseif ($button_url === '#modal') {
            $onclick_attr = ' onClick="abfOpenModal(\'register\')"';
        }
    } else {
        $href_attr = esc_url($button_url);
    }
}

// Fixed 40/60 layout - Text is always 40%, Image is always 60%  
$text_column_fr = '4fr';  // 40%
$image_column_fr = '6fr'; // 60%

// Grid template based on image position
if ($image_position === 'left') {
    $grid_template = $image_column_fr . ' ' . $text_column_fr; // Image Left, Text Right
} else {
    $grid_template = $text_column_fr . ' ' . $image_column_fr; // Text Left, Image Right
}

// Downloads
$downloads = get_field('sh_downloads') ?: array();

// Check if there's any content to display
$has_content = !empty($headline_text) || !empty($text_content) || !empty($image) || !empty($downloads) || ($show_button && !empty($button_text) && !empty($button_url));

if (!$has_content) {
    return;
}

// Dynamische CSS für Headline
$headline_css = '';
if (function_exists('abf_get_color_value') && $headline_color !== 'inherit') {
    $color_value = abf_get_color_value($headline_color);
    if ($color_value) {
        $headline_css .= 'color: ' . $color_value . '; ';
    }
}
if (function_exists('abf_get_font_size_value')) {
    $font_size = abf_get_font_size_value($headline_size);
    if ($font_size) {
        $headline_css .= 'font-size: ' . $font_size . '; ';
    }
}
if (function_exists('abf_get_font_weight_value')) {
    $font_weight = abf_get_font_weight_value($headline_weight);
    if ($font_weight) {
        $headline_css .= 'font-weight: ' . $font_weight . '; ';
    }
}

// Dynamische CSS für Button
$button_css = '';
if (function_exists('abf_get_color_value')) {
    $bg_color = abf_get_color_value($button_bg_color);
    $text_color = abf_get_color_value($button_text_color);
    $hover_bg_color = abf_get_color_value($button_hover_bg_color);
    $hover_text_color = abf_get_color_value($button_hover_text_color);
    
    if ($bg_color) {
        $button_css .= 'background-color: ' . $bg_color . '; ';
    }
    if ($text_color) {
        $button_css .= 'color: ' . $text_color . '; ';
    }
}

// Dynamische CSS für Text-Hintergrund
$text_background_css = '';
if (function_exists('abf_get_color_value') && !empty($background_color)) {
    $bg_color_value = abf_get_color_value($background_color);
    if ($bg_color_value) {
        $text_background_css = 'background-color: ' . $bg_color_value . ';';
    }
}
?>

<div class="block-styleguide-header" id="<?php echo esc_attr($block_id); ?>">
    
    <style>
        #<?php echo esc_attr($block_id); ?> .styleguide-header-grid {
            grid-template-columns: <?php echo esc_attr($grid_template); ?>;
        }
        
        <?php if (!empty($button_css)): ?>
        #<?php echo esc_attr($block_id); ?> .sh-button {
            <?php echo $button_css; ?>
        }
        
        #<?php echo esc_attr($block_id); ?> .sh-button:hover {
            <?php if ($hover_bg_color): ?>background-color: <?php echo $hover_bg_color; ?>; <?php endif; ?>
            <?php if ($hover_text_color): ?>color: <?php echo $hover_text_color; ?>; <?php endif; ?>
        }
        <?php endif; ?>
    </style>
    
    <div class="styleguide-header-container<?php echo !empty($background_color) ? ' has-background-color' : ''; ?>">
        <div class="styleguide-header-grid">
            
            <?php if ($image_position === 'left'): ?>
                <!-- Image Column First (Left) -->
                <div class="styleguide-header-image-column">
                    <?php if (!empty($image)): ?>
                        <div class="styleguide-header-image-container styleguide-header-image--<?php echo esc_attr($image_fit); ?>">
                            <img src="<?php echo esc_url($image['url']); ?>" 
                                 alt="<?php echo esc_attr($image['alt'] ?: $image['title'] ?: ''); ?>" 
                                 class="styleguide-header-image">
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Text Column Second (Right) -->
                <div class="styleguide-header-content-column">
                    <div class="styleguide-header-content"<?php if ($text_background_css): ?> style="<?php echo esc_attr($text_background_css); ?>"<?php endif; ?>>
                        
                        <?php if (!empty($headline_text)): ?>
                            <<?php echo esc_html($headline_tag); ?> class="sh-headline" <?php if ($headline_css): ?>style="<?php echo esc_attr($headline_css); ?>"<?php endif; ?>>
                                <?php echo esc_html($headline_text); ?>
                            </<?php echo esc_html($headline_tag); ?>>
                        <?php endif; ?>
                        
                        <?php if (!empty($text_content)): ?>
                            <<?php echo esc_html($text_tag); ?> class="sh-text-content">
                                <?php echo wp_kses_post($text_content); ?>
                            </<?php echo esc_html($text_tag); ?>>
                        <?php endif; ?>
                        
                        <?php if (!empty($downloads) && is_array($downloads)): ?>
                            <div class="sh-downloads">
                                <h4 class="sh-downloads-title">Downloads</h4>
                                <ul class="sh-downloads-list">
                                    <?php foreach ($downloads as $download): ?>
                                        <?php 
                                        $title = $download['download_title'] ?? '';
                                        $file = $download['download_link'] ?? '';
                                        
                                        if (empty($title) || empty($file)) continue;
                                        
                                        $file_url = '';
                                        $file_size = '';
                                        $file_type = '';
                                        
                                        if (is_array($file)) {
                                            $file_url = $file['url'] ?? '';
                                            $file_size = !empty($file['filesize']) ? size_format($file['filesize']) : '';
                                            $file_type = strtoupper(pathinfo($file['filename'] ?? '', PATHINFO_EXTENSION));
                                        } elseif (is_string($file)) {
                                            $file_url = $file;
                                            $file_type = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
                                        }
                                        
                                        if (empty($file_url)) continue;
                                        ?>
                                        <li>
                                            <a href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener">
                                                <?php echo esc_html($title); ?>
                                                <?php if ($file_type || $file_size): ?>
                                                    <span class="file-meta">[<?php 
                                                        $meta_parts = array();
                                                        if ($file_type) $meta_parts[] = $file_type;
                                                        if ($file_size) $meta_parts[] = $file_size;
                                                        echo esc_html(implode(', ', $meta_parts));
                                                    ?>]</span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($show_button && !empty($button_text) && !empty($href_attr) && $href_attr !== '#'): ?>
                            <div class="sh-button-wrapper">
                                <a href="<?php echo esc_attr($href_attr); ?>" 
                                   class="sh-button" 
                                   <?php if ($button_target !== '_self'): ?>target="<?php echo esc_attr($button_target); ?>" rel="noopener"<?php endif; ?>
                                   <?php echo $onclick_attr; ?>>
                                    <?php echo esc_html($button_text); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                
            <?php else: ?>
                <!-- Text Column First (Left) -->
                <div class="styleguide-header-content-column">
                    <div class="styleguide-header-content"<?php if ($text_background_css): ?> style="<?php echo esc_attr($text_background_css); ?>"<?php endif; ?>>
                        
                        <?php if (!empty($headline_text)): ?>
                            <<?php echo esc_html($headline_tag); ?> class="sh-headline" <?php if ($headline_css): ?>style="<?php echo esc_attr($headline_css); ?>"<?php endif; ?>>
                                <?php echo esc_html($headline_text); ?>
                            </<?php echo esc_html($headline_tag); ?>>
                        <?php endif; ?>
                        
                        <?php if (!empty($text_content)): ?>
                            <<?php echo esc_html($text_tag); ?> class="sh-text-content">
                                <?php echo wp_kses_post($text_content); ?>
                            </<?php echo esc_html($text_tag); ?>>
                        <?php endif; ?>
                        
                        <?php if (!empty($downloads) && is_array($downloads)): ?>
                            <div class="sh-downloads">
                                <h4 class="sh-downloads-title">Downloads</h4>
                                <ul class="sh-downloads-list">
                                    <?php foreach ($downloads as $download): ?>
                                        <?php 
                                        $title = $download['download_title'] ?? '';
                                        $file = $download['download_link'] ?? '';
                                        
                                        if (empty($title) || empty($file)) continue;
                                        
                                        $file_url = '';
                                        $file_size = '';
                                        $file_type = '';
                                        
                                        if (is_array($file)) {
                                            $file_url = $file['url'] ?? '';
                                            $file_size = !empty($file['filesize']) ? size_format($file['filesize']) : '';
                                            $file_type = strtoupper(pathinfo($file['filename'] ?? '', PATHINFO_EXTENSION));
                                        } elseif (is_string($file)) {
                                            $file_url = $file;
                                            $file_type = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
                                        }
                                        
                                        if (empty($file_url)) continue;
                                        ?>
                                        <li>
                                            <a href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener">
                                                <?php echo esc_html($title); ?>
                                                <?php if ($file_type || $file_size): ?>
                                                    <span class="file-meta">[<?php 
                                                        $meta_parts = array();
                                                        if ($file_type) $meta_parts[] = $file_type;
                                                        if ($file_size) $meta_parts[] = $file_size;
                                                        echo esc_html(implode(', ', $meta_parts));
                                                    ?>]</span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($show_button && !empty($button_text) && !empty($href_attr) && $href_attr !== '#'): ?>
                            <div class="sh-button-wrapper">
                                <a href="<?php echo esc_attr($href_attr); ?>" 
                                   class="sh-button" 
                                   <?php if ($button_target !== '_self'): ?>target="<?php echo esc_attr($button_target); ?>" rel="noopener"<?php endif; ?>
                                   <?php echo $onclick_attr; ?>>
                                    <?php echo esc_html($button_text); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                
                <!-- Image Column Second (Right) -->
                <div class="styleguide-header-image-column">
                    <?php if (!empty($image)): ?>
                        <div class="styleguide-header-image-container styleguide-header-image--<?php echo esc_attr($image_fit); ?>">
                            <img src="<?php echo esc_url($image['url']); ?>" 
                                 alt="<?php echo esc_attr($image['alt'] ?: $image['title'] ?: ''); ?>" 
                                 class="styleguide-header-image">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div> 