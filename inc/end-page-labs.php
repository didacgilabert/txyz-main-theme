<?php
/**
 * end-page-labs.php
 * 
 * Shortcode per injectar contingut al final dels archives de Labs.
 * Substitueix l'antic render_block filter.
 *
 * ÚS A GUTENBERG:
 *   Abans:  <div class="wp-block-group end-page-labs"></div>
 *   Ara:    [txyz_end_page_labs]
 *           (dins d'un Group block amb classe "end-page-labs" si vols mantenir l'estètica)
 */

defined('ABSPATH') || exit;

/**
 * Shortcode: [txyz_end_page_labs]
 * Mostra el contingut del post ID 13964 als archives (primera pàgina).
 */
add_shortcode('txyz_end_page_labs', function () {
    if (!is_archive() || is_paged()) {
        return '';
    }

    $post = get_post(13964);
    if (!$post) {
        return '';
    }

    $content = apply_filters('the_content', $post->post_content);

    if (empty($content)) {
        return '';
    }

    return $content;
});
