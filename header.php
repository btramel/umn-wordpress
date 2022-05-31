<!DOCTYPE html>
<html <?php language_attributes(); ?>
<head>
  <!-- responsive charset, given WP settings -->
  <meta charset="<?php bloginfo('charset') ?>">
  <!-- allows responsiveness -->
  <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- WordPress function that allows it manage header style and function -->
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>> <!-- creates dynamic classes for each page! -->
<header class="site-header">
      <div class="container">
        <h1 class="school-logo-text float-left">
          <a href="<?php echo site_url()?>">University of <strong>Minnesota</strong></a>
        </h1>
        <span class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
        <span class="js-menu-trigger site-header__menu-trigger"><i class="fa fa-bars" aria-hidden="true"></i></span>
        <div class="site-header__menu group">
          <nav class="main-navigation">
            <!-- hard coded menu below -->
            <ul>
              <li <?php if (is_page('about-us') or wp_get_post_parent_id(0) == 12) echo 'class="current-menu-item"' ?>><a href="<?php echo site_url('/about-us') ?>">About Us</a></li>

              <li <?php if (get_post_type() == 'event' OR is_page(' past-events')) echo 'class="current-menu-item"' ?>>
              <a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a></li>

              <!-- <li><a href="#">Campuses</a></li> -->

              <li <?php if (get_post_type() == 'subject') echo 'class="current-menu-item"' ?>>
              <a href="<?php echo get_post_type_archive_link('subject'); ?>">Majors</a></li>

              <li <?php if (get_post_type() == 'post') echo 'class="current-menu-item"' ?>><a href="<?php echo site_url('/blog') ?>">Blog</a></li>

            </ul> 
          </nav>
          <div class="site-header__util">
            <?php if (is_user_logged_in()) { ?>
              <a href="<?php echo esc_url(site_url('/my-notes')); ?>" class="btn btn--small btn--orange float-left push-right">My Notes</a>
              <a href="<?php echo wp_logout_url(); ?>" class="btn btn--with-photo btn--small btn--dark-orange float-left">
                <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
                <span class="btn__text">Log Out</span>
              </a>
            <?php } else { ?>
              <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
            
              <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
            <?php } ?>
            
            <span class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
          </div>
        </div>
      </div>
    </header>
