<?php
/**
 * Logo and Icon Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get logo data for current device
 */
function abf_get_logo_data() {
    $logo_data = array(
        'desktop' => get_field('desktop_logo', 'option'),
        'mobile' => get_field('mobile_logo', 'option'),
        'alt_text' => get_field('logo_alt_text', 'option') ?: get_bloginfo('name'),
    );
    
    return $logo_data;
}

/**
 * Get favicon and touch icon data
 */
function abf_get_icon_data() {
    $icon_data = array(
        'favicon' => get_field('favicon', 'option'),
        'apple_touch' => get_field('apple_touch_icon', 'option'),
        'android_touch' => get_field('android_touch_icon', 'option'),
    );
    
    return $icon_data;
}

/**
 * Output favicon and touch icon meta tags
 */
function abf_output_favicon_meta_tags() {
    $icon_data = abf_get_icon_data();
    
    // Favicon
    if (!empty($icon_data['favicon']['url'])) {
        $favicon_extension = pathinfo($icon_data['favicon']['url'], PATHINFO_EXTENSION);
        $is_svg_favicon = strtolower($favicon_extension) === 'svg';
        
        if ($is_svg_favicon) {
            echo '<link rel="icon" type="image/svg+xml" href="' . esc_url($icon_data['favicon']['url']) . '">' . "\n";
        } else {
            echo '<link rel="icon" type="image/x-icon" href="' . esc_url($icon_data['favicon']['url']) . '">' . "\n";
            echo '<link rel="shortcut icon" type="image/x-icon" href="' . esc_url($icon_data['favicon']['url']) . '">' . "\n";
        }
    }
    
    // Apple Touch Icon
    if (!empty($icon_data['apple_touch']['url'])) {
        $apple_extension = pathinfo($icon_data['apple_touch']['url'], PATHINFO_EXTENSION);
        $is_svg_apple = strtolower($apple_extension) === 'svg';
        
        if ($is_svg_apple) {
            echo '<link rel="icon" type="image/svg+xml" sizes="any" href="' . esc_url($icon_data['apple_touch']['url']) . '">' . "\n";
        } else {
            echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url($icon_data['apple_touch']['url']) . '">' . "\n";
        }
    }
    
    // Android Touch Icon
    if (!empty($icon_data['android_touch']['url'])) {
        $android_extension = pathinfo($icon_data['android_touch']['url'], PATHINFO_EXTENSION);
        $is_svg_android = strtolower($android_extension) === 'svg';
        
        if ($is_svg_android) {
            echo '<link rel="icon" type="image/svg+xml" sizes="any" href="' . esc_url($icon_data['android_touch']['url']) . '">' . "\n";
        } else {
            echo '<link rel="icon" type="image/png" sizes="192x192" href="' . esc_url($icon_data['android_touch']['url']) . '">' . "\n";
        }
    }
    
    // Default favicon fallback
    if (empty($icon_data['favicon']['url'])) {
        echo '<link rel="icon" type="image/svg+xml" href="' . esc_url(get_template_directory_uri()) . '/assets/images/favicon.svg">' . "\n";
    }
}

/**
 * Output logo HTML
 */
function abf_output_logo($device = 'desktop', $classes = '') {
    $logo_data = abf_get_logo_data();
    $logo = $device === 'mobile' ? $logo_data['mobile'] : $logo_data['desktop'];
    
    // Fallback to desktop logo if mobile logo not set
    if (empty($logo) && $device === 'mobile') {
        $logo = $logo_data['desktop'];
    }
    
    if (!empty($logo['url'])) {
        $alt_text = esc_attr($logo_data['alt_text']);
        $classes = esc_attr($classes);
        
        // Check if it's an SVG file
        $file_extension = pathinfo($logo['url'], PATHINFO_EXTENSION);
        $is_svg = strtolower($file_extension) === 'svg';
        
        if ($is_svg) {
            // For SVG files, we don't need width/height attributes
            echo '<img src="' . esc_url($logo['url']) . '" alt="' . $alt_text . '" class="' . $classes . '">';
        } else {
            // For other image types, include width/height
            echo '<img src="' . esc_url($logo['url']) . '" alt="' . $alt_text . '" class="' . $classes . '" width="' . esc_attr($logo['width']) . '" height="' . esc_attr($logo['height']) . '">';
        }
    } else {
        // Fallback to text logo
        echo '<span class="text-logo ' . esc_attr($classes) . '">' . esc_html(get_bloginfo('name')) . '</span>';
    }
}

/**
 * Get logo URL for specific device
 */
function abf_get_logo_url($device = 'desktop') {
    $logo_data = abf_get_logo_data();
    $logo = $device === 'mobile' ? $logo_data['mobile'] : $logo_data['desktop'];
    
    // Fallback to desktop logo if mobile logo not set
    if (empty($logo) && $device === 'mobile') {
        $logo = $logo_data['desktop'];
    }
    
    return !empty($logo['url']) ? $logo['url'] : '';
}

/**
 * Check if logo exists for device
 */
function abf_has_logo($device = 'desktop') {
    $logo_data = abf_get_logo_data();
    $logo = $device === 'mobile' ? $logo_data['mobile'] : $logo_data['desktop'];
    
    return !empty($logo['url']);
}

/**
 * Get logo dimensions
 */
function abf_get_logo_dimensions($device = 'desktop') {
    $logo_data = abf_get_logo_data();
    $logo = $device === 'mobile' ? $logo_data['mobile'] : $logo_data['desktop'];
    
    // Fallback to desktop logo if mobile logo not set
    if (empty($logo) && $device === 'mobile') {
        $logo = $logo_data['desktop'];
    }
    
    if (!empty($logo)) {
        return array(
            'width' => $logo['width'] ?? 0,
            'height' => $logo['height'] ?? 0,
        );
    }
    
    return array('width' => 0, 'height' => 0);
}

/**
 * Display logo for specific context (header, navigation, etc.)
 */
function abf_display_logo($context = 'header') {
    $logo_data = abf_get_logo_data();
    
    // Choose logo based on context
    $logo = null;
    $classes = '';
    
    switch ($context) {
        case 'navigation':
            // Use mobile logo for navigation (smaller size)
            $logo = $logo_data['mobile'];
            $classes = 'logo-mobile';
            // Fallback to desktop logo if mobile logo not set
            if (empty($logo)) {
                $logo = $logo_data['desktop'];
                $classes = 'logo-desktop';
            }
            break;
            
        case 'header':
        default:
            // Use desktop logo for header
            $logo = $logo_data['desktop'];
            $classes = 'logo-desktop';
            break;
    }
    
    if (!empty($logo['url'])) {
        $alt_text = esc_attr($logo_data['alt_text']);
        $classes = esc_attr($classes);
        
        // Check if it's an SVG file
        $file_extension = pathinfo($logo['url'], PATHINFO_EXTENSION);
        $is_svg = strtolower($file_extension) === 'svg';
        
        echo '<a href="' . esc_url(home_url('/')) . '" class="logo-link">';
        
        if ($is_svg) {
            // For SVG files, we don't need width/height attributes
            echo '<img src="' . esc_url($logo['url']) . '" alt="' . $alt_text . '" class="' . $classes . '">';
        } else {
            // For other image types, include width/height
            echo '<img src="' . esc_url($logo['url']) . '" alt="' . $alt_text . '" class="' . $classes . '" width="' . esc_attr($logo['width']) . '" height="' . esc_attr($logo['height']) . '">';
        }
        
        echo '</a>';
    } else {
        // Fallback to text logo
        echo '<a href="' . esc_url(home_url('/')) . '" class="logo-link">';
        echo '<span class="text-logo ' . esc_attr($classes) . '">' . esc_html(get_bloginfo('name')) . '</span>';
        echo '</a>';
    }
} 