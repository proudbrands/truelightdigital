<?php
/**
 * Unit tests for tld_formation_build_toc().
 * Run: php tests/test-toc-builder.php
 * Exit code: 0 on all pass, 1 on any fail.
 */

define('ABSPATH', dirname(dirname(__FILE__)) . '/');
require __DIR__ . '/../inc/formation/toc-builder.php';

$failures = 0;

function assertEqual($actual, $expected, $label) {
  global $failures;
  if ($actual !== $expected) {
    echo "FAIL: $label\n  expected: " . var_export($expected, true) . "\n  actual:   " . var_export($actual, true) . "\n";
    $failures++;
    return;
  }
  echo "PASS: $label\n";
}

// Test 1: Empty content returns empty array + original HTML
$res = tld_formation_build_toc('');
assertEqual($res['headings'], [], 'empty content → empty headings');
assertEqual($res['html'], '', 'empty content → empty html');

// Test 2: Single h2 gets an ID, returned in headings list
$res = tld_formation_build_toc('<h2>Hello World</h2>');
assertEqual(count($res['headings']), 1, 'single h2 → 1 heading');
assertEqual($res['headings'][0]['level'], 2, 'h2 level is 2');
assertEqual($res['headings'][0]['text'], 'Hello World', 'h2 text extracted');
assertEqual($res['headings'][0]['id'], 'hello-world', 'h2 slug generated');
assertEqual(
  $res['html'],
  '<h2 id="hello-world">Hello World</h2>',
  'h2 ID injected into rendered html'
);

// Test 3: Existing ID is preserved
$res = tld_formation_build_toc('<h2 id="custom">Hello</h2>');
assertEqual($res['headings'][0]['id'], 'custom', 'existing id preserved');
assertEqual($res['html'], '<h2 id="custom">Hello</h2>', 'html unchanged when id present');

// Test 4: Multiple headings with h2 and h3 hierarchy
$input = '<h2>Section One</h2><p>intro</p><h3>Sub A</h3><p>body</p><h3>Sub B</h3><h2>Section Two</h2>';
$res = tld_formation_build_toc($input);
assertEqual(count($res['headings']), 4, 'four headings found');
assertEqual($res['headings'][0]['level'], 2, 'first heading is h2');
assertEqual($res['headings'][1]['level'], 3, 'second heading is h3');
assertEqual($res['headings'][3]['level'], 2, 'fourth heading is h2');
assertEqual($res['headings'][0]['id'], 'section-one', 'slug section-one');
assertEqual($res['headings'][1]['id'], 'sub-a', 'slug sub-a');

// Test 5: Duplicate heading text gets unique slugs
$res = tld_formation_build_toc('<h2>Intro</h2><h2>Intro</h2>');
assertEqual($res['headings'][0]['id'], 'intro', 'first duplicate keeps base slug');
assertEqual($res['headings'][1]['id'], 'intro-2', 'second duplicate gets -2 suffix');

// Test 6: h4+ ignored
$res = tld_formation_build_toc('<h2>Keep</h2><h4>Skip</h4>');
assertEqual(count($res['headings']), 1, 'h4 excluded from ToC');
assertEqual(strpos($res['html'], 'id="skip"'), false, 'h4 gets no id');

// Test 7: Nested HTML inside heading text
$res = tld_formation_build_toc('<h2>Why <em>this</em> matters</h2>');
assertEqual($res['headings'][0]['text'], 'Why this matters', 'nested markup stripped for toc text');
assertEqual($res['headings'][0]['id'], 'why-this-matters', 'nested markup stripped for slug');

echo "\n";
if ($failures === 0) {
  echo "All tests passed.\n";
  exit(0);
}
echo "$failures test(s) failed.\n";
exit(1);
