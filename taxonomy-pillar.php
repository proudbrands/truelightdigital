<?php
/**
 * Pillar archive template.
 *
 * The pillar's cornerstone essay is rendered AS the pillar archive — the
 * cornerstone is the primary content at /formation/<pillar-slug>/. Below
 * the essay: short reads in this pillar, email capture, resources grid.
 *
 * The cornerstone's own single-piece URL is 404'd (see cpt-formation-piece.php
 * template_redirect hook), so this is the single canonical surface.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();

$term = get_queried_object();
if (!$term || $term->taxonomy !== 'pillar') {
  get_footer();
  return;
}

// Find the cornerstone for this pillar
$cornerstone_q = new WP_Query([
  'post_type'      => 'formation_piece',
  'post_status'    => 'publish',
  'posts_per_page' => 1,
  'tax_query'      => [
    'relation' => 'AND',
    ['taxonomy' => 'pillar',     'field' => 'term_id', 'terms' => [$term->term_id]],
    ['taxonomy' => 'piece_type', 'field' => 'slug',    'terms' => ['cornerstone']],
  ],
  'orderby' => 'date',
  'order'   => 'DESC',
]);

$has_cornerstone = $cornerstone_q->have_posts();

if ($has_cornerstone) {
  $cornerstone_q->the_post();

  // Build ToC (respects toc_enabled ACF field; defaults on for cornerstones)
  $toc_enabled = (get_field('toc_enabled') !== false);
  $content     = apply_filters('the_content', get_the_content());

  if ($toc_enabled) {
    $toc_data = tld_formation_build_toc($content);
    $content  = $toc_data['html'];
    $headings = $toc_data['headings'];
  } else {
    $headings = [];
  }

  // Hero (cornerstone hero renders from current post context)
  get_template_part('template-parts/formation/cornerstone-hero');
  ?>
  <main id="primary" class="site-main formation-piece">
    <?php if ($toc_enabled && !empty($headings)): ?>
      <div class="container">
        <?php get_template_part('template-parts/formation/toc', null, ['headings' => $headings, 'mobile' => true]); ?>
        <div class="formation-piece-body-grid">
          <article class="formation-piece-body">
            <?php echo $content; ?>
          </article>
          <?php get_template_part('template-parts/formation/toc', null, ['headings' => $headings, 'mobile' => false]); ?>
        </div>
      </div>
    <?php else: ?>
      <div class="container">
        <article class="formation-piece-body">
          <?php echo $content; ?>
        </article>
      </div>
    <?php endif; ?>

    <div class="container">
      <?php get_template_part('template-parts/formation/email-capture', null, ['pillar_slug' => $term->slug]); ?>
    </div>

    <?php
    // "More from this pillar" — short reads (non-cornerstone) in this pillar
    $shorts = new WP_Query([
      'post_type'      => 'formation_piece',
      'post_status'    => 'publish',
      'posts_per_page' => -1,
      'post__not_in'   => [get_the_ID()],
      'tax_query'      => [
        'relation' => 'AND',
        ['taxonomy' => 'pillar',     'field' => 'term_id', 'terms' => [$term->term_id]],
        ['taxonomy' => 'piece_type', 'field' => 'slug',    'terms' => ['short-read', 'field-note'], 'operator' => 'IN'],
      ],
      'orderby' => 'date',
      'order'   => 'DESC',
    ]);

    if ($shorts->have_posts()): ?>
      <section class="formation-related">
        <div class="container">
          <h2 class="formation-related__heading">More from this pillar</h2>
          <div class="formation-pieces-grid row">
            <?php while ($shorts->have_posts()): $shorts->the_post();
              $pt_terms = get_the_terms(get_the_ID(), 'piece_type');
              $pt = (!is_wp_error($pt_terms) && !empty($pt_terms)) ? $pt_terms[0]->slug : '';
            ?>
              <div class="col-md-6 col-lg-4 mb-4" data-piece-type="<?php echo esc_attr($pt); ?>">
                <?php get_template_part('template-parts/blocks/tld-piece-card', null, [
                  'piece_id'     => get_the_ID(),
                  'variant'      => 'default',
                  'show_summary' => true,
                ]); ?>
              </div>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <?php
    // Resources grid for this pillar
    get_template_part('template-parts/formation/resources-grid', null, [
      'pillar_term_id' => $term->term_id,
      'heading'        => 'Resources for this pillar',
      'intro'          => 'Templates, worksheets, and reflection guides to take away. All free, no email required.',
    ]);
    ?>
  </main>
  <?php
  wp_reset_postdata();
} else {
  // Fallback: no cornerstone seeded yet — show the old-style pillar hero + grid
  get_template_part('template-parts/formation/pillar-hero');
  ?>
  <main id="primary" class="site-main">
    <div class="container py-4">
      <p><em>This pillar's cornerstone essay is not yet published.</em></p>
      <?php
      get_template_part('template-parts/formation/resources-grid', null, [
        'pillar_term_id' => $term->term_id,
        'heading'        => 'Resources for this pillar',
        'intro'          => '',
      ]);
      ?>
    </div>
  </main>
  <?php
}
?>

<?php get_template_part('template-parts/formation/preview-modal'); ?>
<?php
get_footer();
