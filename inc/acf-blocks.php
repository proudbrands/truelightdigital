<?php
/**
 * ACF Block Registrations
 *
 * Registers 4 custom ACF blocks for the Gutenberg editor.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

if (!function_exists('acf_register_block_type')) {
  return;
}

add_action('acf/init', 'tld_register_acf_blocks');
function tld_register_acf_blocks() {

  // FAQ Accordion
  acf_register_block_type([
    'name'            => 'tld-faq-accordion',
    'title'           => 'FAQ Accordion',
    'description'     => 'Bootstrap 5 accordion for frequently asked questions.',
    'render_template' => get_stylesheet_directory() . '/template-parts/blocks/tld-faq-accordion.php',
    'category'        => 'tld',
    'icon'            => 'editor-help',
    'keywords'        => ['faq', 'accordion', 'questions'],
    'mode'            => 'preview',
    'supports'        => ['align' => false, 'mode' => true],
  ]);

  // CTA Band
  acf_register_block_type([
    'name'            => 'tld-cta-band',
    'title'           => 'CTA Band',
    'description'     => 'Full-width call-to-action section with heading, text, and button.',
    'render_template' => get_stylesheet_directory() . '/template-parts/blocks/tld-cta-band.php',
    'category'        => 'tld',
    'icon'            => 'megaphone',
    'keywords'        => ['cta', 'call to action', 'banner'],
    'mode'            => 'preview',
    'supports'        => ['align' => ['full', 'wide'], 'mode' => true],
  ]);

  // Process Steps
  acf_register_block_type([
    'name'            => 'tld-process-steps',
    'title'           => 'Process Steps',
    'description'     => 'Numbered step-by-step process with counter circles.',
    'render_template' => get_stylesheet_directory() . '/template-parts/blocks/tld-process-steps.php',
    'category'        => 'tld',
    'icon'            => 'editor-ol',
    'keywords'        => ['process', 'steps', 'how it works'],
    'mode'            => 'preview',
    'supports'        => ['align' => false, 'mode' => true],
  ]);

  // Stats Strip
  acf_register_block_type([
    'name'            => 'tld-stats-strip',
    'title'           => 'Stats Strip',
    'description'     => 'Dark background strip with stat numbers and labels.',
    'render_template' => get_stylesheet_directory() . '/template-parts/blocks/tld-stats-strip.php',
    'category'        => 'tld',
    'icon'            => 'chart-bar',
    'keywords'        => ['stats', 'numbers', 'proof'],
    'mode'            => 'preview',
    'supports'        => ['align' => ['full', 'wide'], 'mode' => true],
  ]);

  // ── Formation blocks ──

  // Pillar Card — one per pillar, four on the Formation landing page
  acf_register_block_type([
    'name'            => 'tld-pillar-card',
    'title'           => 'Pillar Card',
    'description'     => 'Featured card for one Formation pillar. Place four on the Formation landing page.',
    'render_template' => get_stylesheet_directory() . '/template-parts/blocks/tld-pillar-card.php',
    'category'        => 'formation',
    'icon'            => 'book-alt',
    'keywords'        => ['formation', 'pillar', 'card'],
    'mode'            => 'preview',
    'supports'        => ['align' => false, 'mode' => false, 'jsx' => false],
  ]);

  // Callout Action — "What to do this week" inline in cornerstone essays
  acf_register_block_type([
    'name'            => 'tld-callout-action',
    'title'           => 'What to do this week',
    'description'     => 'A gold-bordered action callout. Use at the end of cornerstone essays.',
    'render_template' => get_stylesheet_directory() . '/template-parts/blocks/tld-callout-action.php',
    'category'        => 'formation',
    'icon'            => 'lightbulb',
    'keywords'        => ['formation', 'callout', 'action', 'steps'],
    'mode'            => 'edit',
    'supports'        => ['align' => ['wide'], 'mode' => false, 'jsx' => false],
  ]);

  // Piece Card — hand-feature a specific Formation piece
  acf_register_block_type([
    'name'            => 'tld-piece-card',
    'title'           => 'Piece Card',
    'description'     => 'Feature a specific Formation piece. Used both as a block and by template grids.',
    'render_template' => get_stylesheet_directory() . '/template-parts/blocks/tld-piece-card.php',
    'category'        => 'formation',
    'icon'            => 'media-document',
    'keywords'        => ['formation', 'piece', 'feature'],
    'mode'            => 'preview',
    'supports'        => ['align' => false, 'mode' => false, 'jsx' => false],
  ]);
}

// Register custom block categories
add_filter('block_categories_all', function ($categories) {
  array_unshift($categories, [
    'slug'  => 'formation',
    'title' => 'Formation',
    'icon'  => 'book-alt',
  ]);
  array_unshift($categories, [
    'slug'  => 'tld',
    'title' => 'True Light Digital',
  ]);
  return $categories;
});
