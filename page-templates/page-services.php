<?php
/**
 * Template Name: Services Landing
 *
 * Rich, immersive services hub page showcasing all service offerings
 * and audience pathways.
 *
 * Sections: Hero -> Intro -> Services Grid -> Who We Serve ->
 *           Approach -> Stats -> CTA
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();

// ── ACF Fields ──────────────────────────────────────
// Hero
$eyebrow   = get_field('services_hero_eyebrow') ?: '';
$subtitle  = get_field('services_hero_subtitle') ?: '';
$cta1_text = get_field('services_hero_cta_text') ?: '';
$cta2_text = get_field('services_hero_cta2_text') ?: '';
$cta2_url  = get_field('services_hero_cta2_url') ?: '';

// Intro
$intro_stmt = get_field('services_intro_statement') ?: '';
$intro_text = get_field('services_intro_text') ?: '';

// Services grid
$services_eyebrow = get_field('services_grid_eyebrow') ?: '';
$services_heading = get_field('services_grid_heading') ?: '';

// Who We Serve
$serve_eyebrow = get_field('services_serve_eyebrow') ?: '';
$serve_heading = get_field('services_serve_heading') ?: '';
$serve_text    = get_field('services_serve_text') ?: '';
$audiences     = get_field('services_audiences') ?: [];

// Approach
$approach_eyebrow = get_field('services_approach_eyebrow') ?: '';
$approach_heading = get_field('services_approach_heading') ?: '';
$approach_text    = get_field('services_approach_text') ?: '';
$approach_points  = get_field('services_approach_points') ?: [];

// Stats
$stats_heading = get_field('services_stats_heading') ?: '';
$stats_bg      = get_field('services_stats_bg_image') ?: '';
$stats         = get_field('services_stat_items') ?: [];

// Arrow SVG for buttons
$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>';
?>

<main id="primary" class="site-main">

  <!-- ════════════════════════════════════════════════
       SECTION 1: HERO
       ════════════════════════════════════════════════ -->
  <section class="tld-hero-service">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9 text-center">
          <?php if ($eyebrow) : ?>
            <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);"><?= esc_html($eyebrow); ?></p>
          <?php endif; ?>
          <h1 class="tld-hero-service-title tld-reveal tld-reveal-d1"><?= esc_html(get_the_title()); ?></h1>
          <?php if ($subtitle) : ?>
            <p class="tld-hero-service-subtitle mx-auto tld-reveal tld-reveal-d2" style="text-align: center;"><?= esc_html($subtitle); ?></p>
          <?php endif; ?>
          <?php if ($cta1_text || $cta2_text) : ?>
            <div class="tld-hero-service-ctas justify-content-center tld-reveal tld-reveal-d3">
              <?php if ($cta1_text) : ?>
                <a href="#" class="btn tld-btn-gold btn-lg tld-btn-arrow" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
                  <?= esc_html($cta1_text); ?><?= $arrow_svg; ?>
                </a>
              <?php endif; ?>
              <?php if ($cta2_text) : ?>
                <a href="<?= esc_url($cta2_url ?: '#our-services'); ?>" class="btn btn-outline-light btn-lg tld-btn-arrow">
                  <?= esc_html($cta2_text); ?><?= $arrow_svg; ?>
                </a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>


  <!-- ════════════════════════════════════════════════
       SECTION 2: INTRO STATEMENT
       ════════════════════════════════════════════════ -->
  <?php if ($intro_stmt) : ?>
  <section class="tld-section tld-problem-statement">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9 text-center">
          <h2 class="tld-problem-heading tld-reveal"><?= wp_kses_post($intro_stmt); ?></h2>
          <?php if ($intro_text) : ?>
            <div class="tld-gold-divider-center tld-reveal tld-reveal-d1"></div>
            <p class="tld-problem-text tld-reveal tld-reveal-d2"><?= esc_html($intro_text); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 3: SERVICES GRID (auto from child pages)
       ════════════════════════════════════════════════ -->
  <?php
  $service_pages = tld_get_service_pages();
  if ($service_pages) :
  ?>
  <section class="tld-section tld-services-grid-section" id="our-services">
    <div class="container">
      <div class="text-center mb-5">
        <p class="tld-eyebrow tld-reveal"><?= esc_html($services_eyebrow ?: 'What We Offer'); ?></p>
        <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($services_heading ?: 'Our Services'); ?></h2>
      </div>
      <div class="row g-4">
        <?php
        $i = 0;
        foreach ($service_pages as $sp) :
          $i++;
        ?>
          <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
            <?php get_template_part('template-parts/service-card', null, ['post' => $sp]); ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 4: WHO WE SERVE (audience hubs)
       ════════════════════════════════════════════════ -->
  <?php if ($audiences) : ?>
  <section class="tld-section tld-whyus-section">
    <div class="container">
      <div class="text-center mb-5">
        <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);"><?= esc_html($serve_eyebrow ?: 'Who We Serve'); ?></p>
        <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($serve_heading ?: 'Built for organisations with a mission'); ?></h2>
        <?php if ($serve_text) : ?>
          <p class="tld-reveal tld-reveal-d2" style="color: rgba(255,255,255,0.7); font-size: 1.0625rem; max-width: 640px; margin: 1rem auto 0;"><?= esc_html($serve_text); ?></p>
        <?php endif; ?>
      </div>
      <div class="row g-4 justify-content-center">
        <?php $i = 0; foreach ($audiences as $audience) : $i++; ?>
          <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
            <a href="<?= esc_url($audience['link'] ?: '#'); ?>" class="tld-audience-hub-card">
              <?php if (!empty($audience['icon'])) : ?>
                <div class="tld-audience-hub-icon">
                  <?= wp_kses_post($audience['icon']); ?>
                </div>
              <?php endif; ?>
              <h3 class="tld-audience-hub-title"><?= esc_html($audience['title']); ?></h3>
              <p class="tld-audience-hub-desc"><?= esc_html($audience['description']); ?></p>
              <span class="tld-audience-hub-link">
                Learn more
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
              </span>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 5: APPROACH / HOW TO START
       ════════════════════════════════════════════════ -->
  <?php if ($approach_heading || $approach_text || $approach_points) : ?>
  <section class="tld-section tld-approach-section">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-5">
          <?php if ($approach_eyebrow) : ?>
            <p class="tld-eyebrow tld-reveal"><?= esc_html($approach_eyebrow); ?></p>
          <?php endif; ?>
          <?php if ($approach_heading) : ?>
            <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($approach_heading); ?></h2>
          <?php endif; ?>
          <?php if ($approach_text) : ?>
            <p class="tld-reveal tld-reveal-d2" style="font-size: 1.0625rem; line-height: 1.8; color: var(--tld-muted);"><?= esc_html($approach_text); ?></p>
          <?php endif; ?>
        </div>
        <?php if ($approach_points) : ?>
          <div class="col-lg-6 offset-lg-1">
            <div class="row g-3">
              <?php $i = 0; foreach ($approach_points as $point) : $i++; ?>
                <div class="col-sm-6 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
                  <div class="tld-approach-card">
                    <span class="tld-approach-number"><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
                    <h3 class="tld-approach-card-title"><?= esc_html($point['title']); ?></h3>
                    <p class="tld-approach-card-text"><?= esc_html($point['description']); ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 6: STATS
       ════════════════════════════════════════════════ -->
  <?php if ($stats) :
    $stats_class = 'tld-section tld-stats-strip';
    if ($stats_bg) {
      $stats_class .= ' has-bg-image';
    }
  ?>
  <section class="<?= esc_attr($stats_class); ?>"<?php if ($stats_bg) : ?> style="background-image: url('<?= esc_url($stats_bg); ?>');"<?php endif; ?>>
    <div class="container">
      <?php if ($stats_heading) : ?>
        <div class="text-center mb-5">
          <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">The Numbers</p>
          <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($stats_heading); ?></h2>
        </div>
      <?php endif; ?>
      <div class="row g-4">
        <?php $i = 0; foreach ($stats as $stat) : $i++; ?>
          <div class="col-6 col-lg-3 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
            <div class="tld-stat-card text-center">
              <span class="tld-stat-number"><?= esc_html($stat['number']); ?></span>
              <h3 class="tld-stat-label"><?= esc_html($stat['label']); ?></h3>
              <?php if (!empty($stat['description'])) : ?>
                <p class="tld-stat-desc"><?= esc_html($stat['description']); ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 7: CTA
       ════════════════════════════════════════════════ -->
  <?php tld_render_cta(); ?>

</main>

<?php get_footer(); ?>
