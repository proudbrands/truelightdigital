<?php
defined('ABSPATH') || exit;

/**
 * Formation taxonomies.
 *
 * `pillar` is public and drives the URL: /formation/<pillar-slug>/
 * `piece_type` is internal; only affects template variant.
 */

add_action('init', 'tld_formation_register_taxonomies', 5);

function tld_formation_register_taxonomies() {

  register_taxonomy('pillar', ['formation_piece', 'tld_resource'], [
    'labels' => [
      'name'          => 'Pillars',
      'singular_name' => 'Pillar',
      'menu_name'     => 'Pillars',
      'all_items'     => 'All Pillars',
      'edit_item'     => 'Edit Pillar',
      'add_new_item'  => 'Add New Pillar',
      'search_items'  => 'Search Pillars',
    ],
    'hierarchical'       => false,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_rest'       => true,
    'show_admin_column'  => true,
    'show_tagcloud'      => false,
    'rewrite'            => [
      'slug'         => 'formation',
      'with_front'   => false,
      'hierarchical' => false,
    ],
    'meta_box_cb'        => false, // hidden: ACF radio replaces it
  ]);

  register_taxonomy('piece_type', ['formation_piece'], [
    'labels' => [
      'name'          => 'Piece Types',
      'singular_name' => 'Piece Type',
      'menu_name'     => 'Piece Types',
    ],
    'hierarchical'       => false,
    'public'             => false,
    'publicly_queryable' => false,
    'show_ui'            => true,
    'show_in_rest'       => true,
    'show_admin_column'  => true,
    'rewrite'            => false,
    'meta_box_cb'        => false, // hidden: ACF radio replaces it
  ]);
}

/**
 * Seed pillar and piece_type terms on theme activation.
 * Actual content (name, tagline, sort order) populated by the seed mu-plugin.
 */
add_action('after_switch_theme', 'tld_formation_seed_default_terms');

function tld_formation_seed_default_terms() {
  $pillar_slugs = [
    'communications-champion'     => 'The Communications Champion',
    'rhythm-and-restraint'        => 'Rhythm & Restraint',
    'invitation-and-patience'     => 'Invitation & Patience',
    'guardrails-and-discernment'  => 'Guardrails & Discernment',
  ];

  foreach ($pillar_slugs as $slug => $name) {
    if (!term_exists($slug, 'pillar')) {
      wp_insert_term($name, 'pillar', ['slug' => $slug]);
    }
  }

  $piece_types = ['cornerstone', 'short-read', 'field-note'];
  foreach ($piece_types as $slug) {
    if (!term_exists($slug, 'piece_type')) {
      wp_insert_term(ucwords(str_replace('-', ' ', $slug)), 'piece_type', ['slug' => $slug]);
    }
  }
}
