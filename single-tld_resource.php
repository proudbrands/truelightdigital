<?php
/**
 * Single Resource
 *
 * Two-column layout: resource detail + download (left) + sidebar (right).
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main">

  <?php while (have_posts()) : the_post();
    $file       = function_exists('get_field') ? get_field('resource_file') : null;
    $type       = function_exists('get_field') ? get_field('resource_type') : 'pdf';
    $desc       = function_exists('get_field') ? get_field('resource_description') : '';
    $preview    = function_exists('get_field') ? get_field('resource_preview_image') : '';
    $gated      = function_exists('get_field') ? get_field('resource_gated') : false;
    $gate_form  = function_exists('get_field') ? get_field('resource_gate_form_id') : '';
    $cats       = get_the_terms(get_the_ID(), 'resource_category');
    $cat_name   = (!empty($cats) && !is_wp_error($cats)) ? $cats[0]->name : '';
    $file_url   = ($file && isset($file['url'])) ? $file['url'] : '';
    $file_size  = ($file && isset($file['filesize'])) ? size_format($file['filesize']) : '';
    $file_ext   = ($file && isset($file['filename'])) ? strtoupper(pathinfo($file['filename'], PATHINFO_EXTENSION)) : '';
  ?>

    <!-- Hero -->
    <section class="tld-hero-inner">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="d-flex align-items-center gap-2 mb-3" style="font-size: 0.875rem;">
              <?php if ($cat_name) : ?>
                <span style="color: var(--tld-gold); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;"><?= esc_html($cat_name); ?></span>
                <span style="color: rgba(255,255,255,0.5);">&middot;</span>
              <?php endif; ?>
              <span class="tld-resource-type-badge tld-resource-type-badge--<?= esc_attr($type); ?>"><?= esc_html(tld_resource_type_label($type)); ?></span>
            </div>
            <h1 class="tld-hero-inner-title"><?php the_title(); ?></h1>
            <?php if ($desc) : ?>
              <p class="tld-hero-inner-subtitle"><?= esc_html($desc); ?></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- Content + Sidebar -->
    <section class="tld-section">
      <div class="container">
        <div class="row g-5">

          <!-- Main Content -->
          <div class="col-lg-8">

            <!-- Preview Image -->
            <?php
            $img = $preview ?: get_the_post_thumbnail_url(get_the_ID(), 'large');
            if ($img) : ?>
              <div class="tld-resource-preview mb-4">
                <img src="<?= esc_url($img); ?>" alt="<?= esc_attr(get_the_title()); ?>" class="img-fluid rounded-3">
              </div>
            <?php endif; ?>

            <!-- Post Content (editor body) -->
            <div class="tld-service-content">
              <div class="col-lg-8">
                <?php the_content(); ?>
              </div>
            </div>

            <!-- Download Box -->
            <?php if ($file_url) : ?>
              <div class="tld-resource-download-box mt-5" id="download">
                <div class="tld-resource-download-icon">
                  <?= tld_resource_file_icon($type); ?>
                </div>
                <div class="tld-resource-download-info">
                  <h3 class="tld-resource-download-title">Download this resource</h3>
                  <p class="tld-resource-download-meta">
                    <?php if ($file_ext) : ?><span><?= esc_html($file_ext); ?></span><?php endif; ?>
                    <?php if ($file_size) : ?><span><?= esc_html($file_size); ?></span><?php endif; ?>
                  </p>
                </div>
                <?php if ($gated && $gate_form && function_exists('gravity_form')) : ?>
                  <button class="btn tld-btn-gold btn-lg tld-btn-arrow" data-bs-toggle="modal" data-bs-target="#tld-resource-gate-modal">
                    Get Free Download
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/><path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/></svg>
                  </button>
                <?php else : ?>
                  <a href="<?= esc_url($file_url); ?>" class="btn tld-btn-gold btn-lg tld-btn-arrow" download>
                    Download Free
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/><path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/></svg>
                  </a>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>

          <!-- Sidebar -->
          <div class="col-lg-4">
            <div class="tld-resource-sidebar">

              <!-- Quick Download Card -->
              <?php if ($file_url && !$gated) : ?>
                <div class="tld-resource-sidebar-card mb-4">
                  <h4>Quick Download</h4>
                  <p class="tld-resource-sidebar-meta">
                    <?php if ($file_ext) : ?><?= esc_html($file_ext); ?> file<?php endif; ?>
                    <?php if ($file_size) : ?> &middot; <?= esc_html($file_size); ?><?php endif; ?>
                  </p>
                  <a href="<?= esc_url($file_url); ?>" class="btn tld-btn-gold w-100 tld-btn-arrow" download>
                    Download
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/><path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/></svg>
                  </a>
                </div>
              <?php endif; ?>

              <!-- Email Signup -->
              <div class="tld-resource-sidebar-card tld-resource-sidebar-card--dark">
                <h4>Get resources like this delivered</h4>
                <p>Join the mailing list for new frameworks, templates, and guides.</p>
                <a href="#" class="btn tld-btn-gold w-100" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">Subscribe</a>
              </div>

              <!-- Related Resources -->
              <?php
              $related = get_posts([
                'post_type'      => 'tld_resource',
                'posts_per_page' => 3,
                'post__not_in'   => [get_the_ID()],
                'post_status'    => 'publish',
                'tax_query'      => (!empty($cats) && !is_wp_error($cats)) ? [
                  ['taxonomy' => 'resource_category', 'terms' => $cats[0]->term_id],
                ] : [],
              ]);
              if (!empty($related)) : ?>
                <div class="tld-resource-sidebar-related mt-4">
                  <h4>Related Resources</h4>
                  <?php foreach ($related as $rel) :
                    $rel_type = get_field('resource_type', $rel->ID) ?: 'pdf';
                  ?>
                    <a href="<?= esc_url(get_permalink($rel->ID)); ?>" class="tld-resource-sidebar-link">
                      <span class="tld-resource-type-badge tld-resource-type-badge--<?= esc_attr($rel_type); ?>"><?= esc_html(tld_resource_type_label($rel_type)); ?></span>
                      <span><?= esc_html($rel->post_title); ?></span>
                    </a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

            </div>
          </div>

        </div>
      </div>
    </section>

  <?php endwhile; ?>

  <!-- Email Gate Modal (if gated) -->
  <?php if ($gated && $gate_form && function_exists('gravity_form')) : ?>
    <div class="modal fade" id="tld-resource-gate-modal" tabindex="-1" aria-labelledby="tld-resource-gate-label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tld-modal-content">
          <div class="modal-header tld-modal-header">
            <div>
              <h5 class="modal-title" id="tld-resource-gate-label">Get your free download</h5>
              <p class="tld-modal-subtitle">Enter your email and we will send you the download link.</p>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body tld-modal-body">
            <?php gravity_form($gate_form, false, false, false, null, true); ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Bottom CTA -->
  <?php tld_render_cta(); ?>

</main>

<?php get_footer(); ?>
