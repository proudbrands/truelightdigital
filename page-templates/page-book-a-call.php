<?php
/**
 * Template Name: Book a Call
 *
 * Dedicated booking page with inline Gravity Form.
 * Linkable URL for campaigns, ads, and email CTAs.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main">

  <!-- Hero -->
  <?php tld_render_hero(
    'Book a Discovery Call',
    'A 30-minute conversation about your goals, your challenges, and whether we are the right fit.',
    'Get Started'
  ); ?>

  <!-- Form + Sidebar -->
  <section class="tld-section">
    <div class="container">
      <div class="row g-5">

        <!-- Form Column -->
        <div class="col-lg-7">
          <div class="tld-booking-form-intro tld-reveal">
            <h2 class="tld-heading-section">Tell us a little about your project</h2>
            <p style="color: var(--tld-muted); font-size: 1.0625rem; line-height: 1.7; margin-bottom: 2rem;">
              Fill in the form below and we will be in touch within one working day to arrange your call. No pressure, no hard sell.
            </p>
          </div>

          <div class="tld-reveal tld-reveal-d1">
            <?php
            if (function_exists('gravity_form')) {
              gravity_form(TLD_DISCOVERY_FORM_ID, false, false, false, null, true);
            } else {
              echo '<p>Contact form is currently unavailable. Please email us directly.</p>';
            }
            ?>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 offset-lg-1">
          <div class="tld-booking-sidebar">

            <!-- What happens next -->
            <div class="tld-resource-sidebar-card mb-4 tld-reveal tld-reveal-d1">
              <h4>What happens next</h4>
              <ol class="tld-booking-steps">
                <li>
                  <strong>We respond</strong>
                  <span>Within 1 working day, we will reply to confirm a time.</span>
                </li>
                <li>
                  <strong>Discovery call</strong>
                  <span>A 30-minute video call to understand your goals and challenges.</span>
                </li>
                <li>
                  <strong>Tailored proposal</strong>
                  <span>If there is a fit, we send a clear proposal with scope, timeline, and cost.</span>
                </li>
              </ol>
            </div>

            <!-- Who we work best with -->
            <div class="tld-resource-sidebar-card mb-4 tld-reveal tld-reveal-d2">
              <h4>Who we work best with</h4>
              <p>Christian businesses, churches, ministries, and Catholic organisations that care about quality, clarity, and doing things well.</p>
            </div>

            <!-- Direct contact -->
            <div class="tld-resource-sidebar-card tld-resource-sidebar-card--dark tld-reveal tld-reveal-d3">
              <h4>Prefer email?</h4>
              <?php $email = tld_get_option('company_email'); ?>
              <?php if ($email) : ?>
                <a href="mailto:<?= esc_attr($email); ?>" class="btn tld-btn-gold w-100"><?= esc_html($email); ?></a>
              <?php else : ?>
                <p>Reach out directly and we will get back to you.</p>
              <?php endif; ?>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
