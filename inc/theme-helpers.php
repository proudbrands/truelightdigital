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
