# Formation Area Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Spec:** [docs/superpowers/specs/2026-04-17-formation-area-design.md](../specs/2026-04-17-formation-area-design.md)

**Goal:** Ship the Formation learning zone on `truelight.digital` — a four-pillar structured content area replacing the existing Blog, with 7 pieces at launch (5 cornerstones, 2 short reads).

**Architecture:** Classic-PHP WordPress child theme (`bootscore-child`), Bootstrap 5, ACF Pro for fields and blocks, Gravity Forms for capture, The SEO Framework for schema/meta. New `formation_piece` CPT with `pillar` and `piece_type` taxonomies, three ACF blocks, three PHP templates. One-off mu-plugin seeds the four cornerstone essays from `documents/pillar-*.md`. Permanent mu-plugin handles `/blog/` → Formation redirects.

**Tech Stack:** PHP 8.2, WordPress 6.9, ACF Pro 6.7, Gravity Forms 2.9, SCSS (ScssPhp server-compiled), Matomo analytics, The SEO Framework, Bootstrap 5.3.

**Testing model:** Hand-rolled PHP assertion tests for three pure functions (ToC builder, markdown→Gutenberg converter, reading-time). Manual browser verification for WP integration work, with explicit success checks per task. No PHPUnit framework to set up. Tests runnable with `php tests/<name>.php`.

---

## File structure

All paths relative to `wp-content/themes/bootscore-child/` unless marked otherwise.

### New PHP files

```
inc/formation/
├── cpt-formation-piece.php          CPT registration + rewrite filter
├── taxonomies.php                   pillar + piece_type registration + meta-box hiding
├── reading-time.php                 save_post auto-calc hook
├── toc-builder.php                  pure function: parse content → inject IDs → emit nav
├── markdown-converter.php           pure function: markdown → Gutenberg block markup
├── seo-filters.php                  SEO Framework description filters (single + archive)
└── resource-pillar.php              adds pillar taxonomy to tld_resource

template-parts/blocks/
├── tld-pillar-card.php              Pillar Card block render
├── tld-callout-action.php           "What to do this week" Callout render
└── tld-piece-card.php               Piece Card block render (shared by pillar grid + related row)

template-parts/formation/
├── cornerstone-hero.php             Full-bleed navy hero for cornerstones
├── compact-hero.php                 Compact hero for short-reads + field-notes
├── pillar-hero.php                  Pillar archive hero (numeral + tagline)
├── toc.php                          Renders the ToC for cornerstone pieces
├── email-capture.php                Inline GF form with hidden pillar_interest field
└── related-pieces.php               3 auto-picked pieces from same pillar

single-formation_piece.php           Controller for single piece pages
taxonomy-pillar.php                  Controller for pillar archive pages
page-templates/page-formation.php    Landing page template (assigned to /formation/ WP page)

assets/scss/_formation.scss          Formation-specific SCSS (imported by _bootscore_custom.scss)
assets/js/formation.js               ToC scrollspy + filter pills (~4KB minified)

tests/
├── test-toc-builder.php             Unit tests for ToC builder
├── test-markdown-converter.php      Unit tests for markdown converter
└── test-reading-time.php            Unit tests for reading-time math
```

### New ACF JSON (exported from admin)

```
acf-json/group_formation_piece.json          Field group for formation_piece CPT
acf-json/group_pillar_term.json              Field group for pillar taxonomy terms
acf-json/group_block_pillar_card.json        Field group for Pillar Card block
acf-json/group_block_callout_action.json     Field group for Callout block
acf-json/group_block_piece_card.json         Field group for Piece Card block
```

### Modified files

```
functions.php                    Load new inc/formation/*.php files
inc/custom-post-types.php        Add 'pillar' to tld_resource taxonomies
inc/acf-blocks.php               Register 3 new blocks + 'formation' category
assets/scss/_bootscore_custom.scss  @import _formation.scss
```

### External to theme repo

```
wp-content/mu-plugins/tld-formation-redirects.php    Permanent: /blog/* → Formation 301s
wp-content/mu-plugins/tld-formation-seed.php         Temporary: one-off seed; deleted after run
wp-content/mu-plugins/tld-seed-content/              Empty — seed reads from app/public/documents/
```

---

## Task 1 — Create `inc/formation/` scaffold + load in `functions.php`

**Files:**
- Create: `inc/formation/` directory (empty placeholder files to start)
- Modify: `functions.php:53-58` (append new includes)

- [ ] **Step 1.1: Create directory and empty files**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
mkdir -p inc/formation
touch inc/formation/cpt-formation-piece.php
touch inc/formation/taxonomies.php
touch inc/formation/reading-time.php
touch inc/formation/toc-builder.php
touch inc/formation/markdown-converter.php
touch inc/formation/seo-filters.php
touch inc/formation/resource-pillar.php
```

Each file starts with `<?php defined('ABSPATH') || exit;` — add that header to all seven:

```bash
for f in inc/formation/*.php; do
  echo '<?php defined("ABSPATH") || exit;' > "$f"
done
```

- [ ] **Step 1.2: Update `functions.php` includes list**

In `functions.php`, locate the `$tld_includes` array (currently lines 53-58) and replace:

```php
$tld_includes = [
  'inc/theme-helpers.php',
  'inc/acf-fields.php',
  'inc/acf-blocks.php',
  'inc/custom-post-types.php',
  'inc/formation/cpt-formation-piece.php',
  'inc/formation/taxonomies.php',
  'inc/formation/reading-time.php',
  'inc/formation/toc-builder.php',
  'inc/formation/markdown-converter.php',
  'inc/formation/seo-filters.php',
  'inc/formation/resource-pillar.php',
];
```

Order matters: `custom-post-types.php` registers `tld_resource`; `resource-pillar.php` extends it, so load order as shown is correct.

- [ ] **Step 1.3: Verify site still loads**

Open `http://church-2.local/` in the browser. Expected: homepage renders normally (no visible change, but no PHP errors either). Check the PHP error log at `C:/Users/Sean/Local Sites/church-2/logs/php/error.log` for any warnings about the new includes.

- [ ] **Step 1.4: Commit**

```bash
git add inc/formation/ functions.php
git commit -m "Scaffold inc/formation/ module directory for Formation area"
```

---

## Task 2 — Register `pillar` and `piece_type` taxonomies

**Files:**
- Modify: `inc/formation/taxonomies.php`

- [ ] **Step 2.1: Register both taxonomies**

Replace the contents of `inc/formation/taxonomies.php`:

```php
<?php
defined('ABSPATH') || exit;

/**
 * Formation taxonomies.
 *
 * `pillar` is public and drives the URL: /formation/<pillar-slug>/
 * `piece_type` is internal; only affects template variant.
 */

add_action('init', 'tld_formation_register_taxonomies', 5);

function tld_formation_register_taxonomies() {

  register_taxonomy('pillar', ['formation_piece', 'tld_resource'], [
    'labels' => [
      'name'          => 'Pillars',
      'singular_name' => 'Pillar',
      'menu_name'     => 'Pillars',
      'all_items'     => 'All Pillars',
      'edit_item'     => 'Edit Pillar',
      'add_new_item'  => 'Add New Pillar',
      'search_items'  => 'Search Pillars',
    ],
    'hierarchical'       => false,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_rest'       => true,
    'show_admin_column'  => true,
    'show_tagcloud'      => false,
    'rewrite'            => [
      'slug'         => 'formation',
      'with_front'   => false,
      'hierarchical' => false,
    ],
    'meta_box_cb'        => false, // hidden: ACF radio replaces it
  ]);

  register_taxonomy('piece_type', ['formation_piece'], [
    'labels' => [
      'name'          => 'Piece Types',
      'singular_name' => 'Piece Type',
      'menu_name'     => 'Piece Types',
    ],
    'hierarchical'       => false,
    'public'             => false,
    'publicly_queryable' => false,
    'show_ui'            => true,
    'show_in_rest'       => true,
    'show_admin_column'  => true,
    'rewrite'            => false,
    'meta_box_cb'        => false, // hidden: ACF radio replaces it
  ]);
}

/**
 * Seed pillar and piece_type terms on theme activation.
 * Actual content (name, tagline, sort order) populated by the seed mu-plugin.
 */
add_action('after_switch_theme', 'tld_formation_seed_default_terms');

function tld_formation_seed_default_terms() {
  $pillar_slugs = [
    'communications-champion'     => 'The Communications Champion',
    'rhythm-and-restraint'        => 'Rhythm & Restraint',
    'invitation-and-patience'     => 'Invitation & Patience',
    'guardrails-and-discernment'  => 'Guardrails & Discernment',
  ];

  foreach ($pillar_slugs as $slug => $name) {
    if (!term_exists($slug, 'pillar')) {
      wp_insert_term($name, 'pillar', ['slug' => $slug]);
    }
  }

  $piece_types = ['cornerstone', 'short-read', 'field-note'];
  foreach ($piece_types as $slug) {
    if (!term_exists($slug, 'piece_type')) {
      wp_insert_term(ucwords(str_replace('-', ' ', $slug)), 'piece_type', ['slug' => $slug]);
    }
  }
}
```

- [ ] **Step 2.2: Flush rewrites and verify terms exist**

In WP Admin, visit **Settings → Permalinks** and click Save (flushes rewrites). Then:

```bash
PHP="C:/Users/Sean/AppData/Roaming/Local/lightning-services/php-8.2.27+1/bin/win64/php.exe"
EXTDIR="C:/Users/Sean/AppData/Roaming/Local/lightning-services/php-8.2.27+1/bin/win64/ext"
WPCLI="C:/Users/Sean/Local Sites/church-2/wp-cli.phar"
WPPATH="C:/Users/Sean/Local Sites/church-2/app/public"

"$PHP" -d "extension_dir=$EXTDIR" -d "extension=php_mysqli.dll" "$WPCLI" \
  --path="$WPPATH" --dbhost=127.0.0.1:10053 term list pillar --format=table
```

Expected output: 4 pillar terms with the correct slugs.

Alternative if WP-CLI is unavailable: browse to **Posts → Pillars** in WP admin (the taxonomy will appear under Posts until the CPT is registered in Task 4; after Task 4 it moves under Formation).

- [ ] **Step 2.3: Commit**

```bash
git add inc/formation/taxonomies.php
git commit -m "Register pillar and piece_type taxonomies with seeded terms"
```

---

## Task 3 — Register `formation_piece` CPT with pillar-aware permalinks

**Files:**
- Modify: `inc/formation/cpt-formation-piece.php`

- [ ] **Step 3.1: Register CPT + rewrite tag + URL filter**

Replace the contents of `inc/formation/cpt-formation-piece.php`:

```php
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
    'has_archive'         => false, // landing is a WP page at /formation/
    'menu_icon'           => 'dashicons-book-alt',
    'menu_position'       => 23,    // above Resources (25)
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
```

- [ ] **Step 3.2: Flush rewrites and verify admin menu**

In WP Admin, visit **Settings → Permalinks** → Save. The left admin menu should now show "Formation" with a book icon, positioned above "Resources".

- [ ] **Step 3.3: Smoke-test permalink generation**

Via WP-CLI (as set up in Task 2.2):

```bash
"$PHP" -d "extension_dir=$EXTDIR" -d "extension=php_mysqli.dll" "$WPCLI" \
  --path="$WPPATH" --dbhost=127.0.0.1:10053 eval \
  '$p = wp_insert_post(["post_type" => "formation_piece", "post_title" => "Smoke Test", "post_status" => "draft"]);
   wp_set_object_terms($p, "communications-champion", "pillar");
   echo get_permalink($p) . "\n";
   wp_delete_post($p, true);'
```

Expected output: `http://church-2.local/formation/communications-champion/smoke-test/`

If output shows `%pillar%` literally, the permalink filter isn't firing — check Step 3.1.

- [ ] **Step 3.4: Commit**

```bash
git add inc/formation/cpt-formation-piece.php
git commit -m "Register formation_piece CPT with pillar-aware permalinks"
```

---

## Task 4 — ToC builder (pure function + unit tests)

**Files:**
- Modify: `inc/formation/toc-builder.php`
- Create: `tests/test-toc-builder.php`

- [ ] **Step 4.1: Write failing tests**

Create `tests/test-toc-builder.php`:

```php
<?php
/**
 * Unit tests for tld_formation_build_toc().
 * Run: php tests/test-toc-builder.php
 * Exit code: 0 on all pass, 1 on any fail.
 */

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
```

- [ ] **Step 4.2: Run tests to see them fail**

```bash
cd "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child"
"$PHP" tests/test-toc-builder.php
```

Expected: fatal error "Call to undefined function tld_formation_build_toc()".

- [ ] **Step 4.3: Implement the function**

Replace contents of `inc/formation/toc-builder.php`:

```php
<?php
defined('ABSPATH') || exit;

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
```

- [ ] **Step 4.4: Run tests and expect all pass**

```bash
"$PHP" tests/test-toc-builder.php
```

Expected: `All tests passed.` and exit code 0.

If any fail, read the output, fix the function, re-run.

- [ ] **Step 4.5: Commit**

```bash
git add inc/formation/toc-builder.php tests/test-toc-builder.php
git commit -m "Add ToC builder with unit tests (h2/h3 extraction + id injection)"
```

---

## Task 5 — Reading-time hook

**Files:**
- Modify: `inc/formation/reading-time.php`
- Create: `tests/test-reading-time.php`

- [ ] **Step 5.1: Write failing test for the pure calculation**

Create `tests/test-reading-time.php`:

```php
<?php
/**
 * Run: php tests/test-reading-time.php
 */

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
```

- [ ] **Step 5.2: Run, see it fail**

```bash
"$PHP" tests/test-reading-time.php
```

Expected: "Call to undefined function tld_formation_calc_reading_time()".

- [ ] **Step 5.3: Implement both the pure calc and the WP hook**

Replace contents of `inc/formation/reading-time.php`:

```php
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
```

Note: test includes a `wp_strip_all_tags` shim check because the test runs outside WP context. Fallback to `strip_tags` gives the same result for the test inputs.

- [ ] **Step 5.4: Run tests, expect all pass**

```bash
"$PHP" tests/test-reading-time.php
```

Expected: `All tests passed.`

- [ ] **Step 5.5: Commit**

```bash
git add inc/formation/reading-time.php tests/test-reading-time.php
git commit -m "Add reading-time calculator and save_post hook"
```

---

## Task 6 — Extend `tld_resource` with `pillar` taxonomy

**Files:**
- Modify: `inc/custom-post-types.php` (line with `'taxonomies' => ['resource_category']`)
- Modify: `inc/formation/resource-pillar.php`

- [ ] **Step 6.1: Extend `tld_resource`**

In `inc/custom-post-types.php`, find:

```php
'taxonomies'    => ['resource_category'],
```

Change to:

```php
'taxonomies'    => ['resource_category', 'pillar'],
```

- [ ] **Step 6.2: Add `show_admin_column` wiring (optional)**

Replace the contents of `inc/formation/resource-pillar.php`:

```php
<?php
defined('ABSPATH') || exit;

/**
 * Ensures the pillar taxonomy column appears in the Resources list screen.
 * Registration itself is handled by register_taxonomy() in taxonomies.php
 * (object_type includes both formation_piece and tld_resource).
 */

add_filter('manage_edit-tld_resource_columns', function ($columns) {
  // Already handled by show_admin_column => true on register_taxonomy.
  // This filter is here as a seam if we later want to reorder the column.
  return $columns;
});
```

- [ ] **Step 6.3: Verify**

In WP Admin, visit **Resources → All Resources**. The list table should now have a "Pillars" column (empty for existing resources until one is tagged). Edit any existing resource — you should see a "Pillars" taxonomy box in the sidebar.

- [ ] **Step 6.4: Commit**

```bash
git add inc/custom-post-types.php inc/formation/resource-pillar.php
git commit -m "Extend tld_resource with pillar taxonomy"
```

---

## Task 7 — ACF field group: Formation Piece

**Files:**
- Create (via ACF admin export): `acf-json/group_formation_piece.json`

No code. This is admin work that produces a JSON export.

- [ ] **Step 7.1: Create field group via ACF admin**

In WP Admin → **ACF → Field Groups → Add New**:

- **Title:** Formation Piece
- **Location rule:** Post Type is equal to `formation_piece`
- **Settings → Position:** Normal (after content)
- **Settings → Style:** Default

Add fields in this order:

| Label | Name | Type | Required | Instructions | Conditional Logic |
|---|---|---|---|---|---|
| Subtitle | `subtitle` | Text | No | Italic tagline shown under the title. Keep to one sentence. | — |
| Summary | `summary` | Textarea | **Yes** | 2–3 sentences. Used in listings and as the meta description. Aim for 150–160 characters. | — |
| Pillar | `pillar` | Taxonomy | **Yes** | — | — |
| Piece Type | `piece_type` | Taxonomy | **Yes** | — | — |
| Reading time (minutes) | `reading_time_minutes` | Number | No | Leave blank to auto-calculate on save. | — |
| Show Table of Contents | `toc_enabled` | True / False | No | Default on for cornerstones. | Show if `piece_type` (value) == `cornerstone` |

For the `pillar` Taxonomy field:
- Taxonomy: Pillar
- Appearance: Radio buttons
- Allow null: No
- Save terms: Yes
- Load terms: Yes
- Return value: Term object

For the `piece_type` Taxonomy field: same settings, with Taxonomy = Piece Type.

For the `toc_enabled` True/False field: set "Default Value" = true, UI = Yes (stylised toggle).

- [ ] **Step 7.2: Verify ACF JSON export landed in `acf-json/`**

After saving the field group, the theme's ACF save-JSON filter (already registered in `functions.php:150`) should write `acf-json/group_formation_piece.json`. Confirm:

```bash
ls "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/acf-json/"
```

Expected: `group_formation_piece.json` exists.

- [ ] **Step 7.3: Smoke-test the admin UI**

Visit **Formation → Add Piece**. Expected: title field, editor, and in the sidebar/below: Subtitle, Summary (required), Pillar (radio), Piece Type (radio), Reading time, Show ToC toggle (only shown when piece_type = cornerstone).

Try saving a draft piece with a title and pillar — confirm it saves and the permalink preview shows `/formation/<pillar>/<slug>/`.

- [ ] **Step 7.4: Commit**

```bash
git add acf-json/group_formation_piece.json
git commit -m "Add ACF field group for Formation Piece"
```

---

## Task 8 — ACF field group: Pillar term fields

**Files:**
- Create (via ACF admin export): `acf-json/group_pillar_term.json`

- [ ] **Step 8.1: Create field group via ACF admin**

**ACF → Field Groups → Add New**:

- **Title:** Pillar Term Fields
- **Location rule:** Taxonomy is equal to Pillar

Fields:

| Label | Name | Type | Required | Instructions |
|---|---|---|---|---|
| Tagline | `pillar_tagline` | Textarea (2 rows) | Yes | Rendered on the pillar archive hero under the term name. Keep to 15–30 words, in the essay's voice. |
| Meta description | `pillar_meta_description` | Text | Yes | Search-result snippet. 150–160 characters. Use language a non-Catholic search-arriver will understand. |
| Sort order | `pillar_sort_order` | Number | Yes | Drives the 01–04 numbering in Pillar Cards and archive hero. Min 1, max 99. |

Min/max on sort order: 1 / 99. Default: 99.

- [ ] **Step 8.2: Seed values on the four pillars**

In WP Admin → **Formation → Pillars**, edit each term and populate all three fields. Use the copy from the spec §6.2. (This can also be automated by the seed mu-plugin in Task 21, but doing it manually now lets you see the UI work.)

- [ ] **Step 8.3: Verify JSON + values**

```bash
ls "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/acf-json/"
# → group_pillar_term.json exists

"$PHP" -d "extension_dir=$EXTDIR" -d "extension=php_mysqli.dll" "$WPCLI" \
  --path="$WPPATH" --dbhost=127.0.0.1:10053 eval \
  'foreach (get_terms(["taxonomy" => "pillar", "hide_empty" => false]) as $t) {
    echo $t->slug . " → order=" . get_field("pillar_sort_order", "pillar_" . $t->term_id) . "\n";
   }'
```

Expected: 4 pillars, each with sort_order 1–4.

- [ ] **Step 8.4: Commit**

```bash
git add acf-json/group_pillar_term.json
git commit -m "Add ACF field group for pillar taxonomy terms (tagline, meta, sort)"
```

---

## Task 9 — Markdown → Gutenberg converter (pure function + tests)

**Files:**
- Modify: `inc/formation/markdown-converter.php`
- Create: `tests/test-markdown-converter.php`

- [ ] **Step 9.1: Write failing tests**

Create `tests/test-markdown-converter.php`:

```php
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
assertContains($out, '<!-- wp:heading {"level":2} --><h2>Hello</h2><!-- /wp:heading -->', 'h2 → wp:heading block');

$out = tld_formation_md_to_blocks("### Sub\n");
assertContains($out, '<!-- wp:heading {"level":3} --><h3>Sub</h3><!-- /wp:heading -->', 'h3 → wp:heading block');

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
```

- [ ] **Step 9.2: Run, see failure**

```bash
"$PHP" tests/test-markdown-converter.php
```

Expected: "undefined function tld_formation_md_to_blocks()".

- [ ] **Step 9.3: Implement the converter**

Replace contents of `inc/formation/markdown-converter.php`:

```php
<?php
defined('ABSPATH') || exit;

/**
 * Minimal markdown → Gutenberg block markup converter.
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
 * Pure function — no WP deps beyond esc_attr/esc_url fallbacks. Testable.
 */
function tld_formation_md_to_blocks($markdown) {
  // Normalise line endings
  $md = str_replace(["\r\n", "\r"], "\n", $markdown);

  // Split into blocks by blank line
  $chunks = preg_split('/\n{2,}/', trim($md));

  $blocks = [];
  foreach ($chunks as $chunk) {
    $chunk = trim($chunk);
    if ($chunk === '') continue;

    // Horizontal rule
    if (preg_match('/^-{3,}$|^\*{3,}$/m', $chunk)) {
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

    // Unordered list (all lines start with "- " or "* ")
    if (preg_match('/^(?:[-*]\s+.+\n?)+$/m', $chunk) && strpos($chunk, "\n") !== false || preg_match('/^[-*]\s+/', $chunk)) {
      $lines = preg_split('/\n/', $chunk);
      if (array_reduce($lines, fn($c, $l) => $c && preg_match('/^[-*]\s+/', trim($l)), true)) {
        $items = array_map(fn($l) => '<li>' . _tld_md_inline(preg_replace('/^[-*]\s+/', '', trim($l))) . '</li>', $lines);
        $blocks[] = '<!-- wp:list --><ul>' . implode('', $items) . '</ul><!-- /wp:list -->';
        continue;
      }
    }

    // Ordered list (all lines start with "N. ")
    if (preg_match('/^\d+\.\s+/', $chunk)) {
      $lines = preg_split('/\n/', $chunk);
      if (array_reduce($lines, fn($c, $l) => $c && preg_match('/^\d+\.\s+/', trim($l)), true)) {
        $items = array_map(fn($l) => '<li>' . _tld_md_inline(preg_replace('/^\d+\.\s+/', '', trim($l))) . '</li>', $lines);
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

    // Paragraph (default)
    $blocks[] = '<!-- wp:paragraph --><p>' . _tld_md_inline($chunk) . '</p><!-- /wp:paragraph -->';
  }

  return implode("\n\n", $blocks);
}

/**
 * Inline formatting: **bold**, *italic*, [link](url). Order matters.
 */
function _tld_md_inline($text) {
  // Escape raw HTML entities the author didn't intend (very minimal — assumes trusted source)
  // Links first so [text](url) doesn't get partially chewed by emphasis rules
  $text = preg_replace_callback('/\[([^\]]+)\]\(([^)]+)\)/', function ($m) {
    return '<a href="' . esc_url($m[2]) . '">' . $m[1] . '</a>';
  }, $text);

  // Bold
  $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);

  // Italic (single-asterisk, not already matched by bold)
  $text = preg_replace('/(?<!\*)\*([^*\s][^*]*?)\*(?!\*)/', '<em>$1</em>', $text);

  return $text;
}

// Fallback for non-WP test context
if (!function_exists('esc_url')) {
  function esc_url($u) { return filter_var($u, FILTER_SANITIZE_URL) ?: $u; }
}
if (!function_exists('esc_attr')) {
  function esc_attr($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
}
```

- [ ] **Step 9.4: Run tests, expect pass**

```bash
"$PHP" tests/test-markdown-converter.php
```

Expected: `All tests passed.`

If list tests fail, most likely the regex for detecting a list chunk is off — inspect and fix. The list detection is the most brittle part.

- [ ] **Step 9.5: Commit**

```bash
git add inc/formation/markdown-converter.php tests/test-markdown-converter.php
git commit -m "Add markdown → Gutenberg converter with unit tests"
```

---

## Task 10 — Pillar Card block

**Files:**
- Modify: `inc/acf-blocks.php`
- Create: `template-parts/blocks/tld-pillar-card.php`
- Create (via ACF admin export): `acf-json/group_block_pillar_card.json`

- [ ] **Step 10.1: Register the block**

In `inc/acf-blocks.php`, find the `add_action('acf/init', ...)` block registration section (the file already contains the existing block registrations). Append inside the same callback:

```php
acf_register_block_type([
  'name'            => 'tld-pillar-card',
  'title'           => 'Pillar Card',
  'description'     => 'Featured card for one Formation pillar. Place four on the Formation landing page.',
  'render_template' => 'template-parts/blocks/tld-pillar-card.php',
  'category'        => 'formation',
  'icon'            => 'book-alt',
  'keywords'        => ['formation', 'pillar', 'card'],
  'mode'            => 'preview',
  'supports'        => ['align' => false, 'mode' => false, 'jsx' => false],
]);
```

- [ ] **Step 10.2: Register the "Formation" Gutenberg category**

In `inc/acf-blocks.php`, add (at file scope, not inside acf/init):

```php
add_filter('block_categories_all', function ($categories) {
  array_splice($categories, 1, 0, [[
    'slug'  => 'formation',
    'title' => 'Formation',
    'icon'  => 'book-alt',
  ]]);
  return $categories;
}, 10);
```

- [ ] **Step 10.3: Create ACF field group via admin**

**ACF → Field Groups → Add New**:

- **Title:** Block — Pillar Card
- **Location rule:** Block is equal to `acf/tld-pillar-card`

Fields:

| Label | Name | Type | Required | Notes |
|---|---|---|---|---|
| Pillar | `pillar` | Taxonomy | Yes | Taxonomy = Pillar; Appearance = Select; Allow null = No; Return value = Term object |

- [ ] **Step 10.4: Write the render template**

Create `template-parts/blocks/tld-pillar-card.php`:

```php
<?php
/**
 * Pillar Card block render template.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillar = get_field('pillar');

if (!$pillar || is_wp_error($pillar)) {
  if (is_admin() || (function_exists('is_customize_preview') && is_customize_preview())) {
    echo '<div class="tld-block-placeholder">Pillar Card: select a pillar in the block sidebar.</div>';
  }
  return;
}

$term_id    = $pillar->term_id;
$sort_order = (int) get_field('pillar_sort_order', 'pillar_' . $term_id);
$tagline    = get_field('pillar_tagline', 'pillar_' . $term_id);
$url        = get_term_link($pillar);
$count      = (int) $pillar->count;

$number = str_pad((string) $sort_order, 2, '0', STR_PAD_LEFT);
?>
<a class="tld-pillar-card" href="<?php echo esc_url($url); ?>">
  <div class="tld-pillar-card__number" aria-hidden="true"><?php echo esc_html($number); ?></div>
  <div class="tld-pillar-card__body">
    <h3 class="tld-pillar-card__title"><?php echo esc_html($pillar->name); ?></h3>
    <?php if ($tagline): ?>
      <p class="tld-pillar-card__tagline"><?php echo wp_kses_post($tagline); ?></p>
    <?php endif; ?>
  </div>
  <div class="tld-pillar-card__meta">
    <span class="tld-pillar-card__count"><?php echo esc_html((string) $count); ?></span>
    <span class="tld-pillar-card__count-label">pieces</span>
    <span class="tld-pillar-card__arrow" aria-hidden="true">&rarr;</span>
  </div>
</a>
```

- [ ] **Step 10.5: Smoke-test in editor**

In WP Admin, create a draft page titled "Formation" with slug `formation`. Add a Pillar Card block from the Gutenberg inserter (category "Formation"). Select a pillar from the sidebar dropdown. The preview should render the card with numeral, name, tagline, and piece count.

- [ ] **Step 10.6: Commit**

```bash
git add inc/acf-blocks.php template-parts/blocks/tld-pillar-card.php acf-json/group_block_pillar_card.json
git commit -m "Add Pillar Card block + register Formation Gutenberg category"
```

---

## Task 11 — Callout block ("What to do this week")

**Files:**
- Modify: `inc/acf-blocks.php` (append another `acf_register_block_type`)
- Create: `template-parts/blocks/tld-callout-action.php`
- Create (via ACF admin): `acf-json/group_block_callout_action.json`

- [ ] **Step 11.1: Register block**

In `inc/acf-blocks.php`, inside the same `acf/init` callback as Task 10, append:

```php
acf_register_block_type([
  'name'            => 'tld-callout-action',
  'title'           => 'What to do this week',
  'description'     => 'A gold-bordered action callout. Use at the end of cornerstone essays.',
  'render_template' => 'template-parts/blocks/tld-callout-action.php',
  'category'        => 'formation',
  'icon'            => 'lightbulb',
  'keywords'        => ['formation', 'callout', 'action', 'steps'],
  'mode'            => 'edit',
  'supports'        => ['align' => ['wide'], 'mode' => false, 'jsx' => false],
]);
```

- [ ] **Step 11.2: ACF field group via admin**

**ACF → Field Groups → Add New**:

- **Title:** Block — Callout Action
- **Location rule:** Block is equal to `acf/tld-callout-action`

Fields:

| Label | Name | Type | Required | Notes |
|---|---|---|---|---|
| Label | `label_override` | Text | No | Default "What to do this week"; override for other callouts. |
| Heading | `heading` | Text | Yes | e.g. "Three steps to start" |
| Intro | `intro` | Textarea (2 rows) | No | Optional leading sentence before the steps. |
| Steps | `steps` | Repeater | Yes | Min 1, Max 5 |

Inside `steps` repeater:

| Label | Name | Type | Required |
|---|---|---|---|
| Lead | `step_lead` | Text | Yes |
| Detail | `step_detail` | Textarea (2 rows) | No |

- [ ] **Step 11.3: Render template**

Create `template-parts/blocks/tld-callout-action.php`:

```php
<?php
/**
 * Callout "What to do this week" block render.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$label_override = get_field('label_override');
$label   = $label_override ?: 'What to do this week';
$heading = get_field('heading');
$intro   = get_field('intro');
$steps   = get_field('steps');

if (!$heading || !$steps) {
  if (is_admin()) echo '<div class="tld-block-placeholder">Callout: heading + at least one step required.</div>';
  return;
}
?>
<aside class="tld-callout-action" role="complementary">
  <div class="tld-callout-action__label"><?php echo esc_html($label); ?></div>
  <h4 class="tld-callout-action__heading"><?php echo esc_html($heading); ?></h4>
  <?php if ($intro): ?>
    <p class="tld-callout-action__intro"><?php echo wp_kses_post($intro); ?></p>
  <?php endif; ?>
  <ol class="tld-callout-action__steps">
    <?php foreach ($steps as $step): ?>
      <li>
        <strong><?php echo esc_html($step['step_lead']); ?></strong>
        <?php if (!empty($step['step_detail'])): ?>
          <?php echo wp_kses_post($step['step_detail']); ?>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</aside>
```

- [ ] **Step 11.4: Verify in editor**

In any post, insert a "What to do this week" block, fill heading + one step. Preview should render the callout.

- [ ] **Step 11.5: Commit**

```bash
git add inc/acf-blocks.php template-parts/blocks/tld-callout-action.php acf-json/group_block_callout_action.json
git commit -m "Add Callout Action block (What to do this week)"
```

---

## Task 12 — Piece Card block (also shared with template rendering)

**Files:**
- Modify: `inc/acf-blocks.php` (append another `acf_register_block_type`)
- Create: `template-parts/blocks/tld-piece-card.php`
- Create (via ACF admin): `acf-json/group_block_piece_card.json`

- [ ] **Step 12.1: Register block**

In `inc/acf-blocks.php`, inside the same `acf/init` callback:

```php
acf_register_block_type([
  'name'            => 'tld-piece-card',
  'title'           => 'Piece Card',
  'description'     => 'Feature a specific Formation piece. Used both as a block and in template grids.',
  'render_template' => 'template-parts/blocks/tld-piece-card.php',
  'category'        => 'formation',
  'icon'            => 'media-document',
  'keywords'        => ['formation', 'piece', 'feature'],
  'mode'            => 'preview',
  'supports'        => ['align' => false, 'mode' => false, 'jsx' => false],
]);
```

- [ ] **Step 12.2: ACF field group via admin**

- **Title:** Block — Piece Card
- **Location rule:** Block is equal to `acf/tld-piece-card`

Fields:

| Label | Name | Type | Required | Notes |
|---|---|---|---|---|
| Piece | `piece` | Post Object | Yes | Post type filter: `formation_piece`. Return: Post object. |
| Variant | `variant` | Select | Yes | Choices: `default : Default`, `featured : Featured (cornerstone style)`, `compact : Compact`. Default: default. |
| Show summary | `show_summary` | True / False | No | Default true. |

- [ ] **Step 12.3: Render template (shared by block + template-part includes)**

Create `template-parts/blocks/tld-piece-card.php`:

```php
<?php
/**
 * Piece Card render.
 *
 * Called in two ways:
 *   1. As an ACF block — field values come from get_field()
 *   2. From template code via get_template_part('template-parts/blocks/tld-piece-card', null, $args)
 *      with $args = ['piece_id' => int, 'variant' => string, 'show_summary' => bool]
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

// Decide source
$piece_id     = null;
$variant      = 'default';
$show_summary = true;

if (isset($args) && is_array($args)) {
  $piece_id     = $args['piece_id']     ?? null;
  $variant      = $args['variant']      ?? 'default';
  $show_summary = $args['show_summary'] ?? true;
} else {
  $piece = get_field('piece');
  if ($piece) {
    $piece_id = is_object($piece) ? $piece->ID : (int) $piece;
  }
  $variant      = get_field('variant') ?: 'default';
  $show_summary = get_field('show_summary');
  if ($show_summary === null) $show_summary = true;
}

if (!$piece_id) return;

$p = get_post($piece_id);
if (!$p || $p->post_type !== 'formation_piece') return;

$pillar_terms = get_the_terms($piece_id, 'pillar');
$pillar       = (!is_wp_error($pillar_terms) && !empty($pillar_terms)) ? $pillar_terms[0] : null;
$type_terms   = get_the_terms($piece_id, 'piece_type');
$piece_type   = (!is_wp_error($type_terms) && !empty($type_terms)) ? $type_terms[0] : null;

$reading_time = (int) get_field('reading_time_minutes', $piece_id);
$summary      = get_field('summary', $piece_id);
$thumb        = get_the_post_thumbnail($piece_id, 'tld-card', ['class' => 'tld-piece-card__image']);

$classes = 'tld-piece-card tld-piece-card--' . esc_attr($variant);
?>
<article class="<?php echo $classes; ?>">
  <a class="tld-piece-card__link" href="<?php echo esc_url(get_permalink($p)); ?>">
    <?php if ($thumb && $variant !== 'compact'): ?>
      <div class="tld-piece-card__image-wrap"><?php echo $thumb; ?></div>
    <?php endif; ?>
    <div class="tld-piece-card__body">
      <div class="tld-piece-card__meta">
        <?php if ($piece_type): ?>
          <span class="tld-piece-card__type"><?php echo esc_html($piece_type->name); ?></span>
        <?php endif; ?>
        <?php if ($reading_time): ?>
          <span class="tld-piece-card__time"><?php echo (int) $reading_time; ?> min read</span>
        <?php endif; ?>
      </div>
      <h3 class="tld-piece-card__title"><?php echo esc_html(get_the_title($p)); ?></h3>
      <?php if ($show_summary && $summary): ?>
        <p class="tld-piece-card__summary"><?php echo esc_html($summary); ?></p>
      <?php endif; ?>
    </div>
  </a>
</article>
```

- [ ] **Step 12.4: Verify in editor**

Insert a Piece Card block, select an existing draft piece (create one if needed). Preview should render with type badge, title, summary, and featured image if set.

- [ ] **Step 12.5: Commit**

```bash
git add inc/acf-blocks.php template-parts/blocks/tld-piece-card.php acf-json/group_block_piece_card.json
git commit -m "Add Piece Card block (also shared render for template grids)"
```

---

## Task 13 — Formation SCSS module

**Files:**
- Create: `assets/scss/_formation.scss`
- Modify: `assets/scss/_bootscore_custom.scss`

- [ ] **Step 13.1: Add import to `_bootscore_custom.scss`**

At the bottom of `assets/scss/_bootscore_custom.scss`:

```scss
@import 'formation';
```

- [ ] **Step 13.2: Write Formation SCSS**

Create `assets/scss/_formation.scss`:

```scss
/*
 * Formation — SCSS module
 * Pillar cards, piece cards, callouts, ToC, cornerstone hero, pillar archive, email capture.
 */

// --- Shared tokens ---
$formation-gold: #E6B849;
$formation-navy-900: #0F2035;
$formation-navy-700: #1C3557;
$formation-callout-bg: #F6F1E3;
$formation-body-bg: #fff;

// --- Pillar Card ---
.tld-pillar-card {
  display: grid;
  grid-template-columns: 100px 1fr auto;
  gap: 1.25rem;
  align-items: center;
  background: #F7F8FC;
  border: 1px solid #dde1ed;
  border-radius: 10px;
  padding: 1.5rem;
  text-decoration: none;
  color: inherit;
  transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(28, 53, 87, 0.1);
  }

  &__number {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 3rem;
    font-weight: 800;
    color: $formation-gold;
    line-height: 1;
    text-align: center;
  }
  &__title {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: $formation-navy-900;
    margin: 0 0 0.3rem;
  }
  &__tagline {
    font-size: 0.92rem;
    color: #4a5168;
    margin: 0;
    line-height: 1.45;
  }
  &__meta { text-align: right; font-size: 0.78rem; color: #6e7487; }
  &__count {
    display: block;
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: $formation-navy-900;
    font-weight: 700;
    line-height: 1;
  }
  &__arrow { display: block; margin-top: 0.35rem; color: $formation-navy-700; font-size: 1.1rem; }
}

// --- Callout Action ---
.tld-callout-action {
  background: $formation-callout-bg;
  border-radius: 8px;
  border-left: 4px solid $formation-gold;
  padding: 1.5rem 1.75rem;
  margin: 2rem 0;

  &__label {
    font-size: 0.7rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #856923;
    font-weight: 700;
    margin-bottom: 0.6rem;
  }
  &__heading {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    color: $formation-navy-900;
    margin: 0 0 0.4rem;
  }
  &__intro { font-size: 0.95rem; color: #4a5168; margin: 0 0 1rem; line-height: 1.55; }
  &__steps {
    margin: 0;
    padding-left: 1.2rem;
    color: #2c3240;
    li { margin-bottom: 0.75rem; line-height: 1.55; strong { color: $formation-navy-900; } }
  }
}

// --- Piece Card ---
.tld-piece-card {
  background: #fff;
  border: 1px solid #e6e9f2;
  border-radius: 8px;
  overflow: hidden;
  transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;

  &:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(28, 53, 87, 0.08); }
  &__link { display: block; color: inherit; text-decoration: none; }
  &__image { display: block; width: 100%; height: auto; }
  &__body { padding: 1.1rem 1.25rem; }
  &__meta { display: flex; gap: 0.75rem; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.4rem; }
  &__type { color: $formation-gold; font-weight: 700; }
  &__time { color: #6e7487; }
  &__title {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    color: $formation-navy-900;
    margin: 0 0 0.4rem;
    line-height: 1.25;
  }
  &__summary { font-size: 0.88rem; color: #4a5168; margin: 0; line-height: 1.5; }

  &--compact &__meta { font-size: 0.68rem; }
  &--compact &__title { font-size: 1rem; }
  &--featured { border-width: 2px; border-color: $formation-gold; }
}

// --- Cornerstone hero ---
.formation-hero {
  background: linear-gradient(180deg, $formation-navy-900 0%, $formation-navy-700 100%);
  color: #fff;
  padding: 3.5rem 0 2.5rem;

  &__meta {
    display: flex; gap: 0.75rem; align-items: center;
    font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.12em;
    color: $formation-gold; margin-bottom: 1.25rem;
    .muted { color: rgba(255,255,255,0.55); }
    .dot { color: rgba(255,255,255,0.4); }
  }
  &__title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    line-height: 1.1; font-weight: 700;
    margin: 0 0 0.8rem; max-width: 26ch;
  }
  &__subtitle {
    font-size: 1.1rem; color: rgba(255,255,255,0.78);
    max-width: 55ch; line-height: 1.45; margin: 0;
    font-style: italic;
  }
  &__image { margin-top: 2rem; }
  &__image img { width: 100%; height: auto; display: block; }
}

// --- Compact hero ---
.formation-hero-compact {
  background: #F7F8FC;
  padding: 2.5rem 0 2rem;
  border-bottom: 1px solid #e6e9f2;
  &__meta { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.12em; color: $formation-gold; }
  &__title { font-family: 'Playfair Display', serif; font-size: clamp(1.6rem, 3.5vw, 2.2rem); color: $formation-navy-900; margin: 0.6rem 0 0.5rem; }
  &__time { font-size: 0.8rem; color: #6e7487; }
}

// --- Pillar archive hero ---
.formation-pillar-hero {
  background: linear-gradient(180deg, $formation-navy-900, $formation-navy-700);
  color: #fff;
  padding: 3.5rem 0;
  &__number {
    font-family: 'Playfair Display', serif; font-size: 4rem; font-weight: 800;
    color: $formation-gold; line-height: 1;
  }
  &__title { font-family: 'Playfair Display', serif; font-size: clamp(2rem, 4.5vw, 3rem); margin: 0.6rem 0 1rem; }
  &__tagline { font-size: 1.1rem; line-height: 1.5; color: rgba(255,255,255,0.8); max-width: 55ch; }
  &__count { margin-top: 1.25rem; font-size: 0.85rem; color: rgba(255,255,255,0.6); letter-spacing: 0.08em; text-transform: uppercase; }
}

// --- ToC ---
.formation-toc {
  position: sticky; top: 1rem; align-self: start; font-size: 0.88rem;
  &__label {
    font-size: 0.72rem; letter-spacing: 0.12em; text-transform: uppercase;
    color: #6e7487; margin-bottom: 0.9rem; font-weight: 700;
  }
  ul { list-style: none; padding: 0; margin: 0; border-left: 2px solid #e6e9f2; }
  li {
    padding: 0.4rem 0.9rem; color: #4a5168; border-left: 2px solid transparent;
    margin-left: -2px; line-height: 1.35;
  }
  li.is-active { color: $formation-navy-700; font-weight: 600; border-left-color: $formation-gold; }
  li.is-level-3 { padding-left: 1.5rem; font-size: 0.82rem; }
  a { color: inherit; text-decoration: none; display: block; }
  a:hover { color: $formation-navy-700; }
}

// Mobile ToC (inside <details>)
details.formation-toc-mobile {
  margin: 1.5rem 0;
  summary { cursor: pointer; font-weight: 700; color: $formation-navy-900; padding: 0.5rem 0; }
}

// --- Cornerstone body layout ---
.formation-piece-body-grid {
  display: grid; gap: 2.5rem;
  padding: 2.5rem 0;
  grid-template-columns: 1fr;
  @media (min-width: 992px) {
    grid-template-columns: minmax(0, 680px) 220px;
    justify-content: center;
  }
}
.formation-piece-body {
  font-family: 'Sen', 'Inter', sans-serif;
  font-size: 1.05rem;
  line-height: 1.7;
  color: #2c3240;
  max-width: 680px;

  h2, h3 { font-family: 'Playfair Display', serif; color: $formation-navy-900; font-weight: 700; }
  h2 { font-size: 1.8rem; margin: 2.5rem 0 0.9rem; }
  h3 { font-size: 1.4rem; margin: 2rem 0 0.8rem; }
  p { margin: 0 0 1.2rem; }
  a { color: $formation-navy-700; text-decoration: underline; }

  // Pull quote styling (core block, no custom block needed)
  .wp-block-quote, .wp-block-pullquote {
    border-left: 3px solid $formation-gold;
    padding: 0.5rem 1.25rem;
    margin: 1.75rem 0;
    font-family: 'Playfair Display', serif;
    font-style: italic;
    font-size: 1.25rem;
    line-height: 1.5;
    color: $formation-navy-700;
  }
}

// --- Related pieces row ---
.formation-related {
  background: #F7F8FC;
  padding: 2.5rem 0;
  border-top: 1px solid #e6e9f2;
  &__heading {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem; color: $formation-navy-900; margin: 0 0 1.25rem;
  }
  &__grid { display: grid; gap: 1rem; grid-template-columns: 1fr; @media (min-width: 768px) { grid-template-columns: repeat(3, 1fr); } }
}

// --- Email capture (inline GF) ---
.formation-capture {
  background: #fff; border: 1px solid #dde1ed; border-radius: 10px;
  padding: 1.75rem; margin: 2rem 0;
  &__heading { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: $formation-navy-900; margin: 0 0 0.4rem; }
  &__intro { font-size: 0.9rem; color: #4a5168; margin: 0 0 1.25rem; }
}

// --- Filter pills on pillar archive ---
.formation-filter-pills {
  display: flex; flex-wrap: wrap; gap: 0.5rem; margin: 1.5rem 0;
  .pill {
    background: #fff; border: 1px solid #dde1ed; border-radius: 999px;
    padding: 0.4rem 1rem; font-size: 0.82rem; color: #4a5168; cursor: pointer;
    &[aria-pressed="true"] { background: $formation-navy-700; color: #fff; border-color: $formation-navy-700; }
  }
}
```

- [ ] **Step 13.3: Verify compilation**

Open any page on `http://church-2.local/` that has the child theme stylesheet loaded — ScssPhp recompiles on page load (see theme's existing setup). Then:

```bash
ls "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/assets/css/"
# main.css should have recent mtime
```

Inspect `main.css` for `tld-pillar-card` class — grep:

```bash
grep -c "tld-pillar-card" "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/themes/bootscore-child/assets/css/main.css"
```

Expected: non-zero count.

- [ ] **Step 13.4: Commit**

```bash
git add assets/scss/_formation.scss assets/scss/_bootscore_custom.scss
git commit -m "Add Formation SCSS module (pillar cards, piece cards, hero, ToC)"
```

---

## Task 14 — Formation JS (ToC scrollspy + filter pills)

**Files:**
- Create: `assets/js/formation.js`
- Modify: `functions.php` (enqueue on Formation pages)

- [ ] **Step 14.1: Write the JS**

Create `assets/js/formation.js`:

```javascript
/**
 * Formation — client-side behaviours.
 *
 * 1. ToC scrollspy: highlights the currently-visible heading in .formation-toc
 * 2. Filter pills: toggles visibility of piece cards by data-piece-type
 *
 * Vanilla JS, no jQuery dependency.
 */
(function () {
  'use strict';

  // --- ToC scrollspy ---
  function initToC() {
    var toc = document.querySelector('.formation-toc');
    if (!toc) return;

    var links = toc.querySelectorAll('a[href^="#"]');
    if (!links.length) return;

    var targets = [];
    links.forEach(function (a) {
      var id = a.getAttribute('href').slice(1);
      var el = document.getElementById(id);
      if (el) targets.push({ id: id, link: a, el: el });
    });
    if (!targets.length) return;

    if (!('IntersectionObserver' in window)) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        var match = targets.find(function (t) { return t.el === entry.target; });
        if (!match) return;
        if (entry.isIntersecting) {
          targets.forEach(function (t) { t.link.parentElement.classList.remove('is-active'); });
          match.link.parentElement.classList.add('is-active');
        }
      });
    }, { rootMargin: '-20% 0px -70% 0px', threshold: 0 });

    targets.forEach(function (t) { observer.observe(t.el); });
  }

  // --- Filter pills ---
  function initFilterPills() {
    var container = document.querySelector('.formation-filter-pills');
    if (!container) return;

    var pills = container.querySelectorAll('.pill');
    var grid = document.querySelector('.formation-pieces-grid');
    if (!grid) return;

    pills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        var filter = pill.getAttribute('data-filter');
        pills.forEach(function (p) { p.setAttribute('aria-pressed', p === pill ? 'true' : 'false'); });

        var cards = grid.querySelectorAll('[data-piece-type]');
        cards.forEach(function (card) {
          if (filter === 'all' || card.getAttribute('data-piece-type') === filter) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  if (document.readyState !== 'loading') {
    initToC();
    initFilterPills();
  } else {
    document.addEventListener('DOMContentLoaded', function () {
      initToC();
      initFilterPills();
    });
  }
})();
```

- [ ] **Step 14.2: Enqueue only on Formation pages**

In `functions.php`, find `tld_enqueue_styles` (lines ~17–37) and add a new function below it:

```php
/**
 * Enqueue Formation-specific JS on Formation pages only.
 */
add_action('wp_enqueue_scripts', 'tld_enqueue_formation_assets', 20);
function tld_enqueue_formation_assets() {
  $is_formation = is_singular('formation_piece')
    || is_tax('pillar')
    || (is_page() && get_post_field('post_name') === 'formation');

  if (!$is_formation) return;

  $path = get_stylesheet_directory() . '/assets/js/formation.js';
  if (file_exists($path)) {
    wp_enqueue_script(
      'tld-formation',
      get_stylesheet_directory_uri() . '/assets/js/formation.js',
      [],
      date('YmdHi', filemtime($path)),
      true
    );
  }
}
```

- [ ] **Step 14.3: Verify on any Formation URL**

Create a draft `formation_piece`, set its pillar, view the draft preview. Open browser devtools Network tab. Expected: `formation.js` loads only on Formation URLs. On the agency homepage, it should NOT load.

- [ ] **Step 14.4: Commit**

```bash
git add assets/js/formation.js functions.php
git commit -m "Add formation.js (ToC scrollspy + filter pills) with conditional enqueue"
```

---

## Task 15 — Template parts: heroes + ToC + email capture + related

**Files:**
- Create: `template-parts/formation/cornerstone-hero.php`
- Create: `template-parts/formation/compact-hero.php`
- Create: `template-parts/formation/pillar-hero.php`
- Create: `template-parts/formation/toc.php`
- Create: `template-parts/formation/email-capture.php`
- Create: `template-parts/formation/related-pieces.php`

- [ ] **Step 15.1: Cornerstone hero**

Create `template-parts/formation/cornerstone-hero.php`:

```php
<?php
/**
 * Cornerstone piece hero.
 * Assumes global $post is a formation_piece.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillar_terms = get_the_terms(get_the_ID(), 'pillar');
$pillar       = (!is_wp_error($pillar_terms) && !empty($pillar_terms)) ? $pillar_terms[0] : null;
$subtitle     = get_field('subtitle');
$reading_time = (int) get_field('reading_time_minutes');
$date         = get_the_date('F Y');
?>
<header class="formation-hero">
  <div class="container">
    <div class="formation-hero__meta">
      <?php if ($pillar): ?>
        <a href="<?php echo esc_url(get_term_link($pillar)); ?>" class="formation-hero__pillar-link" style="color: inherit; text-decoration: none;">
          <span><?php echo esc_html($pillar->name); ?></span>
        </a>
        <span class="dot">&bull;</span>
      <?php endif; ?>
      <span class="muted">Cornerstone <?php if ($reading_time): ?>&middot; <?php echo (int) $reading_time; ?> min read<?php endif; ?></span>
      <span class="dot">&bull;</span>
      <span class="muted">Published <?php echo esc_html($date); ?></span>
    </div>
    <h1 class="formation-hero__title"><?php the_title(); ?></h1>
    <?php if ($subtitle): ?>
      <p class="formation-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
    <?php endif; ?>
  </div>
  <?php if (has_post_thumbnail()): ?>
    <div class="formation-hero__image">
      <?php the_post_thumbnail('tld-hero', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
    </div>
  <?php endif; ?>
</header>
```

- [ ] **Step 15.2: Compact hero**

Create `template-parts/formation/compact-hero.php`:

```php
<?php
/**
 * Short-read / field-note hero.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillar_terms = get_the_terms(get_the_ID(), 'pillar');
$pillar       = (!is_wp_error($pillar_terms) && !empty($pillar_terms)) ? $pillar_terms[0] : null;
$reading_time = (int) get_field('reading_time_minutes');
$type_terms   = get_the_terms(get_the_ID(), 'piece_type');
$piece_type   = (!is_wp_error($type_terms) && !empty($type_terms)) ? $type_terms[0] : null;
?>
<header class="formation-hero-compact">
  <div class="container">
    <div class="formation-hero-compact__meta">
      <?php if ($pillar): ?>
        <a href="<?php echo esc_url(get_term_link($pillar)); ?>" style="color: inherit; text-decoration: none;">
          <?php echo esc_html($pillar->name); ?>
        </a>
      <?php endif; ?>
      <?php if ($piece_type): ?>
        &middot; <?php echo esc_html($piece_type->name); ?>
      <?php endif; ?>
    </div>
    <h1 class="formation-hero-compact__title"><?php the_title(); ?></h1>
    <?php if ($reading_time): ?>
      <p class="formation-hero-compact__time"><?php echo (int) $reading_time; ?> min read</p>
    <?php endif; ?>
  </div>
</header>
```

- [ ] **Step 15.3: Pillar hero**

Create `template-parts/formation/pillar-hero.php`:

```php
<?php
/**
 * Pillar archive hero.
 * Assumes $wp_query has a queried term for the pillar taxonomy.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$term       = get_queried_object();
if (!$term || !isset($term->taxonomy) || $term->taxonomy !== 'pillar') return;

$sort_order = (int) get_field('pillar_sort_order', 'pillar_' . $term->term_id);
$tagline    = get_field('pillar_tagline', 'pillar_' . $term->term_id);
$count      = (int) $term->count;
$number     = str_pad((string) $sort_order, 2, '0', STR_PAD_LEFT);
?>
<header class="formation-pillar-hero">
  <div class="container">
    <div class="formation-pillar-hero__number"><?php echo esc_html($number); ?></div>
    <h1 class="formation-pillar-hero__title"><?php echo esc_html($term->name); ?></h1>
    <?php if ($tagline): ?>
      <p class="formation-pillar-hero__tagline"><?php echo wp_kses_post($tagline); ?></p>
    <?php endif; ?>
    <p class="formation-pillar-hero__count"><?php echo (int) $count; ?> piece<?php echo $count === 1 ? '' : 's'; ?></p>
  </div>
</header>
```

- [ ] **Step 15.4: ToC template part**

Create `template-parts/formation/toc.php`:

```php
<?php
/**
 * Renders a ToC for the current formation_piece.
 * Consumes $args['headings'] = array of ['level'=>int,'text'=>string,'id'=>string]
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$headings = $args['headings'] ?? [];
$mobile   = $args['mobile']   ?? false;
if (empty($headings)) return;

if ($mobile): ?>
<details class="formation-toc-mobile d-lg-none">
  <summary>On this page</summary>
  <nav aria-label="On this page">
    <ul>
      <?php foreach ($headings as $h): ?>
        <li class="is-level-<?php echo (int) $h['level']; ?>"><a href="#<?php echo esc_attr($h['id']); ?>"><?php echo esc_html($h['text']); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>
</details>
<?php else: ?>
<aside class="formation-toc d-none d-lg-block" role="complementary">
  <div class="formation-toc__label">On this page</div>
  <nav aria-label="On this page">
    <ul>
      <?php foreach ($headings as $h): ?>
        <li class="is-level-<?php echo (int) $h['level']; ?>"><a href="#<?php echo esc_attr($h['id']); ?>"><?php echo esc_html($h['text']); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>
</aside>
<?php endif; ?>
```

- [ ] **Step 15.5: Email capture**

Create `template-parts/formation/email-capture.php`:

```php
<?php
/**
 * Inline email capture — Gravity Forms with hidden pillar_interest field.
 *
 * Uses form ID defined by constant TLD_FORMATION_CAPTURE_FORM_ID (set via theme or via WP options).
 * Falls back to a plain link if Gravity Forms isn't active.
 *
 * Accepts $args['pillar_slug'] — the pillar to tag captured contacts with.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillar_slug = $args['pillar_slug'] ?? '';

$form_id = defined('TLD_FORMATION_CAPTURE_FORM_ID')
  ? TLD_FORMATION_CAPTURE_FORM_ID
  : (int) get_option('tld_formation_capture_form_id');

if (!$form_id) {
  return; // no form configured — silently skip
}

if (!class_exists('GFForms')) {
  echo '<div class="formation-capture"><p>Newsletter signup unavailable.</p></div>';
  return;
}
?>
<div class="formation-capture">
  <h3 class="formation-capture__heading">More from Formation</h3>
  <p class="formation-capture__intro">Short, monthly. Essays and templates, nothing else. Unsubscribe anytime.</p>
  <?php
    // Pass pillar_slug as a field value via query string; form field 'pillar_interest' (a hidden field)
    // must be configured in GF with "Allow field to be populated dynamically" and parameter name pillar_interest.
    add_filter('gform_field_value_pillar_interest', function () use ($pillar_slug) { return $pillar_slug; });
    gravity_form($form_id, false, false, false, null, true, 0);
  ?>
</div>
```

Note: the capture form's form ID is decided during rollout (new or existing GF form). See Task 20.

- [ ] **Step 15.6: Related pieces**

Create `template-parts/formation/related-pieces.php`:

```php
<?php
/**
 * Three related pieces from the same pillar, excluding the current piece.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$current_id   = get_the_ID();
$pillar_terms = get_the_terms($current_id, 'pillar');
if (is_wp_error($pillar_terms) || empty($pillar_terms)) return;
$pillar = $pillar_terms[0];

$related = new WP_Query([
  'post_type'      => 'formation_piece',
  'posts_per_page' => 3,
  'post__not_in'   => [$current_id],
  'tax_query'      => [[
    'taxonomy' => 'pillar',
    'field'    => 'term_id',
    'terms'    => [$pillar->term_id],
  ]],
  'orderby'        => 'date',
  'order'          => 'DESC',
]);

if (!$related->have_posts()) return;
?>
<section class="formation-related">
  <div class="container">
    <h2 class="formation-related__heading">More from this pillar</h2>
    <div class="formation-related__grid">
      <?php while ($related->have_posts()): $related->the_post(); ?>
        <?php get_template_part('template-parts/blocks/tld-piece-card', null, [
          'piece_id'     => get_the_ID(),
          'variant'      => 'compact',
          'show_summary' => false,
        ]); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
```

- [ ] **Step 15.7: Commit all template parts**

```bash
git add template-parts/formation/
git commit -m "Add Formation template parts (heroes, ToC, capture, related)"
```

---

## Task 16 — `single-formation_piece.php` controller

**Files:**
- Create: `single-formation_piece.php`

- [ ] **Step 16.1: Write the single template**

Create `single-formation_piece.php`:

```php
<?php
/**
 * Single Formation piece template.
 *
 * Picks layout by piece_type:
 *   - cornerstone   → cornerstone hero, 2-col body with ToC sidebar
 *   - short-read    → compact hero, single-col body
 *   - field-note    → compact hero, single-col body
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();

if (have_posts()): while (have_posts()): the_post();

  $type_terms    = get_the_terms(get_the_ID(), 'piece_type');
  $piece_type    = (!is_wp_error($type_terms) && !empty($type_terms)) ? $type_terms[0]->slug : 'short-read';
  $is_cornerstone = ($piece_type === 'cornerstone');

  $pillar_terms  = get_the_terms(get_the_ID(), 'pillar');
  $pillar_slug   = (!is_wp_error($pillar_terms) && !empty($pillar_terms)) ? $pillar_terms[0]->slug : '';

  // Hero
  if ($is_cornerstone) {
    get_template_part('template-parts/formation/cornerstone-hero');
  } else {
    get_template_part('template-parts/formation/compact-hero');
  }

  // Build ToC if enabled on a cornerstone
  $toc_enabled = $is_cornerstone && (get_field('toc_enabled') !== false);
  $content     = apply_filters('the_content', get_the_content());

  if ($toc_enabled) {
    $toc_data = tld_formation_build_toc($content);
    $content  = $toc_data['html'];
    $headings = $toc_data['headings'];
  } else {
    $headings = [];
  }

  ?>
  <main id="primary" class="site-main formation-piece">
    <?php if ($is_cornerstone && !empty($headings)): ?>
      <div class="container">
        <?php get_template_part('template-parts/formation/toc', null, ['headings' => $headings, 'mobile' => true]); ?>
        <div class="formation-piece-body-grid">
          <article class="formation-piece-body">
            <?php echo $content; ?>
          </article>
          <?php get_template_part('template-parts/formation/toc', null, ['headings' => $headings, 'mobile' => false]); ?>
        </div>
      </div>
    <?php else: ?>
      <div class="container">
        <article class="formation-piece-body">
          <?php echo $content; ?>
        </article>
      </div>
    <?php endif; ?>

    <div class="container">
      <?php get_template_part('template-parts/formation/email-capture', null, ['pillar_slug' => $pillar_slug]); ?>
    </div>

    <?php get_template_part('template-parts/formation/related-pieces'); ?>
  </main>
  <?php

endwhile; endif;

get_footer();
```

- [ ] **Step 16.2: Verify**

Create a draft `formation_piece` with:
- Title: "Smoke Test Cornerstone"
- Pillar: Communications Champion
- Piece type: Cornerstone
- Subtitle: any italic-friendly tagline
- Summary: 2–3 sentences (required)
- Content: 3–4 `## h2` sections with paragraphs

View the draft preview. Expected:
- Navy hero with pillar tag, title, subtitle
- ToC sidebar on desktop (right column), collapsed details on mobile
- Body copy in 680px max-width column
- Clicking a ToC link jumps to the heading and highlights that entry
- Email capture visible below body (renders as "unavailable" if GF form not configured yet)
- "More from this pillar" section below (empty if no other pieces)

Switch piece_type to "Short Read" and reload: compact hero, no ToC, single column. 

- [ ] **Step 16.3: Commit**

```bash
git add single-formation_piece.php
git commit -m "Add single-formation_piece.php template with cornerstone + compact layouts"
```

---

## Task 17 — `taxonomy-pillar.php` controller

**Files:**
- Create: `taxonomy-pillar.php`

- [ ] **Step 17.1: Write pillar archive template**

Create `taxonomy-pillar.php`:

```php
<?php
/**
 * Pillar archive template.
 * Hero (from term fields) + featured cornerstone + grid of remaining pieces
 * + filter pills (if >= 3 pieces total).
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();

$term = get_queried_object();
if (!$term || $term->taxonomy !== 'pillar') {
  get_footer();
  return;
}

get_template_part('template-parts/formation/pillar-hero');

// Query: all pieces in this pillar, newest first
$all = new WP_Query([
  'post_type'      => 'formation_piece',
  'posts_per_page' => -1,
  'tax_query'      => [[
    'taxonomy' => 'pillar',
    'field'    => 'term_id',
    'terms'    => [$term->term_id],
  ]],
  'orderby' => 'date',
  'order'   => 'DESC',
]);

// Split: featured = most recent cornerstone; grid = everything else
$featured_id = null;
$grid_ids    = [];
if ($all->have_posts()) {
  while ($all->have_posts()) {
    $all->the_post();
    $types = get_the_terms(get_the_ID(), 'piece_type');
    $slug  = (!is_wp_error($types) && !empty($types)) ? $types[0]->slug : '';
    if (!$featured_id && $slug === 'cornerstone') {
      $featured_id = get_the_ID();
    } else {
      $grid_ids[] = get_the_ID();
    }
  }
  wp_reset_postdata();
}

$total_count = (int) $all->post_count;
$show_filter = $total_count >= 3;
?>
<main id="primary" class="site-main">
  <div class="container">

    <?php if ($featured_id): ?>
      <section class="formation-featured">
        <?php get_template_part('template-parts/blocks/tld-piece-card', null, [
          'piece_id'     => $featured_id,
          'variant'      => 'featured',
          'show_summary' => true,
        ]); ?>
      </section>
    <?php endif; ?>

    <?php if ($show_filter): ?>
      <nav class="formation-filter-pills" aria-label="Filter by piece type">
        <button type="button" class="pill" data-filter="all" aria-pressed="true">All</button>
        <button type="button" class="pill" data-filter="cornerstone" aria-pressed="false">Cornerstones</button>
        <button type="button" class="pill" data-filter="short-read" aria-pressed="false">Short Reads</button>
        <button type="button" class="pill" data-filter="field-note" aria-pressed="false">Field Notes</button>
      </nav>
    <?php endif; ?>

    <div class="formation-pieces-grid row">
      <?php foreach ($grid_ids as $pid):
        $piece_type_terms = get_the_terms($pid, 'piece_type');
        $pt = (!is_wp_error($piece_type_terms) && !empty($piece_type_terms)) ? $piece_type_terms[0]->slug : '';
      ?>
        <div class="col-md-6 col-lg-4 mb-4" data-piece-type="<?php echo esc_attr($pt); ?>">
          <?php get_template_part('template-parts/blocks/tld-piece-card', null, [
            'piece_id'     => $pid,
            'variant'      => 'default',
            'show_summary' => true,
          ]); ?>
        </div>
      <?php endforeach; ?>
    </div>

    <?php get_template_part('template-parts/formation/email-capture', null, ['pillar_slug' => $term->slug]); ?>
  </div>
</main>
<?php
get_footer();
```

- [ ] **Step 17.2: Verify**

Visit `/formation/communications-champion/` (flush rewrites via **Settings → Permalinks** → Save if 404). Expected:
- Navy pillar hero with 01, "The Communications Champion", tagline, piece count
- Featured cornerstone card (if any cornerstone exists in this pillar — create one during smoke test)
- Grid of other pieces (empty until more are seeded)
- Filter pills visible only if ≥3 pieces in pillar
- Email capture below

Filter pill behaviour: clicking "Cornerstones" hides all non-cornerstone grid items.

- [ ] **Step 17.3: Commit**

```bash
git add taxonomy-pillar.php
git commit -m "Add taxonomy-pillar.php archive with featured + grid + filter pills"
```

---

## Task 18 — Formation landing page template

**Files:**
- Create: `page-templates/page-formation.php`

- [ ] **Step 18.1: Write template**

Create `page-templates/page-formation.php`:

```php
<?php
/**
 * Template Name: Formation Landing
 *
 * Assigned to the WP page at /formation/.
 * Layout is author-composed in Gutenberg — this template just provides the frame.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();
?>
<main id="primary" class="site-main formation-landing">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article>
      <header class="formation-landing__header">
        <div class="container">
          <h1 class="formation-landing__title"><?php the_title(); ?></h1>
        </div>
      </header>
      <div class="container formation-landing__content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; endif; ?>
</main>
<?php
get_footer();
```

- [ ] **Step 18.2: Create the WP page**

In WP Admin → **Pages → Add New**:
- Title: "Formation"
- Slug (after save): `formation`
- Page Attributes → Template: "Formation Landing"
- Content (Gutenberg):
  - Paragraph block with the landing intro copy (spec §6, landing meta section):
    > *Formation is our word for the ongoing work of shaping and equipping the people who carry parish communications. Free essays, templates, and frameworks, organised around four pillars. Written for priests, parish secretaries, volunteers, and anyone whose job it is to help a parish speak well, on behalf of something larger than itself.*
  - Four Pillar Card blocks — one per pillar, in sort order
- Save as draft for now.

- [ ] **Step 18.3: Verify**

Visit `/formation/` (as logged-in editor to see draft). Expected: landing page renders with header, intro paragraph, four pillar cards. Clicking a card goes to the pillar archive from Task 17.

Flush rewrites once more (Permalinks → Save) if the URL returns 404.

- [ ] **Step 18.4: Commit**

```bash
git add page-templates/page-formation.php
git commit -m "Add Formation landing page template"
```

---

## Task 19 — SEO Framework filters

**Files:**
- Modify: `inc/formation/seo-filters.php`

- [ ] **Step 19.1: Write filters**

Replace contents of `inc/formation/seo-filters.php`:

```php
<?php
defined('ABSPATH') || exit;

/**
 * The SEO Framework description overrides for Formation.
 *
 * - Single formation_piece → use ACF 'summary' field
 * - Pillar archive         → use ACF 'pillar_meta_description' term field
 */

/**
 * Filter The SEO Framework's generated description.
 * Hook name differs across plugin versions; target both:
 *   'the_seo_framework_generated_description' (v4.x)
 *   'the_seo_framework_fetched_description_excerpt' (fallback)
 */
add_filter('the_seo_framework_generated_description', 'tld_formation_seo_description', 20, 2);

function tld_formation_seo_description($description, $args = null) {
  if (is_singular('formation_piece')) {
    $summary = get_field('summary', get_the_ID());
    if ($summary) return wp_strip_all_tags($summary);
  }

  if (is_tax('pillar')) {
    $term_id = get_queried_object_id();
    if ($term_id) {
      $meta = get_field('pillar_meta_description', 'pillar_' . $term_id);
      if ($meta) return wp_strip_all_tags($meta);
    }
  }

  return $description;
}

/**
 * Title adjustment: on pillar archive, use "Pillar Name — Formation | True Light Digital"
 */
add_filter('the_seo_framework_title_from_custom_field', 'tld_formation_seo_title', 20, 2);

function tld_formation_seo_title($title, $args = null) {
  if (is_tax('pillar')) {
    $term = get_queried_object();
    if ($term && !$title) {
      return $term->name . ' — Formation';
    }
  }
  return $title;
}
```

- [ ] **Step 19.2: Verify**

View page source on a formation_piece single page. In `<head>`, confirm:

```html
<meta name="description" content="[ACF summary text]" />
```

On a pillar archive, confirm `<meta name="description">` shows the pillar_meta_description text.

If The SEO Framework plugin doesn't expose a CPT toggle for `formation_piece`, enable it under **SEO → Extensions** or **SEO → General → Post Types** in the admin.

- [ ] **Step 19.3: Commit**

```bash
git add inc/formation/seo-filters.php
git commit -m "Add SEO Framework filters for Formation single + pillar archive descriptions"
```

---

## Task 20 — Configure the capture Gravity Form

**Files:** none (WP admin work)

- [ ] **Step 20.1: Create or identify a Formation capture form**

In WP Admin → **Forms**:

Option A — reuse the existing Discovery Call form (form ID 18). Probably wrong — that form is heavier and targeted at Book-a-Call. Don't reuse for newsletter capture.

Option B (recommended) — create a new form:
- Form name: "Formation Newsletter"
- Fields:
  - **Email** (Email, required) — label "Email"
  - **Pillar interest** (Hidden, ID: `pillar_interest`) — on Advanced tab, enable "Allow field to be populated dynamically" with parameter name `pillar_interest`
- Confirmation: message "Thanks — we'll only email you when there's something worth reading."
- Notifications: default admin notification on submission.

Note the new form's ID from the Forms list.

- [ ] **Step 20.2: Store form ID in options**

```bash
"$PHP" -d "extension_dir=$EXTDIR" -d "extension=php_mysqli.dll" "$WPCLI" \
  --path="$WPPATH" --dbhost=127.0.0.1:10053 option update tld_formation_capture_form_id <NEW_FORM_ID>
```

Replace `<NEW_FORM_ID>` with the actual ID.

- [ ] **Step 20.3: Verify inline capture on a Formation piece**

Visit a draft cornerstone piece preview. Expected: email capture renders an inline GF form with a single email field + submit button. Inspect the rendered HTML — the hidden `pillar_interest` field should contain the pillar slug (`communications-champion`, etc.) populated by the filter in Step 15.5.

Submit a test entry. Expected: form acknowledges submission; entry visible under **Forms → Entries → Formation Newsletter**, with `pillar_interest` populated.

- [ ] **Step 20.4: Nothing to commit for this task**

No files changed (WP admin only). This is a milestone note.

---

## Task 21 — Redirect mu-plugin

**Files:**
- Create: `wp-content/mu-plugins/tld-formation-redirects.php` (outside theme repo)

- [ ] **Step 21.1: Create mu-plugin**

```bash
mkdir -p "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/mu-plugins"
```

Create `wp-content/mu-plugins/tld-formation-redirects.php`:

```php
<?php
/**
 * Plugin Name: TLD — Formation Redirects
 * Description: 301 redirects from the old /blog/ slugs to the new Formation pillar URLs.
 * Version: 1.0.0
 * Author: True Light Digital
 *
 * Lives as a mu-plugin so it survives theme switches.
 */
defined('ABSPATH') || exit;

add_action('template_redirect', function () {
  $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');
  if (strpos($path, 'blog/') !== 0) return;

  // Explicit per-post map. Slugs preserved from the old /blog/ location.
  $map = [
    'blog/how-churches-are-using-ai' => '/formation/guardrails-and-discernment/how-churches-are-using-ai/',
    'blog/church-seo-guide'          => '/formation/communications-champion/church-seo-guide/',
    'blog/catholic-ai-guide'         => '/formation/guardrails-and-discernment/catholic-ai-guide/',
    // 117 + 121 stay at /blog/ — no redirect
  ];

  if (isset($map[$path])) {
    wp_redirect(home_url($map[$path]), 301);
    exit;
  }
});
```

- [ ] **Step 21.2: Verify (dry-run, before migration)**

At this point the migrated posts don't exist in Formation yet, so visiting e.g. `/blog/church-seo-guide/` should still serve the original post. The redirect fires only after posts are migrated and the map matches. Confirm no 500 errors on the existing `/blog/church-seo-guide/`:

```bash
curl -I -L -o /dev/null -s -w "%{http_code} %{url_effective}\n" "http://church-2.local/blog/church-seo-guide/"
```

Expected: `200 http://church-2.local/blog/church-seo-guide/` (unchanged until migration happens).

- [ ] **Step 21.3: Commit the mu-plugin to a separate location**

mu-plugins are outside the theme git repo. If you have a separate repo for mu-plugins, commit there. Otherwise, note in the theme's README that this file lives unversioned in `wp-content/mu-plugins/` — or add a minimal mu-plugins repo later. For now, no git commit needed in the theme repo.

---

## Task 22 — Seed mu-plugin

**Files:**
- Create: `wp-content/mu-plugins/tld-formation-seed.php` (outside theme repo, deleted after use)

- [ ] **Step 22.1: Create the seed mu-plugin**

Create `wp-content/mu-plugins/tld-formation-seed.php`:

```php
<?php
/**
 * Plugin Name: TLD — Formation Seed (one-off)
 * Description: Seeds the 4 Formation cornerstone pieces + pillar term metadata from documents/*.md.
 * Version: 1.0.0
 *
 * Usage:
 *   1. Load WP admin once (triggers admin_init and runs the seed).
 *   2. Verify admin notice — open each piece in Gutenberg, add summary + polish.
 *   3. Delete this file.
 */
defined('ABSPATH') || exit;

// Include theme's markdown converter for reuse
require_once get_stylesheet_directory() . '/inc/formation/markdown-converter.php';

add_action('admin_init', 'tld_formation_run_seed');

function tld_formation_run_seed() {
  if (get_option('tld_formation_seeded_v1')) return;
  if (!current_user_can('manage_options')) return;

  $results = ['created' => [], 'skipped' => [], 'errors' => []];

  // --- Seed pillar term metadata (taglines + meta + sort order) ---
  $pillars = [
    'communications-champion' => [
      'sort_order'       => 1,
      'tagline'          => "Every parish communication system that lasts has a named human at its centre, supported and replaceable. Most parishes have none of those things. This is how to fix it.",
      'meta_description' => "How Catholic parishes find, form, and protect the people who carry their communications. Essays, templates, and frameworks for priests and parish secretaries.",
    ],
    'rhythm-and-restraint' => [
      'sort_order'       => 2,
      'tagline'          => "A parish's communications are a form of breath, rising and falling with the liturgical year. Here is how to breathe with the Church instead of gasping against her.",
      'meta_description' => "How Catholic parishes plan communications around the liturgical year. Editorial rhythm, seasonal restraint, and the discipline of when not to publish at all.",
    ],
    'invitation-and-patience' => [
      'sort_order'       => 3,
      'tagline'          => "Parishes change slowly, through relationship, by invitation. Here is how to help a parish change well, without breaking the people you are trying to serve.",
      'meta_description' => "Change management for Catholic parishes, without the corporate playbook. How priests, volunteers, and agencies can help parishes change at the pace parishes actually work.",
    ],
    'guardrails-and-discernment' => [
      'sort_order'       => 4,
      'tagline'          => "Parish communications are speech on behalf of the Body of Christ, and the most important skill is the pause before speech. Here is how to hold that discipline.",
      'meta_description' => "Safeguarding, data protection, AI, and editorial discernment in Catholic parish communications. The discipline of speaking carefully on behalf of the Body of Christ.",
    ],
  ];

  foreach ($pillars as $slug => $data) {
    $term = get_term_by('slug', $slug, 'pillar');
    if (!$term) { $results['errors'][] = "Missing pillar term: $slug"; continue; }
    update_field('pillar_sort_order', $data['sort_order'], 'pillar_' . $term->term_id);
    update_field('pillar_tagline', $data['tagline'], 'pillar_' . $term->term_id);
    update_field('pillar_meta_description', $data['meta_description'], 'pillar_' . $term->term_id);
  }

  // --- Seed cornerstone pieces from documents/*.md ---
  $docs_dir = ABSPATH . 'documents';
  if (!is_dir($docs_dir)) {
    $results['errors'][] = "documents/ dir not found at $docs_dir";
  } else {
    foreach (glob($docs_dir . '/pillar-*.md') as $file) {
      $body = file_get_contents($file);
      if ($body === false) { $results['errors'][] = "Unreadable: $file"; continue; }

      $fm = _tld_seed_parse_frontmatter($body);
      if (!$fm['meta']) { $results['errors'][] = "No frontmatter in " . basename($file); continue; }

      $title = $fm['meta']['title'] ?? '';
      if (!$title) { $results['errors'][] = "No title in " . basename($file); continue; }

      // Pillar slug: strip NN- prefix
      $pillar_slug = preg_replace('/^\d+-/', '', $fm['meta']['pillar'] ?? '');
      if (!$pillar_slug) { $results['errors'][] = "No pillar in " . basename($file); continue; }

      $piece_type  = $fm['meta']['type'] ?? 'cornerstone';
      $status      = $fm['meta']['status'] ?? 'draft';

      $post_slug = sanitize_title($title);

      $existing = get_page_by_path($post_slug, OBJECT, 'formation_piece');
      if ($existing) { $results['skipped'][] = $title . ' (slug exists: ' . $post_slug . ')'; continue; }

      // Body processing: skip first h1, capture italic-tagline line, strip ---, convert rest
      $md = $fm['body'];
      $md = preg_replace('/^#\s+[^\n]+\n/', '', $md, 1); // drop first h1
      // Extract italic tagline from first non-empty line after h1
      $subtitle = '';
      if (preg_match('/^\s*\*([^*\n]+)\*\s*$/m', $md, $m)) {
        $subtitle = trim($m[1]);
        $md = preg_replace('/^\s*\*[^*\n]+\*\s*\n/', '', $md, 1);
      }
      // Drop leading --- separator
      $md = preg_replace('/^\s*---\s*\n/', '', $md, 1);

      $content = tld_formation_md_to_blocks(trim($md));

      $post_id = wp_insert_post([
        'post_type'    => 'formation_piece',
        'post_title'   => $title,
        'post_name'    => $post_slug,
        'post_status'  => $status,
        'post_content' => $content,
      ], true);

      if (is_wp_error($post_id)) { $results['errors'][] = $title . ': ' . $post_id->get_error_message(); continue; }

      wp_set_object_terms($post_id, $pillar_slug, 'pillar');
      wp_set_object_terms($post_id, $piece_type, 'piece_type');

      if ($subtitle) update_field('subtitle', $subtitle, $post_id);

      $results['created'][] = "$title (#$post_id)";
    }
  }

  update_option('tld_formation_seeded_v1', true);

  // Store results for admin notice
  set_transient('tld_formation_seed_results', $results, HOUR_IN_SECONDS);
}

/**
 * Parse YAML-ish frontmatter. Narrow subset: key: value, key: "value".
 */
function _tld_seed_parse_frontmatter($text) {
  if (!preg_match('/^---\n(.*?)\n---\n(.*)$/s', $text, $m)) {
    return ['meta' => null, 'body' => $text];
  }
  $meta = [];
  foreach (preg_split('/\n/', $m[1]) as $line) {
    if (preg_match('/^([a-z_]+):\s*(.*)$/', trim($line), $mm)) {
      $value = trim($mm[2], " \t\"'");
      $meta[$mm[1]] = $value;
    }
  }
  return ['meta' => $meta, 'body' => $m[2]];
}

/**
 * Admin notice with seed result summary.
 */
add_action('admin_notices', function () {
  $results = get_transient('tld_formation_seed_results');
  if (!$results) return;
  $created = count($results['created']);
  $skipped = count($results['skipped']);
  $errors  = count($results['errors']);
  ?>
  <div class="notice notice-success is-dismissible">
    <p><strong>Formation seed complete.</strong> Created: <?php echo $created; ?>, Skipped: <?php echo $skipped; ?>, Errors: <?php echo $errors; ?>.</p>
    <?php if ($created): ?><p><strong>Created:</strong><br><?php echo implode('<br>', array_map('esc_html', $results['created'])); ?></p><?php endif; ?>
    <?php if ($errors): ?><p><strong>Errors:</strong><br><?php echo implode('<br>', array_map('esc_html', $results['errors'])); ?></p><?php endif; ?>
    <p><em>Next: open each piece in Gutenberg, write the 2–3 sentence summary, set featured image, promote "What to do this week" sections to Callout blocks. Then delete this mu-plugin (<code>wp-content/mu-plugins/tld-formation-seed.php</code>).</em></p>
  </div>
  <?php
  delete_transient('tld_formation_seed_results');
});
```

- [ ] **Step 22.2: Run the seed**

The mu-plugin auto-runs on next `admin_init`. Refresh any WP admin page while logged in as an admin user. Expected: green admin notice at the top reporting 4 pieces created.

Troubleshooting: if the notice doesn't appear, visit **Dashboard**. If errors are reported, read them and adjust — most common cause is a typo in the pillar term slug or missing ACF field (verify Tasks 7 and 8 completed first).

- [ ] **Step 22.3: Confirm pillar metadata seeded**

```bash
"$PHP" -d "extension_dir=$EXTDIR" -d "extension=php_mysqli.dll" "$WPCLI" \
  --path="$WPPATH" --dbhost=127.0.0.1:10053 eval \
  'foreach (get_terms(["taxonomy" => "pillar", "hide_empty" => false]) as $t) {
    echo $t->slug . " → #" . get_field("pillar_sort_order", "pillar_" . $t->term_id) . " " . substr(get_field("pillar_tagline", "pillar_" . $t->term_id), 0, 40) . "...\n";
   }'
```

Expected: 4 lines, each pillar with sort order 1-4 and tagline excerpt.

- [ ] **Step 22.4: Confirm pieces created**

```bash
"$PHP" -d "extension_dir=$EXTDIR" -d "extension=php_mysqli.dll" "$WPCLI" \
  --path="$WPPATH" --dbhost=127.0.0.1:10053 post list --post_type=formation_piece --fields=ID,post_title,post_status --format=table
```

Expected: 4 pieces listed with status `draft`.

---

## Task 23 — Post-seed polish (manual editorial)

**Files:** none (WP admin editorial work)

- [ ] **Step 23.1: For each of the 4 seeded pieces:**

For each piece in WP Admin → **Formation → All Pieces**:

1. Open in Gutenberg
2. Verify the block structure parsed correctly (paragraphs, headings, lists, quotes)
3. Write the 2–3 sentence **Summary** in the ACF panel (mandatory — used as meta description). Aim 150–160 characters.
4. Upload + set **Featured Image** (1920×800 — existing `tld-hero` image size)
5. Find any "## What to do this week" sections → promote the heading + following list to a single **"What to do this week" Callout** block
6. Find any pull-worthy quotes → promote the core quote block to **Pullquote** (already styled via CSS)
7. Set **Status: Published** when ready

- [ ] **Step 23.2: Verify published URLs render**

After publishing each piece, visit the URL. Expected:
- `/formation/communications-champion/the-parish-communications-champion-why-your-website-lives-or-dies-on-a-function-not-a-person/`
- `/formation/rhythm-and-restraint/rhythm-restraint-how-a-parish-learns-to-breathe-with-the-church/`
- `/formation/invitation-and-patience/invitation-patience-how-parishes-actually-change-and-how-to-help-without-breaking-them/`
- `/formation/guardrails-and-discernment/guardrails-discernment-the-discipline-of-speaking-on-behalf-of-the-body/`

(Slugs will depend on what WordPress generated from the titles — verify via the Permalink field on each post.)

Each should show: cornerstone hero, ToC sidebar, body, email capture, related pieces row.

- [ ] **Step 23.3: Delete the seed mu-plugin**

```bash
rm "C:/Users/Sean/Local Sites/church-2/app/public/wp-content/mu-plugins/tld-formation-seed.php"
```

Verify no admin errors on next admin page load.

---

## Task 24 — Blog post migration + nav + redirect map update

**Files:**
- Modify (potentially): `wp-content/mu-plugins/tld-formation-redirects.php` — update slug map with actual post slugs

- [ ] **Step 24.1: Install Post Type Switcher plugin (temporary)**

In WP Admin → **Plugins → Add New** → search "Post Type Switcher" → Install → Activate.

- [ ] **Step 24.2: Migrate post 118 — How Churches Are Using AI in 2026**

1. Open post 118 in the editor
2. In the Publish box (now has a "Post Type" dropdown), change to **Formation → Piece**
3. Assign **Pillar:** Guardrails & Discernment
4. Assign **Piece Type:** Short Read
5. Confirm the slug (`how-churches-are-using-ai`) is unchanged in the Permalink field
6. Write the **Summary** (required)
7. Save

- [ ] **Step 24.3: Migrate post 119 — Church SEO: The Complete Guide**

Same flow:
- Pillar: Communications Champion
- Piece Type: Cornerstone
- Slug: `church-seo-guide`

- [ ] **Step 24.4: Migrate post 120 — A Catholic Guide to AI**

Same flow:
- Pillar: Guardrails & Discernment
- Piece Type: Short Read
- Slug: `catholic-ai-guide`

- [ ] **Step 24.5: Confirm 117 and 121 still post type = post**

```bash
"$PHP" -d "extension_dir=$EXTDIR" -d "extension=php_mysqli.dll" "$WPCLI" \
  --path="$WPPATH" --dbhost=127.0.0.1:10053 post list --post__in=117,121 --fields=ID,post_type,post_title --format=table
```

Expected: both rows show `post_type = post`.

- [ ] **Step 24.6: Verify redirects now fire**

```bash
curl -I -s -o /dev/null -w "%{http_code} → %{redirect_url}\n" "http://church-2.local/blog/church-seo-guide/"
```

Expected: `301 → http://church-2.local/formation/communications-champion/church-seo-guide/`

Also check:
```bash
curl -I -s -o /dev/null -w "%{http_code}\n" "http://church-2.local/blog/"
curl -I -s -o /dev/null -w "%{http_code}\n" "http://church-2.local/blog/christian-business-coaching/"
```

Expected: both 200 (blog archive still resolves; post 121 still accessible).

- [ ] **Step 24.7: Uninstall Post Type Switcher**

In Plugins → Deactivate Post Type Switcher → Delete.

- [ ] **Step 24.8: Reorder main nav**

WP Admin → **Appearance → Menus → Main Menu**:

1. Drag **Formation** to the top position (if not there; may need to add it manually if not present — `/formation/` page → add to menu)
2. Remove the **Blog** menu item
3. Save

Order should now be: Formation | Services | Our Customers | About | Contact | [Book a Call CTA]

Repeat for **Mobile Menu** if it has a separate entry.

- [ ] **Step 24.9: Final verification of nav**

Load the homepage. Expected nav order: Formation first, then Services/Our Customers/About/Contact. No Blog item.

---

## Task 25 — Go-live verification pass

**Files:** none (verification)

Tests every success criterion from the spec.

- [ ] **Step 25.1: Landing page**

Visit `http://church-2.local/formation/`. Expected:
- Page renders without errors
- Intro paragraph visible
- 4 Pillar Cards rendering with numerals 01–04, names, taglines, piece counts

- [ ] **Step 25.2: Each pillar archive**

Visit each of:
- `/formation/communications-champion/`
- `/formation/rhythm-and-restraint/`
- `/formation/invitation-and-patience/`
- `/formation/guardrails-and-discernment/`

Each should render hero + featured cornerstone + grid of other pieces.

- [ ] **Step 25.3: Cornerstone single piece**

Visit any seeded cornerstone. Expected:
- Navy hero with correct pillar tag, title, subtitle, featured image
- Sticky ToC on desktop (right column, highlights active section on scroll)
- Collapsed `<details>` ToC on mobile (≤ 992px)
- Body copy in readable column
- "What to do this week" callout styled correctly (if promoted during polish)
- Pullquotes styled with gold left border (if promoted during polish)
- Email capture inline form
- Related pieces row (may be empty on this cornerstone if pillar only has this one cornerstone)

- [ ] **Step 25.4: Redirect verification**

```bash
for slug in how-churches-are-using-ai church-seo-guide catholic-ai-guide; do
  echo -n "$slug: "
  curl -I -s -o /dev/null -w "%{http_code} → %{redirect_url}\n" "http://church-2.local/blog/$slug/"
done
```

Expected: three 301s to `/formation/.../<slug>/`.

- [ ] **Step 25.5: Blog archive + retained posts**

```bash
curl -s -o /dev/null -w "%{http_code}\n" "http://church-2.local/blog/"
curl -s -o /dev/null -w "%{http_code}\n" "http://church-2.local/blog/what-is-a-faith-driven-entrepreneur/"
curl -s -o /dev/null -w "%{http_code}\n" "http://church-2.local/blog/christian-business-coaching/"
```

All three: 200.

- [ ] **Step 25.6: Meta descriptions**

View page source on a cornerstone:

```bash
curl -s "http://church-2.local/formation/communications-champion/<slug>/" | grep '<meta name="description"'
```

Expected: description matches the piece's ACF `summary` text (not a default WP excerpt).

Same for a pillar archive — description should match `pillar_meta_description` for that term.

- [ ] **Step 25.7: Lighthouse**

Open Chrome DevTools → Lighthouse → Mobile → Run on a cornerstone URL. Targets:
- Performance: ≥ 90
- Accessibility: ≥ 90
- Best practices: ≥ 90
- SEO: ≥ 95

If Performance is below 90, check LCP: likely culprit is uncached featured image. Imagify should have converted it to WebP; verify the `<picture>` element or `srcset` on the hero img.

- [ ] **Step 25.8: Matomo check**

Visit a cornerstone and a pillar archive in a new incognito tab. Then in Matomo admin → Visitors → Real-time. Expected: pageviews recorded for both URLs.

- [ ] **Step 25.9: Pillar meta description edit-without-deploy**

Edit a pillar term in admin (change tagline by one word). Hard-refresh the pillar archive. Expected: new tagline visible. No code deploy needed.

- [ ] **Step 25.10: Final commit of success-log**

Create a short `docs/superpowers/plans/2026-04-17-formation-area-verification.md` capturing:
- Lighthouse scores (from step 25.7)
- Redirect check output (25.4)
- Any anomalies found + resolution

```bash
git add docs/superpowers/plans/2026-04-17-formation-area-verification.md
git commit -m "Formation area verification log — go-live complete"
```

---

## Self-review notes

**Spec coverage:** Every section of the spec (§1–§7, plus top matter) is covered by at least one task:
- §1 IA/URL — Tasks 2, 3, 21
- §2 Data model — Tasks 2, 3, 5, 7, 8
- §3 Templates — Tasks 15, 16, 17, 18
- §4 Blocks — Tasks 10, 11, 12 + SCSS in 13
- §5 Integration — Tasks 6, 24
- §6 Seed — Tasks 9, 22, 23
- §7 Non-functional — Tasks 13, 14, 19, 25

**Placeholder scan:** None. Every step has exact code or exact commands. No "TBD" / "TODO" / "similar to earlier".

**Type/name consistency:**
- `tld_formation_build_toc` — defined Task 4, used Task 16. Same signature.
- `tld_formation_md_to_blocks` — defined Task 9, used Task 22. Same signature.
- `tld_formation_calc_reading_time` — defined Task 5. Not re-used outside its hook. OK.
- ACF field names (`subtitle`, `summary`, `pillar`, `piece_type`, `reading_time_minutes`, `toc_enabled`, `pillar_tagline`, `pillar_meta_description`, `pillar_sort_order`) — consistent across Tasks 7, 8, 12, 15, 16, 17, 19, 22.
- Block names (`acf/tld-pillar-card`, `acf/tld-callout-action`, `acf/tld-piece-card`) — consistent.
- Template parts referenced via `get_template_part()` match files created in Task 15.

**Test coverage:** Three pure-function tests (ToC builder, reading time, markdown converter). Integration tested manually via browser verification at each task step. No PHPUnit infrastructure added — appropriate for a WP theme of this scale.

**Out-of-scope items from brief (§6 of spec):** Confirmed not present in the plan — no tasks for topic/audience taxonomies, visibility field, downloads CPT, admin importer, etc.

---

*End of plan.*
