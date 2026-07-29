<?php
defined('ABSPATH') || exit;

add_filter('render_block', function($block_content, $block) {
    if (!is_single() || get_post_type() !== 'post') {
        return $block_content;
    }

    if (!isset($block['attrs']['className'])) {
        return $block_content;
    }

    $classes = $block['attrs']['className'];

    if (str_contains($classes, 'blog-related-projects')) {
        ob_start();
        include get_template_directory() . '/inc/related-projects.php';
        return ob_get_clean();
    }

    if (str_contains($classes, 'blog-related-highly')) {
        ob_start();
        include get_template_directory() . '/inc/related-highly-articles.php';
        return ob_get_clean();
    }

    return $block_content;
}, 10, 2);
