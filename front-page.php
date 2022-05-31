<?php

    get_header();

    ?>

<div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/fall_campus_0.jpg')?>)"></div>
      <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">We are driven</h1>
        <h2 class="headline headline--medium">Bold minds don&rsquo;t wait.</h2>
        <h3 class="headline headline--small">Discover the University of Minnesota Twin Cities.</h3>
        <a href="<?php echo get_post_type_archive_link('subject') ?>" class="btn btn--large btn--blue">Find Your Major</a>
      </div>
    </div>

    <div class="full-width-split group">
      <div class="full-width-split__one">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>

          <?php
            $today = date('Ymd');
            $homepageEvents = new WP_Query(array( // capitalized because it's a Class
              'posts_per_page' => 2, // returns all posts
              'post_type' => 'event',
              'meta_key' => 'event_date', // creates a metadata field for our event_date custom field
              'orderby' => 'meta_value_num', // controls display order type 
              'order' => 'ASC', // ascending
              'meta_query' => array(
                // allows fine-grained control of how we order things
                array(
                  'key' => 'event_date',
                  'compare' => '>=',
                  'value' => $today,
                  'type' => 'numeric'
                ), // return posts if event date greater than or equal to today
              )
            )); 

            while($homepageEvents->have_posts()) {
              $homepageEvents->the_post(); // gets data
              get_template_part('template-parts/content', 'event');
              }
            ?>
        

          <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a></p>
        </div>
      </div>
      <div class="full-width-split__two">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
          <?php
            $homepagePosts = new WP_Query(array(
              'posts_per_page' => 2,
            )); // initializes custom query. $homepagePosts is an object

            while ($homepagePosts->have_posts()) {
              $homepagePosts->the_post(); // gets data ready ?>
              <div class="event-summary">
                <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                  <span class="event-summary__month"><?php the_time('M'); ?></span>
                  <span class="event-summary__day"><?php the_time('d'); ?></span>
                </a>
                <div class="event-summary__content">
                  <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                  <p><?php if (has_excerpt()) {
                    echo get_the_excerpt();
                  } else {
                    echo wp_trim_words(get_the_content(), 18);
                  } ?>
                     <a href="<?php the_permalink(); ?>" class="nu gray"> Read more</a></p>
                </div>
              </div>
            <?php } wp_reset_postdata(); // resets state of global variables, etc. good practice
          ?>

          <p class="t-center no-margin"><a href="<?php echo site_url('/blog'); ?>" class="btn btn--yellow">View All Blog Posts</a></p>
        </div>
      </div>
    </div>

    <div class="hero-slider">
      <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
          <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('/images/bus.jpg')?>)">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">Public Transportation</h2>
                <p class="t-center">All UMN students enjoy unlimited free bus fare.</p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('/images/apples.jpg')?>)">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">An Apple a Day</h2>
                <p class="t-center">All UMN students enjoy quality health care.</p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('/images/bread.jpg')?>)">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">Never Hungry</h2>
                <p class="t-center">UMN offers dining plans for those in need.</p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>
        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
      </div>
    </div>

    <?php get_footer();
?>