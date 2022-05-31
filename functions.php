<?php

    require get_theme_file_path('/inc/like-route.php');
    require get_theme_file_path( '/inc/search-route.php' );
    
    function hogwarts_custom_rest() {
        register_rest_field('post', 'authorName', array(
            'get_callback' => function() {return get_the_author();}
        ));

        register_rest_field('note', 'userNoteCount', array(
            'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');}
        ));
    }

    // customizing the wp REST API
    add_action('rest_api_init', 'hogwarts_custom_rest');

    function pageBanner($args = NULL) { // these args create flexibility. NULL to make args optional.
        if (!$args['title']) {
            $args['title'] = get_the_title();
        }

        if (!$args['subtitle']) {
            $args['subtitle'] = get_field('page_banner_subtitle');
        }

        if (!$args['photo']) {
            if (get_field('page_banner_background_image') AND !is_archive() AND !is_home()) {
                $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
            } else {
                $args['photo'] = get_theme_file_uri('/images/northrop.jpg'); // fallback image
            }
        }
        ?>
         <div class="page-banner">
                <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>"></div>
                <div class="page-banner__content container container--narrow">
                    <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
                    <div class="page-banner__intro">
                    <p><?php echo $args['subtitle'] ?></p>
                    </div>
                </div>
            </div>
    <?php }

    function hogwarts_files() {
        // load stylesheets
        wp_enqueue_style('hogwarts_styles', get_theme_file_uri('/build/style-index.css'));
        wp_enqueue_style('hogwarts_extra_styles', get_theme_file_uri('/build/index.css'));
        // load Font Awesome icons
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        //load Google Fonts
        wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        // load JS, specify dependencies, version #, load at end of page (true)
        wp_enqueue_script('main_hogwarts_js', 
        get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);

        wp_localize_script('main_hogwarts_js', 'hogwartsData', array(
            'root_url' => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest') // creates secret unique number for our user session
        ));

    }

    // call functions
    add_action('wp_enqueue_scripts', 'hogwarts_files');

    // enable specific theme feature
    // register nav menu creates new menu display locations inside the wp dashboard
    function hogwarts_features() {
        register_nav_menu( 'headerMenuLocation', 'Header Menu Location' );
        register_nav_menu( 'footerLocationOne', 'Footer Location One' );
        register_nav_menu( 'footerLocationTwo', 'Footer Location Two' );
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails'); // enables featured images for blogs
        add_image_size( 'pageBanner', 1500, 350, true ); // enables page banner image sizing
        add_image_size( 'professorLandscape', 400, 260, true);
        add_image_size( 'professorPortrait', 480, 650, true );
    }
    
    add_action('after_setup_theme', 'hogwarts_features');

    function hogwarts_adjust_queries($query) {
        $today = date('Ymd'); // setting global-ish date variable

        // sort settings for Subjects (alphabetical)
        if (!is_admin() AND is_post_type_archive('subject') AND is_main_query()) {
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
            $query->set('posts_per_page', '-1');
        }
        // sort settings for Events (ascending, excluding past events)
        if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
            $query->set('meta-key', 'event_date'); // we look inside the object with -> ... set() is a method within the object
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'ASC');
            $query->set('meta_query', array(
                array(
                  'key' => 'event_date',
                  'compare' => '>=',
                  'value' => $today,
                  'type' => 'numeric'
                ), 
              ));

        }
    }

    add_action('pre_get_posts', 'hogwarts_adjust_queries');

    add_filter('acf/settings/remove_wp_meta_box', '__return_false');


    // Redirect subscriber accounts out of admin and onto homepage
    add_action('admin_init', 'redirectSubsToFrontend');
    
    function redirectSubsToFrontend() {
        $ourCurrentUser = wp_get_current_user();
        if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
            wp_redirect(site_url('/'));
            exit;
        }
    }
    // No admin bar for subscribers
    add_action('wp_loaded', 'noSubsAdminBar');

    function noSubsAdminBar() {
        $ourCurrentUser = wp_get_current_user();
        if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
            show_admin_bar(false);
        }
    }

    // Customize login screen
    add_filter('login_headerurl', 'ourHeaderUrl');

    function ourHeaderUrl() {
        return esc_url(site_url('/'));
    }

    // Load custom CSS to WP login screen
    add_action('login_enqueue_scripts', 'ourLoginCSS');

    function ourLoginCSS() {
        // load stylesheets
        wp_enqueue_style('hogwarts_styles', get_theme_file_uri('/build/style-index.css'));
        wp_enqueue_style('hogwarts_extra_styles', get_theme_file_uri('/build/index.css'));
        // load Font Awesome icons
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        //load Google Fonts
        wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    }

    // Change WP login title
    add_filter('login_headertitle', 'ourLoginTitle');

    function ourLoginTitle() {
        return get_bloginfo('name');
    }

    // Force Note posts to be private!
    // Sanitize user-generated content so no malicious scripts or html can be stored in our database
    add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2); // works with 2 parameters

    function makeNotePrivate($data, $postarr) {
        if ($data['post_type'] == 'note') {
            if (count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']) {
                die("You have reached your note limit.");
            }
            $data['post_content'] = sanitize_textarea_field($data['post_content']);
            $data['post_title'] = sanitize_text_field($data['post_title']);
        }
        if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
        $data['post_status'] = "private";
    }
        // return filtered data
        return $data;
    }

    add_filter('ai1wm_exclude_content_from_export', 'ignoreCertainFiles');

    function ignoreCertainFiles($exclude_filters) {
        $exclude_filters[] = 'themes/hogwarts-theme/node_modules';
        return $exclude_filters;
    }