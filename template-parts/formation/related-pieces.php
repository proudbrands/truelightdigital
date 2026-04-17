<?php
/**
 * Three related pieces from the same pillar, excluding the current piece.
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$current_id   = get_the_ID();
$pillar_terms = get_the_terms($current_id, 'pillar');
if (is_wp_error($pillar_terms) || empty($pillar_terms)) return;
$pillar = $pillar_terms[0];

$related = new WP_Query([
  'post_type'      => 'formation_piece',
  'posts_per_page' => 3,
  'post__not_in'   => [$current_id],
  'tax_query'      => [[
    'taxonomy' => 'pillar',
    'field'    => 'term_id',
    'terms'    => [$pillar->term_id],
  ]],
  'orderby'        => 'date',
  'order'          => 'DESC',
]);

if (!$related->have_posts()) return;
?>
<section class="formation-related">
  <div class="container">
    <h2 class="formation-related__heading">More from this pillar</h2>
    <div class="formation-related__grid">
      <?php while ($related->have_posts()): $related->the_post(); ?>
        <?php get_template_part('template-parts/blocks/tld-piece-card', null, [
          'piece_id'     => get_the_ID(),
          'variant'      => 'compact',
          'show_summary' => false,
        ]); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
