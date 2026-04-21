<?php
/**
 * Homepage: Hero — full-bleed bokeh + mission headline.
 *
 * Reuses the .formation-hero--image pattern from /formation/.
 * Image source: front page's Featured Image; falls back to Unsplash.
 * Copy is hardcoded — this is a mission statement, not editable content.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full')
  ?: 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=2000&q=75';
$hero_video = tld_get_hero_video_url();
?>
<header class="formation-hero--image<?= $hero_video ? ' formation-hero--image--has-video' : ''; ?>" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
  <?php if ($hero_video): ?>
    <video class="formation-hero--image__video" autoplay muted loop playsinline preload="metadata" poster="<?php echo esc_url($hero_image); ?>" aria-hidden="true">
      <source src="<?php echo esc_url($hero_video); ?>" type="video/webm">
    </video>
  <?php endif; ?>
  <div class="container">
    <span class="formation-hero--image__eyebrow">A digital practice run like a charity</span>
    <h1 class="formation-hero--image__title">If we look after each other, the rest follows.</h1>
    <p class="formation-hero--image__intro">We equip the people who tell the Church's story &mdash; priests, parish secretaries, ministry leaders, Christian founders &mdash; with writing, templates, and tools. All of it free. Direct help if you want it. No pitch.</p>
    <div class="formation-hero--image__ctas">
      <a class="btn tld-btn-gold btn-lg tld-btn-arrow" href="<?= esc_url(home_url('/formation/')); ?>">Start in Formation &rarr;</a>
      <a class="btn btn-outline-light btn-lg" href="<?= esc_url(home_url('/contact/')); ?>">Write to us</a>
    </div>
  </div>
</header>
