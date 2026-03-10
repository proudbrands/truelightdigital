<?php
/**
 * Homepage: Blog Teaser — Modern card grid with hover effects
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$recent_posts = get_posts([
  'posts_per_page' => 3,
  'post_status'    => 'publish',
]);

if (empty($recent_posts)) {
  return;
}
?>

<section class="tld-section bg-off-white">
  <div class="container">

    <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-3">
      <div>
        <p class="tld-eyebrow tld-reveal">Insights</p>
        <h2 class="tld-heading-section mb-0 tld-reveal tld-reveal-d1">Resources worth reading</h2>
      </div>
      <a href="<?= esc_url(home_url('/blog/')); ?>" class="btn btn-outline-primary tld-btn-arrow tld-reveal tld-reveal-d1">
        View all
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="ms-2"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
      </a>
    </div>

    <div class="row g-4">
      <?php foreach ($recent_posts as $i => $rp) : ?>
        <div class="col-md-4 tld-reveal tld-reveal-d<?= min($i + 1, 3); ?>">
          <a href="<?= esc_url(get_permalink($rp->ID)); ?>" class="tld-blog-card-v2">
            <?php if (has_post_thumbnail($rp->ID)) : ?>
              <div class="tld-blog-card-v2-img-wrap">
                <img src="<?= esc_url(get_the_post_thumbnail_url($rp->ID, 'tld-blog-thumb')); ?>"
                     alt="<?= esc_attr($rp->post_title); ?>"
                     loading="lazy">
              </div>
            <?php endif; ?>
            <div class="tld-blog-card-v2-body">
              <span class="tld-blog-card-v2-date"><?= esc_html(get_the_date('', $rp->ID)); ?></span>
              <h3 class="tld-blog-card-v2-title"><?= esc_html($rp->post_title); ?></h3>
              <p class="tld-blog-card-v2-excerpt"><?= esc_html(wp_trim_words($rp->post_content, 18)); ?></p>
              <span class="tld-blog-card-v2-link">
                Read article
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
              </span>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
