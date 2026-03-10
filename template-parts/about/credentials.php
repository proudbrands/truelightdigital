<?php
/**
 * About Page: Credentials — Stats strip + company legal info block
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$stats_heading  = $args['stats_heading'] ?? '';
$stats_bg       = $args['stats_bg'] ?? '';
$stats          = $args['stats'] ?? [];
$legal_name     = $args['legal_name'] ?? '';
$trading_as     = $args['trading_as'] ?? '';
$company_number = $args['company_number'] ?? '';
$location       = $args['location'] ?? '';
$market         = $args['market'] ?? '';
$detail         = $args['detail'] ?? '';
?>

<?php // ── Stats Strip (dark) ── ?>
<?php if ($stats) : ?>
<section class="tld-section tld-stats-strip<?= $stats_bg ? ' has-bg-image' : ''; ?>"<?php if ($stats_bg) : ?> style="background-image: url('<?= esc_url($stats_bg); ?>');"<?php endif; ?>>
  <div class="container">
    <?php if ($stats_heading) : ?>
      <div class="text-center mb-5">
        <p class="tld-eyebrow tld-reveal" style="color: var(--tld-gold);">Track Record</p>
        <h2 class="tld-heading-section text-white tld-reveal tld-reveal-d1"><?= esc_html($stats_heading); ?></h2>
      </div>
    <?php endif; ?>
    <div class="row g-4">
      <?php $i = 0; foreach ($stats as $stat) : $i++; ?>
        <div class="col-6 col-lg-<?= count($stats) <= 3 ? '4' : '3'; ?> tld-reveal tld-reveal-d<?= min($i, 3); ?>">
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

<?php // ── Company Info Block ── ?>
<?php if ($legal_name || $trading_as) : ?>
<section class="tld-section bg-off-white">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="tld-about-company-info tld-reveal">

          <?php if ($legal_name) : ?>
          <div class="tld-about-company-row">
            <span class="tld-about-company-label">Registered Name</span>
            <span class="tld-about-company-value"><?= esc_html($legal_name); ?></span>
          </div>
          <?php endif; ?>

          <?php if ($trading_as) : ?>
          <div class="tld-about-company-row">
            <span class="tld-about-company-label">Trading As</span>
            <span class="tld-about-company-value"><?= esc_html($trading_as); ?></span>
          </div>
          <?php endif; ?>

          <?php if ($company_number) : ?>
          <div class="tld-about-company-row">
            <span class="tld-about-company-label">Company No.</span>
            <span class="tld-about-company-value"><?= esc_html($company_number); ?></span>
          </div>
          <?php endif; ?>

          <?php if ($location) : ?>
          <div class="tld-about-company-row">
            <span class="tld-about-company-label">Based In</span>
            <span class="tld-about-company-value"><?= esc_html($location); ?></span>
          </div>
          <?php endif; ?>

          <?php if ($market) : ?>
          <div class="tld-about-company-row">
            <span class="tld-about-company-label">Market</span>
            <span class="tld-about-company-value"><?= esc_html($market); ?></span>
          </div>
          <?php endif; ?>

          <?php if ($detail) : ?>
          <div class="tld-about-company-detail">
            <?= esc_html($detail); ?>
          </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
