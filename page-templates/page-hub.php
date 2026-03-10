<?php
/**
 * Template Name: Audience Hub
 *
 * Structured multi-section landing page for "Who We Serve" audience hubs.
 * Hero → Intro → Challenge → What We Do (pillar cards) → Closing → Services → CTA.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();

// ── ACF Fields ──
$subtitle          = function_exists('get_field') ? get_field('hero_subtitle') : '';
$eyebrow           = function_exists('get_field') ? get_field('hero_eyebrow') : 'Who We Serve';
$intro_heading     = function_exists('get_field') ? get_field('hub_intro_heading') : '';
$intro_text        = function_exists('get_field') ? get_field('hub_intro_text') : '';
$challenge_eyebrow = function_exists('get_field') ? get_field('hub_challenge_eyebrow') : '';
$challenge_heading = function_exists('get_field') ? get_field('hub_challenge_heading') : '';
$challenge_text    = function_exists('get_field') ? get_field('hub_challenge_text') : '';
$challenge_points  = function_exists('get_field') ? get_field('hub_challenge_points') : [];
$services_eyebrow  = function_exists('get_field') ? get_field('hub_services_eyebrow') : '';
$services_heading  = function_exists('get_field') ? get_field('hub_services_heading') : '';
$services          = function_exists('get_field') ? get_field('hub_services') : [];
$closing_heading   = function_exists('get_field') ? get_field('hub_closing_heading') : '';
$closing_text      = function_exists('get_field') ? get_field('hub_closing_text') : '';
?>

<main id="primary" class="site-main">

  <!-- ════════════════════════════════════════════════
       SECTION 1: HERO
       ════════════════════════════════════════════════ -->
  <?php tld_render_hero(get_the_title(), $subtitle, $eyebrow); ?>


  <!-- ════════════════════════════════════════════════
       SECTION 2: INTRO STATEMENT
       ════════════════════════════════════════════════ -->
  <?php if ($intro_heading) : ?>
  <section class="tld-section tld-problem-statement">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-9">
          <h2 class="tld-problem-heading tld-reveal"><?= wp_kses_post($intro_heading); ?></h2>
          <?php if ($intro_text) : ?>
            <p class="tld-problem-text tld-reveal tld-reveal-d1"><?= esc_html($intro_text); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 3: THE CHALLENGE
       ════════════════════════════════════════════════ -->
  <?php if ($challenge_heading || $challenge_points) : ?>
  <section class="tld-section bg-off-white">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <?php if ($challenge_eyebrow) : ?>
            <p class="tld-eyebrow tld-reveal"><?= esc_html($challenge_eyebrow); ?></p>
          <?php endif; ?>
          <?php if ($challenge_heading) : ?>
            <h2 class="tld-heading-section tld-reveal tld-reveal-d1 mb-4"><?= esc_html($challenge_heading); ?></h2>
          <?php endif; ?>
          <?php if ($challenge_text) :
            $paragraphs = preg_split('/\n\s*\n/', trim($challenge_text));
            foreach ($paragraphs as $para) :
          ?>
            <p class="tld-problem-text tld-reveal tld-reveal-d2"><?= esc_html(trim($para)); ?></p>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <?php if ($challenge_points) : ?>
        <div class="row g-3 justify-content-center mt-4">
          <?php $i = 0; foreach ($challenge_points as $point) : $i++; ?>
            <div class="col-md-6 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
              <div class="tld-hub-benefit-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022z"/>
                </svg>
                <span><?= esc_html($point['text']); ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 4: WHAT WE DO (PILLAR CARDS)
       ════════════════════════════════════════════════ -->
  <?php if ($services) : ?>
  <section class="tld-section tld-pillars-section">
    <div class="container">
      <div class="text-center mb-5">
        <p class="tld-eyebrow tld-reveal"><?= esc_html($services_eyebrow ?: 'What We Do'); ?></p>
        <h2 class="tld-heading-section tld-reveal tld-reveal-d1"><?= esc_html($services_heading ?: 'How we can help'); ?></h2>
      </div>
      <div class="row g-4">
        <?php
        $count = count($services);
        $col_class = $count <= 3 ? 'col-md-6 col-lg-4' : ($count === 4 ? 'col-md-6 col-lg-3' : 'col-md-6 col-lg-4');
        $i = 0;
        foreach ($services as $service) : $i++;
        ?>
          <div class="<?= $col_class; ?> tld-reveal tld-reveal-d<?= min($i, 3); ?>">
            <div class="tld-pillar-card">
              <h3 class="tld-pillar-title"><?= esc_html($service['title']); ?></h3>
              <p class="tld-pillar-text"><?= esc_html($service['description']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 5: CLOSING
       ════════════════════════════════════════════════ -->
  <?php if ($closing_heading || $closing_text) : ?>
  <section class="tld-section tld-whyus-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <?php if ($closing_heading) : ?>
            <h2 class="tld-heading-section text-white tld-reveal mb-4"><?= esc_html($closing_heading); ?></h2>
          <?php endif; ?>
          <?php if ($closing_text) :
            $paragraphs = preg_split('/\n\s*\n/', trim($closing_text));
            foreach ($paragraphs as $para) :
          ?>
            <p class="tld-reveal tld-reveal-d1" style="color: rgba(255,255,255,0.75); font-size: 1.125rem; line-height: 1.8;"><?= esc_html(trim($para)); ?></p>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 6: LINKED SERVICES
       ════════════════════════════════════════════════ -->
  <?php
  $linked = function_exists('get_field') ? get_field('linked_services') : [];
  if ($linked) :
    $linked_posts = tld_get_service_pages($linked);
  ?>
    <section class="tld-section bg-off-white">
      <div class="container">
        <div class="text-center mb-5">
          <p class="tld-eyebrow tld-reveal">Explore More</p>
          <h2 class="tld-heading-section tld-reveal tld-reveal-d1">How We Can Help</h2>
        </div>
        <div class="row g-4">
          <?php $i = 0; foreach ($linked_posts as $lp) : $i++; ?>
            <div class="col-md-6 col-lg-4 tld-reveal tld-reveal-d<?= min($i, 3); ?>">
              <?php get_template_part('template-parts/service-card', null, ['post' => $lp]); ?>
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
