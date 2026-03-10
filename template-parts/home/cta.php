<?php
/**
 * Homepage: Final CTA — Bold statement section
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$cta_bg = function_exists('get_field') ? get_field('home_cta_bg_image', 'option') : '';
?>

<section class="tld-cta-modern<?= $cta_bg ? ' has-bg-image' : ''; ?>"<?php if ($cta_bg) : ?> style="background-image: url('<?= esc_url($cta_bg); ?>');"<?php endif; ?>>
  <div class="container">
    <div class="tld-cta-modern-inner">
      <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">Get Started</p>
      <h2 class="tld-cta-modern-heading tld-reveal tld-reveal-d1">
        Ready to build<br>something stronger?
      </h2>
      <p class="tld-cta-modern-text tld-reveal tld-reveal-d2">
        A digital partner who understands faith, takes outcomes seriously, and knows how to make strategy usable. We work best with organizations that care about clarity, move with purpose, and invest in work that lasts.
      </p>
      <div class="tld-reveal tld-reveal-d3">
        <a href="#" class="btn tld-btn-gold btn-lg tld-btn-arrow" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
          Book a Discovery Call
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>
