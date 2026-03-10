<?php
/**
 * About Page: Story / Origin — Two-column narrative layout
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$eyebrow = $args['eyebrow'] ?? 'Our Story';
$heading = $args['heading'] ?? '';
$text    = $args['text'] ?? '';
$image   = $args['image'] ?? '';
?>

<section class="tld-section bg-off-white">
  <div class="container">
    <div class="row align-items-start">
      <div class="col-lg-5 mb-4 mb-lg-0">
        <?php if ($eyebrow) : ?>
          <p class="tld-eyebrow tld-reveal"><?= esc_html($eyebrow); ?></p>
        <?php endif; ?>
        <?php if ($heading) : ?>
          <h2 class="tld-heading-lg tld-reveal tld-reveal-d1"><?= nl2br(esc_html($heading)); ?></h2>
        <?php endif; ?>
        <div class="tld-gold-divider mt-3 tld-reveal tld-reveal-d1"></div>
      </div>
      <div class="col-lg-6 offset-lg-1">
        <?php if ($text) : ?>
          <div class="tld-values-content tld-reveal tld-reveal-d1">
            <?= wp_kses_post($text); ?>
          </div>
        <?php endif; ?>
        <?php if ($image) : ?>
          <img src="<?= esc_url($image); ?>"
               alt=""
               class="tld-about-story-image tld-reveal tld-reveal-d2"
               loading="lazy">
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
