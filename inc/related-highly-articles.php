<div class="highly-related">
    <h2 class="wp-block-heading has-medium-font-size highly-related">
        <span class="main-title"><?php echo get_the_title(5653); ?></span>
        <span class="secondary-title line-break"><?php echo get_secondary_title(5653); ?></span>
    </h2>

    <?php
    $current_post_id = get_the_ID();
    $terms = get_the_terms( $current_post_id, 'project' );
    $found_by_project = false;

    if ( $terms && ! empty( $terms[1] ) ) {
        $second_term_id = $terms[1]->term_id;

        $second_query = new WP_Query( array(
            'post_type'           => 'post',
            'tax_query'           => array( array(
                'taxonomy'         => 'project',
                'field'            => 'id',
                'terms'            => $second_term_id,
                'operator'         => 'IN',
                'include_children' => false,
            ) ),
            'posts_per_page'      => -1,
            'ignore_sticky_posts' => 1,
            'orderby'             => 'date',
            'fields'              => 'ids',
            'no_found_rows'       => true,
            'post__not_in'        => array( $current_post_id ),
        ) );

        if ( $second_query->have_posts() ) {
            $found_by_project = true;
            $ids = $second_query->posts;
            shuffle( $ids );
            $ids = array_slice( $ids, 0, 3 );
            ?>
            <ul class="related-articles by-project">
            <?php
            foreach ( $ids as $rel_id ) {
                $rel_post = get_post( $rel_id );
                if ( ! $rel_post ) { continue; }
                setup_postdata( $rel_post ); ?>
                <li>
                    <a href="<?php echo get_permalink( $rel_post ); ?>" title="<?php echo esc_attr( get_the_title( $rel_post ) ); ?>">
                        <div class="related-cover" style="background-image: url('<?php echo get_the_post_thumbnail_url( $rel_post, 'medium' ); ?>');">
                            <h3 class="wp-block-post-title has-small-font-size"><?php echo get_the_title( $rel_post ); ?></h3>
                        </div>
                    </a>
                </li>
            <?php }
            wp_reset_postdata();
            ?>
            </ul>
            <?php
        }
    }

    // Fallback per tags: s'activa si no hi ha segon project term O si el project term no ha retornat posts
    if ( ! $found_by_project ) :
        $tag_terms = get_the_terms( $current_post_id, 'post_tag' );

        if ( $tag_terms && ! is_wp_error( $tag_terms ) ) {
            $term_ids = wp_list_pluck( $tag_terms, 'term_id' );

            $tag_query = new WP_Query( array(
                'post_type'           => 'post',
                'tax_query'           => array( array(
                    'taxonomy'         => 'post_tag',
                    'field'            => 'id',
                    'terms'            => $term_ids,
                    'operator'         => 'IN',
                    'include_children' => false,
                ) ),
                'posts_per_page'      => -1,
                'ignore_sticky_posts' => 1,
                'orderby'             => 'date',
                'fields'              => 'ids',
                'no_found_rows'       => true,
                'post__not_in'        => array( $current_post_id ),
            ) );

            if ( $tag_query->have_posts() ) {
                $ids = $tag_query->posts;
                shuffle( $ids );
                $ids = array_slice( $ids, 0, 3 );
                ?>
                <ul class="related-articles by-tag" style="background: var(--wp--preset--color--accent-3);">
                <?php
                foreach ( $ids as $rel_id ) {
                    $rel_post = get_post( $rel_id );
                    if ( ! $rel_post ) { continue; }
                    setup_postdata( $rel_post ); ?>
                    <li>
                        <a href="<?php echo get_permalink( $rel_post ); ?>" title="<?php echo esc_attr( get_the_title( $rel_post ) ); ?>">
                            <div class="related-cover" style="background-image: url('<?php echo get_the_post_thumbnail_url( $rel_post, 'medium' ); ?>');">
                                <h3 class="wp-block-post-title has-small-font-size"><?php echo get_the_title( $rel_post ); ?></h3>
                            </div>
                        </a>
                    </li>
                <?php }
                wp_reset_postdata();
                ?>
                </ul>
                <?php
            }
        }
    endif;
    ?>
</div>