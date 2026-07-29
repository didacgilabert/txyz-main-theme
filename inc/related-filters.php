<?php
/**
 * related-filters.php
 * 
 * Shortcode per injectar els filtres del blog (project, category, tags, location).
 * Substitueix l'antic render_block filter.
 *
 * ÚS A GUTENBERG:
 *   Abans:  <div class="wp-block-group filters-on-blog"></div>
 *   Ara:    [txyz_blog_filters]
 *           (dins d'un Group block amb classe "filters-on-blog" si vols mantenir l'estètica)
 */

defined('ABSPATH') || exit;

/**
 * Shortcode: [txyz_blog_filters]
 * Mostra els filtres de taxonomia del post actual.
 */
add_shortcode('txyz_blog_filters', function () {
    if (!is_single() || get_post_type() !== 'post') {
        return '';
    }

    ob_start();
    include get_template_directory() . '/inc/filters-blog.php';
    return ob_get_clean();
});
