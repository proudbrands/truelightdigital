<?php
/**
 * Template Name: Service Page
 *
 * Modern multi-section service landing page inspired by agency design.
 * Structured ACF sections render as designed blocks; the_content()
 * provides narrative body text between them.
 *
 * Sections: Hero -> Intro Statement -> Pillars Grid -> Body Content ->
 *           Process Steps -> Results -> FAQ -> Related Services -> CTA
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();

// ── ACF Fields ──
$subtitle       = function_exists('get_field') ? get_field('hero_subtitle') : '';
$eyebrow        = function_exists('get_field') ? get_field('hero_eyebrow') : 'Our Services';
$cta1_text      = function_exists('get_field') ? get_field('hero_cta_primary_text') : '';
$cta1_url       = function_exists('get_field') ? get_field('hero_cta_primary_url') : '';
$cta2_text      = function_exists('get_field') ? get_field('hero_cta_secondary_text') : '';
$cta2_url       = function_exists('get_field') ? get_field('hero_cta_secondary_url') : '';
$intro_stmt     = function_exists('get_field') ? get_field('intro_statement') : '';
$intro_text     = function_exists('get_field') ? get_field('intro_text') : '';
$pillars        = function_exists('get_field') ? get_field('service_pillars') : [];
$pillars_heading = function_exists('get_field') ? get_field('pillars_heading') : '';
$steps          = function_exists('get_field') ? get_field('process_steps') : [];
$process_heading = function_exists('get_field') ? get_field('process_heading') : '';
$results        = function_exists('get_field') ? get_field('results_items') : [];
$results_heading = function_exists('get_field') ? get_field('results_heading') : '';
$faqs           = function_exists('get_field') ? get_field('faq_items') : [];
$stats          = function_exists('get_field') ? get_field('stat_items') : [];
$stats_heading  = function_exists('get_field') ? get_field('stats_heading') : '';
$problems       = function_exists('get_field') ? get_field('problem_items') : [];
$problems_heading = function_exists('get_field') ? get_field('problems_heading') : '';
$problems_intro = function_exists('get_field') ? get_field('problems_intro') : '';
$audience       = function_exists('get_field') ? get_field('audience_items') : [];
$audience_heading = function_exists('get_field') ? get_field('audience_heading') : '';
$whyus          = function_exists('get_field') ? get_field('whyus_items') : [];
$whyus_heading  = function_exists('get_field') ? get_field('whyus_heading') : '';
$stats_bg       = function_exists('get_field') ? get_field('stats_bg_image') : '';
$whyus_bg       = function_exists('get_field') ? get_field('whyus_bg_image') : '';
$process_bg     = function_exists('get_field') ? get_field('process_bg_image') : '';
$has_visual     = $stats || $problems || $audience || $whyus;

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>';
?>

<main id="primary" class="site-main">

  <!-- ════════════════════════════════════════════════
       SECTION 1: HERO
       ════════════════════════════════════════════════ -->
  <section class="tld-hero-service">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <?php if ($eyebrow) : ?>
            <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);"><?= esc_html($eyebrow); ?></p>
          <?php endif; ?>
          <h1 class="tld-hero-service-title tld-reveal tld-reveal-d1"><?= esc_html(get_the_title()); ?></h1>
          <?php if ($subtitle) : ?>
            <p class="tld-hero-service-subtitle tld-reveal tld-reveal-d2"><?= esc_html($subtitle); ?></p>
          <?php endif; ?>
          <?php if ($cta1_text || $cta2_text) : ?>
            <div class="tld-hero-service-ctas tld-reveal tld-reveal-d3">
              <?php if ($cta1_text) : ?>
                <a href="#" class="btn tld-btn-gold btn-lg tld-btn-arrow" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
                  <?= esc_html($cta1_text); ?><?= $arrow_svg; ?>
                </a>
              <?php endif; ?>
              <?php if ($cta2_text) : ?>
                <a href="<?= esc_url($cta2_url ?: '#how-we-work'); ?>" class="btn btn-outline-light btn-lg tld-btn-arrow">
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
       SECTION 2: PROBLEM STATEMENT
       ════════════════════════════════════════════════ -->
  <?php if ($intro_stmt) : ?>
  <section class="tld-section tld-problem-statement">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9 text-center">
          <h2 class="tld-problem-heading tld-reveal"><?= wp_kses_post($intro_stmt); ?></h2>
          <?php if ($intro_text) : ?>
            <p class="tld-problem-text tld-reveal tld-reveal-d1"><?= esc_html($intro_text); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 3: SERVICE PILLARS
       ════════════════════════════════════════════════ -->
  <?php if ($pillars) : ?>
  <section class="tld-section tld-pillars-section">
    <div class="container">
      <div class="text-center mb-5">
        <p class="tld-eyebrow tld-reveal">What We Do</p>
        <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($pillars_heading ?: 'What this service includes'); ?></h2>
      </div>
      <div class="row g-4">
        <?php $i = 0; foreach ($pillars as $pillar) : $i++; ?>
          <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
            <div class="tld-pillar-card">
              <h3 class="tld-pillar-title"><?= esc_html($pillar['title']); ?></h3>
              <p class="tld-pillar-text"><?= esc_html($pillar['description']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 4: VISUAL CONTENT (or Gutenberg fallback)
       ════════════════════════════════════════════════ -->
  <?php if ($has_visual) : ?>

    <?php // ── 4A: Stats Strip (dark) ── ?>
    <?php if ($stats) : ?>
    <section class="tld-section tld-stats-strip<?= $stats_bg ? ' has-bg-image' : ''; ?>"<?php if ($stats_bg) : ?> style="background-image: url('<?= esc_url($stats_bg); ?>');"<?php endif; ?>>
      <div class="container">
        <?php if ($stats_heading) : ?>
          <div class="text-center mb-5">
            <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">The Reality</p>
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

    <?php // ── 4B: What Changes (positive benefits) ── ?>
    <?php if ($problems) : ?>
    <section class="tld-section bg-off-white">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <p class="tld-eyebrow tld-reveal">What Changes</p>
            <h2 class="tld-heading-section tld-reveal tld-reveal-d1 mb-4"><?= esc_html($problems_heading ?: 'What changes when you work with us'); ?></h2>
            <?php if ($problems_intro) : ?>
              <p class="tld-problem-text tld-reveal tld-reveal-d2"><?= esc_html($problems_intro); ?></p>
            <?php endif; ?>
          </div>
        </div>
        <div class="row g-3 justify-content-center mt-4">
          <?php $i = 0; foreach ($problems as $problem) : $i++; ?>
            <div class="col-md-6 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
              <div class="tld-hub-benefit-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/>
                </svg>
                <span><?= esc_html($problem['text']); ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <?php // ── 4C: Audience Cards ── ?>
    <?php if ($audience) : ?>
    <section class="tld-section tld-audience-section">
      <div class="container">
        <div class="text-center mb-5">
          <p class="tld-eyebrow tld-reveal">Built For</p>
          <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($audience_heading ?: 'Who this is for'); ?></h2>
        </div>
        <div class="row g-4 justify-content-center">
          <?php $i = 0; foreach ($audience as $card) : $i++; ?>
            <div class="col-md-6 col-lg-3 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
              <div class="tld-audience-card">
                <span class="tld-audience-number"><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
                <h3 class="tld-audience-title"><?= esc_html($card['title']); ?></h3>
                <p class="tld-audience-desc"><?= esc_html($card['description']); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <?php // ── 4D: Why Us / Differentiators (dark) ── ?>
    <?php if ($whyus) : ?>
    <section class="tld-section tld-whyus-section<?= $whyus_bg ? ' has-bg-image' : ''; ?>"<?php if ($whyus_bg) : ?> style="background-image: url('<?= esc_url($whyus_bg); ?>');"<?php endif; ?>>
      <div class="container">
        <div class="text-center mb-5">
          <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">The Difference</p>
          <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($whyus_heading ?: 'Why churches choose us'); ?></h2>
        </div>
        <div class="row g-4">
          <?php $i = 0; foreach ($whyus as $item) : $i++; ?>
            <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
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

  <?php else : ?>
    <?php // Fallback: render Gutenberg content if no visual sections ?>
    <article class="tld-section tld-service-content">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 tld-reveal">
            <?php
            while (have_posts()) :
              the_post();
              the_content();
            endwhile;
            ?>
          </div>
        </div>
      </div>
    </article>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 5: PROCESS STEPS
       ════════════════════════════════════════════════ -->
  <?php if ($steps) : ?>
  <section class="tld-section tld-process-dark<?= $process_bg ? ' has-bg-image' : ''; ?>"<?php if ($process_bg) : ?> style="background-image: url('<?= esc_url($process_bg); ?>');"<?php endif; ?> id="how-we-work">
    <div class="container">
      <div class="text-center mb-5">
        <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">The Process</p>
        <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($process_heading ?: 'How we work'); ?></h2>
      </div>
      <div class="row g-4">
        <?php $i = 0; foreach ($steps as $step) : $i++; ?>
          <div class="col-md-6 col-lg-3 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
            <div class="tld-step-card">
              <span class="tld-step-number"><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></span>
              <h3 class="tld-step-title"><?= esc_html($step['title']); ?></h3>
              <p class="tld-step-text"><?= esc_html($step['description']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 6: RESULTS / IMPACT
       ════════════════════════════════════════════════ -->
  <?php if ($results) : ?>
  <section class="tld-section tld-results-section">
    <div class="container">
      <div class="text-center mb-5">
        <p class="tld-eyebrow tld-reveal">The Impact</p>
        <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($results_heading ?: 'What results should you expect'); ?></h2>
      </div>
      <div class="row g-4">
        <?php $i = 0; foreach ($results as $result) : $i++; ?>
          <div class="col-md-6 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
            <div class="tld-result-card">
              <h3 class="tld-result-title"><?= esc_html($result['title']); ?></h3>
              <p class="tld-result-text"><?= esc_html($result['description']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 7: FAQ ACCORDION
       ════════════════════════════════════════════════ -->
  <?php if ($faqs) : ?>
  <section class="tld-section tld-faq-modern">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="text-center mb-5">
            <p class="tld-eyebrow tld-reveal">FAQ</p>
            <h2 class="tld-heading-section tld-reveal tld-reveal-d1">Frequently asked questions</h2>
          </div>
          <div class="accordion tld-accordion tld-reveal tld-reveal-d2" id="serviceFaq">
            <?php $i = 0; foreach ($faqs as $faq) : $i++; ?>
              <div class="accordion-item">
                <h3 class="accordion-header">
                  <button class="accordion-button collapsed" type="button"
                          data-bs-toggle="collapse" data-bs-target="#faq-<?= $i; ?>">
                    <?= esc_html($faq['question']); ?>
                  </button>
                </h3>
                <div id="faq-<?= $i; ?>" class="accordion-collapse collapse" data-bs-parent="#serviceFaq">
                  <div class="accordion-body">
                    <?= wp_kses_post($faq['answer']); ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 8: RELATED SERVICES
       ════════════════════════════════════════════════ -->
  <?php
  $related = function_exists('get_field') ? get_field('related_services') : [];
  $related_posts = $related ? tld_get_service_pages($related) : tld_get_service_pages();
  $related_posts = array_filter($related_posts, function ($rp) {
    return $rp->ID !== get_the_ID();
  });
  $related_posts = array_slice($related_posts, 0, 3);

  if ($related_posts) :
  ?>
    <section class="tld-section bg-off-white">
      <div class="container">
        <div class="text-center mb-5">
          <p class="tld-eyebrow tld-reveal">Explore More</p>
          <h2 class="tld-heading-section tld-reveal tld-reveal-d1">Related Services</h2>
        </div>
        <div class="row g-4">
          <?php
          $i = 0;
          foreach ($related_posts as $rp) :
            $i++;
          ?>
            <div class="col-md-4 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
              <?php get_template_part('template-parts/service-card', null, ['post' => $rp]); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 9: CTA
       ════════════════════════════════════════════════ -->
  <?php tld_render_cta(); ?>

</main>

<?php get_footer(); ?>
