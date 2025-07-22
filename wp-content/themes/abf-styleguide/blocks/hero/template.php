<?php
/**
 * Hero Block Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get block data
$background_type = get_field('hero_background_type') ?: 'image';
$background_image = get_field('hero_background_image');
$background_video = get_field('hero_background_video');
$glass_effect = get_field('hero_glass_effect') ?: 'light'; // 🎨 Glaseffekt-Stil
$headline = get_field('hero_headline');
$headline_tag = get_field('hero_headline_tag') ?: 'h1';
$headline_weight = get_field('hero_headline_weight') ?: '700';
$headline_size = get_field('hero_headline_size') ?: '48';
$headline_color = get_field('hero_headline_color') ?: 'primary';
$subline = get_field('hero_subline');
$subline_tag = get_field('hero_subline_tag') ?: 'h2';
$subline_weight = get_field('hero_subline_weight') ?: '400';
$subline_size = get_field('hero_subline_size') ?: '24';
$subline_color = get_field('hero_subline_color') ?: 'secondary';
$button_text = get_field('hero_button_text');
$button_url_field = get_field('hero_button_url');
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
$button_bg_color = get_field('hero_button_bg_color') ?: 'primary';
$button_text_color = get_field('hero_button_text_color') ?: 'white';
$button_hover_bg = get_field('hero_button_hover_bg') ?: 'secondary';
$button_hover_text = get_field('hero_button_hover_text') ?: 'white';

// Don't render if no headline
if (!$headline) {
    return;
}

// Build inline styles for elements
$headline_styles = array();
$subline_styles = array();
$button_styles = array();
$button_hover_styles = array();

// Headline styles
// Font-size: Backend-Einstellung hat Vorrang, CSS Custom Properties als Fallback
if ($headline_size) {
    $headline_styles[] = 'font-size: ' . esc_attr($headline_size) . 'px';
}
if ($headline_weight) {
    $headline_styles[] = 'font-weight: ' . esc_attr($headline_weight);
}
if ($headline_color) {
    if ($headline_color === 'inherit') {
        $headline_styles[] = 'color: #575756'; // Standard text color
    } elseif ($headline_color === 'primary') {
        $headline_styles[] = 'color: #66a98c'; // Primary brand color
    } elseif ($headline_color === 'secondary') {
        $headline_styles[] = 'color: #c50d14'; // Secondary brand color
    } else {
        $color_value = abf_get_color_value($headline_color);
        if ($color_value) {
            $headline_styles[] = 'color: ' . $color_value;
        }
    }
}

// Subline styles
// Font-size: Backend-Einstellung hat Vorrang, CSS Custom Properties als Fallback
if ($subline_size) {
    $subline_styles[] = 'font-size: ' . esc_attr($subline_size) . 'px';
}
if ($subline_weight) {
    $subline_styles[] = 'font-weight: ' . esc_attr($subline_weight);
}
if ($subline_color) {
    if ($subline_color === 'inherit') {
        $subline_styles[] = 'color: #575756'; // Standard text color
    } elseif ($subline_color === 'primary') {
        $subline_styles[] = 'color: #66a98c'; // Primary brand color
    } elseif ($subline_color === 'secondary') {
        $subline_styles[] = 'color: #c50d14'; // Secondary brand color
    } else {
        $color_value = abf_get_color_value($subline_color);
        if ($color_value) {
            $subline_styles[] = 'color: ' . $color_value;
        }
    }
}

// Button styles
$button_bg_value = '';
$button_text_value = '';
$button_hover_bg_value = '';
$button_hover_text_value = '';

if ($button_bg_color === 'inherit') {
    $button_bg_value = '#575756'; // Standard text color
} elseif ($button_bg_color === 'primary') {
    $button_bg_value = '#66a98c'; // Primary brand color
} elseif ($button_bg_color === 'secondary') {
    $button_bg_value = '#c50d14'; // Secondary brand color
} else {
    $color_value = abf_get_color_value($button_bg_color);
    if ($color_value) {
        $button_bg_value = $color_value;
    }
}

if ($button_text_color === 'inherit') {
    $button_text_value = '#575756'; // Standard text color
} elseif ($button_text_color === 'primary') {
    $button_text_value = '#66a98c'; // Primary brand color
} elseif ($button_text_color === 'secondary') {
    $button_text_value = '#c50d14'; // Secondary brand color
} else {
    $color_value = abf_get_color_value($button_text_color);
    if ($color_value) {
        $button_text_value = $color_value;
    }
}

if ($button_hover_bg === 'inherit') {
    $button_hover_bg_value = '#575756'; // Standard text color
} elseif ($button_hover_bg === 'primary') {
    $button_hover_bg_value = '#66a98c'; // Primary brand color
} elseif ($button_hover_bg === 'secondary') {
    $button_hover_bg_value = '#c50d14'; // Secondary brand color
} else {
    $color_value = abf_get_color_value($button_hover_bg);
    if ($color_value) {
        $button_hover_bg_value = $color_value;
    }
}

if ($button_hover_text === 'inherit') {
    $button_hover_text_value = '#575756'; // Standard text color
} elseif ($button_hover_text === 'primary') {
    $button_hover_text_value = '#66a98c'; // Primary brand color
} elseif ($button_hover_text === 'secondary') {
    $button_hover_text_value = '#c50d14'; // Secondary brand color
} else {
    $color_value = abf_get_color_value($button_hover_text);
    if ($color_value) {
        $button_hover_text_value = $color_value;
    }
}

$block_id = 'hero-' . uniqid();
?>

<div class="block-hero" id="<?php echo esc_attr($block_id); ?>">
    <div class="container-home">
        
        <!-- Background -->
        <div class="hero-background">
            <?php if ($background_type === 'video' && $background_video): ?>
                <video class="hero-background-video" autoplay muted loop playsinline>
                    <source src="<?php echo esc_url($background_video['url']); ?>" type="<?php echo esc_attr($background_video['mime_type']); ?>">
                </video>
            <?php elseif ($background_image): ?>
                <img class="hero-background-image" 
                     src="<?php echo esc_url($background_image['url']); ?>" 
                     alt="<?php echo esc_attr($background_image['alt']); ?>">
            <?php endif; ?>
        </div>

        <!-- Logo -->
        <div class="hero-logo">
            <svg width="122" height="50" viewBox="0 0 122 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.9044 41.0847L13.5471 48.833C13.378 49.3161 12.9239 49.6591 12.3877 49.6591H7.76962C7.0885 49.6591 6.53297 49.1084 6.53297 48.4273C6.53297 48.2968 6.5523 48.1664 6.59094 48.0505L8.67295 41.0847H1.24824C1.11782 41.0847 0.972898 41.0557 0.84247 41.0074C0.195164 40.79 -0.152643 40.0992 0.0647361 39.4519L1.40283 35.1816C1.57673 34.6599 2.06945 34.3363 2.596 34.3459H10.7163L13.0205 26.8149C13.1799 26.3174 13.6389 25.9551 14.1895 25.9551H14.2185L18.8608 25.9406C19.5419 25.9406 20.0829 26.4913 20.0829 27.1724C20.0829 27.3125 20.0588 27.4429 20.0201 27.5685L17.943 34.3411H25.7686C25.899 34.3411 26.0343 34.3604 26.1647 34.4039C26.812 34.6213 27.2033 35.172 26.9859 35.8193L25.6575 40.2345C25.4788 40.7562 24.9909 41.075 24.4643 41.0654H15.8996L15.9044 41.0847Z" fill="#74A68E"/>
                <path d="M35.0144 49.6639C34.3333 49.6639 33.7778 49.1132 33.7778 48.4321V45.2632C33.7778 44.5821 34.3285 44.0314 35.0096 44.0314H37.3041L26.2419 7.6276H26.0825L22.0296 20.9698C21.8605 21.4529 21.4065 21.7959 20.8703 21.7959H16.17C15.4889 21.7959 14.972 21.2935 14.972 20.6124C14.972 20.482 14.9914 20.3564 15.03 20.2356L20.9282 0.806718C21.1021 0.333315 21.5466 0 22.0779 0H30.2466C30.7779 0 31.2223 0.333315 31.3962 0.806718L33.4734 7.63243L44.5308 44.0314H46.4775C47.1587 44.0314 47.7045 44.5821 47.7045 45.2632V48.4321C47.7045 49.1132 47.1538 49.6639 46.4727 49.6639H35.0192H35.0144Z" fill="#B62D1F"/>
                <path d="M107.204 48.4321C107.204 49.1132 106.658 49.6639 105.977 49.6639H94.5329C93.8518 49.6639 93.2963 49.1132 93.2963 48.4321V45.2632C93.2963 44.5821 93.847 44.0314 94.5281 44.0314H96.4652V6.33781H94.5329C93.8518 6.33781 93.2963 5.78712 93.2963 5.106V1.23182C93.2963 0.550694 93.847 0 94.5281 0H120.773C121.454 0 122 0.550694 122 1.23182V8.2749C122 8.95602 121.444 9.50672 120.763 9.50672H116.899C116.218 9.50672 115.657 8.95602 115.657 8.2749V6.33781H103.682V20.7766H115.049C115.73 20.7766 116.276 21.3273 116.276 22.0084V25.8826C116.276 26.5637 115.725 27.1144 115.044 27.1144H103.682V44.0265H105.977C106.658 44.0265 107.204 44.5772 107.204 45.2584V48.4273V48.4321Z" fill="#B62D1F"/>
                <path d="M70.6162 0C78.9878 0 84.7169 5.41516 84.7169 12.4631C84.7169 17.2841 82.2581 21.8442 78.239 23.9504C83.7991 25.3899 86.6926 30.6794 86.6926 36.4134C86.6926 43.7319 80.5432 49.6591 73.2296 49.6591H53.4191C52.738 49.6591 52.1825 49.1084 52.1825 48.4273V45.2584C52.1873 44.5772 52.7332 44.0265 53.4143 44.0265H55.3514V5.59389H53.4191C52.738 5.59389 52.1825 5.0432 52.1825 4.36208V1.23182C52.1873 0.550694 52.7332 0 53.4143 0H70.6114H70.6162ZM70.6356 27.1144H62.5732V44.0265L70.6307 44.0652C75.3213 44.0652 79.1278 40.2586 79.1278 35.5632C79.1278 30.8678 75.3261 27.1096 70.6356 27.1096M70.5583 20.7863C74.5484 20.7863 77.4709 17.5497 77.4709 13.5596C77.4709 9.56952 75.3116 5.84025 70.5631 5.58906H62.578V20.8153L70.5631 20.7815L70.5583 20.7863Z" fill="#B62D1F"/>
            </svg>
        </div>

        <!-- Plus Icon -->
        <div class="hero-plus">
            <svg width="793" height="716" viewBox="0 0 793 716" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.7" d="M792.32 490.05H515.24L515.4 490.67L446.98 715.45H214.23L281.44 490.67H41.24C37.02 490.67 32.33 489.73 28.11 488.17C7.16996 481.14 -4.08002 458.8 2.94998 437.87L46.24 299.78C51.87 282.91 67.81 272.44 84.84 272.76H347.55L422.1 29.23C427.26 13.14 442.1 1.42001 459.92 1.42001H460.86L611.05 0.950012C633.08 0.950012 650.59 18.76 650.59 40.78C650.59 45.31 649.81 49.53 648.56 53.59L581.36 272.6H792.11L792.34 490.05H792.32Z" fill="white"/>
            </svg>
        </div>

        <!-- Content -->
        <div class="hero-content hero-content--<?php echo esc_attr($glass_effect); ?>">
            <!-- Headline -->
            <?php if ($headline): ?>
                <<?php echo esc_attr($headline_tag); ?> class="hero-headline" 
                    <?php if (!empty($headline_styles)): ?>style="<?php echo esc_attr(implode('; ', $headline_styles)); ?>"<?php endif; ?>>
                    <?php echo wp_kses_post($headline); ?>
                </<?php echo esc_attr($headline_tag); ?>>
            <?php endif; ?>

            <!-- Subline -->
            <?php if ($subline): ?>
                <<?php echo esc_attr($subline_tag); ?> class="hero-subline" 
                    <?php if (!empty($subline_styles)): ?>style="<?php echo esc_attr(implode('; ', $subline_styles)); ?>"<?php endif; ?>>
                    <?php echo wp_kses_post($subline); ?>
                </<?php echo esc_attr($subline_tag); ?>>
            <?php endif; ?>

            <!-- Button -->
            <?php if ($button_text): ?>
                <div class="hero-button-wrapper">
                    <?php if ($button_url): ?>
                        <?php
                        // Handle special modal URLs
                        $onclick_attr = '';
                        $href_attr = esc_url($button_url);
                        
                        if ($button_url === '#register-modal' || $button_url === '#register') {
                            $onclick_attr = ' onClick="ABF_UserManagement.showModal(); ABF_UserManagement.switchTab(\'register\'); return false;"';
                            $href_attr = '#';
                        } elseif ($button_url === '#login-modal' || $button_url === '#login') {
                            $onclick_attr = ' onClick="ABF_UserManagement.showModal(); ABF_UserManagement.switchTab(\'login\'); return false;"';
                            $href_attr = '#';
                        } elseif ($button_url === '#modal' || $button_url === '#anmelden') {
                            $onclick_attr = ' onClick="ABF_UserManagement.showModal(); return false;"';
                            $href_attr = '#';
                        }
                        ?>
                        <a href="<?php echo $href_attr; ?>" target="<?php echo esc_attr($button_target); ?>" class="hero-button"<?php echo $onclick_attr; ?>>
                            <?php echo esc_html($button_text); ?>
                        </a>
                    <?php else: ?>
                        <button class="hero-button" type="button">
                            <?php echo esc_html($button_text); ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Inline CSS for button hover effects -->
    <?php if ($button_text && ($button_bg_value || $button_text_value || $button_hover_bg_value || $button_hover_text_value)): ?>
        <style>
            #<?php echo esc_attr($block_id); ?> .hero-button {
                <?php if ($button_bg_value): ?>background-color: <?php echo esc_attr($button_bg_value); ?>;<?php endif; ?>
                <?php if ($button_text_value): ?>color: <?php echo esc_attr($button_text_value); ?>;<?php endif; ?>
            }
            #<?php echo esc_attr($block_id); ?> .hero-button:hover {
                <?php if ($button_hover_bg_value): ?>background-color: <?php echo esc_attr($button_hover_bg_value); ?>;<?php endif; ?>
                <?php if ($button_hover_text_value): ?>color: <?php echo esc_attr($button_hover_text_value); ?>;<?php endif; ?>
            }
        </style>
    <?php endif; ?>
</div> 