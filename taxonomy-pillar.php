<?php
/**
 * Pillar archive template.
 * Hero (from term fields) + featured cornerstone + grid of remaining pieces
 * + filter pills (if >= 3 pieces total).
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

get_template_part('template-parts/formation/pillar-hero');

// Query: all pieces in this pillar, newest first
$all = new WP_Query([
  'post_type'      => 'formation_piece',
  'posts_per_page' => -1,
  'tax_query'      => [[
    'taxonomy' => 'pillar',
    'field'    => 'term_id',
    'terms'    => [$term->term_id],
  ]],
  'orderby' => 'date',
  'order'   => 'DESC',
]);

// Split: featured = most recent cornerstone; grid = everything else
$featured_id = null;
$grid_ids    = [];
if ($all->have_posts()) {
  while ($all->have_posts()) {
    $all->the_post();
    $types = get_the_terms(get_the_ID(), 'piece_type');
    $slug  = (!is_wp_error($types) && !empty($types)) ? $types[0]->slug : '';
    if (!$featured_id && $slug === 'cornerstone') {
      $featured_id = get_the_ID();
    } else {
      $grid_ids[] = get_the_ID();
    }
  }
  wp_reset_postdata();
}

$total_count = (int) $all->post_count;
$show_filter = $total_count >= 3;
?>
<main id="primary" class="site-main">
  <div class="container py-4">

    <?php if ($featured_id): ?>
      <section class="formation-featured mb-5">
        <?php get_template_part('template-parts/blocks/tld-piece-card', null, [
          'piece_id'     => $featured_id,
          'variant'      => 'featured',
          'show_summary' => true,
        ]); ?>
      </section>
    <?php endif; ?>

    <?php if ($show_filter): ?>
      <nav class="formation-filter-pills" aria-label="Filter by piece type">
        <button type="button" class="pill" data-filter="all" aria-pressed="true">All</button>
        <button type="button" class="pill" data-filter="cornerstone" aria-pressed="false">Cornerstones</button>
        <button type="button" class="pill" data-filter="short-read" aria-pressed="false">Short Reads</button>
        <button type="button" class="pill" data-filter="field-note" aria-pressed="false">Field Notes</button>
      </nav>
    <?php endif; ?>

    <div class="formation-pieces-grid row">
      <?php foreach ($grid_ids as $pid):
        $piece_type_terms = get_the_terms($pid, 'piece_type');
        $pt = (!is_wp_error($piece_type_terms) && !empty($piece_type_terms)) ? $piece_type_terms[0]->slug : '';
      ?>
        <div class="col-md-6 col-lg-4 mb-4" data-piece-type="<?php echo esc_attr($pt); ?>">
          <?php get_template_part('template-parts/blocks/tld-piece-card', null, [
            'piece_id'     => $pid,
            'variant'      => 'default',
            'show_summary' => true,
          ]); ?>
        </div>
      <?php endforeach; ?>
    </div>

    <?php get_template_part('template-parts/formation/email-capture', null, ['pillar_slug' => $term->slug]); ?>
  </div>

  <?php
  get_template_part('template-parts/formation/resources-grid', null, [
    'pillar_term_id' => $term->term_id,
    'heading'        => 'Resources for this pillar',
    'intro'          => 'Templates, worksheets, and reflection guides to take away. All free, no email required.',
  ]);
  ?>
</main>

<?php get_template_part('template-parts/formation/preview-modal'); ?>
<?php
get_footer();
