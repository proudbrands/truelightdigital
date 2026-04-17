<?php
defined('ABSPATH') || exit;

// --- Test-environment fallbacks (only defined when WP functions don't exist) ---
if (!function_exists('wp_strip_all_tags')) {
  function wp_strip_all_tags($text) { return trim(strip_tags($text)); }
}
if (!function_exists('sanitize_title')) {
  function sanitize_title($text) {
    $s = strtolower(trim($text));
    $s = preg_replace('/[^a-z0-9\s-]/', '', $s);
    $s = preg_replace('/[\s-]+/', '-', $s);
    return trim($s, '-');
  }
}
if (!function_exists('esc_attr')) {
  function esc_attr($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
}

/**
 * Build a Table of Contents from HTML content.
 *
 * Parses h2 and h3 elements, injects id="..." attributes where missing,
 * and returns both the modified HTML and an ordered list of headings.
 *
 * @param string $html Rendered post content.
 * @return array ['html' => string, 'headings' => [['level'=>int, 'text'=>string, 'id'=>string], ...]]
 */
function tld_formation_build_toc($html) {
  if (!$html) return ['html' => '', 'headings' => []];

  $headings = [];
  $used_ids = [];

  $pattern = '#<h([23])(\s[^>]*)?>(.*?)</h\1>#is';

  $new_html = preg_replace_callback($pattern, function ($m) use (&$headings, &$used_ids) {
    $level     = (int) $m[1];
    $attrs_raw = $m[2] ?? '';
    $inner     = $m[3];
    $text      = trim(wp_strip_all_tags($inner));

    // Extract existing id if present
    $id = null;
    if (preg_match('#\bid\s*=\s*(["\'])(.*?)\1#i', $attrs_raw, $mm)) {
      $id = $mm[2];
    }

    if (!$id) {
      $base = sanitize_title($text);
      if ($base === '') $base = 'section';
      $candidate = $base;
      $n = 2;
      while (in_array($candidate, $used_ids, true)) {
        $candidate = $base . '-' . $n;
        $n++;
      }
      $id = $candidate;
      // Build new opening tag with id appended
      $new_open = '<h' . $level . ($attrs_raw ? $attrs_raw : '') . ' id="' . esc_attr($id) . '">';
    } else {
      $new_open = '<h' . $level . $attrs_raw . '>';
    }

    $used_ids[] = $id;
    $headings[] = ['level' => $level, 'text' => $text, 'id' => $id];

    return $new_open . $inner . '</h' . $level . '>';
  }, $html);

  return ['html' => $new_html, 'headings' => $headings];
}
