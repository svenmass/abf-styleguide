<?php
/**
 * Styleguide Text Zweispaltig Block Template
 */

// Get the block ID
$block_id = 'styleguide-text-zweispaltig-' . $block['id'];

// Get content fields - Headline
$headline_text = get_field('stz_headline_text') ?: '';
$headline_tag = get_field('stz_headline_tag') ?: 'h2';
$headline_size = get_field('stz_headline_size') ?: '24';
$headline_weight = get_field('stz_headline_weight') ?: '400';
$headline_color = get_field('stz_headline_color') ?: 'inherit';

// Get content fields - Left Text
$left_text_content = get_field('stz_left_text') ?: '';
$left_text_tag = get_field('stz_left_text_tag') ?: 'p';

// Get content fields - Right Text  
$right_text_content = get_field('stz_right_text') ?: '';
$right_text_tag = get_field('stz_right_text_tag') ?: 'p';

// Hintergrundfarbe (optional)
$background_color = get_field('stz_background_color') ?: '';

// Button fields
$show_button = get_field('stz_show_button') ?: false;
$button_text = get_field('stz_button_text') ?: '';
$button_url_field = get_field('stz_button_url') ?: '';
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
$button_bg_color = get_field('stz_button_bg_color') ?: 'primary';
$button_text_color = get_field('stz_button_text_color') ?: 'white';
$button_hover_bg_color = get_field('stz_button_hover_bg_color') ?: 'secondary';
$button_hover_text_color = get_field('stz_button_hover_text_color') ?: 'white';

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
        } else {
            $onclick_attr = ' onClick="abfOpenModal()"';
        }
    } else {
        $href_attr = esc_url($button_url);
    }
}

// Downloads
$downloads = get_field('stz_downloads') ?: array();

// Check if there's any content to display
$has_content = !empty($headline_text) || !empty($left_text_content) || !empty($right_text_content) || !empty($downloads) || ($show_button && !empty($button_text) && !empty($button_url));

if (!$has_content) {
    return;
}

// Container classes
$container_classes = array('styleguide-text-zweispaltig');
if (!empty($background_color)) {
    $container_classes[] = 'has-background';
    $container_classes[] = 'bg-' . $background_color;
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
    
    // Hover-Styles
    if ($hover_bg_color || $hover_text_color) {
        $button_css .= ' } #' . $block_id . ' .stz-button:hover { ';
        if ($hover_bg_color) {
            $button_css .= 'background-color: ' . $hover_bg_color . '; ';
        }
        if ($hover_text_color) {
            $button_css .= 'color: ' . $hover_text_color . '; ';
        }
    }
}
?>

<div id="<?php echo esc_attr($block_id); ?>" class="<?php echo esc_attr(implode(' ', $container_classes)); ?>">
    
    <?php if (!empty($button_css)): ?>
        <style>
            #<?php echo esc_attr($block_id); ?> .stz-button {
                <?php echo $button_css; ?>
            }
        </style>
    <?php endif; ?>
    
    <?php if (!empty($headline_text)): ?>
        <<?php echo esc_html($headline_tag); ?> class="stz-headline" <?php if ($headline_css): ?>style="<?php echo esc_attr($headline_css); ?>"<?php endif; ?>>
            <?php echo esc_html($headline_text); ?>
        </<?php echo esc_html($headline_tag); ?>>
    <?php endif; ?>
    
    <?php if (!empty($left_text_content) || !empty($right_text_content)): ?>
        <div class="stz-text-columns">
            <div class="stz-text-column stz-text-left">
                <?php if (!empty($left_text_content)): ?>
                    <<?php echo esc_html($left_text_tag); ?> class="stz-text-content">
                        <?php echo wp_kses_post($left_text_content); ?>
                    </<?php echo esc_html($left_text_tag); ?>>
                <?php endif; ?>
            </div>
            
            <div class="stz-text-column stz-text-right">
                <?php if (!empty($right_text_content)): ?>
                    <<?php echo esc_html($right_text_tag); ?> class="stz-text-content">
                        <?php echo wp_kses_post($right_text_content); ?>
                    </<?php echo esc_html($right_text_tag); ?>>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($downloads) && is_array($downloads)): ?>
        <div class="stz-downloads">
            <h4 class="stz-downloads-title">Downloads</h4>
            <ul class="stz-downloads-list">
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
    
    <?php if ($show_button && !empty($button_text) && !empty($button_url)): ?>
        <div class="stz-button-wrapper">
            <a href="<?php echo esc_attr($href_attr); ?>" 
               class="stz-button" 
               <?php if ($button_target !== '_self'): ?>target="<?php echo esc_attr($button_target); ?>" rel="noopener"<?php endif; ?>
               <?php echo $onclick_attr; ?>>
                <?php echo esc_html($button_text); ?>
            </a>
        </div>
    <?php endif; ?>
    
</div> 