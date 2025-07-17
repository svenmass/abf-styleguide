<?php
/**
 * Styleguide Einzelbild Block Template
 *
 * @package ABF_Styleguide
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'styleguide-einzelbild-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'styleguide-einzelbild-block';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

// Get ACF fields
$image = get_field('se_image');
$alt_text = get_field('se_alt_text');
$caption = get_field('se_caption');
$show_download = get_field('se_show_download');
$download_text = get_field('se_download_text') ?: 'Bild herunterladen';
$custom_download = get_field('se_custom_download');

// Convert ACF boolean values to actual booleans
$show_download = ($show_download === true || $show_download === '1' || $show_download === 1);

// Early return if no image
if (!$image) {
    echo '<div class="styleguide-einzelbild-placeholder"><p>Bitte wählen Sie ein Bild aus.</p></div>';
    return;
}

// Prepare image data
$image_url = $image['url'];
$image_alt = !empty($alt_text) ? $alt_text : $image['alt'];
$image_title = $image['title'] ?: $image['alt'];

// Determine download URL (custom download or original image)
$download_url = $custom_download ? $custom_download['url'] : $image['url'];
$download_filename = $custom_download ? $custom_download['filename'] : $image['filename'];

// Generate unique IDs for PhotoSwipe
$gallery_id = 'gallery-' . $block['id'];
$lightbox_id = 'lightbox-' . $block['id'];
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class_name); ?>">
    <div class="styleguide-einzelbild-container">
        
        <!-- Image Container with 16:9 aspect ratio -->
        <div class="styleguide-einzelbild-image-wrapper">
            <a href="<?php echo esc_url($image_url); ?>" 
               class="styleguide-einzelbild-image-link"
               data-pswp-width="<?php echo esc_attr($image['width']); ?>"
               data-pswp-height="<?php echo esc_attr($image['height']); ?>"
               data-gallery="<?php echo esc_attr($gallery_id); ?>"
               <?php if ($show_download): ?>
               data-download-url="<?php echo esc_url($download_url); ?>"
               data-download-text="<?php echo esc_attr($download_text); ?>"
               data-download-filename="<?php echo esc_attr($download_filename); ?>"
               <?php endif; ?>>
                
                <img src="<?php echo esc_url($image_url); ?>" 
                     alt="<?php echo esc_attr($image_alt); ?>" 
                     title="<?php echo esc_attr($image_title); ?>"
                     class="styleguide-einzelbild-image"
                     loading="lazy">
                
                <!-- Hover Overlay -->
                <div class="styleguide-einzelbild-overlay">
                    <div class="styleguide-einzelbild-overlay-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 9V7C21 5.9 20.1 5 19 5H5C3.9 5 3 5.9 3 7V9M21 9V17C21 18.1 20.1 19 19 19H5C3.9 19 3 18.1 3 17V9M21 9H3M9 12H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="styleguide-einzelbild-overlay-text">Vollbild anzeigen</span>
                </div>
                
            </a>
        </div>
        
        <!-- Caption -->
        <?php if ($caption): ?>
            <div class="styleguide-einzelbild-caption">
                <?php echo wp_kses_post($caption); ?>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<?php
// Enqueue PhotoSwipe assets
wp_enqueue_script('photoswipe');
wp_enqueue_style('photoswipe');

// Add inline script for this specific gallery
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize PhotoSwipe for this gallery
    const galleryElements = document.querySelectorAll('[data-gallery="<?php echo esc_js($gallery_id); ?>"]');
    
    if (galleryElements.length > 0) {
        galleryElements.forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Prepare PhotoSwipe items
                const items = [{
                    src: this.href,
                    width: parseInt(this.dataset.pswpWidth),
                    height: parseInt(this.dataset.pswpHeight),
                    alt: this.querySelector('img').alt
                }];
                
                // PhotoSwipe options
                const options = {
                    index: 0,
                    showHideAnimationType: 'fade',
                    <?php if ($show_download): ?>
                    // Add download button
                    toolbar: [
                        'close',
                        {
                            name: 'download',
                            order: 9,
                            isButton: true,
                            tagName: 'a',
                            html: '<?php echo esc_js($download_text); ?>',
                            onInit: (el, pswp) => {
                                el.setAttribute('href', '<?php echo esc_js($download_url); ?>');
                                el.setAttribute('download', '<?php echo esc_js($download_filename); ?>');
                                el.setAttribute('target', '_blank');
                                el.classList.add('styleguide-download-link');
                            }
                        }
                    ]
                    <?php endif; ?>
                };
                
                // Initialize PhotoSwipe
                const lightbox = new PhotoSwipe(options);
                lightbox.init();
                lightbox.loadAndOpen(0, items);
            });
        });
    }
});
</script> 