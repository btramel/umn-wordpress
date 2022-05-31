<?php
// custom rest api route that outputs json data
// WP automatically converts php data structures into JSON
// section 15
add_action('rest_api_init', 'hogwartsRegisterSearch');

function hogwartsRegisterSearch() {
    // namespace, , associative array
    register_rest_route('hogwarts/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'hogwartsSearchResults'
    ));
}

function hogwartsSearchResults($data) {
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'subject', 'event', 'campus'),
        // sanitized for security
        's' => sanitize_text_field($data['term'])
    ));

    // use WP loop to populate our JSON with only the data we want
    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'subjects' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();
        if (get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'postType' => get_post_type(),
            'authorName' => get_the_author()
        ));
        }

        if (get_post_type() == 'professor') {
            array_push($results['professors'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
        ));
        }

        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if (has_excerpt()) {
                $description = get_the_excerpt();
                } else {
                $description = wp_trim_words(get_the_content(), 18);
                }

            array_push($results['events'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'month' => $eventDate->format('M'),
            'day' => $eventDate->format('d'),
            'description' => $description
        ));
        }

        if (get_post_type() == 'subject') {
            $relatedCampuses = get_field('related_campus');

            if ($relatedCampuses) {
                foreach($relatedCampuses as $campus) {
                    array_push($results['campuses'], array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }   

            array_push($results['subjects'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'id' => get_the_id()
        ));
        }

        if (get_post_type() == 'campus') {
            array_push($results['campuses'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
        ));
        }
    }

    // 1. Make new custom query
    // give us any professors where any of the related programs is <search term>
    if ($results['subjects']) {
        $subjectsMetaQuery = array('relation' => 'OR');

        foreach($results['subjects'] as $item) {
            array_push($subjectsMetaQuery, array(
                'key' => 'related_subjects',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"'
            ));
        }
        $subjectRelationshipQuery = new WP_Query(array(
            'post_type' => array('professor', 'event'),
            'meta_query' => $subjectsMetaQuery
            )
        );
                // loop through and push items that have this relationship
                while($subjectRelationshipQuery->have_posts()) {
                    $subjectRelationshipQuery->the_post();
    
                    if (get_post_type() == 'event') {
                        $eventDate = new DateTime(get_field('event_date'));
                        $description = null;
                        if (has_excerpt()) {
                            $description = get_the_excerpt();
                            } else {
                            $description = wp_trim_words(get_the_content(), 18);
                            }
            
                        array_push($results['events'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'month' => $eventDate->format('M'),
                        'day' => $eventDate->format('d'),
                        'description' => $description
                    ));
                    }

                    if (get_post_type() == 'professor') {
                        array_push($results['professors'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                    ));
                    }
                }
                // get rid of duplicate results
                $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
                $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
   
            }

    return $results;

}