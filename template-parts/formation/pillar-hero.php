<?php
/**
 * Pillar archive hero.
 * Assumes $wp_query has a queried term for the pillar taxonomy.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$term = get_queried_object();
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
