<?php
/**
 * Run: php tests/test-reading-time.php
 */

define('ABSPATH', dirname(__DIR__) . '/');

// Mock WP functions for testing
if (!function_exists('wp_is_post_revision')) {
  function wp_is_post_revision($post) { return false; }
  function wp_is_post_autosave($post) { return false; }
  function add_action($hook, $callback) {}
}

require __DIR__ . '/../inc/formation/reading-time.php';

$failures = 0;

function assertEqual($actual, $expected, $label) {
  global $failures;
  if ($actual !== $expected) {
    echo "FAIL: $label — expected $expected, got $actual\n";
    $failures++;
    return;
  }
  echo "PASS: $label\n";
}

assertEqual(tld_formation_calc_reading_time(''), 1, 'empty content → 1 min');
assertEqual(tld_formation_calc_reading_time('one two three'), 1, '3 words → 1 min');
assertEqual(tld_formation_calc_reading_time(str_repeat('word ', 225)), 1, '225 words → 1 min');
assertEqual(tld_formation_calc_reading_time(str_repeat('word ', 226)), 2, '226 words → 2 min');
assertEqual(tld_formation_calc_reading_time(str_repeat('word ', 450)), 2, '450 words → 2 min');
assertEqual(tld_formation_calc_reading_time(str_repeat('word ', 4000)), 18, '4000 words → 18 min');
assertEqual(tld_formation_calc_reading_time('<p>Hello <strong>world</strong></p>'), 1, 'html stripped');

echo "\n";
if ($failures === 0) { echo "All tests passed.\n"; exit(0); }
echo "$failures failed.\n"; exit(1);
