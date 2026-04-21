<?php
/**
 * Template Name: Service Page
 *
 * Six-section service page. Hero → Problem → Pillars → Pull-quote →
 * Why Us (dark anchor) → Process → FAQ → CTA. All copy kept from the
 * previous template; audience / results / related / inline-CTA /
 * testimonials sections removed. See docs/superpowers/specs/
 * 2026-04-21-service-page-redesign-design.md.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();

// ── ACF fields ──
$eyebrow         = function_exists('get_field') ? (get_field('hero_eyebrow') ?: 'Our Services') : 'Our Services';
$subtitle        = function_exists('get_field') ? get_field('hero_subtitle') : '';
$cta1_text       = function_exists('get_field') ? get_field('hero_cta_primary_text') : '';
$cta2_text       = function_exists('get_field') ? get_field('hero_cta_secondary_text') : '';
$cta2_url        = function_exists('get_field') ? get_field('hero_cta_secondary_url') : '';
$intro_stmt      = function_exists('get_field') ? get_field('intro_statement') : '';
$intro_text      = function_exists('get_field') ? get_field('intro_text') : '';
$pillars         = function_exists('get_field') ? get_field('service_pillars') : [];
$pillars_heading = function_exists('get_field') ? get_field('pillars_heading') : '';
$whyus           = function_exists('get_field') ? get_field('whyus_items') : [];
$whyus_heading   = function_exists('get_field') ? get_field('whyus_heading') : '';
$whyus_bg        = function_exists('get_field') ? get_field('whyus_bg_image') : '';
$steps           = function_exists('get_field') ? get_field('process_steps') : [];
$process_heading = function_exists('get_field') ? get_field('process_heading') : '';
$process_bg      = function_exists('get_field') ? get_field('process_bg_image') : '';
$faqs            = function_exists('get_field') ? get_field('faq_items') : [];
$pullquote_text  = function_exists('get_field') ? get_field('pullquote_text') : '';
$pullquote_attr  = function_exists('get_field') ? get_field('pullquote_attribution') : '';

// Fallback: if no explicit pullquote, use the first whyus description.
if (!$pullquote_text && !empty($whyus[0]['description'])) {
  $pullquote_text = $whyus[0]['description'];
}

// Hero image — page Featured Image with Unsplash fallback (same pattern as /home/ and /formation/).
$hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full')
  ?: 'https://images.unsplash.com/photo-1519741497674-611481863552?w=2000&q=75';

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>';
?>
<main id="primary" class="site-main">

  <?php if (have_posts()): while (have_posts()): the_post(); ?>

    <!-- Section 1: Hero -->
    <header class="formation-hero--image" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
      <div class="container">
        <?php if ($eyebrow): ?>
          <span class="formation-hero--image__eyebrow"><?= esc_html($eyebrow); ?></span>
        <?php endif; ?>
        <h1 class="formation-hero--image__title"><?= esc_html(get_the_title()); ?></h1>
        <?php if ($subtitle): ?>
          <p class="formation-hero--image__intro"><?= esc_html($subtitle); ?></p>
        <?php endif; ?>
        <?php if ($cta1_text || $cta2_text): ?>
          <div class="formation-hero--image__ctas">
            <?php if ($cta1_text): ?>
              <a class="btn tld-btn-gold btn-lg tld-btn-arrow" href="#" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
                <?= esc_html($cta1_text); ?><?= $arrow_svg; ?>
              </a>
            <?php endif; ?>
            <?php if ($cta2_text): ?>
              <a class="btn btn-outline-light btn-lg" href="<?= esc_url($cta2_url ?: '#what-we-do'); ?>"><?= esc_html($cta2_text); ?></a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </header>

    <!-- Section 2: Problem statement / positioning intro -->
    <?php if ($intro_stmt): ?>
      <section class="tld-section tld-problem-statement">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-9 text-center">
              <h2 class="tld-problem-heading tld-reveal"><?= wp_kses_post($intro_stmt); ?></h2>
              <?php if ($intro_text): ?>
                <p class="tld-problem-text tld-reveal tld-reveal-d1"><?= esc_html($intro_text); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 3: Pillars / What's included -->
    <?php if ($pillars): ?>
      <section class="tld-section tld-pillars-section bg-off-white" id="what-we-do">
        <div class="container">
          <div class="text-center mb-5">
            <p class="tld-eyebrow tld-reveal">What We Do</p>
            <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($pillars_heading ?: 'What this service includes'); ?></h2>
          </div>
          <div class="row g-4">
            <?php foreach ($pillars as $i => $pillar): ?>
              <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
                <div class="tld-pillar-card">
                  <span class="tld-pillar-card__numeral"><?= esc_html(str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                  <h3 class="tld-pillar-title"><?= esc_html($pillar['title']); ?></h3>
                  <p class="tld-pillar-text"><?= esc_html($pillar['description']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 4: Pull-quote breath -->
    <?php if ($pullquote_text): ?>
      <section class="tld-service-pullquote tld-reveal">
        <div class="container">
          <span class="tld-service-pullquote__mark" aria-hidden="true">&ldquo;</span>
          <p class="tld-service-pullquote__text"><?= esc_html($pullquote_text); ?></p>
          <?php if ($pullquote_attr): ?>
            <p class="tld-service-pullquote__attribution"><?= esc_html($pullquote_attr); ?></p>
          <?php endif; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 5: Why us / Faith context (dark anchor) -->
    <?php if ($whyus): ?>
      <section class="tld-section tld-whyus-section<?= $whyus_bg ? ' has-bg-image' : ''; ?>"<?php if ($whyus_bg): ?> style="background-image: url('<?= esc_url($whyus_bg); ?>');"<?php endif; ?>>
        <div class="container">
          <div class="text-center mb-5">
            <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">The Difference</p>
            <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($whyus_heading ?: 'Why churches choose us'); ?></h2>
          </div>
          <div class="row g-4">
            <?php foreach ($whyus as $i => $item): ?>
              <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
                <div class="tld-whyus-card">
                  <div class="tld-whyus-icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/>
                    </svg>
                  </div>
                  <h3 class="tld-whyus-title"><?= esc_html($item['title']); ?></h3>
                  <p class="tld-whyus-desc"><?= esc_html($item['description']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 6: Process / How we work -->
    <?php if ($steps): ?>
      <section class="tld-section tld-process-dark<?= $process_bg ? ' has-bg-image' : ''; ?>"<?php if ($process_bg): ?> style="background-image: url('<?= esc_url($process_bg); ?>');"<?php endif; ?> id="how-we-work">
        <div class="container">
          <div class="text-center mb-5">
            <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">The Process</p>
            <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($process_heading ?: 'How we work'); ?></h2>
          </div>
          <div class="row g-4">
            <?php foreach ($steps as $i => $step): ?>
              <div class="col-md-6 col-lg-3 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
                <div class="tld-step-card">
                  <span class="tld-step-number"><?= esc_html(str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                  <h3 class="tld-step-title"><?= esc_html($step['title']); ?></h3>
                  <p class="tld-step-text"><?= esc_html($step['description']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 7: FAQ accordion -->
    <?php if ($faqs): ?>
      <section class="tld-section tld-faq-modern bg-off-white">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8">
              <div class="text-center mb-5">
                <p class="tld-eyebrow tld-reveal">FAQ</p>
                <h2 class="tld-heading-section tld-reveal tld-reveal-d1">Frequently asked questions</h2>
              </div>
              <div class="accordion tld-accordion tld-reveal tld-reveal-d2" id="serviceFaq">
                <?php foreach ($faqs as $i => $faq): $i++; ?>
                  <div class="accordion-item">
                    <h3 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-<?= $i; ?>">
                        <?= esc_html($faq['question']); ?>
                      </button>
                    </h3>
                    <div id="faq-<?= $i; ?>" class="accordion-collapse collapse" data-bs-parent="#serviceFaq">
                      <div class="accordion-body"><?= wp_kses_post($faq['answer']); ?></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Section 8: Closing CTA -->
    <?php tld_render_cta(); ?>

  <?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>
