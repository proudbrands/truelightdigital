<?php
/**
 * Resources grid — auto-populated list of tld_resource posts filtered by pillar.
 *
 * Args:
 *   - $args['pillar_term_id']  (int, required) — taxonomy term ID of the pillar
 *   - $args['heading']         (string, optional) — section heading
 *   - $args['intro']           (string, optional) — short intro paragraph
 *   - $args['limit']           (int, default -1)  — max resources to show
 *   - $args['kind_filter']     (string, optional) — slug of resource_kind to filter to
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$pillar_term_id = $args['pillar_term_id'] ?? null;
$heading        = $args['heading']        ?? 'Resources';
$intro          = $args['intro']          ?? '';
$limit          = $args['limit']          ?? -1;
$kind_filter    = $args['kind_filter']    ?? '';

if (!$pillar_term_id) return;

$tax_query = [[
  'taxonomy' => 'pillar',
  'field'    => 'term_id',
  'terms'    => [(int) $pillar_term_id],
]];

if ($kind_filter) {
  $tax_query[] = [
    'taxonomy' => 'resource_kind',
    'field'    => 'slug',
    'terms'    => [$kind_filter],
  ];
  $tax_query['relation'] = 'AND';
}

$q = new WP_Query([
  'post_type'      => 'tld_resource',
  'post_status'    => 'publish',
  'posts_per_page' => $limit,
  'tax_query'      => $tax_query,
  'orderby'        => 'meta_value',
  'meta_key'       => 'supplemental_id',
  'order'          => 'ASC',
]);

if (!$q->have_posts()) return;
?>
<section class="formation-resources">
  <div class="container">
    <?php if ($heading): ?>
      <h2 class="formation-resources__heading"><?php echo esc_html($heading); ?></h2>
    <?php endif; ?>
    <?php if ($intro): ?>
      <p class="formation-resources__intro"><?php echo esc_html($intro); ?></p>
    <?php endif; ?>

    <div class="formation-resources__grid">
      <?php while ($q->have_posts()): $q->the_post(); ?>
        <?php get_template_part('template-parts/formation/resource-card', null, [
          'resource_id' => get_the_ID(),
          'variant'     => 'default',
        ]); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
