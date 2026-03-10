<?php
/**
 * ACF Block: CTA Band
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$heading    = get_field('cta_heading') ?: 'Ready to Get Started?';
$text       = get_field('cta_text');
$btn_text   = get_field('cta_button_text') ?: 'Book a Discovery Call';
$btn_url    = get_field('cta_button_url') ?: home_url('/contact/');
?>

<div class="tld-cta-band">
  <div class="container">
    <h2 class="tld-cta-heading"><?= esc_html($heading); ?></h2>
    <?php if ($text) : ?>
      <p><?= esc_html($text); ?></p>
    <?php endif; ?>
    <a href="#" class="btn tld-btn-gold btn-lg" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
      <?= esc_html($btn_text); ?>
    </a>
  </div>
</div>
