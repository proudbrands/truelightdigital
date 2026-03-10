<?php
/**
 * Homepage: Services — Full-width hover cards with reveal text
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$services = [
  [
    'title' => 'Web Design &amp; Development',
    'desc'  => 'Sites that explain who you are quickly, guide the next step, perform well on mobile, and give your team a platform you can grow into.',
    'url'   => '/services/christian-web-design/',
    'num'   => '01',
  ],
  [
    'title' => 'Search Engine Optimisation',
    'desc'  => 'Churches need local visibility. Christian businesses need qualified discovery. Pages that rank for what you actually do.',
    'url'   => '/services/seo-for-churches/',
    'num'   => '02',
  ],
  [
    'title' => 'Branding &amp; Messaging',
    'desc'  => 'Define your voice, sharpen your message, and build an identity system that looks credible and feels aligned with your mission.',
    'url'   => '/services/christian-branding/',
    'num'   => '03',
  ],
  [
    'title' => 'AI Strategy &amp; Implementation',
    'desc'  => 'Practical workflows, guardrails, and a trusted guide. Save time, improve communication, and keep human oversight where it belongs.',
    'url'   => '/services/ai-for-churches/',
    'num'   => '04',
  ],
];
?>

<section class="tld-section bg-off-white">
  <div class="container">

    <div class="text-center mb-5">
      <p class="tld-eyebrow tld-reveal">Our Services</p>
      <h2 class="tld-heading-section tld-reveal tld-reveal-d1">What we help you do</h2>
    </div>

    <div class="tld-service-stack">
      <?php foreach ($services as $i => $svc) : ?>
        <a href="<?= esc_url(home_url($svc['url'])); ?>" class="tld-service-row tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
          <span class="tld-service-row-num"><?= $svc['num']; ?></span>
          <div class="tld-service-row-content">
            <h3 class="tld-service-row-title"><?= $svc['title']; ?></h3>
            <p class="tld-service-row-desc"><?= $svc['desc']; ?></p>
          </div>
          <span class="tld-service-row-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
          </span>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-5 tld-reveal">
      <a href="<?= esc_url(home_url('/services/')); ?>" class="btn btn-outline-primary btn-lg tld-btn-arrow">
        View All Services
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
      </a>
    </div>

  </div>
</section>
