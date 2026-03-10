<?php
/**
 * Homepage: Audience Pathways — Hover-reveal cards, no icons
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;
?>

<section class="tld-section">
  <div class="container">

    <div class="text-center mb-5">
      <p class="tld-eyebrow tld-reveal">Who We Work With</p>
      <h2 class="tld-heading-section tld-reveal tld-reveal-d1">Choose the path that fits your mission</h2>
    </div>

    <div class="row g-4">

      <div class="col-lg-4 tld-reveal tld-reveal-d1">
        <a href="<?= esc_url(home_url('/christian-business/')); ?>" class="tld-hover-card">
          <span class="tld-hover-card-number">01</span>
          <h3 class="tld-hover-card-title">Christian Businesses</h3>
          <p class="tld-hover-card-text">You want growth without the usual tradeoffs. We help founders and leadership teams sharpen their message, improve their website, and turn digital marketing into a real business asset.</p>
          <span class="tld-hover-card-link">
            Learn more
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
          </span>
        </a>
      </div>

      <div class="col-lg-4 tld-reveal tld-reveal-d2">
        <a href="<?= esc_url(home_url('/churches-ministries/')); ?>" class="tld-hover-card">
          <span class="tld-hover-card-number">02</span>
          <h3 class="tld-hover-card-title">Churches &amp; Ministries</h3>
          <p class="tld-hover-card-text">The people you have not met yet are searching online. We help churches improve discoverability, modernize their digital presence, and use tools like SEO and AI with wisdom.</p>
          <span class="tld-hover-card-link">
            Learn more
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
          </span>
        </a>
      </div>

      <div class="col-lg-4 tld-reveal tld-reveal-d3">
        <a href="<?= esc_url(home_url('/catholic-organisations/')); ?>" class="tld-hover-card">
          <span class="tld-hover-card-number">03</span>
          <h3 class="tld-hover-card-title">Catholic Organizations</h3>
          <p class="tld-hover-card-text">Parishes, dioceses, schools, and apostolates need digital work that feels reverent, credible, and clear. We build for Catholic audiences with real familiarity.</p>
          <span class="tld-hover-card-link">
            Learn more
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
          </span>
        </a>
      </div>

    </div>

  </div>
</section>
