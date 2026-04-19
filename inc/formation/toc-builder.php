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
if (!function_exists('sanitize_html_class')) {
  function sanitize_html_class($class, $fallback = '') {
    $sanitized = preg_replace('/[^A-Za-z0-9_-]/', '', $class);
    return $sanitized !== '' ? $sanitized : $fallback;
  }
}
if (!function_exists('wp_kses_hair')) {
  // Minimal test-env shim: parse id="..." and class="..." attributes.
  // Real WP wp_kses_hair does full attribute parsing with allowed-protocol checks.
  function wp_kses_hair($attr_str, $allowed_protocols = []) {
    $out = [];
    if (preg_match_all('#\b(id|class)\s*=\s*(["\'])(.*?)\2#i', $attr_str, $m, PREG_SET_ORDER)) {
      foreach ($m as $mm) {
        $out[strtolower($mm[1])] = ['value' => $mm[3]];
      }
    }
    return $out;
  }
}
if (!function_exists('wp_allowed_protocols')) {
  function wp_allowed_protocols() { return ['http', 'https', 'mailto']; }
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
    // Decode entities so a heading like "Tom &amp; Jerry" slugs as "tom-jerry", not "tom-amp-jerry"
    $text      = trim(wp_strip_all_tags($inner));
    if (function_exists('html_entity_decode')) {
      $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    // Whitelist only safe, meaningful attributes from the matched heading.
    // Anything else (onclick=, data-*, style=, etc.) is dropped — prevents
    // re-emission of author markup that might contain XSS vectors.
    $safe_attrs = '';
    $existing_class = '';
    if ($attrs_raw && function_exists('wp_kses_hair')) {
      $parsed = wp_kses_hair($attrs_raw, wp_allowed_protocols());
      foreach (['id', 'class'] as $k) {
        if (isset($parsed[$k])) {
          $val = $parsed[$k]['value'];
          if ($k === 'class') {
            $existing_class = $val;
          }
          $safe_attrs .= ' ' . $k . '="' . esc_attr($val) . '"';
        }
      }
    }

    // Extract existing id (already sanitized via wp_kses_hair path above if present)
    $id = null;
    if (preg_match('#\bid\s*=\s*(["\'])(.*?)\1#i', $attrs_raw, $mm)) {
      $id = sanitize_html_class($mm[2]) ?: null;
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
      // Rebuild opening tag with only whitelisted attrs + new id. If class already
      // existed and was captured, $safe_attrs has it; we just append id.
      $class_attr = $existing_class ? ' class="' . esc_attr($existing_class) . '"' : '';
      $new_open = '<h' . $level . $class_attr . ' id="' . esc_attr($id) . '">';
    } else {
      // Existing id was in the markup — rebuild from whitelisted attrs only.
      $new_open = '<h' . $level . $safe_attrs . '>';
    }

    $used_ids[] = $id;
    $headings[] = ['level' => $level, 'text' => $text, 'id' => $id];

    return $new_open . $inner . '</h' . $level . '>';
  }, $html);

  return ['html' => $new_html, 'headings' => $headings];
}
