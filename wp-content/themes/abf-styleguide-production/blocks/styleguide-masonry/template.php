<?php
/**
 * Styleguide Masonry Block Template
 *
 * @package ABF_Styleguide
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'styleguide-masonry-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'styleguide-masonry-block';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

// Get ACF fields
$headline_text = get_field('sm_headline_text');
$headline_tag = get_field('sm_headline_tag') ?: 'h2';
$headline_size = get_field('sm_headline_size') ?: '24';
$headline_weight = get_field('sm_headline_weight') ?: '400';
$headline_color = get_field('sm_headline_color') ?: 'inherit';
$masonry_images = get_field('sm_masonry_images');

// Early return if no images
if (!$masonry_images || !is_array($masonry_images)) {
    echo '<div class="styleguide-masonry-placeholder"><p>Bitte fügen Sie Bilder hinzu.</p></div>';
    return;
}

// Prepare headline classes - only base class needed
$headline_class_string = 'styleguide-masonry-headline';

// Generate unique gallery ID for PhotoSwipe
$gallery_id = 'masonry-' . $block['id'];

// Prepare gallery data for PhotoSwipe
$gallery_data = array();
foreach ($masonry_images as $index => $item) {
    $image = $item['image'];
    $show_download = ($item['show_download'] == 1 || $item['show_download'] === true || $item['show_download'] === '1');
    $download_file = $item['download_file'];
    
    if ($image && is_array($image)) {
        $download_url = '';
        $download_filename = '';
        if ($show_download) {
            if ($download_file && is_array($download_file)) {
                $download_url = $download_file['url'];
                $download_filename = $download_file['filename'];
            } else {
                $download_url = $image['url'];
                $download_filename = basename($image['url']);
            }
        }
        
        $gallery_data[] = array(
            'src' => $image['url'],
            'width' => (int)$image['width'],
            'height' => (int)$image['height'],
            'alt' => $item['alt_text'] ?: $image['alt'] ?: '',
            'caption' => $item['caption'] ?: '',
            'downloadUrl' => $download_url,
            'downloadText' => $item['download_text'] ?: 'Download',
            'downloadFilename' => $download_filename,
            'showDownload' => $show_download
        );
    }
}
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class_name); ?>">
    
    <?php if ($headline_text): ?>
        <<?php echo esc_attr($headline_tag); ?> class="<?php echo esc_attr($headline_class_string); ?>" 
            style="font-weight: <?php echo esc_attr($headline_weight); ?>; font-size: <?php echo esc_attr($headline_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($headline_color)); ?>;">
            <?php echo esc_html($headline_text); ?>
        </<?php echo esc_attr($headline_tag); ?>>
    <?php endif; ?>
    
    <div class="styleguide-masonry-container" id="<?php echo esc_attr($gallery_id); ?>" data-loading="true">
        <div class="masonry-loading-text">Lade Galerie...</div>
        
        <?php foreach ($masonry_images as $index => $item): ?>
            <?php
            $image = $item['image'];
            $caption = $item['caption'];
            $alt_text = $item['alt_text'];
            $show_download = ($item['show_download'] == 1 || $item['show_download'] === true || $item['show_download'] === '1');
            $download_text = $item['download_text'] ?: 'Download';
            $download_file = $item['download_file'];
            
            // Skip if no image
            if (!$image || !is_array($image)) {
                continue;
            }
            
            // Prepare image data
            $img_url = $image['url'];
            $img_alt = $alt_text ?: $image['alt'] ?: '';
            $img_title = $image['title'] ?: '';
            $img_width = $image['width'] ?: 800;
            $img_height = $image['height'] ?: 600;
            
            // Get different image sizes for responsive display
            $img_large = wp_get_attachment_image_src($image['id'], 'large');
            $img_medium = wp_get_attachment_image_src($image['id'], 'medium');
            
            // Use medium size for display, original for lightbox
            $display_url = $img_medium ? $img_medium[0] : $img_url;
            
            // Determine download URL (custom file or original image)
            $download_url = '';
            $download_filename = '';
            if ($show_download) {
                if ($download_file && is_array($download_file)) {
                    $download_url = $download_file['url'];
                    $download_filename = $download_file['filename'];
                } else {
                    $download_url = $img_url;
                    $download_filename = basename($img_url);
                }
            }
            ?>
            
            <div class="styleguide-masonry-item" data-index="<?php echo esc_attr($index); ?>">
                <a href="<?php echo esc_url($img_url); ?>" 
                   class="styleguide-masonry-image-link"
                   data-pswp-width="<?php echo esc_attr($img_width); ?>"
                   data-pswp-height="<?php echo esc_attr($img_height); ?>"
                   data-gallery="<?php echo esc_attr($gallery_id); ?>"
                   data-index="<?php echo esc_attr($index); ?>"
                   <?php if ($show_download): ?>
                   data-download-url="<?php echo esc_url($download_url); ?>"
                   data-download-text="<?php echo esc_attr($download_text); ?>"
                   data-download-filename="<?php echo esc_attr($download_filename); ?>"
                   <?php endif; ?>>
                    
                    <div class="styleguide-masonry-image-wrapper">
                        <img src="<?php echo esc_url($display_url); ?>" 
                             alt="<?php echo esc_attr($img_alt); ?>" 
                             title="<?php echo esc_attr($img_title); ?>"
                             loading="lazy"
                             class="styleguide-masonry-image">
                        
                        <div class="styleguide-masonry-overlay">
                            <div class="styleguide-masonry-overlay-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 9V4.5C9 4.22386 9.22386 4 9.5 4H14.5C14.7761 4 15 4.22386 15 4.5V9M9 9H4.5C4.22386 9 4 9.22386 4 9.5V14.5C4 14.7761 4.22386 15 4.5 15H9M9 9V15M15 9V15M15 9H19.5C19.7761 9 20 9.22386 20 9.5V14.5C20 14.7761 19.7761 15 19.5 15H15M9 15V19.5C9 19.7761 9.22386 20 9.5 20H14.5C14.7761 20 15 19.7761 15 19.5V15M9 15H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="styleguide-masonry-overlay-text">Vollbild anzeigen</span>
                        </div>
                    </div>
                    
                </a>
                
                <?php if ($caption): ?>
                    <div class="styleguide-masonry-caption">
                        <?php echo esc_html($caption); ?>
                    </div>
                <?php endif; ?>
                
            </div>
            
        <?php endforeach; ?>
    </div>
    
</div>

<?php
// Enqueue PhotoSwipe CSS (JavaScript will be loaded dynamically)
wp_enqueue_style('photoswipe');
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
    console.log('DOM loaded, initializing PhotoSwipe for masonry gallery: <?php echo esc_js($gallery_id); ?>');
    
    // Prepare data for PhotoSwipe
    const galleryData = <?php echo json_encode($gallery_data); ?>;
    
    console.log('PhotoSwipe masonry data:', galleryData);
    
    // Initialize PhotoSwipe for this masonry gallery
    const galleryElements = document.querySelectorAll('[data-gallery="<?php echo esc_js($gallery_id); ?>"]');
    console.log('Found masonry gallery elements:', galleryElements.length);
    
    if (galleryElements.length > 0) {
        galleryElements.forEach(function(element) {
            // Get the index from data attribute
            const index = parseInt(element.dataset.index) || 0;
            
            // Store original href for fallback
            const originalHref = element.href;
            
            // Remove href to prevent default navigation
            element.removeAttribute('href');
            element.style.cursor = 'pointer';
            
            element.addEventListener('click', function(e) {
                // Prevent any default behavior
                e.preventDefault();
                e.stopPropagation();
                
                console.log('PhotoSwipe masonry click handler triggered for index:', index);
                
                // Function to open PhotoSwipe
                function openPhotoSwipe() {
                    if (!window.photoSwipeLoaded || !window.PhotoSwipeModule) {
                        console.error('PhotoSwipe not loaded, using fallback');
                        window.open(originalHref, '_blank');
                        return;
                    }
                    
                    try {
                        console.log('Opening PhotoSwipe masonry with data:', galleryData);
                        
                        // Create PhotoSwipe options
                        const options = {
                            dataSource: galleryData,
                            index: index,
                            bgOpacity: 0.8,
                            closeOnVerticalDrag: true,
                            showHideAnimationType: 'fade'
                        };
                        
                        // Create PhotoSwipe instance
                        const pswp = new window.PhotoSwipeModule.default(options);
                        
                        // Add download button for items that have downloads
                        pswp.on('uiRegister', function() {
                            pswp.ui.registerElement({
                                name: 'download-button',
                                order: 8,
                                isButton: true,
                                tagName: 'a',
                                html: '<svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#download-clip-masonry)"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.63781 10.1193C2.14292 9.5872 2.38809 9.08691 3.09184 9.08691H16.6174C17.362 9.08691 17.5663 9.54172 17.1123 10.1193L10.7195 17.8373C10.3836 18.3058 9.73429 18.4104 9.26664 18.0738C9.17584 18.0102 9.09411 17.9283 9.03055 17.8373L2.63781 10.1193Z" fill="#C6D6D1"/><path fill-rule="evenodd" clip-rule="evenodd" d="M18.3927 24.0001H1.5664C0.703746 24.0001 0 23.2997 0 22.4355C0 21.5714 0.699206 20.8665 1.5664 20.8665H18.4336C19.2963 20.8665 20 21.5669 20 22.4355C19.9682 23.3042 19.2599 23.9955 18.3927 24.0046V24.0001Z" fill="#C6D6D1"/><path fill-rule="evenodd" clip-rule="evenodd" d="M7.54596 10.4515V2.43788C7.53688 1.1053 8.60384 0.0137728 9.93869 0.000128653C9.95231 0.000128653 9.96593 0.000128653 9.97955 0.000128653C11.3099 -0.0135155 12.3995 1.05982 12.4132 2.39694C12.4132 2.41059 12.4132 2.42423 12.4132 2.43788V10.4515C12.4268 11.7841 11.3553 12.8756 10.0204 12.8893C10.0068 12.8893 9.99317 12.8893 9.97955 12.8893C8.63563 12.8893 7.5505 11.7977 7.54596 10.4515Z" fill="#C6D6D1"/></g><defs><clipPath id="download-clip-masonry"><rect width="20" height="24" fill="white"/></clipPath></defs></svg>',
                                onInit: (el, pswp) => {
                                    el.style.cssText = 'color: white; background: rgba(0,0,0,0.5); padding: 8px 12px; border-radius: 4px; margin-right: 8px; text-decoration: none; font-size: 14px; display: none; align-items: center; justify-content: center;';
                                    
                                    // Update download button on slide change
                                    const updateDownloadButton = () => {
                                        const currentSlide = pswp.currSlide?.data;
                                        if (currentSlide?.downloadUrl && currentSlide?.showDownload) {
                                            el.setAttribute('href', currentSlide.downloadUrl);
                                            el.setAttribute('download', currentSlide.downloadFilename || '');
                                            el.setAttribute('target', '_blank');
                                            el.setAttribute('rel', 'noopener');
                                            // Icon wird bereits über HTML gesetzt, kein textContent nötig
                                            el.style.display = 'flex';
                                        } else {
                                            el.style.display = 'none';
                                        }
                                    };
                                    
                                    // Initial update
                                    updateDownloadButton();
                                    
                                    // Update on slide change
                                    pswp.on('change', updateDownloadButton);
                                }
                            });
                        });
                        
                        // Initialize PhotoSwipe (this will open it)
                        pswp.init();
                        
                        console.log('PhotoSwipe masonry opened successfully');
                        
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
    
    // =============================================================================
    // JAVASCRIPT MASONRY LAYOUT
    // =============================================================================
    
    function initMasonryLayout() {
        const container = document.getElementById('<?php echo esc_js($gallery_id); ?>');
        const items = container.querySelectorAll('.styleguide-masonry-item');
        
        if (!container || items.length === 0) return;
        
        console.log('Initializing masonry layout for:', '<?php echo esc_js($gallery_id); ?>', 'Items:', items.length);
        
        // Get breakpoints and calculate columns (abgestimmt auf CSS-Breakpoints)
        function getColumns() {
            const width = window.innerWidth;
            if (width <= 576) return 1;      // Mobile: 1 column ($breakpoint-mobile)
            if (width <= 768) return 2;     // Tablet: 2 columns ($breakpoint-tablet)
            return 3;                       // Desktop: 3 columns (>768px)
        }
        
        function getGap() {
            return 1;                       // Nur Haarlinie: 1px
        }
        
        let imagesLoaded = 0;
        const totalImages = items.length;
        
        function checkAllImagesLoaded() {
            imagesLoaded++;
            if (imagesLoaded === totalImages) {
                layoutMasonry();
            }
        }
        
        function layoutMasonry() {
            const columns = getColumns();
            const gap = getGap();
            const containerWidth = container.offsetWidth;
            const itemWidth = (containerWidth - (gap * (columns - 1))) / columns;
            
            console.log('Masonry layout:', {columns, gap, containerWidth, itemWidth});
            
            // Initialize column heights
            const columnHeights = new Array(columns).fill(0);
            
            // Hide loading text and show items
            container.setAttribute('data-loading', 'false');
            
            // Position each item
            items.forEach((item, index) => {
                // Set width
                item.style.width = itemWidth + 'px';
                
                // Find shortest column
                const shortestColumnIndex = columnHeights.indexOf(Math.min(...columnHeights));
                
                // Position item
                const x = shortestColumnIndex * (itemWidth + gap);
                const y = columnHeights[shortestColumnIndex];
                
                item.style.left = x + 'px';
                item.style.top = y + 'px';
                
                // Update column height
                const itemHeight = item.offsetHeight;
                columnHeights[shortestColumnIndex] += itemHeight + gap;
            });
            
            // Set container height
            const maxHeight = Math.max(...columnHeights);
            container.style.height = maxHeight + 'px';
            
            console.log('Masonry layout complete. Container height:', maxHeight);
        }
        
        // Wait for all images to load
        items.forEach(item => {
            const img = item.querySelector('img');
            if (img) {
                if (img.complete) {
                    checkAllImagesLoaded();
                } else {
                    img.addEventListener('load', checkAllImagesLoaded);
                    img.addEventListener('error', checkAllImagesLoaded);
                }
            } else {
                checkAllImagesLoaded();
            }
        });
        
        // Re-layout on window resize
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(layoutMasonry, 250);
        });
    }
    
    // Initialize masonry
    initMasonryLayout();
});
</script>

<?php
if (current_user_can('administrator') && defined('WP_DEBUG') && WP_DEBUG) {
    echo '<!-- DEBUG: Styleguide Masonry Block -->';
    echo '<!-- Images: ' . count($masonry_images) . ' -->';
    echo '<!-- Headline: ' . ($headline_text ? $headline_text : 'None') . ' -->';
    echo '<!-- Gallery ID: ' . $gallery_id . ' -->';
    echo '<!-- END DEBUG -->';
}
?>
