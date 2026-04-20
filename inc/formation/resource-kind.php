<?php
defined('ABSPATH') || exit;

/**
 * `resource_kind` taxonomy — classifies tld_resource into template / worksheet / reflection-guide.
 *
 * Drives the type badge on Resource Cards and enables filtering by kind
 * on the Resources library page and on pillar archives.
 */

add_action('init', 'tld_formation_register_resource_kind', 5);

function tld_formation_register_resource_kind() {
  register_taxonomy('resource_kind', ['tld_resource'], [
    'labels' => [
      'name'          => 'Resource Kinds',
      'singular_name' => 'Resource Kind',
      'menu_name'     => 'Kinds',
      'all_items'     => 'All Kinds',
      'edit_item'     => 'Edit Kind',
      'add_new_item'  => 'Add New Kind',
      'search_items'  => 'Search Kinds',
    ],
    'hierarchical'       => false,
    'public'             => false,
    'publicly_queryable' => false,  // Phase 3: filter-only, no public archive
    'show_ui'            => true,
    'show_in_rest'       => true,
    'show_admin_column'  => true,
    'show_tagcloud'      => false,
    'rewrite'            => false, // archive URLs not needed; filter via query strings
    'meta_box_cb'        => false, // enforced by ACF select field
  ]);
}

/**
 * Seed resource_kind terms on theme activation.
 */
add_action('after_switch_theme', 'tld_formation_seed_resource_kinds');

function tld_formation_seed_resource_kinds() {
  $kinds = [
    'template'         => 'Template',
    'worksheet'        => 'Worksheet',
    'reflection-guide' => 'Reflection Guide',
  ];
  foreach ($kinds as $slug => $name) {
    if (!term_exists($slug, 'resource_kind')) {
      wp_insert_term($name, 'resource_kind', ['slug' => $slug]);
    }
  }
}
