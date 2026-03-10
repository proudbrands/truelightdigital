<?php
/**
 * About Page: Team — Member grid using team-card template-part
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$eyebrow = $args['eyebrow'] ?? 'The Team';
$heading = $args['heading'] ?? 'The people behind the work';
$text    = $args['text'] ?? '';
$members = $args['members'] ?? [];

if (!$members) {
  return;
}
?>

<section class="tld-section">
  <div class="container">
    <div class="text-center mb-5">
      <?php if ($eyebrow) : ?>
        <p class="tld-eyebrow tld-reveal"><?= esc_html($eyebrow); ?></p>
      <?php endif; ?>
      <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($heading); ?></h2>
      <?php if ($text) : ?>
        <p class="tld-subtitle tld-reveal tld-reveal-d1" style="max-width: 600px; margin: 1rem auto 0;"><?= esc_html($text); ?></p>
      <?php endif; ?>
    </div>
    <div class="row g-4 justify-content-center">
      <?php $i = 0; foreach ($members as $member) : $i++; ?>
        <div class="col-sm-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
          <?php get_template_part('template-parts/team-card', null, [
            'name'      => $member['name'],
            'role'      => $member['role'],
            'image_url' => $member['image'] ?? '',
            'bio'       => $member['bio'] ?? '',
          ]); ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
