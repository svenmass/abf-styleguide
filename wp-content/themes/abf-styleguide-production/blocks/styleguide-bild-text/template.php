<?php
/**
 * Styleguide Bild-Text Block Template
 */

// Get the block ID
$block_id = 'styleguide-bild-text-' . $block['id'];

// Layout settings
$column_split = get_field('sbt_column_split') ?: '50/50';
$image_position = get_field('sbt_image_position') ?: 'left';

// Image settings
$image = get_field('sbt_image');
$image_fit = get_field('sbt_image_fit') ?: 'cover';

// Content fields
$headline_text = get_field('sbt_headline_text') ?: '';
$headline_tag = get_field('sbt_headline_tag') ?: 'h2';
$headline_size = get_field('sbt_headline_size') ?: '24';
$headline_weight = get_field('sbt_headline_weight') ?: '400';
$headline_color = get_field('sbt_headline_color') ?: 'inherit';

$text_content = get_field('sbt_text_content') ?: '';
$text_tag = get_field('sbt_text_tag') ?: 'p';

$show_button = get_field('sbt_show_button') ?: false;
$button_text = get_field('sbt_button_text') ?: '';
$button_url_field = get_field('sbt_button_url') ?: '';
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
$button_bg_color = get_field('sbt_button_bg_color') ?: 'primary';
$button_text_color = get_field('sbt_button_text_color') ?: 'white';
$button_hover_bg_color = get_field('sbt_button_hover_bg_color') ?: 'secondary';
$button_hover_text_color = get_field('sbt_button_hover_text_color') ?: 'white';

// Hintergrundfarbe (optional)
$background_color = get_field('sbt_background_color') ?: '';

// Color function is now centralized in functions.php

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

// Calculate grid columns based on split - use fr units for flexibility
$splits = explode('/', $column_split);
$left_width = (int) $splits[0];
$right_width = (int) $splits[1];

// Convert to fr units for flexible grid (25 becomes 2.5fr, 50 becomes 5fr, etc.)
$left_fr = $left_width / 10;
$right_fr = $right_width / 10;

// Determine which column gets which content
$image_column_fr = $image_position === 'left' ? $left_fr : $right_fr;
$text_column_fr = $image_position === 'left' ? $right_fr : $left_fr;

?>

<div class="block-styleguide-bild-text" id="<?php echo esc_attr($block_id); ?>" data-split="<?php echo esc_attr($column_split); ?>" data-image-position="<?php echo esc_attr($image_position); ?>">
    <div class="styleguide-bild-text-container<?php echo $background_color ? ' has-background-color' : ''; ?>"
         <?php if ($background_color): ?>
         style="background-color: <?php echo esc_attr(abf_get_styleguide_color_value($background_color)); ?>;"
         <?php endif; ?>>
        
        <div class="styleguide-bild-text-grid" style="grid-template-columns: <?php echo $image_position === 'left' ? $left_fr . 'fr ' . $right_fr . 'fr' : $right_fr . 'fr ' . $left_fr . 'fr'; ?>;">
            
            <?php if ($image_position === 'left'): ?>
                <!-- Bild-Spalte (links) -->
                <div class="styleguide-bild-text-image-column">
                    <?php if ($image): ?>
                        <div class="styleguide-bild-text-image-container styleguide-bild-text-image--<?php echo esc_attr($image_fit); ?>">
                            <img src="<?php echo esc_url($image['url']); ?>" 
                                 alt="<?php echo esc_attr($image['alt']); ?>" 
                                 class="styleguide-bild-text-image"/>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Text-Spalte (rechts) -->
                <div class="styleguide-bild-text-content-column">
                    <div class="styleguide-bild-text-content">
                        
                        <?php if ($headline_text): ?>
                            <<?php echo $headline_tag; ?> class="styleguide-bild-text-headline" 
                                style="font-weight: <?php echo esc_attr($headline_weight); ?>; font-size: <?php echo esc_attr($headline_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($headline_color)); ?>;">
                                <?php echo wp_kses_post($headline_text); ?>
                            </<?php echo $headline_tag; ?>>
                        <?php endif; ?>
                        
                        <?php if ($text_content): ?>
                            <div class="styleguide-bild-text-text">
                                <?php echo wp_kses_post($text_content); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php 
                        // Downloads/Links
                        $downloads = get_field('sbt_downloads');
                        if ($downloads && is_array($downloads) && count($downloads) > 0): 
                        ?>
                            <div class="styleguide-bild-text-downloads">
                                <?php foreach ($downloads as $download): ?>
                                    <?php if ($download['download_title'] && $download['download_link']): ?>
                                        <a href="<?php echo esc_url($download['download_link']['url']); ?>" download>
                                            <?php echo esc_html($download['download_title']); ?>
                                            <?php if ($download['download_link']['filesize']): ?>
                                                <?php echo abf_get_file_meta($download['download_link']); ?>
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($show_button && $button_text && $button_url): ?>
                            <?php
                            $button_styles = [];
                            if ($button_bg_color) $button_styles[] = "background-color: " . abf_get_styleguide_color_value($button_bg_color);
                            if ($button_text_color) $button_styles[] = "color: " . abf_get_styleguide_color_value($button_text_color);
                            $button_style_attr = !empty($button_styles) ? ' style="' . implode('; ', $button_styles) . '"' : '';
                            ?>
                            
                            <div class="styleguide-bild-text-button-wrapper">
                                <a href="<?php echo $href_attr; ?>" 
                                   target="<?php echo esc_attr($button_target); ?>"
                                   class="btn styleguide-bild-text__button"
                                   data-button-id="<?php echo esc_attr($block_id); ?>-btn"
                                   data-hover-bg="<?php echo esc_attr(abf_get_styleguide_color_value($button_hover_bg_color)); ?>"
                                   data-hover-text="<?php echo esc_attr(abf_get_styleguide_color_value($button_hover_text_color)); ?>"
                                   data-normal-bg="<?php echo esc_attr(abf_get_styleguide_color_value($button_bg_color)); ?>"
                                   data-normal-text="<?php echo esc_attr(abf_get_styleguide_color_value($button_text_color)); ?>"
                                   <?php echo $button_style_attr; ?><?php echo $onclick_attr; ?>>
                                    <?php echo esc_html($button_text); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                
            <?php else: ?>
                <!-- Text-Spalte (links) -->
                <div class="styleguide-bild-text-content-column">
                    <div class="styleguide-bild-text-content">
                        
                        <?php if ($headline_text): ?>
                            <<?php echo $headline_tag; ?> class="styleguide-bild-text-headline" 
                                style="font-weight: <?php echo esc_attr($headline_weight); ?>; font-size: <?php echo esc_attr($headline_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($headline_color)); ?>;">
                                <?php echo wp_kses_post($headline_text); ?>
                            </<?php echo $headline_tag; ?>>
                        <?php endif; ?>
                        
                        <?php if ($text_content): ?>
                            <div class="styleguide-bild-text-text">
                                <?php echo wp_kses_post($text_content); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php 
                        // Downloads/Links
                        $downloads = get_field('sbt_downloads');
                        if ($downloads && is_array($downloads) && count($downloads) > 0): 
                        ?>
                            <div class="styleguide-bild-text-downloads">
                                <?php foreach ($downloads as $download): ?>
                                    <?php if ($download['download_title'] && $download['download_link']): ?>
                                        <a href="<?php echo esc_url($download['download_link']['url']); ?>" download>
                                            <?php echo esc_html($download['download_title']); ?>
                                            <?php if ($download['download_link']['filesize']): ?>
                                                <?php echo abf_get_file_meta($download['download_link']); ?>
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($show_button && $button_text && $button_url): ?>
                            <?php
                            $button_styles = [];
                            if ($button_bg_color) $button_styles[] = "background-color: " . abf_get_styleguide_color_value($button_bg_color);
                            if ($button_text_color) $button_styles[] = "color: " . abf_get_styleguide_color_value($button_text_color);
                            $button_style_attr = !empty($button_styles) ? ' style="' . implode('; ', $button_styles) . '"' : '';
                            ?>
                            
                            <div class="styleguide-bild-text-button-wrapper">
                                <a href="<?php echo $href_attr; ?>" 
                                   target="<?php echo esc_attr($button_target); ?>"
                                   class="btn styleguide-bild-text__button"
                                   data-button-id="<?php echo esc_attr($block_id); ?>-btn"
                                   data-hover-bg="<?php echo esc_attr(abf_get_styleguide_color_value($button_hover_bg_color)); ?>"
                                   data-hover-text="<?php echo esc_attr(abf_get_styleguide_color_value($button_hover_text_color)); ?>"
                                   data-normal-bg="<?php echo esc_attr(abf_get_styleguide_color_value($button_bg_color)); ?>"
                                   data-normal-text="<?php echo esc_attr(abf_get_styleguide_color_value($button_text_color)); ?>"
                                   <?php echo $button_style_attr; ?><?php echo $onclick_attr; ?>>
                                    <?php echo esc_html($button_text); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                
                <!-- Bild-Spalte (rechts) -->
                <div class="styleguide-bild-text-image-column">
                    <?php if ($image): ?>
                        <div class="styleguide-bild-text-image-container styleguide-bild-text-image--<?php echo esc_attr($image_fit); ?>">
                            <img src="<?php echo esc_url($image['url']); ?>" 
                                 alt="<?php echo esc_attr($image['alt']); ?>" 
                                 class="styleguide-bild-text-image"/>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<script>
// Button-Hover-Effekte mit JavaScript (umgeht CSS-Spezifitätsprobleme)
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('<?php echo $block_id; ?>');
    if (element) {
        const button = element.querySelector('.styleguide-bild-text__button[data-button-id="<?php echo $block_id; ?>-btn"]');
        if (button) {
            const hoverBg = button.getAttribute('data-hover-bg');
            const hoverText = button.getAttribute('data-hover-text');
            const normalBg = button.getAttribute('data-normal-bg');
            const normalText = button.getAttribute('data-normal-text');
            
            // Ensure transitions are preserved
            button.style.transition = 'background-color 0.3s ease, color 0.3s ease';
            
            button.addEventListener('mouseenter', function() {
                if (hoverBg) {
                    this.style.backgroundColor = hoverBg;
                }
                if (hoverText) {
                    this.style.color = hoverText;
                }
            });
            
            button.addEventListener('mouseleave', function() {
                if (normalBg) {
                    this.style.backgroundColor = normalBg;
                }
                if (normalText) {
                    this.style.color = normalText;
                }
            });
        }
    }
});
</script> 