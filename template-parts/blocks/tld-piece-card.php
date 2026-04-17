<?php
/**
 * Piece Card render.
 *
 * Called in two modes:
 *   1. As an ACF block — field values via get_field()
 *   2. Via template code:
 *      get_template_part('template-parts/blocks/tld-piece-card', null, [
 *        'piece_id'     => int,
 *        'variant'      => 'default'|'featured'|'compact',
 *        'show_summary' => bool,
 *      ])
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

// Resolve inputs — template-part args take precedence over ACF block fields
$piece_id     = null;
$variant      = 'default';
$show_summary = true;

if (isset($args) && is_array($args)) {
  $piece_id     = $args['piece_id']     ?? null;
  $variant      = $args['variant']      ?? 'default';
  $show_summary = $args['show_summary'] ?? true;
} else {
  $piece = get_field('piece');
  if ($piece) {
    $piece_id = is_object($piece) ? $piece->ID : (int) $piece;
  }
  $variant      = get_field('variant') ?: 'default';
  $show_summary_field = get_field('show_summary');
  $show_summary = $show_summary_field === null ? true : (bool) $show_summary_field;
}

if (!$piece_id) {
  if (isset($args) === false && (is_admin() || (function_exists('is_customize_preview') && is_customize_preview()))) {
    echo '<div class="tld-block-placeholder">Piece Card: select a Formation piece in the block sidebar.</div>';
  }
  return;
}

$p = get_post($piece_id);
if (!$p || $p->post_type !== 'formation_piece') return;

$pillar_terms = get_the_terms($piece_id, 'pillar');
$pillar       = (!is_wp_error($pillar_terms) && !empty($pillar_terms)) ? $pillar_terms[0] : null;
$type_terms   = get_the_terms($piece_id, 'piece_type');
$piece_type   = (!is_wp_error($type_terms) && !empty($type_terms)) ? $type_terms[0] : null;

$reading_time = (int) get_field('reading_time_minutes', $piece_id);
$summary      = get_field('summary', $piece_id);
$thumb        = get_the_post_thumbnail($piece_id, 'tld-card', ['class' => 'tld-piece-card__image']);

$classes = 'tld-piece-card tld-piece-card--' . esc_attr($variant);
?>
<article class="<?php echo $classes; ?>" data-piece-type="<?php echo esc_attr($piece_type ? $piece_type->slug : ''); ?>">
  <a class="tld-piece-card__link" href="<?php echo esc_url(get_permalink($p)); ?>">
    <?php if ($thumb && $variant !== 'compact'): ?>
      <div class="tld-piece-card__image-wrap"><?php echo $thumb; ?></div>
    <?php endif; ?>
    <div class="tld-piece-card__body">
      <div class="tld-piece-card__meta">
        <?php if ($piece_type): ?>
          <span class="tld-piece-card__type"><?php echo esc_html($piece_type->name); ?></span>
        <?php endif; ?>
        <?php if ($reading_time): ?>
          <span class="tld-piece-card__time"><?php echo (int) $reading_time; ?> min read</span>
        <?php endif; ?>
      </div>
      <h3 class="tld-piece-card__title"><?php echo esc_html(get_the_title($p)); ?></h3>
      <?php if ($show_summary && $summary): ?>
        <p class="tld-piece-card__summary"><?php echo esc_html($summary); ?></p>
      <?php endif; ?>
    </div>
  </a>
</article>
