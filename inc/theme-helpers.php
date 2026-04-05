<?php
/**
 * Theme Helper Functions
 *
 * Reusable rendering functions for heroes, CTAs, and ACF options.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;


/**
 * Get an ACF options field with a fallback.
 *
 * @param string $key     ACF field name.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function tld_get_option($key, $default = '') {
  if (!function_exists('get_field')) {
    return $default;
  }
  $value = get_field($key, 'option');
  return $value ?: $default;
}


/**
 * Render the inner-page hero section.
 *
 * @param string $title    Page title (defaults to current post title).
 * @param string $subtitle Hero subtitle text.
 * @param string $eyebrow  Eyebrow text above the title.
 */
function tld_render_hero($title = '', $subtitle = '', $eyebrow = '') {
  if (!$title) {
    $title = get_the_title();
  }
  get_template_part('template-parts/hero-page', null, [
    'title'    => $title,
    'subtitle' => $subtitle,
    'eyebrow'  => $eyebrow,
  ]);
}


/**
 * Render the CTA band.
 *
 * Checks for page-level ACF overrides first, then falls back to site defaults.
 *
 * @param int|null $page_id Post ID to check for CTA overrides (null = current post).
 */
function tld_render_cta($page_id = null) {
  if (!$page_id) {
    $page_id = get_the_ID();
  }

  $heading = '';
  $text    = '';
  $btn_text = '';
  $btn_url  = '';

  // Try page-level overrides
  if (function_exists('get_field')) {
    $heading  = get_field('cta_heading', $page_id);
    $text     = get_field('cta_text', $page_id);
    $btn_text = get_field('cta_button_text', $page_id);
  }

  // Fall back to site defaults
  if (!$heading) {
    $heading = tld_get_option('default_cta_heading', 'Ready to Grow Your Digital Presence?');
  }
  if (!$text) {
    $text = tld_get_option('default_cta_text', "Book a free discovery call and let's talk about what better looks like for your organisation.");
  }
  if (!$btn_text) {
    $btn_text = tld_get_option('default_cta_button_text', 'Book a Discovery Call');
  }
  if (!$btn_url) {
    $btn_url = tld_get_option('default_cta_button_url', home_url('/contact/'));
  }

  get_template_part('template-parts/cta-band', null, [
    'heading'  => $heading,
    'text'     => $text,
    'btn_text' => $btn_text,
    'btn_url'  => $btn_url,
  ]);
}


/**
 * Get service pages for card grids.
 *
 * Returns child pages of the Services page, or specific page IDs.
 *
 * @param array|null $page_ids Specific page IDs, or null to get all service children.
 * @return WP_Post[]
 */
function tld_get_service_pages($page_ids = null) {
  if ($page_ids) {
    return get_posts([
      'post_type'      => 'page',
      'post__in'       => $page_ids,
      'orderby'        => 'post__in',
      'posts_per_page' => -1,
    ]);
  }

  // Find the Services parent page
  $services_page = get_page_by_path('services');
  if (!$services_page) {
    return [];
  }

  return get_posts([
    'post_type'      => 'page',
    'post_parent'    => $services_page->ID,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'posts_per_page' => -1,
  ]);
}


/**
 * Get a human-readable label for a resource type.
 *
 * @param string $type Resource type key.
 * @return string
 */
function tld_resource_type_label($type) {
  $labels = [
    'pdf'          => 'PDF',
    'spreadsheet'  => 'Spreadsheet',
    'presentation' => 'Slides',
    'document'     => 'Document',
    'template'     => 'Template',
    'toolkit'      => 'Toolkit',
    'checklist'    => 'Checklist',
    'guide'        => 'Guide',
  ];
  return $labels[$type] ?? ucfirst($type);
}


/**
 * Get an SVG icon for a resource file type.
 *
 * @param string $type Resource type key.
 * @return string SVG markup.
 */
function tld_resource_file_icon($type) {
  $icons = [
    'pdf'          => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/><path d="M4.603 14.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a6.1 6.1 0 0 1-.911-.95 11.7 11.7 0 0 0-1.997.406 11.3 11.3 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.8.8 0 0 1-.58.029z"/></svg>',
    'spreadsheet'  => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/><path d="M5.5 7a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-5a.5.5 0 0 0-.5-.5zM6 8h1.5v1.5H6zm0 2.5h1.5V12H6zm2.5-2.5H10v1.5H8.5zm0 2.5H10V12H8.5z"/></svg>',
    'presentation' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/><path d="M4.5 12.5A.5.5 0 0 1 5 12h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m0-2A.5.5 0 0 1 5 10h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m1.639-3.708 1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047l1.888.974V7.5a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V7l1.639.792z"/></svg>',
  ];
  // Default file icon for all other types
  $default = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/><path d="M4.5 12.5A.5.5 0 0 1 5 12h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m0-2A.5.5 0 0 1 5 10h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m0-2A.5.5 0 0 1 5 8h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m0-2A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5"/></svg>';
  return $icons[$type] ?? $default;
}
