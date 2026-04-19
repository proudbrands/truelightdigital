<?php
defined('ABSPATH') || exit;

/**
 * Calculate reading time in minutes from raw post content (HTML OK).
 * Assumes 225 words per minute. Minimum: 1.
 *
 * Pure function — no WP deps — unit-testable.
 */
function tld_formation_calc_reading_time($content) {
  $text = function_exists('wp_strip_all_tags') ? wp_strip_all_tags($content) : strip_tags($content);
  $wc   = str_word_count($text);
  return max(1, (int) ceil($wc / 225));
}

/**
 * Auto-populate reading_time_minutes ACF field on formation_piece save
 * if the author hasn't set a value manually.
 */
add_action('save_post_formation_piece', 'tld_formation_save_reading_time', 20, 3);

function tld_formation_save_reading_time($post_id, $post, $update) {
  if (wp_is_post_revision($post_id)) return;
  if (wp_is_post_autosave($post_id)) return;
  if (!function_exists('get_field') || !function_exists('update_field')) return;

  $current = get_field('reading_time_minutes', $post_id);
  if ($current) return; // author override

  $minutes = tld_formation_calc_reading_time($post->post_content);
  update_field('reading_time_minutes', $minutes, $post_id);
}
