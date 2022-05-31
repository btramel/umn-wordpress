<?php

get_header();
pageBanner(array(
  'title' => 'All Events',
  'subtitle' => 'See what we have been doing on these hallowed grounds',
));
?>


<div class="container container--narrow page-section">
<?php
    while(have_posts()) {
        the_post(); // gets data 
        get_template_part('template-parts/content-event');
       } 
       echo paginate_links(); ?>

       <p>Want to see what magic we've been up to? <a href="<?php echo site_url('/past-events') ?>">Check out our past events archive here.</a></p>

</div>

<?php

get_footer();

?>