**University WordPress Template**

Custom university Wordpress template styled to emulate the University of Minnesota, complete with subjects, professors, events, and blog posts. The code often refers to Hogwarts, which was the original inspiration for the university template.

**Languages**: PHP, Javascript, Sass, HTML

**Features**: Live search, custom REST API endpoints, CRUD note-taking, advanced custom fields, custom post types.

I took on this project as part of a Udemy course I used to learn PHP, but quickly added additional features and functionality. I used Local by Flywheel to create my WordPress developer environment. 

The project is responsive between mobile and desktop devices, leverages template pages to generate content easily from the WordPress admin dashboard, and intelligently connects the various posts and pages. Professors and subjects refer to one another and so on. See for yourself: 

**Responsiveness, Taxonomy, and Live Search:**

![umngithubgif](https://user-images.githubusercontent.com/66852498/173600947-d296ce48-4ceb-4e4e-b4c6-a361422ed639.gif)

**Custom Post Types**
There are custom post types enabled for events, professors, subjects, campuses -- as well as notes and likes, enabling some of the cooler features.

```

        // Note Post Type
        register_post_type( 'note', array(
            'capability_type' => 'note',
            'map_meta_cap' => true, // enforces 'note' permissions, above
            'show_in_rest' => true,
            'supports' => array('title', 'editor'), // 'custom-fields' would go here if not using the Advanced Custom Fields plugin.                 'thumbnail' enables featured images on Professor posts
            'rewrite' => array('slug' => 'notes'),
            'show_ui' => true, // shows in admin dashboard
            'public' => false, // we want notes to be private, not searchable
            'menu_icon' => 'dashicons-welcome-write-blog',
            'labels' => array(
                'name' => 'Notes',
                'add_new_item' => 'Add New Note',
                'edit_item' => 'Edit Note',
                'all_items' => 'All Notes',
                'singular_name' => 'Note'
            )
        ));

         // Like Post Type
         register_post_type( 'like', array(
             
            'supports' => array('title'), // 'custom-fields' would go here if not using the Advanced Custom Fields plugin. 'thumbnail'               enables featured images on Professor posts
            'show_ui' => true, // shows in admin dashboard
            'public' => false, // we want notes to be private, not searchable
            'menu_icon' => 'dashicons-heart',
            'labels' => array(
                'name' => 'Likes',
                'add_new_item' => 'Add New Like',
                'edit_item' => 'Edit Like',
                'all_items' => 'All Likes',
                'singular_name' => 'Like'
            )
        ));
```

**CRUD Note-Taking**
Notes can be created, edited, deleted. Feature is programmed so only logged-in users can create notes, and they can only post at most 5 notes.

![notesgif](https://user-images.githubusercontent.com/66852498/173607292-d139099e-824a-4ec4-840c-24fc71481832.gif)

**Custom REST API Endpoints**
Edited the WordPress REST API to return the author name so we can display it in search results, as well as a user note counter to verify they have permissions to create a new one.
```
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
```

Thanks for reading about my WordPress website! Please check out my other projects, and don't hesitate to drop me a line!
