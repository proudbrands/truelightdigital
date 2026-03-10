<?php
/**
 * Template Name: Contact Page
 *
 * Structured contact page: Hero, intro, 2-column form + sidebar, qualifier.
 * Does NOT use the_content() — everything rendered from template + Gravity Form.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();

$email = tld_get_option('company_email', '');
$phone = tld_get_option('company_phone', '');
?>

<main id="primary" class="site-main">

  <!-- ════════════════════════════════════════════════
       SECTION 1: HERO
       ════════════════════════════════════════════════ -->
  <?php tld_render_hero(
    'Start a Conversation',
    'Whether you have a clear brief or just a sense that something needs to change, this is a good place to begin.',
    'Contact'
  ); ?>


  <!-- ════════════════════════════════════════════════
       SECTION 2: FORM + SIDEBAR
       ════════════════════════════════════════════════ -->
  <section class="tld-section">
    <div class="container">
      <div class="row g-5">

        <!-- Form Column -->
        <div class="col-lg-7 tld-reveal">
          <h2 class="tld-heading-section mb-3" style="font-size: 1.75rem;">Tell us about your organisation</h2>
          <p class="tld-problem-text mb-4">Use the form below to introduce yourself and share what you are working on. The more context you provide, the more useful our first reply will be. We do not use this form for sales follow-ups or mailing lists. It goes directly to our strategy team.</p>
          <?php
          if (function_exists('gravity_form')) {
            gravity_form(2, false, false, false, null, true, null, true);
          } else {
            echo '<p>Contact form is currently being set up. In the meantime, please email us at <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>.</p>';
          }
          ?>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-5">
          <div class="tld-contact-sidebar tld-reveal tld-reveal-d1">

            <!-- What Happens Next -->
            <div class="tld-card p-4 mb-4">
              <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem; font-family: var(--tld-heading-font);">What happens next</h3>
              <div class="tld-contact-steps">
                <div class="tld-contact-step">
                  <span class="tld-contact-step-num">1</span>
                  <div>
                    <h4>We respond within one working day</h4>
                    <p>You will hear back from a real person who understands your needs.</p>
                  </div>
                </div>
                <div class="tld-contact-step">
                  <span class="tld-contact-step-num">2</span>
                  <div>
                    <h4>Book a discovery call</h4>
                    <p>A 30-minute conversation to understand your goals and challenges.</p>
                  </div>
                </div>
                <div class="tld-contact-step">
                  <span class="tld-contact-step-num">3</span>
                  <div>
                    <h4>Receive a tailored proposal</h4>
                    <p>A clear plan with timeline, investment, and expected outcomes.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Direct Contact -->
            <?php if ($email || $phone) : ?>
            <div class="tld-card p-4 mb-4">
              <h3 style="font-size: 1.2rem; margin-bottom: 1rem; font-family: var(--tld-heading-font);">Prefer email?</h3>
              <p class="small mb-3" style="color: var(--tld-text-muted); line-height: 1.6;">You can also reach us directly. Include your name, your organisation, and a few lines about what you need.</p>
              <?php if ($email) : ?>
                <p class="mb-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--tld-gold)" class="me-2" viewBox="0 0 16 16"><path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 4.697v7.104z"/></svg>
                  <a href="mailto:<?= esc_attr($email); ?>"><?= esc_html($email); ?></a>
                </p>
              <?php endif; ?>
              <?php if ($phone) : ?>
                <p class="mb-0">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--tld-gold)" class="me-2" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/></svg>
                  <a href="tel:<?= esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?= esc_html($phone); ?></a>
                </p>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Who We Work Best With -->
            <div class="tld-card p-4" style="background: var(--tld-off-white); border: none;">
              <h3 style="font-size: 1.2rem; margin-bottom: 0.75rem; font-family: var(--tld-heading-font);">Who we work best with</h3>
              <p class="small mb-0" style="color: var(--tld-text-muted); line-height: 1.7;">Christian businesses, churches, ministries, and Catholic organisations who are ready to invest in stronger digital presence and willing to collaborate on strategy. If you need a quick logo or a one-off social media post, we are probably not the right partner. If you want a long-term relationship built on clarity and results, we should talk.</p>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
