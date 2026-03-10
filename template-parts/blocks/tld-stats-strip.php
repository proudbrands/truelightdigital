<?php
/**
 * ACF Block: Stats Strip
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$items = get_field('stats_items');

if (!$items) {
  return;
}

$col_class = 'col-6 col-md-' . (12 / min(count($items), 4));
?>

<div class="tld-proof-strip">
  <div class="container">
    <div class="row text-center g-4">
      <?php foreach ($items as $item) : ?>
        <div class="<?= esc_attr($col_class); ?>">
          <div class="tld-stat-number"><?= esc_html($item['number']); ?></div>
          <div class="tld-stat-label"><?= esc_html($item['label']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
