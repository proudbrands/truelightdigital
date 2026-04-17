<?php
defined('ABSPATH') || exit;

/**
 * Ensures the pillar taxonomy column appears in the Resources list screen.
 * Registration itself is handled by register_taxonomy() in taxonomies.php
 * (object_type includes both formation_piece and tld_resource).
 */

add_filter('manage_edit-tld_resource_columns', function ($columns) {
  // Already handled by show_admin_column => true on register_taxonomy.
  // Seam for future reordering if needed.
  return $columns;
});
