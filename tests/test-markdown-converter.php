<?php
/**
 * Run: php tests/test-markdown-converter.php
 */

require __DIR__ . '/../inc/formation/markdown-converter.php';

$failures = 0;
function assertContains($haystack, $needle, $label) {
  global $failures;
  if (strpos($haystack, $needle) === false) {
    echo "FAIL: $label\n  looking for: " . var_export($needle, true) . "\n  in:          " . var_export(substr($haystack, 0, 120), true) . "...\n";
    $failures++; return;
  }
  echo "PASS: $label\n";
}

// Heading
$out = tld_formation_md_to_blocks("## Hello\n");
assertContains($out, '<!-- wp:heading {"level":2} --><h2>Hello</h2><!-- /wp:heading -->', 'h2 -> wp:heading block');

$out = tld_formation_md_to_blocks("### Sub\n");
assertContains($out, '<!-- wp:heading {"level":3} --><h3>Sub</h3><!-- /wp:heading -->', 'h3 -> wp:heading block');

// Paragraph
$out = tld_formation_md_to_blocks("This is a paragraph.\n");
assertContains($out, '<!-- wp:paragraph --><p>This is a paragraph.</p><!-- /wp:paragraph -->', 'paragraph');

// Emphasis
$out = tld_formation_md_to_blocks("A **bold** and *italic* word.\n");
assertContains($out, '<strong>bold</strong>', 'bold');
assertContains($out, '<em>italic</em>', 'italic');

// Link
$out = tld_formation_md_to_blocks("A [link](https://example.com) here.\n");
assertContains($out, '<a href="https://example.com">link</a>', 'link');

// Unordered list
$out = tld_formation_md_to_blocks("- one\n- two\n- three\n");
assertContains($out, '<!-- wp:list -->', 'list block start');
assertContains($out, '<li>one</li>', 'list item 1');
assertContains($out, '<li>three</li>', 'list item 3');

// Ordered list
$out = tld_formation_md_to_blocks("1. first\n2. second\n");
assertContains($out, '<!-- wp:list {"ordered":true} -->', 'ordered list block');
assertContains($out, '<ol>', 'ol tag');

// Blockquote
$out = tld_formation_md_to_blocks("> quoted line\n");
assertContains($out, '<!-- wp:quote -->', 'quote block');
assertContains($out, '<blockquote class="wp-block-quote">', 'blockquote tag with class');

// Horizontal rule
$out = tld_formation_md_to_blocks("---\n");
assertContains($out, '<!-- wp:separator -->', 'separator block');

// Multi-paragraph
$out = tld_formation_md_to_blocks("First para.\n\nSecond para.\n");
assertContains($out, '<p>First para.</p>', 'para 1');
assertContains($out, '<p>Second para.</p>', 'para 2');

echo "\n";
if ($failures === 0) { echo "All tests passed.\n"; exit(0); }
echo "$failures failed.\n"; exit(1);
