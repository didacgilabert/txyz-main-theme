<?php
/**
 * archive-dynamic-cta.php
 * 
 * Shortcode per injectar CTAs dinàmics als archives.
 * Substitueix l'antic render_block filter.
 *
 * ÚS A GUTENBERG:
 *   Abans:  <div class="wp-block-group archive-dynamic-cta"></div>
 *   Ara:    [txyz_archive_cta]
 *           (dins d'un Group block amb classe "archive-dynamic-cta" si vols mantenir l'estètica)
 */

defined('ABSPATH') || exit;

/**
 * Shortcode: [txyz_archive_cta]
 * Mostra el CTA corresponent segons la taxonomia de l'archive actual.
 */
add_shortcode('txyz_archive_cta', function () {
    // Només a la primera pàgina dels archives
    if (is_paged()) {
        return '';
    }

    ob_start();
    include get_template_directory() . '/inc/cta-for-archives.php';
    return ob_get_clean();
});
