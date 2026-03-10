<?php
/**
 * Template Part: Inner Page Hero
 *
 * Modern dark cover section with eyebrow, H1, subtitle, and optional breadcrumb.
 * Pass data via $args: 'title', 'subtitle', 'eyebrow'.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$title    = $args['title'] ?? get_the_title();
$subtitle = $args['subtitle'] ?? '';
$eyebrow  = $args['eyebrow'] ?? '';
?>

<section class="tld-hero-inner">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <?php if ($eyebrow) : ?>
          <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);"><?= esc_html($eyebrow); ?></p>
        <?php endif; ?>
        <h1 class="tld-hero-inner-title tld-reveal tld-reveal-d1"><?= esc_html($title); ?></h1>
        <?php if ($subtitle) : ?>
          <p class="tld-hero-inner-subtitle tld-reveal tld-reveal-d2"><?= esc_html($subtitle); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
