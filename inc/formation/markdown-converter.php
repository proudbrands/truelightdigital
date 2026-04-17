<?php
defined('ABSPATH') || exit;

/**
 * Minimal markdown -> Gutenberg block markup converter.
 *
 * Handles: paragraphs, h2/h3, ordered + unordered lists, blockquotes,
 * horizontal rules, and inline **bold** / *italic* / [link](url).
 *
 * Intentionally NOT supported (manual post-seed polish):
 *   - images
 *   - tables
 *   - "What to do this week" callouts (promote to Callout block)
 *   - pull-worthy quotes (promote to core pullquote)
 *   - inline code (`...`) and fenced code blocks
 *
 * Pure function. Testable standalone.
 */
function tld_formation_md_to_blocks($markdown) {
  $md = str_replace(["\r\n", "\r"], "\n", $markdown);
  $chunks = preg_split('/\n{2,}/', trim($md));

  $blocks = [];
  foreach ($chunks as $chunk) {
    $chunk = trim($chunk);
    if ($chunk === '') continue;

    // Horizontal rule
    if (preg_match('/^-{3,}$|^\*{3,}$/', $chunk)) {
      $blocks[] = '<!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity"/><!-- /wp:separator -->';
      continue;
    }

    // Heading
    if (preg_match('/^(#{2,3})\s+(.+)$/', $chunk, $m)) {
      $level = strlen($m[1]);
      $text  = _tld_md_inline($m[2]);
      $blocks[] = '<!-- wp:heading {"level":' . $level . '} --><h' . $level . '>' . $text . '</h' . $level . '><!-- /wp:heading -->';
      continue;
    }

    // Unordered list
    if (preg_match('/^[-*]\s+/', $chunk)) {
      $lines = preg_split('/\n/', $chunk);
      $all_ul = array_reduce($lines, function ($c, $l) {
        return $c && preg_match('/^[-*]\s+/', trim($l));
      }, true);
      if ($all_ul) {
        $items = array_map(function ($l) {
          return '<li>' . _tld_md_inline(preg_replace('/^[-*]\s+/', '', trim($l))) . '</li>';
        }, $lines);
        $blocks[] = '<!-- wp:list --><ul>' . implode('', $items) . '</ul><!-- /wp:list -->';
        continue;
      }
    }

    // Ordered list
    if (preg_match('/^\d+\.\s+/', $chunk)) {
      $lines = preg_split('/\n/', $chunk);
      $all_ol = array_reduce($lines, function ($c, $l) {
        return $c && preg_match('/^\d+\.\s+/', trim($l));
      }, true);
      if ($all_ol) {
        $items = array_map(function ($l) {
          return '<li>' . _tld_md_inline(preg_replace('/^\d+\.\s+/', '', trim($l))) . '</li>';
        }, $lines);
        $blocks[] = '<!-- wp:list {"ordered":true} --><ol>' . implode('', $items) . '</ol><!-- /wp:list -->';
        continue;
      }
    }

    // Blockquote
    if (preg_match('/^>\s?/', $chunk)) {
      $inner = preg_replace('/^>\s?/m', '', $chunk);
      $inner = '<p>' . _tld_md_inline(trim($inner)) . '</p>';
      $blocks[] = '<!-- wp:quote --><blockquote class="wp-block-quote">' . $inner . '</blockquote><!-- /wp:quote -->';
      continue;
    }

    // Default: paragraph
    $blocks[] = '<!-- wp:paragraph --><p>' . _tld_md_inline($chunk) . '</p><!-- /wp:paragraph -->';
  }

  return implode("\n\n", $blocks);
}

/**
 * Inline formatting: links first (so bracket syntax isn't chewed by emphasis),
 * then bold, then italic.
 */
function _tld_md_inline($text) {
  // Links
  $text = preg_replace_callback('/\[([^\]]+)\]\(([^)]+)\)/', function ($m) {
    $url = function_exists('esc_url') ? esc_url($m[2]) : $m[2];
    return '<a href="' . $url . '">' . $m[1] . '</a>';
  }, $text);

  // Bold (two asterisks)
  $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);

  // Italic (single asterisk, not adjacent to another asterisk)
  $text = preg_replace('/(?<!\*)\*([^*\s][^*]*?)\*(?!\*)/', '<em>$1</em>', $text);

  return $text;
}

// Fallbacks for standalone test context
if (!function_exists('esc_url')) {
  function esc_url($u) { return filter_var($u, FILTER_SANITIZE_URL) ?: $u; }
}
