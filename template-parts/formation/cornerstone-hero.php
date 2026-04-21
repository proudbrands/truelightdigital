<?php
/**
 * Cornerstone piece hero.
 * Assumes global $post is a formation_piece.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillar_terms = get_the_terms(get_the_ID(), 'pillar');
$pillar       = (!is_wp_error($pillar_terms) && !empty($pillar_terms)) ? $pillar_terms[0] : null;
$subtitle     = get_field('subtitle');
$reading_time = (int) get_field('reading_time_minutes');
$date         = get_the_date('F Y');

// Hero video override: read from the piece itself first; fall back to the
// pillar-term-level override if the piece has none. This lets a pillar
// carry a default video for every cornerstone that belongs to it.
$hero_video = tld_get_hero_video_url();
if (!$hero_video && $pillar) {
  $hero_video = tld_get_hero_video_url('pillar_' . $pillar->term_id);
}
$poster = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'tld-hero') : '';
?>
<header class="formation-hero<?= $hero_video ? ' formation-hero--has-video' : ''; ?>">
  <?php if ($hero_video): ?>
    <video class="formation-hero__video" autoplay muted loop playsinline preload="metadata"<?php if ($poster): ?> poster="<?php echo esc_url($poster); ?>"<?php endif; ?> aria-hidden="true">
      <source src="<?php echo esc_url($hero_video); ?>" type="video/webm">
    </video>
  <?php endif; ?>
  <div class="container">
    <div class="formation-hero__meta">
      <?php if ($pillar): ?>
        <a href="<?php echo esc_url(get_term_link($pillar)); ?>" class="formation-hero__pillar-link" style="color: inherit; text-decoration: none;">
          <span><?php echo esc_html($pillar->name); ?></span>
        </a>
        <span class="dot">&bull;</span>
      <?php endif; ?>
      <span class="muted">Cornerstone <?php if ($reading_time): ?>&middot; <?php echo (int) $reading_time; ?> min read<?php endif; ?></span>
      <span class="dot">&bull;</span>
      <span class="muted">Published <?php echo esc_html($date); ?></span>
    </div>
    <h1 class="formation-hero__title"><?php the_title(); ?></h1>
    <?php if ($subtitle): ?>
      <p class="formation-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
    <?php endif; ?>
  </div>
  <?php if (has_post_thumbnail()): ?>
    <div class="formation-hero__image">
      <?php the_post_thumbnail('tld-hero', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
    </div>
  <?php endif; ?>
</header>
