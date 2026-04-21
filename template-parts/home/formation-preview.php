<?php
/**
 * Homepage: Formation preview — 4 compact pillar cards.
 * Pulls from the `pillar` taxonomy, ordered by pillar_sort_order meta.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillars = get_terms([
  'taxonomy'   => 'pillar',
  'hide_empty' => false,
  'meta_key'   => 'pillar_sort_order',
  'orderby'    => 'meta_value_num',
  'order'      => 'ASC',
  'number'     => 4,
]);
if (is_wp_error($pillars) || empty($pillars)) return;
?>
<section class="tld-home-formation-preview">
  <div class="container">
    <div class="tld-home-formation-preview__header">
      <span class="tld-home-formation-preview__eyebrow">Formation</span>
      <h2 class="tld-home-formation-preview__title">The free library</h2>
      <p class="tld-home-formation-preview__lede">Four pillars, twenty-plus pieces, all free. The ongoing work of shaping and equipping the people who carry parish and ministry communications.</p>
    </div>
    <div class="tld-home-formation-preview__grid">
      <?php foreach ($pillars as $i => $term):
        $tagline = function_exists('get_field') ? get_field('pillar_tagline', $term) : '';
        $num = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
      ?>
        <a class="tld-pillar-preview-card" href="<?= esc_url(get_term_link($term)); ?>">
          <span class="tld-pillar-preview-card__num"><?= esc_html($num); ?></span>
          <span class="tld-pillar-preview-card__body">
            <span class="tld-pillar-preview-card__title"><?= esc_html($term->name); ?></span>
            <?php if ($tagline): ?>
              <span class="tld-pillar-preview-card__tagline"><?= esc_html($tagline); ?></span>
            <?php endif; ?>
          </span>
        </a>
      <?php endforeach; ?>
    </div>
    <p class="tld-home-formation-preview__link">
      <a href="<?= esc_url(home_url('/formation/')); ?>">Explore Formation &rarr;</a>
    </p>
  </div>
</section>
