<?php
/**
 * Custom Post Types & Taxonomies
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;


/**
 * Resource Library CPT
 *
 * Downloadable resources: communication plans, frameworks, templates, guides.
 */
add_action('init', 'tld_register_resource_cpt');
function tld_register_resource_cpt() {

  // ── Taxonomy: Resource Category ──
  register_taxonomy('resource_category', 'tld_resource', [
    'labels' => [
      'name'          => 'Resource Categories',
      'singular_name' => 'Resource Category',
      'add_new_item'  => 'Add New Category',
      'edit_item'     => 'Edit Category',
      'search_items'  => 'Search Categories',
      'all_items'     => 'All Categories',
    ],
    'public'            => true,
    'hierarchical'      => true,
    'show_in_rest'      => true,
    'show_admin_column' => true,
    'rewrite'           => ['slug' => 'resources/category', 'with_front' => false],
  ]);

  // ── CPT: Resource ──
  register_post_type('tld_resource', [
    'labels' => [
      'name'               => 'Resources',
      'singular_name'      => 'Resource',
      'add_new'            => 'Add Resource',
      'add_new_item'       => 'Add New Resource',
      'edit_item'          => 'Edit Resource',
      'view_item'          => 'View Resource',
      'search_items'       => 'Search Resources',
      'not_found'          => 'No resources found',
      'not_found_in_trash' => 'No resources found in trash',
    ],
    'public'        => true,
    'has_archive'   => false, // We use a page template instead
    'show_in_rest'  => true,
    'menu_icon'     => 'dashicons-media-document',
    'menu_position' => 25,
    'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
    'rewrite'       => ['slug' => 'resources', 'with_front' => false],
    'taxonomies'    => ['resource_category', 'pillar'],
  ]);
}


/**
 * Seed default resource categories on theme switch
 */
add_action('after_switch_theme', 'tld_seed_resource_categories');
function tld_seed_resource_categories() {
  $defaults = [
    'Communications'  => 'Templates and plans for church and ministry communications.',
    'Strategy'        => 'Frameworks for digital strategy, planning, and decision-making.',
    'Templates'       => 'Ready-to-use documents, spreadsheets, and design files.',
    'Guides'          => 'Step-by-step guides and how-to resources.',
    'Toolkits'        => 'Bundled resource packs for specific initiatives.',
  ];

  foreach ($defaults as $name => $description) {
    if (!term_exists($name, 'resource_category')) {
      wp_insert_term($name, 'resource_category', [
        'description' => $description,
      ]);
    }
  }
}
