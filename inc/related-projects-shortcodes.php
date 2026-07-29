<?php 
// Define a function to append your PHP code to the content of single posts
?>

<h2 class="wp-block-heading has-large-font-size highly-related">
    <span class="main-title"><?php echo get_the_title(6288);?> </span>
    <span class="secondary-title"><?php echo get_secondary_title(6288); // Are you geek?? ?></span>
</h2>

<?php if (has_term('dame-du-cirque', 'project') 
|| has_term('polychromatic-void', 'project') 
|| has_term('326-mormurs', 'project')
|| has_term('how-my-head-works', 'project') 
|| has_term('resonant', 'project') 
): // START RELATED WORK ?> 

    <h3 class="wp-block-heading has-medium-font-size highly-related">
        <span class="main-title"><?php echo get_the_title(6090); //Interested WORK ?> </span>
    </h3>

    <?php if (has_term('dame-du-cirque', 'project')) : // DAME DU CIQUE ?> 
        <h4 class="line-break has-medium-font-size related-project work"><a href="<?php echo esc_url(get_permalink(23)); ?>"><span class="main-title"><?php echo get_the_title(23); ?></span></a></h4>
    <?php endif; ?>

    <?php if (has_term('polychromatic-void', 'project') || has_term('how-my-head-works', 'project')) : // Polychromatic Void ?>
        <h4 class="line-break has-medium-font-size no-ma related-project work"><a href="<?php echo esc_url(get_permalink(6763)); ?>"><span class="main-title"><?php echo get_the_title(6763); ?></span></a></h4>
    <?php endif; ?>

    <?php if (has_term('resonant', 'project')) : // Resonant ?>
        <h4 class="line-break has-medium-font-size related-project work"><a href="<?php echo esc_url(get_permalink(15836)); ?>"><span class="main-title"><?php echo get_the_title(15836); ?></span></a></h4>
    <?php endif; ?>

    <?php if (has_term('326-mormurs', 'project')) : // 326 MORMRURs ?>
        <h4 class="line-break has-medium-font-size related-project work"><a href="<?php echo esc_url(get_permalink(824)); ?>"><span class="main-title"><?php echo get_the_title(824); ?></span></a></h4>
    <?php endif; ?>

<?php endif; // END RELATED WORK ?>

<?php if (has_term('single', 'project') 
|| has_term('learn-the-basics', 'project')
|| has_term('sol', 'project')
|| has_term('nucli', 'project')
|| has_term('share-that-file', 'project')): // START RELATED WORKSHOPS ?>

<h3 class="wp-block-heading has-normal-font-size highly-related">
    <span class="main-title"><?php echo get_the_title(6546); // WORKSHOPS CALL TO ACTION ?> </span>
    <span class="secondary-title"><?php echo get_secondary_title(6546); ?></span>
</h3>

<?php if (has_term('nucli', 'project')) : // Nucli Schools ?>
    <h4 class="line-break has-medium-font-size related-project workshops"><a href="<?php echo esc_url(get_permalink(17504)); ?>"><span class="secondary-title"><?php echo get_the_title(17504); ?></span></a></h4>
<?php endif; ?>

<?php if (has_term('share-that-file', 'project')) : // Share That File ?>
    <h4 class="line-break has-medium-font-size related-project workshops"><a href="<?php echo esc_url(get_permalink(8066)); ?>"><span class="secondary-title"><?php echo get_the_title(8066); ?></span></a></h4>
<?php endif; ?>

<?php if (has_term('learn-the-basics', 'project')) : // LEARN THE BASICS ?>
    <h4 class="line-break has-medium-font-size related-project workshops"><a href="<?php echo esc_url(get_permalink(17504)); ?>"><span class="secondary-title"><?php echo get_the_title(17504); ?></span></a></h4>
<?php endif; ?>

<?php if (has_term('single', 'project')) : // Single ?>
    <h4 class="line-break has-medium-font-size related-project workshops"><a href="<?php echo esc_url(get_permalink(10631)); ?>"><span class="secondary-title"><?php echo get_the_title(10631); ?></span></a></h4>
<?php endif; ?>

<?php if (has_term('sol', 'project')) : // Sòl ?>
    <h4 class="line-break has-medium-font-size related-project workshops"><a href="<?php echo esc_url(get_permalink(246)); ?>"><span class="secondary-title"><?php echo get_the_title(246); ?></span></a></h4>
<?php endif; ?>

<?php endif; // END RELATED WORKSHOPS ?>

<?php if (
    has_term('juggling-research', 'project') 
 || has_term('diabolo-siteswap', 'project') 
 || has_term('en-residencia', 'project') 
 || has_term('how-my-head-works', 'project') 
 || has_term('layer-cards', 'project') 
 || has_term('assaig-i-error','project')): // START RELATED LABS ?>

<div>  
    <h3 class="wp-block-heading has-normal-font-size highly-related">
        <span class="main-title"><?php echo get_the_title(8625); // LABS CALL TO ACTION ?> </span>
    </h3>

    <?php if (has_term('juggling-research', 'project') || has_term('how-my-head-works', 'project')) : // LAYER CARDS ?>
        <h4 class="line-break has-medium-font-size no-mar related-project labs"><a href="<?php echo esc_url(get_permalink(120)); ?>"><span class="main-title"><?php echo get_the_title(120); ?></span></a></h4>
    <?php endif; ?>

    <?php if (has_term('diabolo-siteswap', 'project')) : // DIABOLO SITESWAP ?>
        <h4 class="line-break has-medium-font-size no-mar related-project labs"><a href="<?php echo esc_url(get_permalink(36)); ?>"><span class="main-title"><?php echo get_the_title(36); ?></span></a></h4>
    <?php endif; ?>

    <?php if (has_term('layer-cards', 'project')) : // LAYER CARDS ?>
        <h4 class="line-break has-medium-font-size no-mar related-project labs"><a href="<?php echo esc_url(get_permalink(2044)); ?>"><span class="main-title"><?php echo get_the_title(2044); ?></span></a></h4>
    <?php endif; ?>

    <?php if (has_term('assaig-i-error', 'project')) : // ASSAIG I ERROR ?>
        <h4 class="line-break has-medium-font-size no-mar related-project labs"><a href="<?php echo esc_url(get_permalink(568)); ?>"><span class="main-title"><?php echo get_the_title(568); ?></span></a></h4>
    <?php endif; ?>

    <?php if (has_term('en-residencia', 'project')) : // En Residencia ?>
        <h4 class="line-break has-medium-font-size no-mar related-project labs"><a href="<?php echo esc_url(get_permalink(3078)); ?>"><span class="main-title"><?php echo get_the_title(3078); ?></span></a></h4>
    <?php endif; ?>
</div>
<?php endif; // END RELATED LABS ?>