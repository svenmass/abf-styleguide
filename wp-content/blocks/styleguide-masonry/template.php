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
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class_name); ?>">
    
    <?php if ($headline_text): ?>
        <<?php echo esc_attr($headline_tag); ?> class="<?php echo esc_attr($headline_class_string); ?>" 
            style="font-weight: <?php echo esc_attr($headline_weight); ?>; font-size: <?php echo esc_attr($headline_size); ?>px; color: <?php echo esc_attr(abf_get_styleguide_color_value($headline_color)); ?>;">
            <?php echo esc_html($headline_text); ?>
        </<?php echo esc_attr($headline_tag); ?>>
    <?php endif; ?>
    
    <div class="styleguide-masonry-container" id="<?php echo esc_attr($gallery_id); ?>">
        <?php foreach ($masonry_images as $index => $item): ?>
            <?php
            $image = $item['image'];
            $caption = $item['caption'];
            $alt_text = $item['alt_text'];
            $show_download = $item['show_download'];
            $download_text = $item['download_text'] ?: 'Download';
            $download_file = $item['download_file'];
            
            // Convert ACF boolean values to actual booleans
            $show_download = ($show_download == 1 || $show_download === true || $show_download === '1');
            
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
            $img_thumbnail = wp_get_attachment_image_src($image['id'], 'thumbnail');
            
            // Use medium size for display, large for lightbox
            $display_url = $img_medium ? $img_medium[0] : $img_url;
            $lightbox_url = $img_large ? $img_large[0] : $img_url;
            $lightbox_width = $img_large ? $img_large[1] : $img_width;
            $lightbox_height = $img_large ? $img_large[2] : $img_height;
            
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
                <a href="<?php echo esc_url($lightbox_url); ?>" 
                   class="styleguide-masonry-image-link"
                   data-pswp-width="<?php echo esc_attr($lightbox_width); ?>"
                   data-pswp-height="<?php echo esc_attr($lightbox_height); ?>"
                   data-pswp-caption="<?php echo esc_attr($caption); ?>"
                   data-pswp-download-url="<?php echo esc_attr($download_url); ?>"
                   data-pswp-download-text="<?php echo esc_attr($download_text); ?>"
                   data-pswp-show-download="<?php echo $show_download ? 'true' : 'false'; ?>">
                    
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize PhotoSwipe for this masonry gallery
    const galleryElement = document.getElementById('<?php echo esc_js($gallery_id); ?>');
    
    if (galleryElement && typeof PhotoSwipe !== 'undefined') {
        const links = galleryElement.querySelectorAll('.styleguide-masonry-image-link');
        
        links.forEach((link, index) => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Prepare data source for PhotoSwipe
                const dataSource = [];
                links.forEach((item, idx) => {
                    const showDownload = item.dataset.pswpShowDownload === 'true';
                    const downloadUrl = item.dataset.pswpDownloadUrl;
                    const downloadText = item.dataset.pswpDownloadText;
                    
                    dataSource.push({
                        src: item.href,
                        width: parseInt(item.dataset.pswpWidth) || 800,
                        height: parseInt(item.dataset.pswpHeight) || 600,
                        alt: item.querySelector('img').alt,
                        caption: item.dataset.pswpCaption || '',
                        downloadUrl: showDownload ? downloadUrl : null,
                        downloadText: downloadText || 'Download'
                    });
                });
                
                // PhotoSwipe options
                const options = {
                    index: index,
                    bgOpacity: 0.9,
                    showHideOpacity: true,
                    loop: true,
                    closeOnVerticalDrag: true,
                    wheelToZoom: true,
                    preload: [1, 1]
                };
                
                // Create and open PhotoSwipe instance
                const lightbox = new PhotoSwipe.default(options);
                lightbox.init();
                
                // Add download button functionality
                lightbox.on('uiRegister', function() {
                    lightbox.ui.registerElement({
                        name: 'download-button',
                        order: 8,
                        isButton: true,
                        html: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 12.5L6.25 8.75L7.5 7.5L9.375 9.375V2.5H10.625V9.375L12.5 7.5L13.75 8.75L10 12.5ZM5 15.625V13.75H6.25V15.625H13.75V13.75H15V15.625C15 15.9583 14.8958 16.25 14.6875 16.5C14.4792 16.75 14.1875 16.875 13.8125 16.875H6.1875C5.8125 16.875 5.52083 16.75 5.3125 16.5C5.10417 16.25 5 15.9583 5 15.625Z" fill="currentColor"/></svg>',
                        onInit: (el, pswp) => {
                            el.style.display = 'none'; // Hide by default
                        },
                        onClick: (event, el, pswp) => {
                            const currentSlide = pswp.currSlide?.data;
                            if (currentSlide?.downloadUrl) {
                                const link = document.createElement('a');
                                link.href = currentSlide.downloadUrl;
                                link.download = '';
                                link.style.display = 'none';
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            }
                        }
                    });
                });
                
                // Update download button visibility when slide changes
                lightbox.on('change', function() {
                    const currentSlide = lightbox.currSlide?.data;
                    const downloadButton = lightbox.element.querySelector('.pswp__button--download-button');
                    
                    if (downloadButton) {
                        downloadButton.style.display = currentSlide?.downloadUrl ? 'block' : 'none';
                        downloadButton.title = currentSlide?.downloadText || 'Download';
                    }
                });
                
                lightbox.loadAndOpen(index, dataSource);
            });
        });
    }
});
</script>

<?php
// Debug output for administrators
if (current_user_can('administrator') && defined('WP_DEBUG') && WP_DEBUG) {
    echo '<!-- DEBUG: Styleguide Masonry Block -->';
    echo '<!-- Images: ' . count($masonry_images) . ' -->';
    echo '<!-- Headline: ' . ($headline_text ? $headline_text : 'None') . ' -->';
    echo '<!-- Gallery ID: ' . $gallery_id . ' -->';
    echo '<!-- END DEBUG -->';
}
?> 