<?php

/**
 * True Light Digital — Bootscore Child Theme
 *
 * @package TrueLightDigital
 * @version 1.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Enqueue scripts and styles
 */
add_action('wp_enqueue_scripts', 'tld_enqueue_styles');
function tld_enqueue_styles() {
  // Parent style
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

  // Compiled main.css (with cache-busting)
  $main_css = get_stylesheet_directory() . '/assets/css/main.css';
  if (file_exists($main_css)) {
    wp_enqueue_style('main', get_stylesheet_directory_uri() . '/assets/css/main.css', ['parent-style'], date('YmdHi', filemtime($main_css)));
  }

  // Self-hosted fonts: Inter, Playfair Display, Sen
  $fonts_css = get_stylesheet_directory() . '/assets/css/fonts.css';
  wp_enqueue_style('tld-fonts', get_stylesheet_directory_uri() . '/assets/css/fonts.css', [], date('YmdHi', filemtime($fonts_css)));

  // Custom JS (with cache-busting)
  $custom_js = get_stylesheet_directory() . '/assets/js/custom.js';
  if (file_exists($custom_js)) {
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', ['jquery'], date('YmdHi', filemtime($custom_js)), true);
  }
}


/**
 * Enqueue Google Fonts in editor
 */
add_action('enqueue_block_editor_assets', 'tld_editor_fonts');
function tld_editor_fonts() {
  $fonts_css = get_stylesheet_directory() . '/assets/css/fonts.css';
  wp_enqueue_style('tld-fonts', get_stylesheet_directory_uri() . '/assets/css/fonts.css', [], date('YmdHi', filemtime($fonts_css)));
}


/**
 * Include theme files
 */
$tld_includes = [
  'inc/theme-helpers.php',
  'inc/acf-fields.php',
  'inc/acf-blocks.php',
  'inc/custom-post-types.php',
];

foreach ($tld_includes as $file) {
  $filepath = get_stylesheet_directory() . '/' . $file;
  if (file_exists($filepath)) {
    require_once $filepath;
  }
}


/**
 * ACF Options Page
 */
if (function_exists('acf_add_options_page')) {
  acf_add_options_page([
    'page_title' => 'Site Settings',
    'menu_title' => 'Site Settings',
    'menu_slug'  => 'tld-site-settings',
    'capability' => 'manage_options',
    'icon_url'   => 'dashicons-admin-generic',
    'position'   => 59,
    'redirect'   => false,
  ]);
}


/**
 * Theme supports
 */
add_action('after_setup_theme', 'tld_theme_setup');
function tld_theme_setup() {
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');
  add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);

  // Image sizes
  add_image_size('tld-card', 600, 400, true);
  add_image_size('tld-hero', 1920, 800, true);
  add_image_size('tld-blog-thumb', 800, 450, true);

  // Parent already registers 'main-menu'. Add footer menu.
  register_nav_menus([
    'footer' => __('Footer Menu', 'tld'),
  ]);
}


/**
 * Override Bootscore header classes via filter
 */
add_filter('bootscore/class/header', function () {
  return 'sticky-top tld-header';
});


/**
 * Override Bootscore nav toggler icon (use Bootstrap icon instead of Font Awesome)
 */
add_filter('bootscore/icon/menu', function () {
  return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/></svg>';
});

add_filter('bootscore/icon/chevron-up', function () {
  return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z"/></svg>';
});


/**
 * Override nav toggler button classes
 */
add_filter('bootscore/class/header/button', function ($classes, $context) {
  if ($context === 'nav-toggler') {
    return 'btn btn-outline-light';
  }
  return $classes;
}, 10, 2);


/**
 * ACF save/load JSON for field groups
 */
add_filter('acf/settings/save_json', function () {
  return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
  $paths[] = get_stylesheet_directory() . '/acf-json';
  return $paths;
});


/**
 * Discovery Call Modal — Gravity Forms integration
 *
 * Enqueues GF form 18 scripts/styles on every page and outputs
 * a Bootstrap 5 modal in the footer containing the form.
 */
define('TLD_DISCOVERY_FORM_ID', 18);

// Force-enqueue GF form assets on every page
add_filter('gform_force_enqueue_scripts', '__return_true');
add_action('wp_enqueue_scripts', function () {
  if (class_exists('GFForms')) {
    gravity_form_enqueue_scripts(TLD_DISCOVERY_FORM_ID, true);
  }
});

// Output the modal HTML in the footer
add_action('wp_footer', 'tld_discovery_modal', 5);
function tld_discovery_modal() {
  if (!function_exists('gravity_form')) return;
  ?>
  <div class="modal fade" id="tld-discovery-modal" tabindex="-1" aria-labelledby="tld-discovery-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content tld-modal-content">
        <div class="modal-header tld-modal-header">
          <div>
            <h5 class="modal-title" id="tld-discovery-modal-label">Book a Discovery Call</h5>
            <p class="tld-modal-subtitle">Tell us a little about your project and we'll be in touch within one working day.</p>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body tld-modal-body">
          <?php gravity_form(TLD_DISCOVERY_FORM_ID, false, false, false, null, true); ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
