<?php
/**
 * Text Block Template
 */

// Get the block ID
$block_id = 'text-block-' . $block['id'];

// Get sticky settings
$enable_sticky = get_field('tb_enable_sticky') ?: false;
$sticky_position = get_field('tb_sticky_position') ?: 0;
$z_index = get_field('tb_z_index') ?: 1000;
$sticky_mobile_disable = get_field('tb_sticky_mobile_disable') ?: true;

// DEBUG: ACF Werte ausgeben (nur für Admins)
if (current_user_can('manage_options')) {
    echo '<!-- DEBUG Text Block ACF Values:';
    echo ' tb_enable_sticky: ' . var_export($enable_sticky, true);
    echo ' tb_sticky_position: ' . var_export($sticky_position, true);
    echo ' tb_z_index: ' . var_export($z_index, true);
    echo ' tb_sticky_mobile_disable: ' . var_export($sticky_mobile_disable, true);
    echo ' RAW get_field(tb_z_index): ' . var_export(get_field('tb_z_index'), true);
    echo ' ALL ACF FIELDS: ';
    $all_fields = get_fields();
    if ($all_fields) {
        foreach ($all_fields as $key => $value) {
            if (strpos($key, 'tb_') === 0) {
                echo $key . '=' . var_export($value, true) . '; ';
            }
        }
    } else {
        echo 'NO ACF FIELDS FOUND!';
    }
    echo ' -->';
}

// Get design settings
$background_color = get_field('tb_background_color') ?: 'primary';
$full_height = get_field('tb_full_height') ?: false;

// DEBUG: Design Werte ausgeben (nur für Admins)  
if (current_user_can('manage_options')) {
    echo '<!-- DEBUG Text Block Design Values:';
    echo ' tb_background_color: ' . var_export($background_color, true);
    echo ' tb_full_height: ' . var_export($full_height, true);
    echo ' -->';
}

// Get content fields
$headline_text = get_field('tb_headline_text') ?: '';
$headline_tag = get_field('tb_headline_tag') ?: 'h2';
$headline_weight = get_field('tb_headline_weight') ?: '400';
$headline_size = get_field('tb_headline_size') ?: '36';
$headline_color = get_field('tb_headline_color') ?: 'white';

$subline_text = get_field('tb_subline_text') ?: '';
$subline_tag = get_field('tb_subline_tag') ?: 'h3';
$subline_weight = get_field('tb_subline_weight') ?: '400';
$subline_size = get_field('tb_subline_size') ?: '24';
$subline_color = get_field('tb_subline_color') ?: 'white';

$richtext_content = get_field('tb_richtext_content') ?: '';

$show_button = get_field('tb_show_button') ?: false;
$button_text = get_field('tb_button_text') ?: '';
$button_url = get_field('tb_button_url') ?: '';
$button_bg_color = get_field('tb_button_bg_color') ?: 'secondary';
$button_text_color = get_field('tb_button_text_color') ?: 'white';
$button_hover_bg_color = get_field('tb_button_hover_bg_color') ?: 'primary';
$button_hover_text_color = get_field('tb_button_hover_text_color') ?: 'white';

// Convert color choices to actual values (reuse existing function)
if (!function_exists('abf_get_text_block_color_value')) {
    function abf_get_text_block_color_value($color_choice) {
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
$bg_color_value = abf_get_text_block_color_value($background_color);

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

// Build CSS classes
$css_classes = ['block-text-block'];
if ($enable_sticky) {
    $css_classes[] = 'has-sticky';
}
if ($full_height) {
    $css_classes[] = 'full-height';
}

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

<?php
// Build inline styles with z-index
$inline_styles = [];
$inline_styles[] = "background-color: " . esc_attr($bg_color_value);
$inline_styles[] = "z-index: " . intval($z_index) . " !important"; // Z-Index mit !important erzwingen
$inline_styles[] = "display: block !important";
$inline_styles[] = "visibility: visible !important";
$inline_styles[] = "opacity: 1 !important";
?>

<!-- Individuelle CSS-Regeln für diesen spezifischen Block -->
<style>
#<?php echo esc_attr($block_id); ?> {
    z-index: <?php echo intval($z_index); ?> !important;
    background-color: <?php echo esc_attr($bg_color_value); ?> !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>

<div class="<?php echo implode(' ', $css_classes); ?>" 
     id="<?php echo esc_attr($block_id); ?>"
     style="<?php echo implode('; ', $inline_styles); ?>;"
     <?php echo implode(' ', $data_attributes); ?>>
    
    <div class="text-block-container">
        <div class="text-block-content">
            
            <?php if ($headline_text): ?>
                <?php
                $headline_styles = [];
                if ($headline_weight) $headline_styles[] = "font-weight: {$headline_weight}";
                if ($headline_size) $headline_styles[] = "font-size: {$headline_size}px";
                if ($headline_color) $headline_styles[] = "color: " . abf_get_text_block_color_value($headline_color);
                $headline_style_attr = !empty($headline_styles) ? ' style="' . implode('; ', $headline_styles) . '"' : '';
                ?>
                <<?php echo $headline_tag; ?> class="text-block-headline"<?php echo $headline_style_attr; ?>>
                    <?php echo wp_kses_post($headline_text); ?>
                </<?php echo $headline_tag; ?>>
            <?php endif; ?>
            
            <?php if ($subline_text): ?>
                <?php
                $subline_styles = [];
                if ($subline_weight) $subline_styles[] = "font-weight: {$subline_weight}";
                if ($subline_size) $subline_styles[] = "font-size: {$subline_size}px";
                if ($subline_color) $subline_styles[] = "color: " . abf_get_text_block_color_value($subline_color);
                $subline_style_attr = !empty($subline_styles) ? ' style="' . implode('; ', $subline_styles) . '"' : '';
                ?>
                <<?php echo $subline_tag; ?> class="text-block-subline"<?php echo $subline_style_attr; ?>>
                    <?php echo wp_kses_post($subline_text); ?>
                </<?php echo $subline_tag; ?>>
            <?php endif; ?>
            
            <?php if ($richtext_content): ?>
                <?php
                $richtext_styles = [];
                if ($headline_color) $richtext_styles[] = "color: " . abf_get_text_block_color_value($headline_color);
                $richtext_style_attr = !empty($richtext_styles) ? ' style="' . implode('; ', $richtext_styles) . '"' : '';
                ?>
                <div class="text-block-richtext"<?php echo $richtext_style_attr; ?>>
                    <?php echo wp_kses_post($richtext_content); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($show_button && $button_text): ?>
                <?php
                $button_styles = [];
                if ($button_bg_color) $button_styles[] = "background-color: " . abf_get_text_block_color_value($button_bg_color);
                if ($button_text_color) $button_styles[] = "color: " . abf_get_text_block_color_value($button_text_color);
                $button_style_attr = !empty($button_styles) ? ' style="' . implode('; ', $button_styles) . '"' : '';
                ?>
                <div class="text-block-button-wrapper">
                    <a href="<?php echo $href_attr; ?>" 
                       class="text-block-button"
                       data-button-id="<?php echo esc_attr($block_id); ?>-btn"
                       data-hover-bg="<?php echo esc_attr(abf_get_text_block_color_value($button_hover_bg_color)); ?>"
                       data-hover-text="<?php echo esc_attr(abf_get_text_block_color_value($button_hover_text_color)); ?>"
                       data-normal-bg="<?php echo esc_attr(abf_get_text_block_color_value($button_bg_color)); ?>"
                       data-normal-text="<?php echo esc_attr(abf_get_text_block_color_value($button_text_color)); ?>"
                       <?php echo $button_style_attr; ?><?php echo $onclick_attr; ?>>
                        <?php echo esc_html($button_text); ?>
                    </a>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
    
</div>

<script type="text/javascript">
/* <![CDATA[ */
// Text Block Initialization
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('<?php echo $block_id; ?>');
    if (element) {
            // Z-Index und Sichtbarkeit sicherstellen
    element.style.zIndex = <?php echo intval($z_index); ?>;
    element.style.display = 'block';
    element.style.visibility = 'visible';
    element.style.opacity = '1';
    
    // Text Block erfolgreich initialisiert
    }
    
    // Button-Hover-Effekte mit JavaScript
    const button = element.querySelector('.text-block-button[data-button-id="<?php echo $block_id; ?>-btn"]');
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
/* ]]> */
</script>

<?php if ($enable_sticky): ?>
<script type="text/javascript">
/* <![CDATA[ */
document.addEventListener('DOMContentLoaded', function() {
    const blockId = '<?php echo esc_js($block_id); ?>';
    const blockElement = document.getElementById(blockId);
    
    if (!blockElement) return;
    
    // Sticky-Funktionalität (gleiche Logik wie im Parallax-Grid)
    const enableSticky = <?php echo $enable_sticky ? 'true' : 'false'; ?>;
    const stickyPosition = <?php echo intval($sticky_position); ?>;
    const zIndex = <?php echo intval($z_index); ?>;
    const mobileDisable = <?php echo $sticky_mobile_disable ? 'true' : 'false'; ?>;
    
    let isSticky = false;
    let originalPosition = null;
    let spacerElement = null;
    
    function initStickySystem() {
        if (originalPosition === null) {
            // Speichere die absolute ursprüngliche Position dieses spezifischen Elements
            const rect = blockElement.getBoundingClientRect();
            originalPosition = window.pageYOffset + rect.top;
            
            // Sticky System initialisiert
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
        
        // Prüfe Sticky-Trigger-Punkt
        
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
    
    function applySticky() {
        isSticky = true;
        
        // Erstelle Spacer-Element um den Platz zu behalten
        if (!spacerElement) {
            spacerElement = document.createElement('div');
            spacerElement.style.height = blockElement.offsetHeight + 'px';
            spacerElement.style.width = '100%';
            spacerElement.style.visibility = 'hidden';
            spacerElement.style.pointerEvents = 'none';
            spacerElement.className = 'text-block-sticky-spacer';
            blockElement.parentNode.insertBefore(spacerElement, blockElement);
        }
        
        // FORCIERE Fixed-Position mit !important über CSS
        blockElement.style.cssText += `
            position: fixed !important;
            top: ${stickyPosition}px !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            z-index: ${zIndex} !important;
            transform: none !important;
            will-change: auto !important;
            backface-visibility: visible !important;
        `;
        blockElement.classList.add('is-sticky');
        
        // Sticky-Verhalten erfolgreich angewendet
    }
    
    function releaseSticky() {
        isSticky = false;
        
        // Entferne Spacer-Element
        if (spacerElement) {
            spacerElement.parentNode.removeChild(spacerElement);
            spacerElement = null;
        }
        
        // ENTFERNE Fixed-Position und setze zurück
        blockElement.style.cssText = blockElement.style.cssText.replace(/position:\s*fixed\s*!important;?/gi, '');
        blockElement.style.cssText = blockElement.style.cssText.replace(/top:\s*\d+px\s*!important;?/gi, '');
        blockElement.style.cssText = blockElement.style.cssText.replace(/left:\s*0\s*!important;?/gi, '');
        blockElement.style.cssText = blockElement.style.cssText.replace(/right:\s*0\s*!important;?/gi, '');
        blockElement.style.cssText = blockElement.style.cssText.replace(/width:\s*100%\s*!important;?/gi, '');
        blockElement.style.cssText = blockElement.style.cssText.replace(/transform:\s*none\s*!important;?/gi, '');
        blockElement.style.cssText = blockElement.style.cssText.replace(/will-change:\s*auto\s*!important;?/gi, '');
        blockElement.style.cssText = blockElement.style.cssText.replace(/backface-visibility:\s*visible\s*!important;?/gi, '');
        
        // Setze zurück auf relative Position
        blockElement.style.position = 'relative';
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
        if (isSticky) {
            releaseSticky();
        }
        originalPosition = null;
        initStickySystem();
        handleScroll();
    });
    
    // Initial check
    handleScroll();
});
/* ]]> */
</script>
<?php endif; ?> 