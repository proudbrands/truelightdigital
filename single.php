<?php
/**
 * Single Blog Post
 *
 * Two-column layout: article content (left) + sidebar (right).
 * Sans-serif headings, Sen body font, generous whitespace.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();

$read_time = function_exists('get_field') ? get_field('read_time') : '';
?>

<main id="primary" class="site-main">

  <?php while (have_posts()) : the_post(); ?>

    <!-- Hero -->
    <section class="tld-hero-inner">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="d-flex align-items-center gap-2 mb-3" style="font-size: 0.875rem;">
              <span style="color: var(--tld-gold); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                <?php the_category(', '); ?>
              </span>
              <span style="color: rgba(255,255,255,0.5);">&middot;</span>
              <span style="color: rgba(255,255,255,0.7);"><?php echo get_the_date(); ?></span>
              <?php if ($read_time) : ?>
                <span style="color: rgba(255,255,255,0.5);">&middot;</span>
                <span style="color: rgba(255,255,255,0.7);"><?= esc_html($read_time); ?> min read</span>
              <?php endif; ?>
            </div>
            <h1 class="tld-hero-title"><?php the_title(); ?></h1>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()) : ?>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8" style="margin-top: -2rem; position: relative; z-index: 2;">
            <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded-3', 'style' => 'width: 100%;']); ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Content + Sidebar -->
    <section class="tld-blog-section">
      <div class="container">
        <div class="row g-5 justify-content-center">

          <!-- Article -->
          <div class="col-lg-8">
            <article class="tld-blog-content">
              <?php the_content(); ?>

              <hr class="tld-blog-divider">

              <div class="d-flex align-items-center gap-3">
                <?php echo get_avatar(get_the_author_meta('ID'), 48, '', '', ['class' => 'rounded-circle']); ?>
                <div>
                  <strong style="color: var(--tld-dark-navy);"><?php the_author(); ?></strong>
                  <div class="small" style="color: var(--tld-muted);"><?php echo get_the_date(); ?></div>
                </div>
              </div>
            </article>
          </div>

          <!-- Sidebar -->
          <div class="col-lg-4">
            <aside class="tld-blog-sidebar">

              <!-- Categories -->
              <?php
              $cats = get_categories(['hide_empty' => true, 'exclude' => [1]]);
              if ($cats) :
              ?>
                <div class="tld-sidebar-widget">
                  <h4 class="tld-sidebar-title">Categories</h4>
                  <ul class="tld-sidebar-links">
                    <?php foreach ($cats as $cat) : ?>
                      <li>
                        <a href="<?= esc_url(get_category_link($cat)); ?>">
                          <?= esc_html($cat->name); ?>
                          <span class="tld-sidebar-count"><?= $cat->count; ?></span>
                        </a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>

              <!-- Recent Posts -->
              <?php
              $recent = new WP_Query([
                'posts_per_page' => 4,
                'post__not_in'   => [get_the_ID()],
              ]);
              if ($recent->have_posts()) :
              ?>
                <div class="tld-sidebar-widget">
                  <h4 class="tld-sidebar-title">Recent Articles</h4>
                  <ul class="tld-sidebar-posts">
                    <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                      <li>
                        <a href="<?php the_permalink(); ?>">
                          <span class="tld-sidebar-post-title"><?php the_title(); ?></span>
                          <span class="tld-sidebar-post-date"><?php echo get_the_date('M j, Y'); ?></span>
                        </a>
                      </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                  </ul>
                </div>
              <?php endif; ?>

              <!-- CTA Widget -->
              <div class="tld-sidebar-widget tld-sidebar-cta">
                <h4 class="tld-sidebar-cta-heading">Need help with your digital strategy?</h4>
                <p>Book a free discovery call and let's talk about what's possible.</p>
                <a href="#" class="btn tld-btn-gold btn-sm w-100" data-bs-toggle="modal" data-bs-target="#tld-discovery-modal">
                  Book a Discovery Call
                </a>
              </div>

            </aside>
          </div>

        </div>
      </div>
    </section>

  <?php endwhile; ?>

  <!-- Related Posts -->
  <?php
  $categories = get_the_category();
  if ($categories) :
    $related_query = new WP_Query([
      'category__in'   => [wp_list_pluck($categories, 'term_id')[0]],
      'post__not_in'   => [get_the_ID()],
      'posts_per_page' => 3,
    ]);

    if ($related_query->have_posts()) :
  ?>
    <section class="tld-section bg-off-white">
      <div class="container">
        <h2 class="tld-heading-section text-center mb-4">Related Articles</h2>
        <div class="row g-4">
          <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
            <div class="col-md-4">
              <div class="tld-blog-card">
                <?php if (has_post_thumbnail()) : ?>
                  <img src="<?= esc_url(get_the_post_thumbnail_url(get_the_ID(), 'tld-blog-thumb')); ?>"
                       alt="<?php the_title_attribute(); ?>"
                       class="tld-blog-card-img"
                       loading="lazy">
                <?php endif; ?>
                <div class="tld-card-body">
                  <div class="tld-blog-meta"><?php echo get_the_date(); ?></div>
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                  <p class="tld-blog-excerpt"><?= esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </section>
    <?php
    wp_reset_postdata();
    endif;
  endif;
  ?>

  <?php tld_render_cta(); ?>

</main>

<?php get_footer(); ?>
