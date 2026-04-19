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
 * Replace %pillar% placeholder in generated permalinks with the piece's actual pillar slug.
 */
add_filter('post_type_link', 'tld_formation_piece_permalink', 10, 2);

function tld_formation_piece_permalink($post_link, $post) {
  if ($post->post_type !== 'formation_piece') return $post_link;
  if (strpos($post_link, '%pillar%') === false) return $post_link;

  $terms = get_the_terms($post->ID, 'pillar');
  $slug  = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->slug : 'uncategorised-pillar';

  return str_replace('%pillar%', $slug, $post_link);
}
