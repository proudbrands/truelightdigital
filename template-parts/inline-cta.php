<?php
/**
 * Template Part: Inline CTA Prompt
 *
 * Lightweight mid-page conversion prompt. Not a full-width band.
 * Pass $args: 'text', 'btn_text', 'style' ('light' or 'dark').
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$text     = $args['text'] ?? 'Ready to get started?';
$btn_text = $args['btn_text'] ?? 'Book a Discovery Call';
$style    = $args['style'] ?? 'light';
?>

<div class="tld-inline-cta tld-inline-cta--<?= esc_attr($style); ?> tld-reveal">
  <p class="tld-inline-cta-text"><?= esc_html($text); ?></p>
  <a href="#" class="btn tld-btn-gold tld-btn-arrow" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
    <?= esc_html($btn_text); ?>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
  </a>
</div>
