<?php
/**
 * firts-page-blog-content.php
 *
 * Shortcode per injectar el contingut de la primera pàgina del blog.
 * Substitueix l'antic render_block filter.
 *
 * ÚS A GUTENBERG:
 *   Abans:  <div class="wp-block-group alignfull firts-blog-page-content"></div>
 *   Ara:    [txyz_first_blog_content]
 *           (dins d'un Group block amb classe "firts-blog-page-content alignfull" si vols mantenir l'estètica)
 */
defined( 'ABSPATH' ) || exit;

/**
 * Shortcode: [txyz_first_blog_content]
 * Mostra el contingut del post ID 27 a la primera pàgina del blog.
 */
add_shortcode( 'txyz_first_blog_content', function () {
    // Només a la home del blog i a la primera pàgina
    if ( ! is_home() || is_paged() ) {
        return '';
    }

    // Static cache — apply_filters('the_content') és car.
    // El post ID 27 no canvia entre crides en la mateixa request.
    static $content = null;
    if ( $content !== null ) {
        return $content;
    }

    $post = get_post( 27 );
    if ( ! $post ) {
        $content = '';
        return $content;
    }

    $content = apply_filters( 'the_content', $post->post_content );
    return $content;
} );