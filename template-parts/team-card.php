<?php
/**
 * Template Part: Team Card
 *
 * Team member display card.
 * Pass data via $args: 'name', 'role', 'image_url', 'bio'.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$name      = $args['name'] ?? '';
$role      = $args['role'] ?? '';
$image_url = $args['image_url'] ?? '';
$bio       = $args['bio'] ?? '';

if (!$name) {
  return;
}
?>

<div class="tld-card text-center p-4">
  <?php if ($image_url) : ?>
    <img src="<?= esc_url($image_url); ?>"
         alt="<?= esc_attr($name); ?>"
         class="rounded-circle mb-3"
         width="120"
         height="120"
         style="object-fit: cover;"
         loading="lazy">
  <?php endif; ?>
  <h4 class="mb-1" style="font-size: 1.125rem;"><?= esc_html($name); ?></h4>
  <?php if ($role) : ?>
    <p class="text-muted-custom small mb-2"><?= esc_html($role); ?></p>
  <?php endif; ?>
  <?php if ($bio) : ?>
    <p class="small" style="color: var(--tld-muted);"><?= esc_html($bio); ?></p>
  <?php endif; ?>
</div>
