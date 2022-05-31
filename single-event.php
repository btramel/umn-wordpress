<?php
    
    get_header();
    pageBanner(array(
    'title' => get_the_title(),
    ));
    
    while(have_posts()){
        the_post(); ?>
            
    <div class="container container--narrow page-section">
        <!-- breadcrumb box -->
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i>Events Home</a> <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>

        <div class="generic-content"><?php the_content(); ?></div>

        <?php

            $relatedSubjects = get_field('related_subjects'); // stores the custom field data inside an array
            if ($relatedSubjects) { // if there are related subjects assigned, display them
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Related Programs</h2>';
                echo '<ul class="link-list min-list">';
                foreach($relatedSubjects as $subject) { ?>
                    <li><a href="<?php echo get_the_permalink($subject); ?>"><?php echo get_the_title($subject);?></a></li>
                <?php }} ?>
    </div>

    <?php }
    
    get_footer();

?>