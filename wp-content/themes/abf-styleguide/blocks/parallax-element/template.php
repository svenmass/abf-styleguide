<?php
/**
 * Parallax Element Block Template
 */

// Get the block ID
$block_id = 'parallax-element-' . $block['id'];

// Get sticky settings
$enable_sticky = get_field('enable_sticky') ?: false;
$sticky_position = get_field('sticky_position') ?: 0;
$z_index = get_field('z_index') ?: 1000;
$sticky_mobile_disable = get_field('sticky_mobile_disable') ?: true;

// Get design settings
$background_color = get_field('background_color') ?: 'primary';

// Get content fields
$headline_text = get_field('headline_text') ?: '';
$headline_tag = get_field('headline_tag') ?: 'h2';
$headline_weight = get_field('headline_weight') ?: '400';
$headline_size = get_field('headline_size') ?: '36';
$headline_color = get_field('headline_color') ?: 'white';

$richtext_content = get_field('richtext_content') ?: '';

$show_button = get_field('show_button') ?: false;
$button_text = get_field('button_text') ?: '';
$button_url = get_field('button_url') ?: '';
$button_bg_color = get_field('button_bg_color') ?: 'secondary';
$button_text_color = get_field('button_text_color') ?: 'white';
$button_hover_bg_color = get_field('button_hover_bg_color') ?: 'primary';
$button_hover_text_color = get_field('button_hover_text_color') ?: 'white';

// Get media fields
$media_type = get_field('media_type') ?: 'image';
$image = get_field('image') ?: null;
$image_fit = get_field('image_fit') ?: 'cover';
$video = get_field('video') ?: null;

// Convert color choices to actual values (reuse existing function)
if (!function_exists('abf_get_element_color_value')) {
    function abf_get_element_color_value($color_choice) {
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
}

// Build background color style
$bg_color_value = abf_get_element_color_value($background_color);

// Build data attributes for sticky behavior
$data_attributes = [];
if ($enable_sticky) {
    $data_attributes[] = 'data-sticky="true"';
    $data_attributes[] = 'data-sticky-position="' . intval($sticky_position) . '"';
    $data_attributes[] = 'data-z-index="' . intval($z_index) . '"';
    if ($sticky_mobile_disable) {
        $data_attributes[] = 'data-sticky-mobile-disable="true"';
    }
}

?>

<div class="block-parallax-element <?php echo $enable_sticky ? 'has-sticky' : ''; ?>" 
     id="<?php echo esc_attr($block_id); ?>"
     style="background-color: <?php echo esc_attr($bg_color_value); ?>;"
     <?php echo implode(' ', $data_attributes); ?>>
    
    <!-- E1: Headline Area (min 120px, v-center) -->
    <div class="parallax-element-area-e1">
        <?php if ($headline_text): ?>
            <?php
            $headline_styles = [];
            if ($headline_weight) $headline_styles[] = "font-weight: {$headline_weight}";
            // Font-size: Backend-Einstellung hat Vorrang über globale CSS Custom Properties
            if ($headline_size) $headline_styles[] = "font-size: {$headline_size}px";
            if ($headline_color) $headline_styles[] = "color: " . abf_get_element_color_value($headline_color);
            $headline_style_attr = !empty($headline_styles) ? ' style="' . implode('; ', $headline_styles) . '"' : '';
            ?>
            <<?php echo $headline_tag; ?> class="parallax-element-headline"<?php echo $headline_style_attr; ?>>
                <?php echo wp_kses_post($headline_text); ?>
            </<?php echo $headline_tag; ?>>
        <?php endif; ?>
    </div>
    
    <!-- E2: Spacer Area (empty, mobile hidden) -->
    <div class="parallax-element-area-e2">
        <!-- Leer - sorgt für Alignment mit weißem Layer -->
    </div>
    
    <!-- E3: Content Area (richtext top, button bottom) -->
    <div class="parallax-element-area-e3">
        <div class="parallax-element-e3-inner<?php echo ($show_button ? ($button_text ? ($button_url ? ' has-button' : '') : '') : ''); ?>">
            
            <?php if ($richtext_content): ?>
                <?php
                $richtext_styles = [];
                if ($headline_color) $richtext_styles[] = "color: " . abf_get_element_color_value($headline_color);
                $richtext_style_attr = !empty($richtext_styles) ? ' style="' . implode('; ', $richtext_styles) . '"' : '';
                ?>
                <div class="parallax-element-richtext"<?php echo $richtext_style_attr; ?>>
                    <?php echo wp_kses_post($richtext_content); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($show_button): ?>
                <?php if ($button_text): ?>
                    <?php if ($button_url): ?>
                        <?php
                        $button_styles = [];
                        if ($button_bg_color) $button_styles[] = "background-color: " . abf_get_element_color_value($button_bg_color);
                        if ($button_text_color) $button_styles[] = "color: " . abf_get_element_color_value($button_text_color);
                        $button_style_attr = !empty($button_styles) ? ' style="' . implode('; ', $button_styles) . '"' : '';
                        ?>
                        
                        <div class="parallax-element-button-wrapper">
                            <?php
                            // Handle special modal URLs
                            $onclick_attr = '';
                            $href_attr = esc_url($button_url);
                            
                            if ($button_url === '#register-modal' || $button_url === '#register') {
                                $onclick_attr = ' onclick="ABF_UserManagement.showModal(); ABF_UserManagement.switchTab(\'register\'); return false;"';
                                $href_attr = '#';
                            } elseif ($button_url === '#login-modal' || $button_url === '#login') {
                                $onclick_attr = ' onclick="ABF_UserManagement.showModal(); ABF_UserManagement.switchTab(\'login\'); return false;"';
                                $href_attr = '#';
                            } elseif ($button_url === '#modal' || $button_url === '#anmelden') {
                                $onclick_attr = ' onclick="ABF_UserManagement.showModal(); return false;"';
                                $href_attr = '#';
                            }
                            ?>
                            <a href="<?php echo $href_attr; ?>" 
                               class="parallax-element-button"
                               data-button-id="<?php echo esc_attr($block_id); ?>-btn"
                               data-hover-bg="<?php echo esc_attr(abf_get_element_color_value($button_hover_bg_color)); ?>"
                               data-hover-text="<?php echo esc_attr(abf_get_element_color_value($button_hover_text_color)); ?>"
                               data-normal-bg="<?php echo esc_attr(abf_get_element_color_value($button_bg_color)); ?>"
                               data-normal-text="<?php echo esc_attr(abf_get_element_color_value($button_text_color)); ?>"
                               <?php echo $button_style_attr; ?><?php echo $onclick_attr; ?>>
                                <?php echo esc_html($button_text); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            
        </div>
    </div>
    
    <!-- E4: White Layer with Media -->
    <div class="parallax-element-area-e4">
        <?php 
        $has_media = ($media_type === 'image' ? !empty($image) : false) || ($media_type === 'video' ? !empty($video) : false);
        $media_fit_class = '';
        
        if ($has_media) {
            if ($media_type === 'image') {
                if ($image_fit === 'contain') {
                    $media_fit_class = 'has-contain';
                } elseif ($image_fit === 'cover') {
                    $media_fit_class = 'has-cover';
                }
            } elseif ($media_type === 'video') {
                $media_fit_class = 'has-video';
            }
        }
        ?>
        
        <?php if ($media_type !== 'none'): ?>
            <div class="parallax-element-white-layer <?php echo esc_attr($media_fit_class); ?>">
                <?php if ($has_media): ?>
                    
                    <?php if ($media_type === 'image'): ?>
                        <?php if ($image): ?>
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
                        <?php endif; ?>
                    
                    <?php elseif ($media_type === 'video'): ?>
                        <?php if ($video): ?>
                            <!-- Video: Always cover -->
                            <video class="parallax-media-video" autoplay muted loop playsinline>
                                <source src="<?php echo esc_url($video['url']); ?>" type="<?php echo esc_attr($video['mime_type']); ?>">
                            </video>
                        <?php endif; ?>
                    
                    <?php endif; ?>
                    
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
</div>

<script>
// Z-Index immer setzen (auch ohne Sticky)
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('<?php echo $block_id; ?>');
    if (element) {
        element.style.zIndex = <?php echo intval($z_index); ?>;
    }
    
    // Button-Hover-Effekte mit JavaScript (umgeht CSS-Spezifitätsprobleme)
    const button = element.querySelector('.parallax-element-button[data-button-id="<?php echo $block_id; ?>-btn"]');
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
});
</script>

<?php if ($enable_sticky): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('<?php echo $block_id; ?>');
    if (!element) return;
    
    const stickyPosition = <?php echo intval($sticky_position); ?>;
    const zIndex = <?php echo intval($z_index); ?>;
    const mobileDisable = <?php echo $sticky_mobile_disable ? 'true' : 'false'; ?>;
    
    let isSticky = false;
    let originalPosition = null;
    
    function initStickySystem() {
        if (originalPosition === null) {
            // Speichere die absolute ursprüngliche Position dieses spezifischen Elements
            const rect = element.getBoundingClientRect();
            originalPosition = window.pageYOffset + rect.top;
        }
    }
    
    function handleScroll() {
        // Mobile check
        if (mobileDisable) {
            if (window.innerWidth <= 768) {
                if (isSticky) releaseSticky();
                return;
            }
        }
        
        if (originalPosition === null) initStickySystem();
        
        const scrollTop = window.pageYOffset;
        
        // Dieses spezifische Element basiert NUR auf seiner eigenen ursprünglichen Position
        // Trigger-Punkt: wenn Scroll-Position die ursprüngliche Position - sticky-Position erreicht
        const triggerPoint = originalPosition - stickyPosition;
        
        if (!isSticky) {
            // Wird sticky wenn der Scroll-Punkt erreicht ist
            if (scrollTop >= triggerPoint) {
                applySticky();
            }
        } else {
            // Wird wieder normal wenn wir über den Trigger-Punkt zurück scrollen
            if (scrollTop < triggerPoint) {
                releaseSticky();
            }
        }
    }
    
    let spacerElement = null;
    
    function applySticky() {
        isSticky = true;
        
        // Erstelle Spacer-Element um den Platz zu behalten
        if (!spacerElement) {
            spacerElement = document.createElement('div');
            spacerElement.style.height = element.offsetHeight + 'px';
            spacerElement.style.width = '100%';
            spacerElement.style.visibility = 'hidden';
            spacerElement.style.pointerEvents = 'none';
            element.parentNode.insertBefore(spacerElement, element);
        }
        
        element.style.position = 'fixed';
        element.style.top = stickyPosition + 'px';
        element.style.left = '0';
        element.style.right = '0';
        element.style.width = '100%';
        element.classList.add('is-sticky');
        // Z-Index bereits beim Laden gesetzt
    }
    
    function releaseSticky() {
        isSticky = false;
        
        // Entferne Spacer-Element
        if (spacerElement) {
            spacerElement.parentNode.removeChild(spacerElement);
            spacerElement = null;
        }
        
        element.style.position = '';
        element.style.top = '';
        element.style.left = '';
        element.style.right = '';
        element.style.width = '';
        element.classList.remove('is-sticky');
        // Z-Index bleibt gesetzt für korrekte Stapelreihenfolge
    }
    
    // Init
    initStickySystem();
    
    // Throttled scroll listener
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(function() {
                handleScroll();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
    
    // Resize listener
    window.addEventListener('resize', function() {
        initStickySystem();
        handleScroll();
    });
    
    // Initial check
    handleScroll();
});
</script>
<?php endif; ?> 