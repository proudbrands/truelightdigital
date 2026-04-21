<?php
defined('ABSPATH') || exit;

/**
 * `audience` taxonomy — classifies pieces + resources by intended reader.
 *
 * Attached to formation_piece AND tld_resource. Used to build reader-pathway
 * archives at /formation/for/<audience-slug>/ that cut across pillars,
 * serving a visitor who identifies by role ("I'm a new curator",
 * "I'm the parish secretary", "I'm at the diocese").
 *
 * Multi-assignable per post — a piece written for both priests and PPC
 * chairs would be tagged with both terms. The native multi-select UI
 * is kept visible in the editor because authors need the flexibility.
 */

add_action('init', 'tld_formation_register_audience', 5);

function tld_formation_register_audience() {
  register_taxonomy('audience', ['formation_piece', 'tld_resource'], [
    'labels' => [
      'name'              => 'Audiences',
      'singular_name'     => 'Audience',
      'menu_name'         => 'Audiences',
      'all_items'         => 'All Audiences',
      'edit_item'         => 'Edit Audience',
      'add_new_item'      => 'Add New Audience',
      'search_items'      => 'Search Audiences',
      'separate_items_with_commas' => 'Separate audiences with commas',
    ],
    'hierarchical'       => false,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_rest'       => true,
    'show_admin_column'  => true,
    'show_tagcloud'      => false,
    'rewrite'            => [
      'slug'       => 'formation/for',
      'with_front' => false,
    ],
  ]);
}

/**
 * Seed canonical audience terms. Keep names pastoral / human ("The Priest",
 * not "Priest — Pastor / Canon Law") so they read well as archive headings
 * and as "I'm the…" entry points.
 *
 * Keyed on slug; value is display name.
 */
add_action('after_switch_theme', 'tld_formation_seed_audience_terms');

function tld_formation_seed_audience_terms() {
  // Canonical audience taxonomy — consolidated 2026-04-21. Previous terms
  // new-curator / ppc-chair / ppc-member / all were merged into curator,
  // ppc, and (for 'all') removed entirely. See the 2026-04-21 migration in
  // workfolder/ for the one-off re-tagging on prod.
  $terms = [
    'priest'            => 'The Priest',
    'parish-secretary'  => 'The Parish Secretary',
    'curator'           => 'The Curator',
    'volunteer'         => 'The Volunteer',
    'ppc'               => 'The PPC',
    'diocesan-staff'    => 'Diocesan Staff',
    'agency'            => 'Agencies & Consultants',
  ];
  foreach ($terms as $slug => $name) {
    if (!term_exists($slug, 'audience')) {
      wp_insert_term($name, 'audience', ['slug' => $slug]);
    }
  }
}
