<?php
/**
 * ACF Field Group Loader
 *
 * Loads all ACF field group definitions from inc/acf-fields/.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

if (!function_exists('acf_add_local_field_group')) {
  return;
}

$acf_field_files = [
  'site-settings',
  'service-page-fields',
  'about-page-fields',
  'audience-hub-fields',
  'blog-post-fields',
  'block-fields',
];

foreach ($acf_field_files as $file) {
  $filepath = get_stylesheet_directory() . '/inc/acf-fields/' . $file . '.php';
  if (file_exists($filepath)) {
    require_once $filepath;
  }
}
