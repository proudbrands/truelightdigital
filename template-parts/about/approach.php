<?php
/**
 * About Page: Approach — Pillar cards on white background
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$eyebrow = $args['eyebrow'] ?? 'Our Approach';
$heading = $args['heading'] ?? 'How we think about the work';
$items   = $args['items'] ?? [];

if (!$items) {
  return;
}
?>

<section class="tld-section tld-about-approach">
  <div class="container">
    <div class="text-center mb-5">
      <?php if ($eyebrow) : ?>
        <p class="tld-eyebrow tld-reveal"><?= esc_html($eyebrow); ?></p>
      <?php endif; ?>
      <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($heading); ?></h2>
    </div>
    <div class="row g-4 justify-content-center">
      <?php $i = 0; foreach ($items as $item) : $i++; ?>
        <div class="col-md-6 col-lg-<?= count($items) <= 3 ? '4' : '3'; ?> tld-reveal tld-reveal-d<?= min($i, 3); ?>">
          <div class="tld-pillar-card">
            <h3 class="tld-pillar-title"><?= esc_html($item['title']); ?></h3>
            <p class="tld-pillar-text"><?= esc_html($item['description']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
