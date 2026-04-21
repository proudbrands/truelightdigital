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
 * Enqueue Formation-specific JS on Formation pages only.
 */
add_action('wp_enqueue_scripts', 'tld_enqueue_formation_assets', 20);
function tld_enqueue_formation_assets() {
  $is_formation = is_singular('formation_piece')
    || is_tax('pillar')
    || is_tax('audience')
    || (is_page() && in_array(get_post_field('post_name'), ['formation', 'library'], true))
    || is_page_template(['page-templates/page-formation.php', 'page-templates/page-library.php']);

  if (!$is_formation) return;

  $path = get_stylesheet_directory() . '/assets/js/formation.js';
  if (file_exists($path)) {
    wp_enqueue_script(
      'tld-formation',
      get_stylesheet_directory_uri() . '/assets/js/formation.js',
      [],
      date('YmdHi', filemtime($path)),
      true
    );
  }
}


/**
 * Include theme files
 */
$tld_includes = [
  'inc/theme-helpers.php',
  'inc/acf-fields.php',
  'inc/acf-blocks.php',
  'inc/custom-post-types.php',
  'inc/formation/cpt-formation-piece.php',
  'inc/formation/taxonomies.php',
  'inc/formation/reading-time.php',
  'inc/formation/toc-builder.php',
  'inc/formation/markdown-converter.php',
  'inc/formation/seo-filters.php',
  'inc/formation/resource-pillar.php',
  'inc/formation/resource-kind.php',
  'inc/formation/audience.php',
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
  add_image_size('tld-resource-preview', 800, 600, true);

  // Parent already registers 'main-menu'. Add mobile + footer menus.
  register_nav_menus([
    'mobile-menu' => __('Mobile Menu', 'tld'),
    'footer'      => __('Footer Menu', 'tld'),
  ]);
}


/**
 * Skip to main content link (accessibility)
 */
add_action('wp_body_open', 'tld_skip_link', 1);
function tld_skip_link() {
  echo '<a href="#primary" class="tld-skip-link">Skip to main content</a>';
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

// Output the modal HTML in the footer.
// Modal body is a HubSpot meetings embed — no Gravity Forms dependency.
add_action('wp_footer', 'tld_discovery_modal', 5);
function tld_discovery_modal() {
  ?>
  <div class="modal fade" id="tld-discovery-modal" tabindex="-1" aria-labelledby="tld-discovery-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content tld-modal-content">
        <div class="modal-header tld-modal-header">
          <div>
            <h5 class="modal-title" id="tld-discovery-modal-label">Book a Discovery Call</h5>
            <p class="tld-modal-subtitle">Pick a time that works. We&rsquo;ll send a calendar invite.</p>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body tld-modal-body">
          <!-- Start of Meetings Embed Script -->
          <div class="meetings-iframe-container" data-src="https://meetings-eu1.hubspot.com/sbrannon?embed=true"></div>
          <script type="text/javascript" src="https://static.hsappstatic.net/MeetingsEmbed/ex/MeetingsEmbedCode.js"></script>
          <!-- End of Meetings Embed Script -->
        </div>
      </div>
    </div>
  </div>
  <?php
}

/**
 * Resolve the optional WebM hero video URL for the current hero context.
 *
 * Reads the `hero_video_url` ACF field from either:
 *   - a post/page (pass its ID) — covers home, formation landing, /services/,
 *     /services/<service>/, cornerstone formation_pieces
 *   - a taxonomy term (pass `"pillar_{$term_id}"`) — covers pillar archives
 *
 * Returns the URL string if set, or an empty string.
 */
function tld_get_hero_video_url($object_id = null) {
  if (!function_exists('get_field')) return '';
  $object_id = $object_id ?: get_the_ID();
  $url = get_field('hero_video_url', $object_id);
  return is_string($url) && $url !== '' ? $url : '';
}

/**
 * Allow WebM uploads via the WP Media Library (not permitted by default on
 * most hosts). Required so editors can upload converted hero videos.
 */
add_filter('upload_mimes', function ($mimes) {
  $mimes['webm'] = 'video/webm';
  return $mimes;
});
