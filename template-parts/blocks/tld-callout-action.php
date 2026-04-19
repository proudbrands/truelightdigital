<?php
/**
 * Callout Action ("What to do this week") block render.
 *
 * Gold-bordered cream box, inline in cornerstone essays.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$label_override = get_field('label_override');
$label   = $label_override ?: 'What to do this week';
$heading = get_field('heading');
$intro   = get_field('intro');
$steps   = get_field('steps');

if (!$heading || !$steps) {
  if (is_admin()) {
    echo '<div class="tld-block-placeholder">Callout: heading + at least one step required.</div>';
  }
  return;
}
?>
<aside class="tld-callout-action" role="complementary">
  <div class="tld-callout-action__label"><?php echo esc_html($label); ?></div>
  <h4 class="tld-callout-action__heading"><?php echo esc_html($heading); ?></h4>
  <?php if ($intro): ?>
    <p class="tld-callout-action__intro"><?php echo wp_kses_post($intro); ?></p>
  <?php endif; ?>
  <ol class="tld-callout-action__steps">
    <?php foreach ($steps as $step): ?>
      <li>
        <strong><?php echo esc_html($step['step_lead']); ?></strong>
        <?php if (!empty($step['step_detail'])): ?>
          <?php echo wp_kses_post($step['step_detail']); ?>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</aside>
