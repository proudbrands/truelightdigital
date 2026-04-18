<?php
/**
 * Single Formation piece template.
 *
 * Picks layout by piece_type:
 *   - cornerstone   -> cornerstone hero, 2-col body with ToC sidebar
 *   - short-read    -> compact hero, single-col body
 *   - field-note    -> compact hero, single-col body
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();

if (have_posts()): while (have_posts()): the_post();

  $type_terms     = get_the_terms(get_the_ID(), 'piece_type');
  $piece_type     = (!is_wp_error($type_terms) && !empty($type_terms)) ? $type_terms[0]->slug : 'short-read';
  $is_cornerstone = ($piece_type === 'cornerstone');

  $pillar_terms   = get_the_terms(get_the_ID(), 'pillar');
  $pillar_slug    = (!is_wp_error($pillar_terms) && !empty($pillar_terms)) ? $pillar_terms[0]->slug : '';

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

    <?php
    // Resources grid: show tld_resource posts in this pillar
    $pillar_term = (!is_wp_error($pillar_terms) && !empty($pillar_terms)) ? $pillar_terms[0] : null;
    if ($pillar_term) {
      get_template_part('template-parts/formation/resources-grid', null, [
        'pillar_term_id' => $pillar_term->term_id,
        'heading'        => 'Resources for this pillar',
        'intro'          => 'Templates, worksheets, and reflection guides to take away. All free, no email required.',
      ]);
    }
    ?>

    <?php get_template_part('template-parts/formation/related-pieces'); ?>
  </main>

  <?php get_template_part('template-parts/formation/preview-modal'); ?>
  <?php

endwhile; endif;

get_footer();
