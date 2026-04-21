<?php
/**
 * Template Name: Services Landing
 *
 * Four-section services hub: Hero → Audiences → Services → CTA.
 * Content is hardcoded (stable) matching the post-redesign homepage pattern.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();

// Hero image — page Featured Image with Unsplash fallback (same pattern as homepage).
$hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full')
  ?: 'https://images.unsplash.com/photo-1519741497674-611481863552?w=2000&q=75';

$audiences = [
  [
    'num'   => '01',
    'title' => 'Christian Businesses',
    'text'  => 'You want growth without the usual tradeoffs. We help founders and leadership teams sharpen their message, improve their website, and turn digital marketing into a real business asset.',
    'url'   => '/christian-business/',
  ],
  [
    'num'   => '02',
    'title' => 'Churches & Ministries',
    'text'  => 'The people you have not met yet are searching online. We help churches improve discoverability, modernize their digital presence, and use tools like SEO and AI with wisdom.',
    'url'   => '/churches-ministries/',
  ],
  [
    'num'   => '03',
    'title' => 'Catholic Organisations',
    'text'  => 'Parishes, dioceses, schools, and apostolates need digital work that feels reverent, credible, and clear. We build for Catholic audiences with real familiarity.',
    'url'   => '/catholic-organisations/',
  ],
];

$services = [
  [
    'num'   => '01',
    'title' => 'Web Design &amp; Development',
    'desc'  => 'Sites that explain who you are quickly, guide the next step, perform well on mobile, and give your team a platform you can grow into.',
    'url'   => '/services/christian-web-design/',
  ],
  [
    'num'   => '02',
    'title' => 'Search Engine Optimisation',
    'desc'  => 'Churches need local visibility. Christian businesses need qualified discovery. Pages that rank for what you actually do.',
    'url'   => '/services/seo-for-churches/',
  ],
  [
    'num'   => '03',
    'title' => 'Branding &amp; Messaging',
    'desc'  => 'Define your voice, sharpen your message, and build an identity system that looks credible and feels aligned with your mission.',
    'url'   => '/services/christian-branding/',
  ],
  [
    'num'   => '04',
    'title' => 'AI Strategy &amp; Implementation',
    'desc'  => 'Practical workflows, guardrails, and a trusted guide. Save time, improve communication, and keep human oversight where it belongs.',
    'url'   => '/services/ai-for-churches/',
  ],
];

$arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>';
?>
<main id="primary" class="site-main">

  <?php if (have_posts()): while (have_posts()): the_post(); ?>

    <!-- Section 1: Hero -->
    <header class="formation-hero--image" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
      <div class="container">
        <span class="formation-hero--image__eyebrow">Direct help for the Church&rsquo;s communications</span>
        <h1 class="formation-hero--image__title">When reading isn&rsquo;t enough.</h1>
        <p class="formation-hero--image__intro">Sometimes a parish, ministry, or Christian business needs someone to do the work with them &mdash; a website rebuilt, a search presence that actually finds the people looking, branding that feels like the mission, practical AI that doesn&rsquo;t embarrass the Church. We do that.</p>
        <div class="formation-hero--image__ctas">
          <a class="btn tld-btn-gold btn-lg tld-btn-arrow" href="#" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">Book a Discovery Call <?= $arrow_svg; ?></a>
          <a class="btn btn-outline-light btn-lg" href="#what-we-do">See what we do</a>
        </div>
      </div>
    </header>

    <!-- Section 2: Audiences -->
    <section class="tld-section">
      <div class="container">
        <div class="text-center mb-5">
          <p class="tld-eyebrow tld-reveal">Who We Serve</p>
          <h2 class="tld-heading-section tld-reveal tld-reveal-d1">Choose the path that fits your mission</h2>
        </div>
        <div class="row g-4">
          <?php foreach ($audiences as $i => $a): ?>
            <div class="col-lg-4 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
              <a href="<?= esc_url(home_url($a['url'])); ?>" class="tld-hover-card">
                <span class="tld-hover-card-number"><?= esc_html($a['num']); ?></span>
                <h3 class="tld-hover-card-title"><?= esc_html($a['title']); ?></h3>
                <p class="tld-hover-card-text"><?= esc_html($a['text']); ?></p>
                <span class="tld-hover-card-link">
                  Learn more
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
                </span>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Section 3: Services -->
    <section class="tld-section bg-off-white" id="what-we-do">
      <div class="container">
        <div class="text-center mb-5">
          <p class="tld-eyebrow tld-reveal">What We Do</p>
          <h2 class="tld-heading-section tld-reveal tld-reveal-d1">How we help, in practice.</h2>
        </div>
        <div class="tld-service-stack">
          <?php foreach ($services as $i => $s): ?>
            <a href="<?= esc_url(home_url($s['url'])); ?>" class="tld-service-row tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
              <span class="tld-service-row-num"><?= esc_html($s['num']); ?></span>
              <div class="tld-service-row-content">
                <h3 class="tld-service-row-title"><?= $s['title']; ?></h3>
                <p class="tld-service-row-desc"><?= $s['desc']; ?></p>
              </div>
              <span class="tld-service-row-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
              </span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Section 4: CTA -->
    <section class="tld-cta-modern">
      <div class="container">
        <div class="tld-cta-modern-inner">
          <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">Get Started</p>
          <h2 class="tld-cta-modern-heading tld-reveal tld-reveal-d1">Ready to build something stronger?</h2>
          <p class="tld-cta-modern-text tld-reveal tld-reveal-d2">A digital partner who understands faith, takes outcomes seriously, and knows how to make strategy usable. We work best with organisations that care about clarity, move with purpose, and invest in work that lasts.</p>
          <div class="tld-reveal tld-reveal-d3">
            <a href="#" class="btn tld-btn-gold btn-lg tld-btn-arrow" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
              Book a Discovery Call
              <?= $arrow_svg; ?>
            </a>
          </div>
        </div>
      </div>
    </section>

  <?php endwhile; endif; ?>

</main>
<?php get_footer(); ?>
