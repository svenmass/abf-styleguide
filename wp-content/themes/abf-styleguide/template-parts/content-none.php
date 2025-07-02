<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package ABF_Styleguide
 */

?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Nichts gefunden', 'abf-styleguide'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <?php
        if (is_home() && current_user_can('publish_posts')) :

            printf(
                '<p>' . wp_kses(
                    /* translators: 1: link to WP admin new post page. */
                    __('Bereit, deinen ersten Beitrag zu veröffentlichen? <a href="%1$s">Hier geht\'s los</a>.', 'abf-styleguide'),
                    array(
                        'a' => array(
                            'href' => array(),
                        ),
                    )
                ) . '</p>',
                esc_url(admin_url('post-new.php'))
            );

        elseif (is_search()) :
            ?>

            <p><?php esc_html_e('Entschuldigung, aber nichts entsprach deinen Suchbegriffen. Bitte versuche es mit anderen Schlüsselwörtern.', 'abf-styleguide'); ?></p>
            <?php
            get_search_form();

        else :
            ?>

            <p><?php esc_html_e('Es scheint, als könnten wir das, wonach du suchst, nicht finden. Vielleicht hilft eine Suche.', 'abf-styleguide'); ?></p>
            <?php
            get_search_form();

        endif;
        ?>
    </div><!-- .page-content -->
</section><!-- .no-results --> 