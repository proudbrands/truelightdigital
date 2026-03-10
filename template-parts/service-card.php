<?php
/**
 * Template Part: Service Card — Modern hover card
 *
 * Card component for service grids with hover effects.
 * Pass a WP_Post object via $args['post'].
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$card_post = $args['post'] ?? null;
if (!$card_post) {
  return;
}

$subtitle = function_exists('get_field') ? get_field('hero_subtitle', $card_post->ID) : '';
$excerpt  = $subtitle ?: wp_trim_words($card_post->post_content, 20, '...');
?>

<a href="<?= esc_url(get_permalink($card_post->ID)); ?>" class="tld-service-card-v2">
  <?php if (has_post_thumbnail($card_post->ID)) : ?>
    <div class="tld-service-card-v2-img">
      <img src="<?= esc_url(get_the_post_thumbnail_url($card_post->ID, 'tld-card')); ?>"
           alt="<?= esc_attr($card_post->post_title); ?>"
           loading="lazy">
    </div>
  <?php endif; ?>
  <div class="tld-service-card-v2-body">
    <h3 class="tld-service-card-v2-title"><?= esc_html($card_post->post_title); ?></h3>
    <p class="tld-service-card-v2-text"><?= esc_html($excerpt); ?></p>
    <span class="tld-service-card-v2-link">
      Learn more
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
    </span>
  </div>
</a>
