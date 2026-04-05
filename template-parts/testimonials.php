<?php
/**
 * Template Part: Testimonials Section
 *
 * Reusable testimonial strip. Pass $args['segment'] to filter by audience.
 * Pass $args['heading'] and $args['eyebrow'] for custom text.
 * Pass $args['dark'] = true for dark background variant.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

if (!function_exists('get_field')) return;

$all_testimonials = get_field('testimonials', 'option');
if (empty($all_testimonials)) return;

$segment = $args['segment'] ?? 'all';
$heading = $args['heading'] ?? 'What our clients say';
$eyebrow = $args['eyebrow'] ?? 'Testimonials';
$dark    = !empty($args['dark']);

// Filter by segment
$testimonials = [];
foreach ($all_testimonials as $t) {
  if ($segment === 'all' || ($t['segment'] ?? 'all') === 'all' || ($t['segment'] ?? '') === $segment) {
    $testimonials[] = $t;
  }
}

if (empty($testimonials)) return;

// Limit to 3 for display
$testimonials = array_slice($testimonials, 0, 3);

$section_class = $dark ? 'tld-testimonials tld-testimonials--dark' : 'tld-testimonials';
?>

<section class="<?= esc_attr($section_class); ?>">
  <div class="container">
    <div class="text-center mb-5">
      <p class="tld-eyebrow tld-reveal"<?php if ($dark) : ?> style="color: var(--tld-gold);"<?php endif; ?>><?= esc_html($eyebrow); ?></p>
      <h2 class="tld-heading-section tld-reveal tld-reveal-d1"<?php if ($dark) : ?> style="color: var(--tld-white);"<?php endif; ?>><?= esc_html($heading); ?></h2>
    </div>

    <div class="row g-4">
      <?php foreach ($testimonials as $i => $t) : ?>
        <div class="col-md-4 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
          <div class="tld-testimonial-card<?= $dark ? ' tld-testimonial-card--dark' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16" class="tld-testimonial-quote-icon"><path d="M12 12a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1h-1.388q0-.527.062-1.054.093-.558.31-.992t.559-.683q.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 9 7.558V11a1 1 0 0 0 1 1zm-6 0a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1H4.612q0-.527.062-1.054.094-.558.31-.992.217-.434.56-.683.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 3 7.558V11a1 1 0 0 0 1 1z"/></svg>
            <blockquote class="tld-testimonial-text"><?= esc_html($t['quote']); ?></blockquote>
            <div class="tld-testimonial-author">
              <?php if (!empty($t['photo'])) : ?>
                <img src="<?= esc_url($t['photo']); ?>" alt="<?= esc_attr($t['name']); ?>" class="tld-testimonial-avatar" loading="lazy">
              <?php endif; ?>
              <div>
                <strong class="tld-testimonial-name"><?= esc_html($t['name']); ?></strong>
                <?php if (!empty($t['role']) || !empty($t['organisation'])) : ?>
                  <span class="tld-testimonial-role">
                    <?= esc_html(implode(', ', array_filter([$t['role'] ?? '', $t['organisation'] ?? '']))); ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
