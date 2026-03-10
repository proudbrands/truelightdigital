<?php
/**
 * About Page: Values / Convictions — Dark section with checkmark cards
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$eyebrow  = $args['eyebrow'] ?? 'What We Believe';
$heading  = $args['heading'] ?? 'Convictions that shape our work';
$bg_image = $args['bg_image'] ?? '';
$items    = $args['items'] ?? [];

if (!$items) {
  return;
}
?>

<section class="tld-section tld-whyus-section<?= $bg_image ? ' has-bg-image' : ''; ?>"<?php if ($bg_image) : ?> style="background-image: url('<?= esc_url($bg_image); ?>');"<?php endif; ?>>
  <div class="container">
    <div class="text-center mb-5">
      <?php if ($eyebrow) : ?>
        <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);"><?= esc_html($eyebrow); ?></p>
      <?php endif; ?>
      <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($heading); ?></h2>
    </div>
    <div class="row g-4">
      <?php $i = 0; foreach ($items as $item) : $i++; ?>
        <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
          <div class="tld-whyus-card">
            <div class="tld-whyus-icon-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/>
              </svg>
            </div>
            <h3 class="tld-whyus-title"><?= esc_html($item['title']); ?></h3>
            <p class="tld-whyus-desc"><?= esc_html($item['description']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
