<?php
/**
 * Homepage: Proof Strip — Bold statement + outcome cards
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$proof_bg = function_exists('get_field') ? get_field('home_proof_bg_image', 'option') : '';
?>

<section class="tld-proof-strip<?= $proof_bg ? ' has-bg-image' : ''; ?>"<?php if ($proof_bg) : ?> style="background-image: url('<?= esc_url($proof_bg); ?>');"<?php endif; ?>>
  <div class="container">

    <div class="row align-items-center">
      <div class="col-lg-5 mb-4 mb-lg-0">
        <h2 class="tld-proof-heading tld-reveal">
          What better<br>looks like
        </h2>
        <div class="tld-gold-divider mt-3 tld-reveal tld-reveal-d1"></div>
      </div>
      <div class="col-lg-7">
        <div class="row g-3">

          <div class="col-sm-6 tld-reveal tld-reveal-d1">
            <div class="tld-proof-card">
              <span class="tld-proof-card-num">01</span>
              <p>A church that appears in local search when a family moves into town and looks for Sunday services.</p>
            </div>
          </div>

          <div class="col-sm-6 tld-reveal tld-reveal-d2">
            <div class="tld-proof-card">
              <span class="tld-proof-card-num">02</span>
              <p>A Christian business website that sounds confident, distinct, and credible instead of generic.</p>
            </div>
          </div>

          <div class="col-sm-6 tld-reveal tld-reveal-d2">
            <div class="tld-proof-card">
              <span class="tld-proof-card-num">03</span>
              <p>A Catholic organization that presents its identity with beauty and seriousness while making the next step obvious.</p>
            </div>
          </div>

          <div class="col-sm-6 tld-reveal tld-reveal-d3">
            <div class="tld-proof-card">
              <span class="tld-proof-card-num">04</span>
              <p>A marketing workflow that saves staff time without sacrificing quality or voice.</p>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>
