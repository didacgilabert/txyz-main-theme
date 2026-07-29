
<?php // filters blog ?>
<div class="taxonomy-project wp-block-post-terms has-small-font-size">
    <div class="category-list wp-block-post-terms has-small-font-size">
    <?php echo strip_tags(get_the_term_list( $post->ID, 'project', '', ' »  ', '' )); ?>
    </div>

    <div class="filter category normal-link">
    <?php echo strip_tags( get_the_term_list( get_the_ID(), 'category', '', '/ ') );?>
    </div>

    <div class="filter tags normal-link">
    <?php echo strip_tags(get_the_tag_list('#', ' #', '', get_the_ID())); ?>
    </div>
    <div class="filter loaction normal-link">
    <?php
        $terms = get_the_terms( $post->ID, 'location' );
        echo get_the_term_list( $post->ID, 'location', '▽ ', ', ', '' );
        ?>
    </div>
</div>