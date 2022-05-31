<?php

// custom REST configuration for likes
// imported to functions.php file

add_action('rest_api_init', 'universityLikeRoutes');

// for POST request
function universityLikeRoutes() {
    register_rest_route('hogwarts/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));
    
    // for DELETE request
    register_rest_route('hogwarts/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}

function createLike($data) {
    if (is_user_logged_in()) {
        $professor = sanitize_text_field($data['professorId']);

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $professor
                )
            )
        ));
        // id needs to belong to a professor
        // cannot have liked a professor before
        if ($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor') {
            // creates post (or like) programmatically
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => 'Second Test',
                'meta_input' => array(
                    'liked_professor_id' => $professor
                )
            ));
        } else {
            die("Invalid professor ID");
        }

    } else {
        die("Only logged in users can like a professor.");
    }
}

function deleteLike($data) {
    $likeId = sanitize_text_field($data['like']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
    wp_delete_post($likeId, true);
    return "Congrats. Like deleted.";
    } else {
        die("You do not have permission to delete that.");
    }
}