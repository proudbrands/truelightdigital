<?php
/**
 * Audience archive hero.
 * Assumes the queried object is an `audience` taxonomy term.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$term = get_queried_object();
if (!$term || !isset($term->taxonomy) || $term->taxonomy !== 'audience') return;

$count = (int) $term->count;
?>
<header class="formation-audience-hero">
  <div class="container">
    <div class="formation-audience-hero__eyebrow">Formation &nbsp;&middot;&nbsp; Reading pathway</div>
    <h1 class="formation-audience-hero__title">For <?php echo esc_html($term->name); ?></h1>
    <p class="formation-audience-hero__intro">
      <?php echo (int) $count; ?> piece<?php echo $count === 1 ? '' : 's'; ?> and resource<?php echo $count === 1 ? '' : 's'; ?> across the four pillars, curated for this reader.
    </p>
  </div>
</header>
