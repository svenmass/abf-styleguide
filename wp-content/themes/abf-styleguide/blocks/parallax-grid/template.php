<?php
/**
 * Parallax Grid Block Template
 */

// Get the block ID
$block_id = 'parallax-grid-' . $block['id'];

// Convert color choices to CSS variables
if (!function_exists('convert_color_to_css_var')) {
    function convert_color_to_css_var($color_choice) {
        if (!$color_choice || $color_choice === 'inherit') {
            return 'inherit';
        }
        return "var(--color-{$color_choice})";
    }
}
?>

<div class="block-parallax-grid" id="<?php echo esc_attr($block_id); ?>">
    <div class="parallax-grid-container">
        
        <?php 
        $elements = get_field('parallax_elements');
        
        // Debug: Check if elements exist
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
                $button_bg_color = $element['button_bg_color'] ?? 'primary';
                $button_text_color = $element['button_text_color'] ?? 'inherit';
                $button_hover_bg_color = $element['button_hover_bg_color'] ?? 'secondary';
                $button_hover_text_color = $element['button_hover_text_color'] ?? 'inherit';
            
            // Build style string for background
            $style_parts = [];
            if ($background_type === 'color' && $background_color) {
                $style_parts[] = "background-color: {$background_color}";
            } elseif ($background_type === 'image' && $background_image) {
                $image_url = wp_get_attachment_image_url($background_image, 'full');
                if ($image_url) {
                    $style_parts[] = "background-image: url('{$image_url}')";
                }
            }
            $element_style = !empty($style_parts) ? ' style="' . implode('; ', $style_parts) . '"' : '';
            ?>
            
            <div class="parallax-element parallax-element-<?php echo $element_number; ?>" data-element="<?php echo $element_number; ?>"<?php echo $element_style; ?>>
                
                <?php if ($background_type === 'image' && $background_image): ?>
                    <div class="parallax-background-image"></div>
                <?php endif; ?>
                
                <div class="parallax-content">
                    
                    <?php if ($headline_text): ?>
                        <?php
                        $headline_styles = [];
                        if ($headline_weight) $headline_styles[] = "font-weight: {$headline_weight}";
                        if ($headline_size) $headline_styles[] = "font-size: {$headline_size}px";
                        if ($headline_color) $headline_styles[] = "color: " . convert_color_to_css_var($headline_color);
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
                        if ($subline_color) $subline_styles[] = "color: " . convert_color_to_css_var($subline_color);
                        $subline_style_attr = !empty($subline_styles) ? ' style="' . implode('; ', $subline_styles) . '"' : '';
                        ?>
                        <<?php echo $subline_tag; ?> class="parallax-subline"<?php echo $subline_style_attr; ?>>
                            <?php echo wp_kses_post($subline_text); ?>
                        </<?php echo $subline_tag; ?>>
                    <?php endif; ?>
                    
                    <?php if ($show_button && $button_text && $button_url): ?>
                        <?php
                        $button_styles = [];
                        if ($button_bg_color) $button_styles[] = "background-color: " . convert_color_to_css_var($button_bg_color);
                        if ($button_text_color) $button_styles[] = "color: " . convert_color_to_css_var($button_text_color);
                        $button_style_attr = !empty($button_styles) ? ' style="' . implode('; ', $button_styles) . '"' : '';
                        
                        $button_hover_styles = [];
                        if ($button_hover_bg_color) $button_hover_styles[] = "background-color: " . convert_color_to_css_var($button_hover_bg_color);
                        if ($button_hover_text_color) $button_hover_styles[] = "color: " . convert_color_to_css_var($button_hover_text_color);
                        $button_hover_css = !empty($button_hover_styles) ? implode('; ', $button_hover_styles) : '';
                        ?>
                        
                        <?php if ($button_hover_css): ?>
                            <style>
                                .parallax-element-<?php echo $element_number; ?> .parallax-button:hover,
                                .parallax-element-<?php echo $element_number; ?> .parallax-button:focus {
                                    <?php echo $button_hover_css; ?> !important;
                                }
                            </style>
                        <?php endif; ?>
                        
                        <div class="parallax-button-wrapper">
                            <a href="<?php echo esc_url($button_url); ?>" class="parallax-button"<?php echo $button_style_attr; ?>>
                                <?php echo esc_html($button_text); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
            </div>
            
            <?php endfor; ?>
        <?php } ?>
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎬 Parallax Grid: Script geladen');
    
    const blockId = '<?php echo esc_js($block_id); ?>';
    const elements = document.querySelectorAll('#' + blockId + ' .parallax-element');
    
    console.log('🔍 Parallax Grid: Gefundene Elemente:', elements.length);
    console.log('🎯 Block ID:', blockId);
    
    if (elements.length === 0) {
        console.warn('⚠️ Parallax Grid: Keine .parallax-element gefunden!');
        return;
    }
    
    // Intersection Observer Setup
    const observerOptions = {
        threshold: [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0],
        rootMargin: '0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const element = entry.target;
            const ratio = entry.intersectionRatio;
            
            // Debug-Info
            if (Math.random() < 0.1) { // Nur gelegentlich loggen
                console.log('📊 Parallax Ratio:', ratio.toFixed(2));
            }
            
            if (ratio >= 0.9) {
                // Element ist 90% oder mehr sichtbar - Vollständig eingeblendet
                element.style.transform = 'scale(1)';
                element.style.opacity = '1';
            } else if (ratio > 0) {
                // Element ist teilweise sichtbar - Dramatische Transformation
                const scale = 0.6 + (ratio / 0.9) * 0.4; // 0.6 bis 1.0 (größerer Effekt!)
                const opacity = 0.3 + (ratio / 0.9) * 0.7; // 0.3 bis 1.0 (stärkerer Kontrast!)
                element.style.transform = 'scale(' + scale + ')';
                element.style.opacity = opacity;
            } else {
                // Element ist nicht sichtbar - Stark reduziert
                element.style.transform = 'scale(0.6)';
                element.style.opacity = '0.3';
            }
        });
    }, observerOptions);
    
    // Alle Elemente beobachten
    elements.forEach((element, index) => {
        console.log('🎨 Setup Element ' + (index + 1) + ':', element);
        
        // Initiale Styles setzen - DRAMATISCHER EFFEKT!
        element.style.transform = 'scale(0.6)';
        element.style.opacity = '0.3';
        element.style.transition = 'transform 0.8s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.8s ease-out';
        element.style.transformOrigin = 'center';
        
        // Observer starten
        observer.observe(element);
    });
    
    console.log('✅ Parallax Grid: Setup abgeschlossen!');
});
</script> 