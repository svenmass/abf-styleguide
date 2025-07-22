    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container-content">
            <div class="site-info">
                <nav id="footer-navigation" class="footer-navigation">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </nav><!-- #footer-navigation -->
                
                <div class="footer-text">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('Alle Rechte vorbehalten.', 'abf-styleguide'); ?></p>
                    <p>
                        <a href="<?php echo esc_url(__('https://wordpress.org/', 'abf-styleguide')); ?>">
                            <?php
                            /* translators: %s: CMS name, i.e. WordPress. */
                            printf(esc_html__('Powered by %s', 'abf-styleguide'), 'WordPress');
                            ?>
                        </a>
                        <span class="sep"> | </span>
                        <?php
                        /* translators: 1: Theme name, 2: Theme author. */
                        printf(esc_html__('Theme: %1$s by %2$s.', 'abf-styleguide'), 'ABF Styleguide', '<a href="#">ABF</a>');
                        ?>
                    </p>
                </div><!-- .footer-text -->
            </div><!-- .site-info -->
        </div><!-- .container-content -->
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html> 