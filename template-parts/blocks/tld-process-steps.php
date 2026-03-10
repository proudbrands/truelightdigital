<?php
/**
 * ACF Block: Process Steps
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$heading = get_field('process_heading');
$steps   = get_field('process_steps');

if (!$steps) {
  return;
}
?>

<div class="tld-section-sm">
  <?php if ($heading) : ?>
    <h2 class="tld-heading-section mb-4"><?= esc_html($heading); ?></h2>
  <?php endif; ?>

  <div class="tld-process-steps">
    <?php foreach ($steps as $step) : ?>
      <div class="tld-step">
        <h4><?= esc_html($step['title']); ?></h4>
        <p><?= esc_html($step['description']); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</div>
