<?php
/**
 * Audience archive template.
 *
 * Renders all formation_piece + tld_resource posts tagged with the queried
 * audience term, grouped by pillar. Serves as a "reading pathway" entry
 * point at /formation/for/<audience-slug>/ (see outlines-part3 P1-P10).
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();

$term = get_queried_object();
if (!$term || $term->taxonomy !== 'audience') {
  get_footer();
  return;
}

get_template_part('template-parts/formation/audience-hero');

// Pull the 4 pillars in sort order
$pillar_terms = get_terms([
  'taxonomy'   => 'pillar',
  'hide_empty' => false,
  'meta_key'   => 'pillar_sort_order',
  'orderby'    => 'meta_value_num',
  'order'      => 'ASC',
]);

if (is_wp_error($pillar_terms) || empty($pillar_terms)) {
  get_footer();
  return;
}
?>
<main id="primary" class="site-main">
  <div class="container py-4">

    <?php foreach ($pillar_terms as $pillar): ?>
      <?php
        // Pieces in this pillar assigned to this audience
        $pieces = new WP_Query([
          'post_type'      => 'formation_piece',
          'post_status'    => 'publish',
          'posts_per_page' => -1,
          'tax_query'      => [
            'relation' => 'AND',
            [ 'taxonomy' => 'pillar',   'field' => 'term_id', 'terms' => [$pillar->term_id] ],
            [ 'taxonomy' => 'audience', 'field' => 'term_id', 'terms' => [$term->term_id] ],
          ],
          'orderby' => 'date',
          'order'   => 'DESC',
        ]);

        // Resources in this pillar assigned to this audience
        $resources = new WP_Query([
          'post_type'      => 'tld_resource',
          'post_status'    => 'publish',
          'posts_per_page' => -1,
          'tax_query'      => [
            'relation' => 'AND',
            [ 'taxonomy' => 'pillar',   'field' => 'term_id', 'terms' => [$pillar->term_id] ],
            [ 'taxonomy' => 'audience', 'field' => 'term_id', 'terms' => [$term->term_id] ],
          ],
          'orderby' => 'meta_value',
          'meta_key' => 'supplemental_id',
          'order' => 'ASC',
        ]);

        $has_content = $pieces->have_posts() || $resources->have_posts();
        if (!$has_content) continue;

        $sort_order = (int) get_field('pillar_sort_order', 'pillar_' . $pillar->term_id);
        $number = str_pad((string) $sort_order, 2, '0', STR_PAD_LEFT);
      ?>

      <section class="formation-audience-pillar mb-5">
        <header class="formation-audience-pillar__header">
          <div class="formation-audience-pillar__number"><?php echo esc_html($number); ?></div>
          <div>
            <h2 class="formation-audience-pillar__title">
              <a href="<?php echo esc_url(get_term_link($pillar)); ?>"><?php echo esc_html($pillar->name); ?></a>
            </h2>
          </div>
        </header>

        <?php if ($pieces->have_posts()): ?>
          <div class="formation-pieces-grid row mb-3">
            <?php while ($pieces->have_posts()): $pieces->the_post();
              $type_terms = get_the_terms(get_the_ID(), 'piece_type');
              $pt = (!is_wp_error($type_terms) && !empty($type_terms)) ? $type_terms[0]->slug : '';
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
        <?php endif; ?>

        <?php if ($resources->have_posts()): ?>
          <div class="formation-resources__grid">
            <?php while ($resources->have_posts()): $resources->the_post(); ?>
              <?php get_template_part('template-parts/formation/resource-card', null, [
                'resource_id' => get_the_ID(),
                'variant'     => 'default',
              ]); ?>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        <?php endif; ?>
      </section>

    <?php endforeach; ?>

  </div>
</main>

<?php get_template_part('template-parts/formation/preview-modal'); ?>
<?php
get_footer();
