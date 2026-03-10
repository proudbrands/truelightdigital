<?php
/**
 * Template Part: CTA Band — Modern centered layout
 *
 * Full-width call-to-action section matching homepage CTA style.
 * Pass data via $args: 'heading', 'text', 'btn_text', 'btn_url'.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$heading  = $args['heading'] ?? 'Ready to Grow Your Digital Presence?';
$text     = $args['text'] ?? '';
$btn_text = $args['btn_text'] ?? 'Book a Discovery Call';
$btn_url  = $args['btn_url'] ?? home_url('/contact/');
?>

<section class="tld-cta-modern">
  <div class="container">
    <div class="tld-cta-modern-inner">
      <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">Get Started</p>
      <h2 class="tld-cta-modern-heading tld-reveal tld-reveal-d1"><?= esc_html($heading); ?></h2>
      <?php if ($text) : ?>
        <p class="tld-cta-modern-text tld-reveal tld-reveal-d2"><?= esc_html($text); ?></p>
      <?php endif; ?>
      <div class="tld-reveal tld-reveal-d3">
        <a href="#" class="btn tld-btn-gold btn-lg tld-btn-arrow" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
          <?= esc_html($btn_text); ?>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>
