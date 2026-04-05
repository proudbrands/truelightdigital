<?php
/**
 * Template Name: Resources Library
 *
 * Filterable resource library with email signup CTA.
 * Sections: Hero -> Email CTA -> Category Filter -> Resource Grid -> Bottom CTA
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();

// ── ACF fields ──
$eyebrow  = function_exists('get_field') ? get_field('resources_hero_eyebrow') : '';
$title    = function_exists('get_field') ? get_field('resources_hero_title') : '';
$subtitle = function_exists('get_field') ? get_field('resources_hero_subtitle') : '';

$email_heading = function_exists('get_field') ? get_field('resources_email_heading') : '';
$email_text    = function_exists('get_field') ? get_field('resources_email_text') : '';
$email_form_id = function_exists('get_field') ? get_field('resources_email_form_id') : '';

// ── Query categories with posts ──
$categories = get_terms([
  'taxonomy'   => 'resource_category',
  'hide_empty' => true,
  'orderby'    => 'name',
]);

// ── Query resources ──
$featured = get_posts([
  'post_type'      => 'tld_resource',
  'posts_per_page' => 3,
  'meta_key'       => 'resource_featured',
  'meta_value'     => '1',
  'post_status'    => 'publish',
]);

$resources = get_posts([
  'post_type'      => 'tld_resource',
  'posts_per_page' => -1,
  'post_status'    => 'publish',
  'orderby'        => 'date',
  'order'          => 'DESC',
]);
?>

<main id="primary" class="site-main">

  <!-- ═══ Hero ═══ -->
  <?php
  get_template_part('template-parts/hero-page', null, [
    'title'    => $title ?: 'Practical tools for churches and Christian organisations',
    'subtitle' => $subtitle ?: 'Communication plans, strategy frameworks, and templates built for faith-based teams.',
    'eyebrow'  => $eyebrow ?: 'Free Resources',
  ]);
  ?>


  <!-- ═══ Empty State ═══ -->
  <?php if (empty($resources) && empty($featured)) : ?>
    <section class="tld-section">
      <div class="container">
        <div class="text-center py-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16" style="color: var(--tld-gold); margin-bottom: 1.5rem;"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/></svg>
          <h2 class="tld-heading-section">Resources coming soon</h2>
          <p class="tld-subtitle-center" style="margin-bottom: 2rem;">We are building frameworks, templates, and guides for churches and Christian organisations. Subscribe to be the first to know when they are ready.</p>
          <?php if ($email_form_id && function_exists('gravity_form')) : ?>
            <a href="#" class="btn tld-btn-gold btn-lg" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">Get Notified</a>
          <?php endif; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>


  <!-- ═══ Featured Resources ═══ -->
  <?php if (!empty($featured)) : ?>
    <section class="tld-section">
      <div class="container">
        <p class="tld-eyebrow tld-reveal">Most Popular</p>
        <h2 class="tld-heading-section tld-reveal tld-reveal-d1">Featured resources</h2>

        <div class="row g-4 mt-2">
          <?php foreach ($featured as $i => $res) :
            $file       = get_field('resource_file', $res->ID);
            $type       = get_field('resource_type', $res->ID) ?: 'pdf';
            $desc       = get_field('resource_description', $res->ID);
            $preview    = get_field('resource_preview_image', $res->ID);
            $thumb      = $preview ?: get_the_post_thumbnail_url($res->ID, 'tld-card');
            $cats       = get_the_terms($res->ID, 'resource_category');
            $cat_name   = (!empty($cats) && !is_wp_error($cats)) ? $cats[0]->name : '';
            $file_size  = ($file && isset($file['filesize'])) ? size_format($file['filesize']) : '';
          ?>
            <div class="col-md-4 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
              <a href="<?= esc_url(get_permalink($res->ID)); ?>" class="tld-resource-card tld-resource-card--featured">
                <?php if ($thumb) : ?>
                  <div class="tld-resource-card-img">
                    <img src="<?= esc_url($thumb); ?>" alt="<?= esc_attr($res->post_title); ?>" loading="lazy">
                  </div>
                <?php endif; ?>
                <div class="tld-resource-card-body">
                  <div class="tld-resource-card-meta">
                    <?php if ($cat_name) : ?>
                      <span class="tld-resource-badge"><?= esc_html($cat_name); ?></span>
                    <?php endif; ?>
                    <span class="tld-resource-type-badge tld-resource-type-badge--<?= esc_attr($type); ?>"><?= esc_html(tld_resource_type_label($type)); ?></span>
                  </div>
                  <h3 class="tld-resource-card-title"><?= esc_html($res->post_title); ?></h3>
                  <?php if ($desc) : ?>
                    <p class="tld-resource-card-desc"><?= esc_html(wp_trim_words($desc, 20)); ?></p>
                  <?php endif; ?>
                  <span class="tld-resource-card-link">
                    View resource
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
                  </span>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>


  <!-- ═══ Email Signup CTA (after featured, before grid) ═══ -->
  <?php if ($email_form_id && function_exists('gravity_form')) : ?>
    <section class="tld-resource-email-cta">
      <div class="container">
        <div class="tld-resource-email-inner">
          <div class="row align-items-center g-4">
            <div class="col-lg-5">
              <div class="tld-resource-email-content">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16" class="tld-resource-email-icon"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg>
                <h2 class="tld-resource-email-heading"><?= esc_html($email_heading ?: 'Get new resources in your inbox'); ?></h2>
                <p class="tld-resource-email-text"><?= wp_kses_post($email_text ?: 'We publish new frameworks, templates, and guides regularly. Join the list and we will send them to you when they are ready.'); ?></p>
              </div>
            </div>
            <div class="col-lg-6 offset-lg-1">
              <div class="tld-resource-email-form">
                <?php gravity_form($email_form_id, false, false, false, null, true); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php endif; ?>


  <!-- ═══ All Resources (Filtered Grid) ═══ -->
  <?php if (!empty($resources)) : ?>
    <section class="tld-section bg-off-white">
      <div class="container">
        <div class="text-center mb-4">
          <p class="tld-eyebrow tld-reveal">Browse Library</p>
          <h2 class="tld-heading-section tld-reveal tld-reveal-d1">All resources</h2>
        </div>

        <!-- Category filter tabs -->
        <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
          <div class="tld-resource-filters tld-reveal tld-reveal-d2">
            <button class="tld-resource-filter active" data-filter="all">All <span class="tld-filter-count"><?= count($resources); ?></span></button>
            <?php foreach ($categories as $cat) : ?>
              <button class="tld-resource-filter" data-filter="<?= esc_attr($cat->slug); ?>"><?= esc_html($cat->name); ?> <span class="tld-filter-count"><?= esc_html($cat->count); ?></span></button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- Resource grid -->
        <div class="row g-4 tld-resource-grid">
          <?php foreach ($resources as $i => $res) :
            $file       = get_field('resource_file', $res->ID);
            $type       = get_field('resource_type', $res->ID) ?: 'pdf';
            $desc       = get_field('resource_description', $res->ID);
            $cats       = get_the_terms($res->ID, 'resource_category');
            $cat_slugs  = [];
            $cat_name   = '';
            if (!empty($cats) && !is_wp_error($cats)) {
              $cat_name  = $cats[0]->name;
              $cat_slugs = wp_list_pluck($cats, 'slug');
            }
            $file_size = ($file && isset($file['filesize'])) ? size_format($file['filesize']) : '';
          ?>
            <div class="col-md-6 col-lg-4 tld-resource-item" data-categories="<?= esc_attr(implode(' ', $cat_slugs)); ?>">
              <a href="<?= esc_url(get_permalink($res->ID)); ?>" class="tld-resource-card">
                <div class="tld-resource-card-body">
                  <div class="tld-resource-card-meta">
                    <?php if ($cat_name) : ?>
                      <span class="tld-resource-badge"><?= esc_html($cat_name); ?></span>
                    <?php endif; ?>
                    <span class="tld-resource-type-badge tld-resource-type-badge--<?= esc_attr($type); ?>"><?= esc_html(tld_resource_type_label($type)); ?></span>
                  </div>
                  <h3 class="tld-resource-card-title"><?= esc_html($res->post_title); ?></h3>
                  <?php if ($desc) : ?>
                    <p class="tld-resource-card-desc"><?= esc_html(wp_trim_words($desc, 18)); ?></p>
                  <?php endif; ?>
                  <div class="tld-resource-card-footer">
                    <span class="tld-resource-card-link">
                      View resource
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
                    </span>
                    <?php if ($file_size) : ?>
                      <span class="tld-resource-card-size"><?= esc_html($file_size); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>


  <!-- ═══ Bottom CTA ═══ -->
  <?php tld_render_cta(get_the_ID()); ?>

</main>

<?php get_footer(); ?>
