<!-- archive pages for categories, authors, etc -->
<?php

get_header();
get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'See what we have been doing on these hallowed grounds'
));

?>

<div class="container container--narrow page-section">
<?php

$today = date('Ymd');
$pastEvents = new WP_Query(array( // capitalized because it's a Class
  'post_type' => 'event',
  'paged' => get_query_var('paged', 1),
  'meta_key' => 'event_date', // creates a metadata field for our event_date custom field
  'orderby' => 'meta_value_num', // controls display order type 
  'order' => 'DESC', // ascending
  'meta_query' => array(
    // allows fine-grained control of how we order things
    array(
      'key' => 'event_date',
      'compare' => '<=',
      'value' => $today,
      'type' => 'numeric'
    ), // return posts if event date greater than or equal to today
  )
)); 

    while($pastEvents->have_posts()) {
        $pastEvents->the_post(); // gets data 
        get_template_part('template-parts/content-event');
      } 
       echo paginate_links(array(
           'total' => $pastEvents->max_num_pages
       ));
       ?>

</div>

<?php

get_footer();

?>