<?php
/**
 * Homepage: Hero — Modern split layout with staggered reveals
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$hero_bg = function_exists('get_field') ? get_field('home_hero_bg_image', 'option') : '';
?>

<section class="tld-hero tld-hero-home<?= $hero_bg ? ' has-bg-image' : ''; ?>"<?php if ($hero_bg) : ?> style="background-image: url('<?= esc_url($hero_bg); ?>');"<?php endif; ?>>
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">

        <p class="tld-hero-label tld-reveal">Christian &amp; Catholic Digital Agency</p>

        <h1 class="tld-hero-home-title tld-reveal tld-reveal-d1">
          Digital growth<br>
          built on conviction.<br>
          <span class="tld-text-accent">Measured by results.</span>
        </h1>

        <p class="tld-hero-subtitle tld-reveal tld-reveal-d2">
          Strategy, websites, SEO, branding, and practical AI support for Christian businesses, churches, ministries, and Catholic organizations.
        </p>

        <div class="d-flex gap-3 flex-wrap tld-reveal tld-reveal-d3">
          <a href="#" class="btn tld-btn-gold btn-lg tld-btn-arrow" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
            Book a Discovery Call
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
          </a>
          <a href="<?= esc_url(home_url('/services/')); ?>" class="btn btn-outline-light btn-lg">Explore Services</a>
        </div>

      </div>
      <div class="col-lg-5 d-none d-lg-block">
        <div class="tld-hero-metrics tld-reveal tld-reveal-d2">
          <div class="tld-metric">
            <span class="tld-metric-number" data-count="14">0</span><span class="tld-metric-plus">+</span>
            <span class="tld-metric-label">Years in digital strategy</span>
          </div>
          <div class="tld-metric">
            <span class="tld-metric-number" data-count="100">0</span><span class="tld-metric-plus">%</span>
            <span class="tld-metric-label">Faith-aligned work</span>
          </div>
          <div class="tld-metric">
            <span class="tld-metric-number">3</span><span class="tld-metric-plus">x</span>
            <span class="tld-metric-label">Average organic traffic increase</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
