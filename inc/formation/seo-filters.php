<?php
defined('ABSPATH') || exit;

/**
 * The SEO Framework description overrides for Formation.
 *
 * Single formation_piece -> ACF `summary` field
 * Pillar archive        -> ACF `pillar_meta_description` term field
 */
add_filter('the_seo_framework_generated_description', 'tld_formation_seo_description', 20, 3);

function tld_formation_seo_description($description, $args = null, $type = '') {
  if (is_singular('formation_piece')) {
    $summary = get_field('summary', get_the_ID());
    if ($summary) return wp_strip_all_tags($summary);
  }

  if (is_tax('pillar')) {
    $term_id = get_queried_object_id();
    if ($term_id) {
      $meta = get_field('pillar_meta_description', 'pillar_' . $term_id);
      if ($meta) return wp_strip_all_tags($meta);
    }
  }

  return $description;
}

/**
 * Title adjustment: pillar archive -> "<Pillar Name> - Formation"
 */
add_filter('the_seo_framework_title_from_custom_field', 'tld_formation_seo_title', 20, 2);

function tld_formation_seo_title($title, $args = null) {
  if (is_tax('pillar')) {
    $term = get_queried_object();
    if ($term && !$title) {
      return $term->name . ' — Formation';
    }
  }
  return $title;
}
