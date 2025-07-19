<?php
/**
 * Parallax Content Block Template
 */

// Get the block ID
$block_id = 'parallax-content-' . $block['id'];

// Convert color choices to actual values (same as parallax-grid)
if (!function_exists('abf_get_content_color_value')) {
    function abf_get_content_color_value($color_choice) {
        if (!$color_choice || $color_choice === 'inherit') {
            return '#575756'; // Standard text color
        }
        
        // Handle fixed brand colors
        if ($color_choice === 'primary') {
            return '#66a98c'; // Primary brand color
        } elseif ($color_choice === 'secondary') {
            return '#c50d14'; // Secondary brand color
        }
        
        // Try to get dynamic color from colors.json
        $color_value = abf_get_color_value($color_choice);
        if ($color_value) {
            return $color_value;
        }
        
        // Fallback to CSS variable
        return "var(--color-" . sanitize_title($color_choice) . ")";
    }
}
?>

<div class="block-parallax-content" id="<?php echo esc_attr($block_id); ?>">
    <?php 
    $elements = get_field('parallax_content_elements');
    
    // Check if elements exist
    if (empty($elements) || !is_array($elements)) {
        // Show placeholder if no elements configured
        echo '<div style="padding: 40px; text-align: center; background: #f0f0f0; color: #666;">Parallax Content Block: Bitte füge Content-Elemente hinzu.</div>';
    } else {
        
        foreach ($elements as $index => $element): 
            $element_number = $index + 1;
            
            // Get fields for current element
            $background_color = $element['background_color'] ?? 'primary';
            
            // Left side - Text content
            $headline_text = $element['headline_text'] ?? '';
            $headline_tag = $element['headline_tag'] ?? 'h2';
            $headline_weight = $element['headline_weight'] ?? '400';
            $headline_size = $element['headline_size'] ?? '36';
            $headline_color = $element['headline_color'] ?? 'white';
            
            $richtext_content = $element['richtext_content'] ?? '';
            
            $show_button = $element['show_button'] ?? false;
            $button_text = $element['button_text'] ?? '';
            $button_url_field = $element['button_url'] ?? '';
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
            $button_bg_color = $element['button_bg_color'] ?? 'secondary';
            $button_text_color = $element['button_text_color'] ?? 'white';
            $button_hover_bg_color = $element['button_hover_bg_color'] ?? 'primary';
            $button_hover_text_color = $element['button_hover_text_color'] ?? 'white';
            
            // Right side - Media content
            $media_type = $element['media_type'] ?? 'image';
            $image = $element['image'] ?? null;
            $image_fit = $element['image_fit'] ?? 'cover';
            $video = $element['video'] ?? null;
            
            // Build background color style
            $bg_color_value = abf_get_content_color_value($background_color);
            ?>
            
            <div class="parallax-content-element parallax-content-element-<?php echo $element_number; ?>" 
                 style="background-color: <?php echo esc_attr($bg_color_value); ?>;" 
                 data-element="<?php echo $element_number; ?>">
                
                <!-- E1: Headline Area (min 120px, v-center) -->
                <div class="parallax-content-area-e1">
                    <?php if ($headline_text): ?>
                        <?php
                        $headline_styles = [];
                        if ($headline_weight) $headline_styles[] = "font-weight: {$headline_weight}";
                        if ($headline_size) $headline_styles[] = "font-size: {$headline_size}px";
                        if ($headline_color) $headline_styles[] = "color: " . abf_get_content_color_value($headline_color);
                        $headline_style_attr = !empty($headline_styles) ? ' style="' . implode('; ', $headline_styles) . '"' : '';
                        ?>
                        <<?php echo $headline_tag; ?> class="parallax-content-headline"<?php echo $headline_style_attr; ?>>
                            <?php echo wp_kses_post($headline_text); ?>
                        </<?php echo $headline_tag; ?>>
                    <?php endif; ?>
                </div>
                
                <!-- E2: Spacer Area (empty, mobile hidden) -->
                <div class="parallax-content-area-e2">
                    <!-- Leer - sorgt für Alignment mit weißem Layer -->
                </div>
                
                <!-- E3: Content Area (richtext top, button bottom) -->
                <div class="parallax-content-area-e3">
                    <div class="parallax-content-e3-inner<?php echo ($show_button && $button_text && $button_url) ? ' has-button' : ''; ?>">
                        
                        <?php if ($richtext_content): ?>
                            <?php
                            $richtext_styles = [];
                            if ($headline_color) $richtext_styles[] = "color: " . abf_get_content_color_value($headline_color);
                            $richtext_style_attr = !empty($richtext_styles) ? ' style="' . implode('; ', $richtext_styles) . '"' : '';
                            ?>
                            <div class="parallax-content-richtext"<?php echo $richtext_style_attr; ?>>
                                <?php echo wp_kses_post($richtext_content); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($show_button && $button_text && $button_url): ?>
                            <?php
                            $button_styles = [];
                            if ($button_bg_color) $button_styles[] = "background-color: " . abf_get_content_color_value($button_bg_color);
                            if ($button_text_color) $button_styles[] = "color: " . abf_get_content_color_value($button_text_color);
                            $button_style_attr = !empty($button_styles) ? ' style="' . implode('; ', $button_styles) . '"' : '';
                            
                            $button_hover_styles = [];
                            if ($button_hover_bg_color) $button_hover_styles[] = "background-color: " . abf_get_content_color_value($button_hover_bg_color);
                            if ($button_hover_text_color) $button_hover_styles[] = "color: " . abf_get_content_color_value($button_hover_text_color);
                            $button_hover_css = !empty($button_hover_styles) ? implode('; ', $button_hover_styles) : '';
                            ?>
                            
                            <?php if ($button_hover_css): ?>
                                <style>
                                    .parallax-content-element-<?php echo $element_number; ?> .parallax-content-button:hover,
                                    .parallax-content-element-<?php echo $element_number; ?> .parallax-content-button:focus {
                                        <?php echo $button_hover_css; ?> !important;
                                    }
                                </style>
                            <?php endif; ?>
                            
                            <div class="parallax-content-button-wrapper">
                                <a href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>" class="parallax-content-button"<?php echo $button_style_attr; ?>>
                                    <?php echo esc_html($button_text); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                
                <!-- E4: White Layer with Media -->
                <div class="parallax-content-area-e4">
                    <?php 
                    $has_media = ($media_type === 'image' && $image) || ($media_type === 'video' && $video);
                    $media_fit_class = '';
                    $has_padding = false;
                    
                    if ($has_media) {
                        if ($media_type === 'image' && $image_fit === 'contain') {
                            $media_fit_class = 'has-contain';
                            $has_padding = true;
                        } elseif ($media_type === 'image' && $image_fit === 'cover') {
                            $media_fit_class = 'has-cover';
                        } elseif ($media_type === 'video') {
                            $media_fit_class = 'has-video';
                        }
                    }
                    ?>
                    
                    <div class="parallax-content-white-layer <?php echo esc_attr($media_fit_class); ?>">
                        <?php if ($has_media): ?>
                            
                            <?php if ($media_type === 'image' && $image): ?>
                                <?php if ($image_fit === 'contain'): ?>
                                    <!-- Contain: Image with padding -->
                                    <div class="parallax-media-container contain">
                                        <img src="<?php echo esc_url($image['url']); ?>" 
                                             alt="<?php echo esc_attr($image['alt'] ?: ''); ?>" 
                                             class="parallax-media-image contain">
                                    </div>
                                <?php else: ?>
                                    <!-- Cover: Image fills layer -->
                                    <img src="<?php echo esc_url($image['url']); ?>" 
                                         alt="<?php echo esc_attr($image['alt'] ?: ''); ?>" 
                                         class="parallax-media-image cover">
                                <?php endif; ?>
                            
                            <?php elseif ($media_type === 'video' && $video): ?>
                                <!-- Video: Always cover -->
                                <video class="parallax-media-video" autoplay muted loop playsinline>
                                    <source src="<?php echo esc_url($video['url']); ?>" type="<?php echo esc_attr($video['mime_type']); ?>">
                                </video>
                            
                            <?php endif; ?>
                            
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
            
        <?php endforeach; ?>
    <?php } ?>
</div>

<!-- GSAP ScrollTrigger für Sticky Effekt -->
<script src="https://unpkg.com/gsap@3.12.2/dist/gsap.min.js"></script>
<script src="https://unpkg.com/gsap@3.12.2/dist/ScrollTrigger.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    gsap.registerPlugin(ScrollTrigger);

    const elements = document.querySelectorAll(".parallax-content-element");

    if (elements.length === 0) return;
    
    // Tracking sticky states
    let stickyStates = new Array(elements.length).fill(false);
    
    elements.forEach((element, index) => {
        const offset = index * 120; // 120px Abstand für Sticky-Versatz
        const isLastElement = index === elements.length - 1;

        // ScrollTrigger mit GSAP Pin-System (löst das Container-Problem)
        ScrollTrigger.create({
            trigger: element,
            start: index === 0 ? `top top` : `top top-=${offset}`,
            end: () => `+=${element.offsetHeight}`,
            pin: true,
            pinSpacing: true, // WICHTIG: Erstellt Spacer um Layout zu erhalten
            onUpdate: (self) => {
                // Manuelle Positionierung für gestaffelte Positionen
                if (self.isActive) {
                    element.style.top = offset + 'px';
                    element.style.zIndex = 1000 + index;
                    stickyStates[index] = true;
                } else {
                    stickyStates[index] = false;
                }
            },
            onToggle: (self) => {
                if (self.isActive) {
                    element.classList.add('is-sticky');
                } else {
                    element.classList.remove('is-sticky');
                    
                    // Alle nachfolgenden Elemente auch unsticky machen beim Rückwärts-Scrollen
                    if (self.direction === -1) { // Up scroll
                        for (let i = index + 1; i < elements.length; i++) {
                            if (stickyStates[i]) {
                                elements[i].classList.remove('is-sticky');
                                stickyStates[i] = false;
                            }
                        }
                    }
                }
            },
            markers: false // Production: markers disabled
        });
    });
    
    function makeElementSticky(element, index, offset) {
        element.classList.add('is-sticky');
        element.style.position = 'fixed';
        element.style.top = offset + 'px';
        element.style.left = '0';
        element.style.right = '0';
        element.style.width = '100%';
        element.style.zIndex = 1000 + index;
        
        // Stelle sicher, dass spätere Elemente höhere z-index haben
        elements.forEach((otherElement, otherIndex) => {
            if (otherIndex < index) {
                if (stickyStates[otherIndex]) {
                    otherElement.style.zIndex = 1000 + otherIndex;
                }
            }
        });
    }
    
    function makeElementNormal(element, index) {
        element.classList.remove('is-sticky');
        element.style.position = '';
        element.style.top = '';
        element.style.left = '';
        element.style.right = '';
        element.style.width = '';
        element.style.zIndex = '';
    }
});
</script> 