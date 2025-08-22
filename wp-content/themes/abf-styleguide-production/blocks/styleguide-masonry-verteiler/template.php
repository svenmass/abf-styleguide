<?php
/**
 * Styleguide Masonry Verteiler Block Template
 *
 * @package ABF_Styleguide
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'styleguide-masonry-verteiler-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'styleguide-masonry-verteiler-block';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

// Get ACF fields
$headline_text = get_field('smv_headline_text');
$headline_tag = get_field('smv_headline_tag') ?: 'h2';
$headline_size = get_field('smv_headline_size') ?: '24';
$headline_weight = get_field('smv_headline_weight') ?: '400';
$headline_color = get_field('smv_headline_color') ?: 'inherit';
$masonry_elements = get_field('smv_masonry_elements');

// Early return if no elements
if (!$masonry_elements || !is_array($masonry_elements)) {
    echo '<div class="styleguide-masonry-verteiler-placeholder"><p>Bitte fügen Sie Masonry-Elemente hinzu.</p></div>';
    return;
}

// Prepare headline classes
$headline_class_string = 'styleguide-masonry-verteiler-headline';

// Hybrid-System: Desktop behält PHP-Spalten (für exakte Reihenfolge), 
// Tablet/Mobile verwenden CSS-Columns (für responsive Verteilung)

// Calculate Masonry Layout - 3 columns system für Desktop
$columns = 3;
$column_data = array();
$column_heights = array(0, 0, 0); // Track height of each column

// Initialize columns
for ($i = 0; $i < $columns; $i++) {
    $column_data[$i] = array();
}

// Distribute elements across columns (für Desktop-Darstellung)
foreach ($masonry_elements as $index => $element) {
    $format = $element['element_format'] ?: 'landscape';
    $height_units = ($format === 'portrait') ? 2 : 1; // Portrait takes 2 units, landscape takes 1
    
    // Find column with minimum height
    $min_height = min($column_heights);
    $target_column = array_search($min_height, $column_heights);
    
    // Add element to target column
    $column_data[$target_column][] = array(
        'element' => $element,
        'format' => $format,
        'height_units' => $height_units,
        'index' => $index
    );
    
    // Update column height
    $column_heights[$target_column] += $height_units;
}

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class_name); ?>">
    
    <?php if ($headline_text): ?>
        <<?php echo esc_html($headline_tag); ?> class="<?php echo esc_attr($headline_class_string); ?>"
            style="<?php 
                echo 'font-size: ' . intval($headline_size) . 'px; ';
                echo 'font-weight: ' . intval($headline_weight) . '; ';
                if ($headline_color !== 'inherit') {
                    $color_value = function_exists('abf_get_styleguide_color_value') 
                        ? abf_get_styleguide_color_value($headline_color) 
                        : '#' . $headline_color;
                    echo 'color: ' . $color_value . '; ';
                }
            ?>">
            <?php echo esc_html($headline_text); ?>
        </<?php echo esc_html($headline_tag); ?>>
    <?php endif; ?>
    
    <div class="styleguide-masonry-verteiler-container desktop-columns">
        <?php foreach ($column_data as $column_index => $column_elements): ?>
            <div class="masonry-column masonry-column-<?php echo ($column_index + 1); ?>">
                
                <?php foreach ($column_elements as $item): 
                    $element = $item['element'];
                    $format = $item['format'];
                    $height_units = $item['height_units'];
                    $element_index = $item['index'];
                    
                    // Get element data
                    $content_type = $element['content_type'] ?: 'image';
                    $element_image = $element['element_image'];
                    $element_color = $element['element_color'] ?: 'primary';
                    $element_text = $element['element_text'] ?: '';
                    $text_color = $element['text_color'] ?: 'white';
                    $text_size = $element['text_size'] ?: '18';
                    $element_link = $element['element_link'];
                    $alt_text = $element['alt_text'] ?: '';
                    // ACF True/False Feld richtig auslesen - für Abwärtskompatibilität Standard = true
                    if (array_key_exists('image_shadow', $element)) {
                        // Bei ACF True/False: true/1/'1' = AN, false/0/'0'/null = AUS
                        $image_shadow = ($element['image_shadow'] === true || $element['image_shadow'] === 1 || $element['image_shadow'] === '1');
                    } else {
                        $image_shadow = true; // Abwärtskompatibilität: Standard AN
                    }
                    
                    // Prepare link data
                    $link_url = '';
                    $link_target = '_self';
                    if ($element_link && is_object($element_link)) {
                        $link_url = get_permalink($element_link->ID);
                        $link_target = '_self'; // Internal links always same window
                    }
                    
                    // Prepare content classes
                    $element_classes = array(
                        'masonry-element',
                        'masonry-element--' . $format,
                        'masonry-element--' . $content_type
                    );
                    
                    if ($link_url) {
                        $element_classes[] = 'masonry-element--linked';
                    }
                    
                    // Add shadow class for images with shadow enabled
                    if ($content_type === 'image' && $image_shadow) {
                        $element_classes[] = 'masonry-element--with-shadow';
                    }
                    
                    $element_class_string = implode(' ', $element_classes);
                    
                    // Prepare background/image
                    $background_style = '';
                    $image_src = '';
                    $image_alt = '';
                    
                    if ($content_type === 'image' && $element_image && is_array($element_image)) {
                        $image_src = $element_image['url'];
                        $image_alt = $alt_text ?: $element_image['alt'] ?: '';
                        
                        // Get responsive image sizes
                        $image_srcset = '';
                        if (isset($element_image['sizes'])) {
                            $sizes = $element_image['sizes'];
                            $srcset_parts = array();
                            
                            // Common sizes for masonry
                            $size_keys = array('medium', 'medium_large', 'large');
                            foreach ($size_keys as $size_key) {
                                if (isset($sizes[$size_key])) {
                                    $srcset_parts[] = $sizes[$size_key] . ' ' . $sizes[$size_key . '-width'] . 'w';
                                }
                            }
                            
                            if (!empty($srcset_parts)) {
                                $image_srcset = 'srcset="' . implode(', ', $srcset_parts) . '"';
                            }
                        }
                    } elseif ($content_type === 'color') {
                        $color_value = function_exists('abf_get_styleguide_color_value') 
                            ? abf_get_styleguide_color_value($element_color) 
                            : '#' . $element_color;
                        $background_style = 'background-color: ' . $color_value . ';';
                    }
                    
                    // Prepare text overlay styles
                    $text_color_value = 'white'; // Default
                    if ($text_color === 'white') {
                        $text_color_value = 'white';
                    } elseif (function_exists('abf_get_styleguide_color_value')) {
                        $text_color_value = abf_get_styleguide_color_value($text_color);
                    }
                    
                    $text_style = '';
                    if ($element_text) {
                        $text_style = 'color: ' . $text_color_value . '; font-size: ' . intval($text_size) . 'px;';
                    }
                ?>
                
                <div class="<?php echo esc_attr($element_class_string); ?>">
                    
                    <?php if ($link_url): ?>
                        <a href="<?php echo esc_url($link_url); ?>" 
                           class="masonry-element__link"
                           target="<?php echo esc_attr($link_target); ?>"
                           <?php if ($link_target !== '_self'): ?>rel="noopener"<?php endif; ?>>
                    <?php endif; ?>
                    
                        <div class="masonry-element__content" 
                             <?php if ($background_style): ?>style="<?php echo esc_attr($background_style); ?>"<?php endif; ?>>
                            
                            <?php if ($content_type === 'image' && $image_src): ?>
                                <img src="<?php echo esc_url($image_src); ?>" 
                                     alt="<?php echo esc_attr($image_alt); ?>"
                                     class="masonry-element__image"
                                     <?php if ($image_srcset): echo $image_srcset; endif; ?>
                                     sizes="(max-width: 768px) 50vw, (max-width: 480px) 100vw, 33vw"
                                     loading="lazy">
                            <?php endif; ?>
                            
                            <?php if ($element_text): ?>
                                <div class="masonry-element__text-overlay" style="<?php echo esc_attr($text_style); ?>">
                                    <?php echo esc_html($element_text); ?>
                                </div>
                            <?php endif; ?>
                            
                        </div>
                    
                    <?php if ($link_url): ?>
                        </a>
                    <?php endif; ?>
                    
                </div>
                
                <?php endforeach; ?>
                
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Mobile/Tablet: CSS-Columns für responsive Verteilung -->
    <div class="styleguide-masonry-verteiler-container mobile-columns">
        <?php foreach ($masonry_elements as $index => $element): 
            $format = $element['element_format'] ?: 'landscape';
            
            // Get element data (vereinfacht, da identisch zu oben)
            $content_type = $element['content_type'] ?: 'image';
            $element_image = $element['element_image'];
            $element_color = $element['element_color'] ?: 'primary';
            $element_text = $element['element_text'] ?: '';
            $text_color = $element['text_color'] ?: 'white';
            $text_size = $element['text_size'] ?: '18';
            $element_link = $element['element_link'];
            $alt_text = $element['alt_text'] ?: '';
            
            // ACF True/False Feld richtig auslesen
            if (array_key_exists('image_shadow', $element)) {
                $image_shadow = ($element['image_shadow'] === true || $element['image_shadow'] === 1 || $element['image_shadow'] === '1');
            } else {
                $image_shadow = true;
            }
            
            // Prepare link data
            $link_url = '';
            $link_target = '_self';
            if ($element_link && is_object($element_link)) {
                $link_url = get_permalink($element_link->ID);
                $link_target = '_self';
            }
            
            // Prepare classes
            $element_classes = array(
                'masonry-element',
                'masonry-element--' . $format,
                'masonry-element--' . $content_type
            );
            
            if ($link_url) {
                $element_classes[] = 'masonry-element--linked';
            }
            
            if ($content_type === 'image' && $image_shadow) {
                $element_classes[] = 'masonry-element--with-shadow';
            }
            
            $element_class_string = implode(' ', $element_classes);
            
            // Prepare styles (vereinfacht)
            $background_style = '';
            $image_src = '';
            $image_alt = '';
            
            if ($content_type === 'image' && $element_image && is_array($element_image)) {
                $image_src = $element_image['url'];
                $image_alt = $alt_text ?: $element_image['alt'] ?: '';
            } elseif ($content_type === 'color') {
                $color_value = function_exists('abf_get_styleguide_color_value') 
                    ? abf_get_styleguide_color_value($element_color) 
                    : '#' . $element_color;
                $background_style = 'background-color: ' . $color_value . ';';
            }
            
            $text_color_value = ($text_color === 'white') ? 'white' : 
                (function_exists('abf_get_styleguide_color_value') ? abf_get_styleguide_color_value($text_color) : 'white');
            
            $text_style = $element_text ? 'color: ' . $text_color_value . '; font-size: ' . intval($text_size) . 'px;' : '';
        ?>
        
        <div class="<?php echo esc_attr($element_class_string); ?>">
            <?php if ($link_url): ?>
                <a href="<?php echo esc_url($link_url); ?>" class="masonry-element__link" target="<?php echo esc_attr($link_target); ?>">
            <?php endif; ?>
            
                <div class="masonry-element__content" <?php if ($background_style): ?>style="<?php echo esc_attr($background_style); ?>"<?php endif; ?>>
                    <?php if ($content_type === 'image' && $image_src): ?>
                        <img src="<?php echo esc_url($image_src); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="masonry-element__image" loading="lazy">
                    <?php endif; ?>
                    
                    <?php if ($element_text): ?>
                        <div class="masonry-element__text-overlay" style="<?php echo esc_attr($text_style); ?>">
                            <?php echo esc_html($element_text); ?>
                        </div>
                    <?php endif; ?>
                </div>
            
            <?php if ($link_url): ?>
                </a>
            <?php endif; ?>
        </div>
        
        <?php endforeach; ?>
    </div>
    
</div>

<?php
// Reset post data if needed
wp_reset_postdata();
?> 