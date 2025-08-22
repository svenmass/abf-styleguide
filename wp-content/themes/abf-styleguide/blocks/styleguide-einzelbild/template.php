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
$enable_lightbox = get_field('se_enable_lightbox');
// Add lightbox class for styling (after reading ACF field)
if ($enable_lightbox) {
    $class_name .= ' has-lightbox';
} else {
    $class_name .= ' no-lightbox';
}
$show_download = get_field('se_show_download');
$download_text = get_field('se_download_text') ?: 'Bild herunterladen';
$custom_download = get_field('se_custom_download');

// Convert ACF boolean values to actual booleans
$enable_lightbox = ($enable_lightbox == 1 || $enable_lightbox === true || $enable_lightbox === '1');
$show_download = ($show_download == 1 || $show_download === true || $show_download === '1');

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
            
            <?php if ($enable_lightbox): ?>
                <!-- Lightbox enabled: Image with clickable link -->
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
            <?php else: ?>
                <!-- Lightbox disabled: Just the image -->
                <img src="<?php echo esc_url($image_url); ?>" 
                     alt="<?php echo esc_attr($image_alt); ?>" 
                     title="<?php echo esc_attr($image_title); ?>"
                     class="styleguide-einzelbild-image"
                     loading="lazy">
            <?php endif; ?>
            
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
// Only load PhotoSwipe if lightbox is enabled
if ($enable_lightbox):
    // Enqueue PhotoSwipe CSS (JavaScript will be loaded dynamically)
    wp_enqueue_style('photoswipe');

    // Add inline script for this specific gallery
?>
<script>
// Preload PhotoSwipe and initialize when DOM is loaded
// Use global variables to avoid conflicts between multiple blocks
window.photoSwipeLoaded = window.photoSwipeLoaded || false;
window.PhotoSwipeModule = window.PhotoSwipeModule || null;

// Load PhotoSwipe immediately (only if not already loaded)
if (!window.photoSwipeLoaded && !window.PhotoSwipeModule) {
    import('https://cdn.jsdelivr.net/npm/photoswipe@5.4.4/dist/photoswipe.esm.js')
        .then(module => {
            window.PhotoSwipeModule = module;
            window.photoSwipeLoaded = true;
            console.log('PhotoSwipe preloaded successfully');
        })
        .catch(error => {
            console.error('Failed to preload PhotoSwipe:', error);
        });
}

// Initialize PhotoSwipe when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing PhotoSwipe for gallery: <?php echo esc_js($gallery_id); ?>');
    
    // Prepare data for PhotoSwipe
    const galleryData = [{
        src: '<?php echo esc_js($image_url); ?>',
        width: <?php echo (int)$image['width']; ?>,
        height: <?php echo (int)$image['height']; ?>,
        alt: '<?php echo esc_js($image_alt); ?>'
    }];
    
    console.log('PhotoSwipe data:', galleryData);
    
    // Initialize PhotoSwipe for this gallery
    const galleryElements = document.querySelectorAll('[data-gallery="<?php echo esc_js($gallery_id); ?>"]');
    console.log('Found gallery elements:', galleryElements.length);
    
    if (galleryElements.length > 0) {
        galleryElements.forEach(function(element) {
            // Store original href for fallback
            const originalHref = element.href;
            
            // Remove href to prevent default navigation
            element.removeAttribute('href');
            element.style.cursor = 'pointer';
            
            element.addEventListener('click', function(e) {
                // Prevent any default behavior
                e.preventDefault();
                e.stopPropagation();
                
                console.log('PhotoSwipe click handler triggered');
                
                // Function to open PhotoSwipe
                function openPhotoSwipe() {
                    if (!window.photoSwipeLoaded || !window.PhotoSwipeModule) {
                        console.error('PhotoSwipe not loaded, using fallback');
                        window.open(originalHref, '_blank');
                        return;
                    }
                    
                    try {
                        console.log('Opening PhotoSwipe with data:', galleryData);
                        
                        // Create PhotoSwipe options
                        const options = {
                            dataSource: galleryData,
                            index: 0,
                            bgOpacity: 0.8,
                            closeOnVerticalDrag: true,
                            showHideAnimationType: 'fade'
                        };
                        
                        // Create PhotoSwipe instance
                        const pswp = new window.PhotoSwipeModule.default(options);
                        
                        <?php if ($show_download): ?>
                        // Add download button
                        pswp.on('uiRegister', function() {
                            pswp.ui.registerElement({
                                name: 'download-button',
                                order: 8,
                                isButton: true,
                                tagName: 'a',
                                html: '<svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#download-clip-einzelbild)"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.63781 10.1193C2.14292 9.5872 2.38809 9.08691 3.09184 9.08691H16.6174C17.362 9.08691 17.5663 9.54172 17.1123 10.1193L10.7195 17.8373C10.3836 18.3058 9.73429 18.4104 9.26664 18.0738C9.17584 18.0102 9.09411 17.9283 9.03055 17.8373L2.63781 10.1193Z" fill="#C6D6D1"/><path fill-rule="evenodd" clip-rule="evenodd" d="M18.3927 24.0001H1.5664C0.703746 24.0001 0 23.2997 0 22.4355C0 21.5714 0.699206 20.8665 1.5664 20.8665H18.4336C19.2963 20.8665 20 21.5669 20 22.4355C19.9682 23.3042 19.2599 23.9955 18.3927 24.0046V24.0001Z" fill="#C6D6D1"/><path fill-rule="evenodd" clip-rule="evenodd" d="M7.54596 10.4515V2.43788C7.53688 1.1053 8.60384 0.0137728 9.93869 0.000128653C9.95231 0.000128653 9.96593 0.000128653 9.97955 0.000128653C11.3099 -0.0135155 12.3995 1.05982 12.4132 2.39694C12.4132 2.41059 12.4132 2.42423 12.4132 2.43788V10.4515C12.4268 11.7841 11.3553 12.8756 10.0204 12.8893C10.0068 12.8893 9.99317 12.8893 9.97955 12.8893C8.63563 12.8893 7.5505 11.7977 7.54596 10.4515Z" fill="#C6D6D1"/></g><defs><clipPath id="download-clip-einzelbild"><rect width="20" height="24" fill="white"/></clipPath></defs></svg>',
                                onInit: (el, pswp) => {
                                    el.setAttribute('href', '<?php echo esc_js($download_url); ?>');
                                    el.setAttribute('download', '<?php echo esc_js($download_filename); ?>');
                                    el.setAttribute('target', '_blank');
                                    el.setAttribute('rel', 'noopener');
                                    el.style.cssText = 'color: white; background: rgba(0,0,0,0.5); padding: 8px 12px; border-radius: 4px; margin-right: 8px; text-decoration: none; font-size: 14px; display: flex; align-items: center; justify-content: center;';
                                }
                            });
                        });
                        <?php endif; ?>
                        
                        // Initialize PhotoSwipe (this will open it)
                        pswp.init();
                        
                        console.log('PhotoSwipe opened successfully');
                    } catch (error) {
                        console.error('Error opening PhotoSwipe:', error);
                        window.open(originalHref, '_blank');
                    }
                }
                
                // Check if PhotoSwipe is already loaded
                if (window.photoSwipeLoaded) {
                    openPhotoSwipe();
                } else {
                    // Wait a bit for PhotoSwipe to load
                    console.log('Waiting for PhotoSwipe to load...');
                    setTimeout(function() {
                        openPhotoSwipe();
                    }, 100);
                }
            });
        });
    }
});
</script>
<?php endif; // End of enable_lightbox conditional ?> 