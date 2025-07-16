<?php
/**
 * Styleguide Text Element Block Template
 */

// Get the block ID
$block_id = 'styleguide-text-element-' . $block['id'];

// Get content fields
$headline_text = get_field('ste_headline_text') ?: '';
$headline_tag = get_field('ste_headline_tag') ?: 'h2';
$headline_size = get_field('ste_headline_size') ?: '24';
$headline_weight = get_field('ste_headline_weight') ?: '400';
$headline_color = get_field('ste_headline_color') ?: 'inherit';

$text_content = get_field('ste_text_content') ?: '';
$text_tag = get_field('ste_text_tag') ?: 'p';
$text_size = get_field('ste_text_size') ?: '18';
$text_weight = get_field('ste_text_weight') ?: '400';
$text_color = get_field('ste_text_color') ?: 'inherit';

// Hintergrundfarbe (optional)
$background_color = get_field('ste_background_color') ?: '';

$show_button = get_field('ste_show_button') ?: false;
$button_text = get_field('ste_button_text') ?: '';
$button_url = get_field('ste_button_url') ?: '';
$button_bg_color = get_field('ste_button_bg_color') ?: 'primary';
$button_text_color = get_field('ste_button_text_color') ?: 'white';
$button_hover_bg_color = get_field('ste_button_hover_bg_color') ?: 'secondary';
$button_hover_text_color = get_field('ste_button_hover_text_color') ?: 'white';

// Color function is now centralized in functions.php

// Handle button URLs with modal triggers
$href_attr = '#';
$onclick_attr = '';
if ($button_url) {
    // Check for modal triggers
    if (in_array($button_url, ['#register-modal', '#login-modal', '#modal'])) {
        $href_attr = 'javascript:void(0)';
        if ($button_url === '#register-modal') {
            $onclick_attr = ' onclick="abfOpenModal(\'register\')"';
        } elseif ($button_url === '#login-modal') {
            $onclick_attr = ' onclick="abfOpenModal(\'login\')"';
        } elseif ($button_url === '#modal') {
            $onclick_attr = ' onclick="abfOpenModal(\'register\')"';
        }
    } else {
        $href_attr = esc_url($button_url);
    }
}

?>

<div class="block-styleguide-text-element" id="<?php echo esc_attr($block_id); ?>">
    <div class="styleguide-text-element-container<?php echo $background_color ? ' has-background-color' : ''; ?>"
         <?php if ($background_color): ?>
         style="background-color: <?php echo esc_attr(abf_get_styleguide_color_value($background_color)); ?>;"
         <?php endif; ?>>
        <div class="styleguide-text-element-content">
            
            <?php if ($headline_text): ?>
                <<?php echo $headline_tag; ?> class="styleguide-text-element-headline" 
                    style="font-weight: <?php echo esc_attr($headline_weight); ?>; font-size: <?php echo esc_attr($headline_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($headline_color)); ?>;">
                    <?php echo wp_kses_post($headline_text); ?>
                </<?php echo $headline_tag; ?>>
            <?php endif; ?>
            
            <?php if ($text_content): ?>
                <div class="styleguide-text-element-text" 
                    style="font-weight: <?php echo esc_attr($text_weight); ?>; font-size: <?php echo esc_attr($text_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($text_color)); ?>;">
                    <?php echo wp_kses_post($text_content); ?>
                </div>
            <?php endif; ?>
            
            <?php 
            // Downloads/Links
            $downloads = get_field('ste_downloads');
            if ($downloads && is_array($downloads) && count($downloads) > 0): 
            ?>
                <div class="styleguide-text-element-downloads">
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
            
            <?php if ($show_button && $button_text): ?>
                <?php if ($button_url): ?>
                    <?php
                    $button_styles = [];
                    if ($button_bg_color) $button_styles[] = "background-color: " . abf_get_styleguide_color_value($button_bg_color);
                    if ($button_text_color) $button_styles[] = "color: " . abf_get_styleguide_color_value($button_text_color);
                    $button_style_attr = !empty($button_styles) ? ' style="' . implode('; ', $button_styles) . '"' : '';
                    ?>
                    
                    <div class="styleguide-text-element-button-wrapper">
                        <a href="<?php echo $href_attr; ?>" 
                           class="btn styleguide-text-element__button"
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
            <?php endif; ?>
            
        </div>
    </div>
</div>

<script>
// Button-Hover-Effekte mit JavaScript (umgeht CSS-Spezifitätsprobleme)
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('<?php echo $block_id; ?>');
    if (element) {
        const button = element.querySelector('.styleguide-text-element__button[data-button-id="<?php echo $block_id; ?>-btn"]');
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

 