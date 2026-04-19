<?php
/**
 * Short-read / field-note hero.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillar_terms = get_the_terms(get_the_ID(), 'pillar');
$pillar       = (!is_wp_error($pillar_terms) && !empty($pillar_terms)) ? $pillar_terms[0] : null;
$reading_time = (int) get_field('reading_time_minutes');
$type_terms   = get_the_terms(get_the_ID(), 'piece_type');
$piece_type   = (!is_wp_error($type_terms) && !empty($type_terms)) ? $type_terms[0] : null;
?>
<header class="formation-hero-compact">
  <div class="container">
    <div class="formation-hero-compact__meta">
      <?php if ($pillar): ?>
        <a href="<?php echo esc_url(get_term_link($pillar)); ?>" style="color: inherit; text-decoration: none;">
          <?php echo esc_html($pillar->name); ?>
        </a>
      <?php endif; ?>
      <?php if ($piece_type): ?>
        &middot; <?php echo esc_html($piece_type->name); ?>
      <?php endif; ?>
    </div>
    <h1 class="formation-hero-compact__title"><?php the_title(); ?></h1>
    <?php if ($reading_time): ?>
      <p class="formation-hero-compact__time"><?php echo (int) $reading_time; ?> min read</p>
    <?php endif; ?>
  </div>
</header>
