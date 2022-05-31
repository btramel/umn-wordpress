<?php

get_header();
pageBanner(array(
  'title' => 'Majors and Programs',
  'subtitle' => 'Find your niche!'
));

?>

<div class="container container--narrow page-section">

<ul class="min-list link-list">

<?php
    while(have_posts()) {
        the_post(); // gets data ?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

       <?php } 
       echo paginate_links();
       ?>

       </ul>       

</div>

<?php

get_footer();

?>