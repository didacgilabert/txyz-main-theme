<?php
function get_cta_content_by_id( $post_id ) {
    // Static cache — apply_filters('the_content') és car.
    // Els IDs de CTA són fixes, no cal processar-los més d'una vegada per request.
    static $cache = array();

    if ( isset( $cache[ $post_id ] ) ) {
        return $cache[ $post_id ];
    }

    $post = get_post( $post_id );
    $cache[ $post_id ] = $post ? apply_filters( 'the_content', $post->post_content ) : '';

    return $cache[ $post_id ];
}

// Get the current queried object
$queried_object = get_queried_object();
$cta_post_id    = null;

// Check for categories, tags, and custom taxonomies
if ( is_category() || is_tag() || is_tax() ) {
    if ( has_term( 'workshops', $queried_object->taxonomy ) ) {
        $cta_post_id = 16969; // Workshops
    } elseif ( has_term( 'labs', $queried_object->taxonomy ) ) {
        $cta_post_id = 16970; // Labs
    } elseif ( has_term( 'work', $queried_object->taxonomy ) ) {
        $cta_post_id = 16971; // Work
    } elseif ( is_category( 'juggling' ) || is_tag( 'juggling-research' ) || has_term( 'juggling-research', $queried_object->taxonomy ) ) {
        $cta_post_id = 16912; // Juggling Research
    } elseif ( is_category( 'diabolo' ) || is_tag( 'diabolo-tricks' ) || has_term( 'sol', $queried_object->taxonomy ) ) {
        $cta_post_id = 16903; // Sol
    }
}

// Display the CTA content
if ( $cta_post_id ) {
    echo get_cta_content_by_id( $cta_post_id );
} else {
    echo '<!-- No matching CTA found -->';
}
?>