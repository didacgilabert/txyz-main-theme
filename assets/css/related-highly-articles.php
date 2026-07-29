<div class="highly-related">
    <h3 class="wp-block-heading has-medium-font-size highly-related">
        <span class="main-title"><?php echo get_the_title(5653); ?></span>
        <span class="secondary-title line-break"><?php echo get_secondary_title(5653); ?></span>
    </h3>

    <ul class="related-articles by-project">
        <?php

        // Get the current post's ID
        $current_post_id = get_the_ID();
        
        // Get array of terms
        $terms = get_the_terms($post->ID, 'project', 'string');

        // Check if there are related posts by project
        if ($terms && !empty($terms[1])) {
            // Get the second term in the list
            $second_term_id = $terms[1]->term_id;

            // Query related posts by project
            $second_query = new WP_Query(array(
                'post_type' => 'post',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'project',
                        'field' => 'id',
                        'terms' => $second_term_id,
                        'operator' => 'IN'
                    )
                ),
                'posts_per_page' => 3,
                'ignore_sticky_posts' => 1,
                'orderby' => 'rand',
                'cache_results' => false,
                'no_found_rows' => true, // Optimize query performance
                'post__not_in' => array($current_post_id) // Exclude the current post
            ));

            // Loop through related posts by project and display them
            while ($second_query->have_posts()) : $second_query->the_post(); ?>
                <li>
                    <?php if (has_post_thumbnail()) { ?>
                        <a href="<?php the_permalink() ?>" title="<?php echo strip_tags(get_the_title()); ?>"><figure class="wp-block-post-featured-image"><?php the_post_thumbnail('related_sm', array('alt' => get_the_title())); ?></figure>
                        <h4 class="wp-block-post-title has-small-font-size"><?php the_title(); ?></h4></a>
                    <?php } else { ?>
                        <h4 class="wp-block-post-title has-small-font-size"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
                    <?php } ?>
                </li>
            <?php endwhile;
            wp_reset_query();
    }
        ?>
    </ul>

    <?php
    // If there are no related posts by project, display related posts by tag
    if (!$terms || empty($terms[1])) : ?>
        <ul class="related-articles by-tag">
            <?php
            // Get array of terms
            $terms = get_the_terms($post->ID, 'post_tag', 'string');
            // Pluck out the IDs to get an array of IDS
            $term_ids = wp_list_pluck($terms, 'term_id');

            // Query related posts by tag
            $second_query = new WP_Query(array(
                'post_type' => 'post',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'post_tag',
                        'field' => 'id',
                        'terms' => $term_ids,
                        'operator' => 'IN'
                    )
                ),
                'posts_per_page' => 3,
                'ignore_sticky_posts' => 1,
                'cache_results' => false,
                'no_found_rows' => true, // Optimize query performance
                'order'    => 'ASC',
                'post__not_in' => array($current_post_id) // Exclude the current post
            ));

            // Loop through related posts by tag and display them
            while ($second_query->have_posts()) : $second_query->the_post(); ?>
                <li>
                    <?php if (has_post_thumbnail()) { ?>
                        <a href="<?php the_permalink() ?>" title="<?php echo strip_tags(get_the_title()); ?>"><figure class="wp-block-post-featured-image"><?php the_post_thumbnail('related_sm', array('alt' => get_the_title())); ?></figure>
                        <h4 class="wp-block-post-title has-small-font-size"><?php the_title(); ?></h4></a>
                    <?php } else { ?>
                        <h4 class="wp-block-post-title has-small-font-size"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
                    <?php } ?>
                </li>
            <?php endwhile;
            wp_reset_query();
            ?>
        </ul>
    <?php endif; ?>
</div>