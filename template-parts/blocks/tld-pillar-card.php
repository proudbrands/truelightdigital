<?php
/**
 * Pillar Card block render template.
 *
 * Author places four of these on the Formation landing page,
 * one per pillar. Pulls live piece count and term-level fields
 * (tagline + sort order) via ACF.
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
$number     = str_pad((string) $sort_order, 2, '0', STR_PAD_LEFT);
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
