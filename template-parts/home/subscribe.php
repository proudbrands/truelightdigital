<?php
/**
 * Homepage: Subscribe invitation — email capture, no hard sell.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$form_id = 10;
?>
<section class="tld-home-subscribe">
  <div class="container">
    <h2 class="tld-home-subscribe__title">We&rsquo;ll keep doing this work.</h2>
    <p class="tld-home-subscribe__dek">If you&rsquo;d like a short note when something new goes up in Formation, we&rsquo;ll send one. Free, no marketing, unsubscribe any time.</p>
    <?php if ($form_id && function_exists('gravity_form')): ?>
      <div class="tld-home-subscribe__form">
        <?php gravity_form($form_id, false, false, false, null, true); ?>
      </div>
    <?php else: ?>
      <p class="tld-home-subscribe__fallback"><a href="<?= esc_url(home_url('/contact/')); ?>">Write to us</a> to be added to the list.</p>
    <?php endif; ?>
  </div>
</section>
