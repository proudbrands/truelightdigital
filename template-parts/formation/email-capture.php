<?php
/**
 * Inline email capture — Gravity Forms with hidden pillar_interest field.
 *
 * Form ID set via WP option `tld_formation_capture_form_id` (configured in Task 20).
 * Silently skips if no form is configured yet.
 *
 * Accepts $args['pillar_slug'] — the pillar to tag captured contacts with.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillar_slug = $args['pillar_slug'] ?? '';

$form_id = defined('TLD_FORMATION_CAPTURE_FORM_ID')
  ? TLD_FORMATION_CAPTURE_FORM_ID
  : (int) get_option('tld_formation_capture_form_id');

if (!$form_id) {
  return; // no form configured yet — silently skip
}

if (!class_exists('GFForms')) {
  echo '<div class="formation-capture"><p>Newsletter signup unavailable.</p></div>';
  return;
}
?>
<div class="formation-capture">
  <h3 class="formation-capture__heading">More from Formation</h3>
  <p class="formation-capture__intro">Short, monthly. Essays and templates, nothing else. Unsubscribe anytime.</p>
  <?php
    // Populate the hidden pillar_interest field for this render via a one-shot filter.
    add_filter('gform_field_value_pillar_interest', function () use ($pillar_slug) { return $pillar_slug; });
    gravity_form($form_id, false, false, false, null, true, 0);
  ?>
</div>
