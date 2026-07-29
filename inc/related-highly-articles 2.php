<div class="highly-related">
    <h3 class="wp-block-heading has-medium-font-size highly-related">
        <span class="main-title"><?php echo get_the_title(5653); ?></span>
        <span class="secondary-title line-break"><?php echo get_secondary_title(5653); ?></span>
    </h3>

    <ul class="related-articles by-project">
        <?php
        $current_post_id = get_the_ID();
        $terms = get_the_terms( $current_post_id, 'project' );

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
                'posts_per_page'      => -1,    // tots els relacionats — shuffle complet sense biaix de data
                'ignore_sticky_posts' => 1,
                'orderby'             => 'date', // determinista — el random el fa PHP
                'fields'              => 'ids',
                'no_found_rows'       => true,
                'post__not_in'        => array( $current_post_id ),
            ) );

            if ( $second_query->have_posts() ) {
                $ids = $second_query->posts;
                shuffle( $ids );               // random a PHP, sense cost a MySQL
                $ids = array_slice( $ids, 0, 3 );

                foreach ( $ids as $rel_id ) {
                    $rel_post = get_post( $rel_id );
                    if ( ! $rel_post ) { continue; }
                    setup_postdata( $rel_post ); ?>
                    <li>
                        <a href="<?php echo get_permalink( $rel_post ); ?>" title="<?php echo esc_attr( get_the_title( $rel_post ) ); ?>">
                            <?php if ( has_post_thumbnail( $rel_post ) ) : ?>
                                <figure class="wp-block-post-featured-image">
                                    <?php echo get_the_post_thumbnail( $rel_post, 'medium', array( 'alt' => get_the_title( $rel_post ) ) ); ?>
                                </figure>
                            <?php endif; ?>
                            <h4 class="wp-block-post-title has-small-font-size"><?php echo get_the_title( $rel_post ); ?></h4>
                        </a>
                    </li>
                <?php }
                wp_reset_postdata();
            }
        }
        ?>
    </ul>

    <?php if ( ! $terms || empty( $terms[1] ) ) : ?>
        <ul class="related-articles by-tag">
            <?php
            $terms = get_the_terms( $current_post_id, 'post_tag' );

            if ( $terms && ! is_wp_error( $terms ) ) {
                $term_ids = wp_list_pluck( $terms, 'term_id' );

                $second_query = new WP_Query( array(
                    'post_type'           => 'post',
                    'tax_query'           => array( array(
                        'taxonomy'         => 'post_tag',
                        'field'            => 'id',
                        'terms'            => $term_ids,
                        'operator'         => 'IN',
                        'include_children' => false,
                    ) ),
                    'posts_per_page'      => -1,    // tots els relacionats — shuffle complet sense biaix de data
                    'ignore_sticky_posts' => 1,
                    'orderby'             => 'date', // determinista — el random el fa PHP
                    'fields'              => 'ids',
                    'no_found_rows'       => true,
                    'post__not_in'        => array( $current_post_id ),
                ) );

                if ( $second_query->have_posts() ) {
                    $ids = $second_query->posts;
                    shuffle( $ids );
                    $ids = array_slice( $ids, 0, 3 );

                    foreach ( $ids as $rel_id ) {
                        $rel_post = get_post( $rel_id );
                        if ( ! $rel_post ) { continue; }
                        setup_postdata( $rel_post ); ?>
                        <li>
                            <a href="<?php echo get_permalink( $rel_post ); ?>" title="<?php echo esc_attr( get_the_title( $rel_post ) ); ?>">
                                <?php if ( has_post_thumbnail( $rel_post ) ) : ?>
                                    <figure class="wp-block-post-featured-image">
                                        <?php echo get_the_post_thumbnail( $rel_post, 'medium', array( 'alt' => get_the_title( $rel_post ) ) ); ?>
                                    </figure>
                                <?php endif; ?>
                                <h4 class="wp-block-post-title has-small-font-size"><?php echo get_the_title( $rel_post ); ?></h4>
                            </a>
                        </li>
                    <?php }
                    wp_reset_postdata();
                }
            }
            ?>
        </ul>
    <?php endif; ?>
</div>