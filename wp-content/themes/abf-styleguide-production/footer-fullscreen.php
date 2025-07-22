    <footer id="colophon-fullscreen" class="site-footer-fullscreen">
        <div class="footer-fullscreen-container">
            <div class="footer-fullscreen-logo">
                <?php
                if (abf_has_logo('desktop')) {
                    abf_output_logo('desktop', 'footer-logo');
                } else {
                    abf_output_logo('mobile', 'footer-logo');
                }
                ?>
            </div>
            
            <nav id="footer-fullscreen-navigation" class="footer-fullscreen-navigation">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-fullscreen-menu',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                        'container'      => false,
                    )
                );
                ?>
            </nav>
        </div>
    </footer><!-- #colophon-fullscreen -->
    
    </div><!-- #content -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html> 