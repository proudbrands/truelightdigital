<?php
/**
 * Template Name: Library Landing
 *
 * Filterable index of all published tld_resource posts, with kind + pillar
 * filter pills. Assigned to the WP page at /library/.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();

$all = new WP_Query([
  'post_type'      => 'tld_resource',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'orderby'        => 'meta_value',
  'meta_key'       => 'supplemental_id',
  'order'          => 'ASC',
]);

$total = $all->post_count;

$pillar_terms = get_terms([
  'taxonomy'   => 'pillar',
  'hide_empty' => true,
  'meta_key'   => 'pillar_sort_order',
  'orderby'    => 'meta_value_num',
  'order'      => 'ASC',
]);

$kind_terms = get_terms([
  'taxonomy'   => 'resource_kind',
  'hide_empty' => true,
  'orderby'    => 'name',
  'order'      => 'ASC',
]);
?>

<header class="formation-library-hero">
  <div class="container">
    <div class="formation-library-hero__eyebrow">Formation &nbsp;&middot;&nbsp; Library</div>
    <h1 class="formation-library-hero__title">
      <?php if (have_posts()): the_post(); the_title(); rewind_posts(); else: ?>Parish Communications Library<?php endif; ?>
    </h1>
    <?php
    if (have_posts()): the_post();
      $content = get_the_content();
      if ($content) {
        echo '<div class="formation-library-hero__intro">' . apply_filters('the_content', $content) . '</div>';
      }
      rewind_posts();
    endif;
    ?>
  </div>
</header>

<main id="primary" class="site-main">
  <div class="container py-4">

    <nav class="formation-filter-bar" aria-label="Filter the library">
      <div class="formation-filter-row">
        <span class="formation-filter-label">Kind:</span>
        <button type="button" class="pill" data-library-filter="kind" data-filter="all" aria-pressed="true">All</button>
        <?php foreach ($kind_terms as $k): ?>
          <button type="button" class="pill" data-library-filter="kind" data-filter="<?php echo esc_attr($k->slug); ?>" aria-pressed="false"><?php echo esc_html($k->name); ?></button>
        <?php endforeach; ?>
      </div>
      <div class="formation-filter-row">
        <span class="formation-filter-label">Pillar:</span>
        <button type="button" class="pill" data-library-filter="pillar" data-filter="all" aria-pressed="true">All pillars</button>
        <?php foreach ($pillar_terms as $p): ?>
          <button type="button" class="pill" data-library-filter="pillar" data-filter="<?php echo esc_attr($p->slug); ?>" aria-pressed="false"><?php echo esc_html($p->name); ?></button>
        <?php endforeach; ?>
      </div>
    </nav>

    <div class="formation-library-grid formation-resources__grid" id="formation-library-grid">
      <?php while ($all->have_posts()): $all->the_post();
        $kind_list   = wp_get_object_terms(get_the_ID(), 'resource_kind', ['fields' => 'slugs']);
        $pillar_list = wp_get_object_terms(get_the_ID(), 'pillar',        ['fields' => 'slugs']);
        $kind_attr   = implode(' ', $kind_list);
        $pillar_attr = implode(' ', $pillar_list);
      ?>
        <div class="formation-library-grid__item"
             data-kind="<?php echo esc_attr($kind_attr); ?>"
             data-pillar="<?php echo esc_attr($pillar_attr); ?>">
          <?php get_template_part('template-parts/formation/resource-card', null, [
            'resource_id' => get_the_ID(),
            'variant'     => 'default',
          ]); ?>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</main>

<?php get_template_part('template-parts/formation/preview-modal'); ?>
<?php
get_footer();
