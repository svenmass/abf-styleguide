<?php
/**
 * Hero Block Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get block data
$title = get_field('title');
$text = get_field('text');
$background_image = get_field('background_image');
$button_text = get_field('button_text');
$button_link = get_field('button_link');
$button_color = get_field('button_color');

// Fallback for old field names
if (!$title) $title = get_field('color_name');
if (!$text) $text = get_field('color_value');

// Block classes
$block_classes = array('block-hero');
if ($background_image) {
    $block_classes[] = 'has-background';
}

// Container class based on location
$container_class = is_front_page() ? 'container-home' : 'container-content';
?>

<div class="<?php echo esc_attr(implode(' ', $block_classes)); ?>">
    <?php if ($background_image): ?>
        <div class="hero-background">
            <img src="<?php echo esc_url($background_image['url']); ?>" 
                 alt="<?php echo esc_attr($background_image['alt']); ?>"
                 class="hero-bg-image">
        </div>
    <?php endif; ?>
    
    <div class="hero-content">
        <div class="<?php echo esc_attr($container_class); ?>">
            <div class="hero-inner">
                <?php if ($title): ?>
                    <h1 class="hero-title"><?php echo esc_html($title); ?></h1>
                <?php endif; ?>
                
                <?php if ($text): ?>
                    <div class="hero-text">
                        <?php echo wp_kses_post($text); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($button_text && $button_link): ?>
                    <div class="hero-button">
                        <a href="<?php echo esc_url($button_link); ?>" 
                           class="btn <?php echo $button_color ? 'btn-' . esc_attr($button_color) : 'btn-primary'; ?>"
                           style="<?php echo $button_color && $button_color !== 'primary' && $button_color !== 'secondary' ? 'background-color: var(--color-' . esc_attr($button_color) . ');' : ''; ?>">
                            <?php echo esc_html($button_text); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 