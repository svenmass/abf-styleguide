<?php
/**
 * Parallax Grid Block Template
 */

// Get the block ID
$block_id = 'parallax-grid-' . $block['id'];

// Get sticky settings
$enable_sticky = get_field('grid_enable_sticky') ?: false;
$sticky_position = get_field('grid_sticky_position') ?: 0;
$z_index = get_field('grid_z_index') ?: 1000;
$sticky_mobile_disable = get_field('grid_sticky_mobile_disable') ?: true;

// Get design settings
$container_background_color = get_field('grid_container_background_color') ?: '#ffffff';

// Convert container background color to actual value
if ($container_background_color === '#ffffff') {
    $container_bg_value = '#ffffff';
} elseif ($container_background_color === 'inherit') {
    $container_bg_value = '#575756'; // Standard text color
} elseif ($container_background_color === 'primary') {
    $container_bg_value = '#66a98c'; // Primary brand color
} elseif ($container_background_color === 'secondary') {
    $container_bg_value = '#c50d14'; // Secondary brand color
} else {
    // Try to get dynamic color
    if (function_exists('abf_get_color_value')) {
        $color_value = abf_get_color_value($container_background_color);
        $container_bg_value = $color_value ?: "var(--color-" . sanitize_title($container_background_color) . ")";
    } else {
        $container_bg_value = "var(--color-" . sanitize_title($container_background_color) . ")";
    }
}

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

// Convert color choices to actual values (like in headline block)
if (!function_exists('abf_get_parallax_color_value')) {
    function abf_get_parallax_color_value($color_choice) {
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

<div class="block-parallax-grid <?php echo $enable_sticky ? 'has-sticky' : ''; ?>" 
     id="<?php echo esc_attr($block_id); ?>"
     <?php echo implode(' ', $data_attributes); ?>>
    <div class="parallax-grid-container" style="background-color: <?php echo esc_attr($container_bg_value); ?>;">
        
        <?php 
        $elements = get_field('parallax_elements');
        
        // Check if elements are configured
        if (empty($elements) || !is_array($elements)) {
            // Show placeholder if no elements configured
            echo '<div style="padding: 40px; text-align: center; background: #f0f0f0; color: #666;">Parallax Grid Block: Bitte konfiguriere die 6 Grid-Elemente im Editor.</div>';
        } else {
            // Ensure we have exactly 6 elements
            while (count($elements) < 6) {
                $elements[] = array(); // Add empty element
            }
            
            for ($i = 0; $i < 6; $i++): 
                $element = isset($elements[$i]) ? $elements[$i] : array();
                $element_number = $i + 1;
                
                // Get fields for current element
                $background_type = $element['background_type'] ?? 'color';
                $background_color = $element['background_color'] ?? '#000000';
                $background_image = $element['background_image'] ?? null;
                $background_video = $element['background_video'] ?? null;
                
                $headline_text = $element['headline_text'] ?? '';
                $headline_tag = $element['headline_tag'] ?? 'h2';
                $headline_weight = $element['headline_weight'] ?? '400';
                $headline_size = $element['headline_size'] ?? 24;
                $headline_color = $element['headline_color'] ?? 'inherit';
                
                $subline_text = $element['subline_text'] ?? '';
                $subline_tag = $element['subline_tag'] ?? 'p';
                $subline_weight = $element['subline_weight'] ?? '400';
                $subline_size = $element['subline_size'] ?? 16;
                $subline_color = $element['subline_color'] ?? 'inherit';
                
                $show_button = $element['show_button'] ?? false;
                $button_text = $element['button_text'] ?? '';
                $button_url = $element['button_url'] ?? '';
                $button_url_value = '';
                $button_target = '';
                
                // Handle new ACF Link field format (array) vs old URL field format (string)
                if (is_array($button_url)) {
                    $button_url_value = $button_url['url'] ?? '';
                    $button_target = $button_url['target'] ?? '';
                } else {
                    $button_url_value = $button_url;
                }
                $button_bg_color = $element['button_bg_color'] ?? 'primary';
                $button_text_color = $element['button_text_color'] ?? 'inherit';
                $button_hover_bg_color = $element['button_hover_bg_color'] ?? 'secondary';
                $button_hover_text_color = $element['button_hover_text_color'] ?? 'inherit';
                
                $show_plus = $element['show_plus'] ?? false;
                $plus_type = $element['plus_type'] ?? 'default';
                $plus_icon = $element['plus_icon'] ?? null;
            
            // Build style string for background
            $style_parts = [];
            if ($background_type === 'color' && $background_color) {
                $background_color_value = abf_get_parallax_color_value($background_color);
                $style_parts[] = "background-color: {$background_color_value}";
            } elseif ($background_type === 'image' && $background_image) {
                $image_url = wp_get_attachment_image_url($background_image, 'full');
                if ($image_url) {
                    $style_parts[] = "background-image: url('{$image_url}')";
                }
            }
            // Video background wird direkt über HTML-Element gelöst
            $element_style = !empty($style_parts) ? ' style="' . implode('; ', $style_parts) . '"' : '';
            ?>
            
            <div class="parallax-element parallax-element-<?php echo $element_number; ?>" data-element="<?php echo $element_number; ?>"<?php echo $element_style; ?>>
                
                <?php if ($background_type === 'image' && $background_image): ?>
                    <div class="parallax-background-image"></div>
                <?php elseif ($background_type === 'video' && $background_video): ?>
                    <video class="parallax-background-video" autoplay muted loop playsinline>
                        <source src="<?php echo esc_url($background_video['url']); ?>" type="<?php echo esc_attr($background_video['mime_type']); ?>">
                    </video>
                <?php endif; ?>
                
                <div class="parallax-content">
                    
                    <div class="parallax-text-content">
                        <?php if ($headline_text): ?>
                            <?php
                            $headline_styles = [];
                            if ($headline_weight) $headline_styles[] = "font-weight: {$headline_weight}";
                            if ($headline_size) $headline_styles[] = "font-size: {$headline_size}px";
                            if ($headline_color) $headline_styles[] = "color: " . abf_get_parallax_color_value($headline_color);
                            $headline_style_attr = !empty($headline_styles) ? ' style="' . implode('; ', $headline_styles) . '"' : '';
                            ?>
                            <<?php echo $headline_tag; ?> class="parallax-headline"<?php echo $headline_style_attr; ?>>
                                <?php echo wp_kses_post($headline_text); ?>
                            </<?php echo $headline_tag; ?>>
                        <?php endif; ?>
                        
                        <?php if ($subline_text): ?>
                            <?php
                            $subline_styles = [];
                            if ($subline_weight) $subline_styles[] = "font-weight: {$subline_weight}";
                            if ($subline_size) $subline_styles[] = "font-size: {$subline_size}px";
                            if ($subline_color) $subline_styles[] = "color: " . abf_get_parallax_color_value($subline_color);
                            $subline_style_attr = !empty($subline_styles) ? ' style="' . implode('; ', $subline_styles) . '"' : '';
                            ?>
                            <<?php echo $subline_tag; ?> class="parallax-subline"<?php echo $subline_style_attr; ?>>
                                <?php echo wp_kses_post($subline_text); ?>
                            </<?php echo $subline_tag; ?>>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($show_button && $button_text && $button_url_value): ?>
                        <?php
                        $button_styles = [];
                        if ($button_bg_color) $button_styles[] = "background-color: " . abf_get_parallax_color_value($button_bg_color);
                        if ($button_text_color) $button_styles[] = "color: " . abf_get_parallax_color_value($button_text_color);
                        $button_style_attr = !empty($button_styles) ? ' style="' . implode('; ', $button_styles) . '"' : '';
                        
                        $button_hover_styles = [];
                        if ($button_hover_bg_color) $button_hover_styles[] = "background-color: " . abf_get_parallax_color_value($button_hover_bg_color);
                        if ($button_hover_text_color) $button_hover_styles[] = "color: " . abf_get_parallax_color_value($button_hover_text_color);
                        $button_hover_css = !empty($button_hover_styles) ? implode('; ', $button_hover_styles) : '';
                        ?>
                        
                        <?php if ($button_hover_css): ?>
                            <style>
                                .parallax-grid-container .parallax-element-<?php echo $element_number; ?> .parallax-button:hover,
                                .parallax-grid-container .parallax-element-<?php echo $element_number; ?> .parallax-button:focus {
                                    <?php echo $button_hover_css; ?> !important;
                                }
                            </style>
                        <?php endif; ?>
                        
                        <div class="parallax-button-wrapper">
                            <?php
                            // Handle special modal URLs
                            $onclick_attr = '';
                            $href_attr = esc_url($button_url_value);
                            $target_attr = $button_target ? ' target="' . esc_attr($button_target) . '"' : '';
                            
                            if ($button_url_value === '#register-modal' || $button_url_value === '#register') {
                                $onclick_attr = ' onclick="ABF_UserManagement.showModal(); ABF_UserManagement.switchTab(\'register\'); return false;"';
                                $href_attr = '#';
                            } elseif ($button_url_value === '#login-modal' || $button_url_value === '#login') {
                                $onclick_attr = ' onclick="ABF_UserManagement.showModal(); ABF_UserManagement.switchTab(\'login\'); return false;"';
                                $href_attr = '#';
                            } elseif ($button_url_value === '#modal' || $button_url_value === '#anmelden') {
                                $onclick_attr = ' onclick="ABF_UserManagement.showModal(); return false;"';
                                $href_attr = '#';
                            }
                            ?>
                            <a href="<?php echo $href_attr; ?>" class="parallax-button"<?php echo $button_style_attr; ?><?php echo $target_attr; ?><?php echo $onclick_attr; ?>>
                                <?php echo esc_html($button_text); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
                <?php if ($show_plus): ?>
                    <div class="parallax-plus-overlay">
                        <?php if ($plus_type === 'custom' && $plus_icon): ?>
                            <img src="<?php echo esc_url($plus_icon['url']); ?>" alt="Plus Icon" class="parallax-plus-icon">
                        <?php else: ?>
                            <svg class="parallax-plus-icon" width="793" height="716" viewBox="0 0 793 716" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.7" d="M792.32 490.05H515.24L515.4 490.67L446.98 715.45H214.23L281.44 490.67H41.24C37.02 490.67 32.33 489.73 28.11 488.17C7.16996 481.14 -4.08002 458.8 2.94998 437.87L46.24 299.78C51.87 282.91 67.81 272.44 84.84 272.76H347.55L422.1 29.23C427.26 13.14 442.1 1.42001 459.92 1.42001H460.86L611.05 0.950012C633.08 0.950012 650.59 18.76 650.59 40.78C650.59 45.31 649.81 49.53 648.56 53.59L581.36 272.6H792.11L792.34 490.05H792.32Z" fill="white"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
            </div>
            
            <?php endfor; ?>
        <?php } ?>
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const blockId = '<?php echo esc_js($block_id); ?>';
    const blockElement = document.getElementById(blockId);
    const elements = document.querySelectorAll('#' + blockId + ' .parallax-element');
    
    // Z-Index immer setzen (auch ohne Sticky)
    if (blockElement) {
        blockElement.style.zIndex = <?php echo intval($z_index); ?>;
    }
    
    if (elements.length === 0) {
        return;
    }
    
    // Scroll-Richtung tracken
    let lastScrollY = window.scrollY;
    let scrollDirection = 'down';
    
    window.addEventListener('scroll', function() {
        const currentScrollY = window.scrollY;
        scrollDirection = currentScrollY > lastScrollY ? 'down' : 'up';
        lastScrollY = currentScrollY;
    });
    
    // Intersection Observer Setup
    const observerOptions = {
        threshold: [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0],
        rootMargin: '0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const element = entry.target;
            const ratio = entry.intersectionRatio;
            

            
            if (ratio >= 0.9) {
                // Element ist 90% oder mehr sichtbar - Vollständig eingeblendet
                element.style.transform = 'scale(1)';
                element.style.opacity = '1';
                element.dataset.reachedFullAt = scrollDirection; // Merke Richtung beim Erreichen von 100%
            } else if (ratio > 0) {
                // Element ist teilweise sichtbar - Normaler Parallax
                const scale = 0.6 + (ratio / 0.9) * 0.4; // 0.6 bis 1.0
                const opacity = 0.3 + (ratio / 0.9) * 0.7; // 0.3 bis 1.0
                element.style.transform = 'scale(' + scale + ')';
                element.style.opacity = opacity;
            } else if (ratio === 0) {
                // Element ist nicht sichtbar - EINFACHE Richtungslogik!
                const reachedFullAt = element.dataset.reachedFullAt; // 'down' oder 'up' oder undefined
                
                if (reachedFullAt) {
                    // Element hat schon mal 100% erreicht
                    if (scrollDirection === reachedFullAt) {
                        // Gleiche Richtung wie beim Erreichen von 100% → BLEIBT groß!
                        element.style.transform = 'scale(1)';
                        element.style.opacity = '1';
                    } else {
                        // Richtungswechsel → Parallax reaktiviert → wird klein!
                        element.style.transform = 'scale(0.6)';
                        element.style.opacity = '0.3';
                        element.dataset.reachedFullAt = ''; // Reset für nächsten Zyklus
                    }
                } else {
                    // Noch nie 100% erreicht → normal klein
                    element.style.transform = 'scale(0.6)';
                    element.style.opacity = '0.3';
                }
            }
        });
    }, observerOptions);
    
    // Alle Elemente beobachten
    elements.forEach((element, index) => {
        
        // Initiale Styles setzen - DRAMATISCHER EFFEKT!
        element.style.transform = 'scale(0.6)';
        element.style.opacity = '0.3';
        element.style.transition = 'transform 0.8s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.8s ease-out';
        element.style.transformOrigin = 'center';
        
        // Observer starten
        observer.observe(element);
    });
    
    <?php if ($enable_sticky): ?>
    // Sticky-Funktionalität
    const enableSticky = <?php echo $enable_sticky ? 'true' : 'false'; ?>;
    const stickyPosition = <?php echo intval($sticky_position); ?>;
    const zIndex = <?php echo intval($z_index); ?>;
    const mobileDisable = <?php echo $sticky_mobile_disable ? 'true' : 'false'; ?>;
    
    let isSticky = false;
    let originalPosition = null;
    
    function initStickySystem() {
        if (originalPosition === null) {
            // Speichere die absolute ursprüngliche Position dieses spezifischen Elements
            const rect = blockElement.getBoundingClientRect();
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
            spacerElement.style.height = blockElement.offsetHeight + 'px';
            spacerElement.style.width = '100%';
            spacerElement.style.visibility = 'hidden';
            spacerElement.style.pointerEvents = 'none';
            blockElement.parentNode.insertBefore(spacerElement, blockElement);
        }
        
        blockElement.style.position = 'fixed';
        blockElement.style.top = stickyPosition + 'px';
        blockElement.style.left = '0';
        blockElement.style.right = '0';
        blockElement.style.width = '100%';
        blockElement.classList.add('is-sticky');
        // Z-Index bereits beim Laden gesetzt
    }
    
    function releaseSticky() {
        isSticky = false;
        
        // Entferne Spacer-Element
        if (spacerElement) {
            spacerElement.parentNode.removeChild(spacerElement);
            spacerElement = null;
        }
        
        blockElement.style.position = '';
        blockElement.style.top = '';
        blockElement.style.left = '';
        blockElement.style.right = '';
        blockElement.style.width = '';
        blockElement.classList.remove('is-sticky');
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
    <?php endif; ?>
    
});
</script> 