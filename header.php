<?php
/**
 * True Light Digital — Custom Header
 *
 * Dark navy header with logo, primary nav, and gold CTA button.
 * Overrides the parent theme's header.php.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?= esc_attr(get_bloginfo('charset')); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="page" class="site">

  <!-- Skip Links -->
  <a class="skip-link visually-hidden-focusable" href="#primary"><?php esc_html_e('Skip to content', 'tld'); ?></a>
  <a class="skip-link visually-hidden-focusable" href="#footer"><?php esc_html_e('Skip to footer', 'tld'); ?></a>

  <?php do_action('bootscore_before_masthead'); ?>

  <header id="masthead" class="sticky-top tld-header site-header">

    <nav id="nav-main" class="navbar navbar-expand-lg">
      <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>">
          <img src="<?= esc_url(get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg'); ?>"
               alt="<?= esc_attr(get_bloginfo('name')); ?>"
               height="40"
               width="auto">
        </a>

        <!-- Offcanvas Navbar (mobile) -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-navbar">
          <div class="offcanvas-header">
            <span class="h5 offcanvas-title"><?php esc_html_e('Menu', 'tld'); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e('Close', 'tld'); ?>"></button>
          </div>
          <div class="offcanvas-body">

            <?php
            wp_nav_menu([
              'theme_location' => 'main-menu',
              'container'      => false,
              'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0',
              'fallback_cb'    => '__return_false',
              'items_wrap'     => '<ul id="bootscore-navbar" class="navbar-nav ms-auto %2$s">%3$s</ul>',
              'depth'          => 2,
              'walker'         => new bootstrap_5_wp_nav_menu_walker(),
            ]);
            ?>

          </div>
        </div>

        <!-- Header Actions -->
        <div class="header-actions d-flex align-items-center">

          <!-- Gold CTA Button (desktop) -->
          <a href="#" class="btn tld-btn-gold d-none d-lg-inline-flex ms-3" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
            Book a Call
          </a>

          <!-- Mobile Toggler -->
          <button class="btn btn-outline-light d-lg-none ms-2 nav-toggler"
                  type="button"
                  data-bs-toggle="offcanvas"
                  data-bs-target="#offcanvas-navbar"
                  aria-controls="offcanvas-navbar"
                  aria-label="<?php esc_attr_e('Toggle navigation', 'tld'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
            </svg>
          </button>

        </div><!-- .header-actions -->

      </div><!-- .container -->
    </nav><!-- .navbar -->

    <?php do_action('bootscore_before_masthead_close'); ?>

  </header><!-- #masthead -->

  <?php do_action('bootscore_after_masthead'); ?>
