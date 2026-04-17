<?php
defined('ABSPATH') || exit;

/**
 * Formation Piece CPT.
 *
 * Permalink structure: /formation/%pillar%/%postname%/
 * The %pillar% rewrite tag is resolved per-post by post_type_link filter below.
 */

add_action('init', 'tld_formation_register_cpt', 10);

function tld_formation_register_cpt() {

  register_post_type('formation_piece', [
    'labels' => [
      'name'               => 'Formation',
      'singular_name'      => 'Piece',
      'menu_name'          => 'Formation',
      'add_new'            => 'Add Piece',
      'add_new_item'       => 'Add New Piece',
      'edit_item'          => 'Edit Piece',
      'new_item'           => 'New Piece',
      'view_item'          => 'View Piece',
      'search_items'       => 'Search Pieces',
      'not_found'          => 'No pieces found',
      'not_found_in_trash' => 'No pieces in trash',
    ],
    'public'              => true,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_rest'        => true,
    'show_in_menu'        => true,
    'has_archive'         => false,
    'menu_icon'           => 'dashicons-book-alt',
    'menu_position'       => 23,
    'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    'taxonomies'          => ['pillar', 'piece_type'],
    'rewrite'             => [
      'slug'       => 'formation/%pillar%',
      'with_front' => false,
    ],
  ]);

  add_rewrite_tag('%pillar%', '([^/]+)', 'pillar=');
}

/**
 * Build the complete permalink for formation_piece posts since WordPress can't handle %pillar% natively.
 */
add_filter('post_type_link', 'tld_formation_piece_permalink', 10, 2);

function tld_formation_piece_permalink($post_link, $post) {
  // Only process formation_piece posts
  if ($post->post_type !== 'formation_piece') {
    return $post_link;
  }

  // Get the pillar term
  $terms = get_the_terms($post->ID, 'pillar');
  if (is_wp_error($terms) || empty($terms)) {
    $pillar_slug = 'uncategorised-pillar';
  } else {
    $pillar_slug = $terms[0]->slug;
  }

  // Build the permalink manually
  $post_name = ( '' === $post->post_name ) ? sanitize_title( $post->post_title, $post->ID ) : $post->post_name;
  $url = home_url( "formation/$pillar_slug/$post_name/" );

  return $url;
}
