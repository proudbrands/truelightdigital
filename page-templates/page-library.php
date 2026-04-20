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

// Audience terms are presented in editorial reading order (priest → people
// around the priest → broader stakeholders). Falls back to get_terms order
// for any term not in the canonical list (so new terms still surface).
$audience_order = ['priest', 'parish-secretary', 'curator', 'new-curator', 'volunteer', 'ppc-chair', 'ppc-member', 'diocesan-staff', 'agency', 'all'];
$audience_terms_raw = get_terms([
  'taxonomy'   => 'audience',
  'hide_empty' => true,
]);
if (is_wp_error($audience_terms_raw)) { $audience_terms_raw = []; }
$audience_terms = [];
foreach ($audience_order as $slug) {
  foreach ($audience_terms_raw as $t) {
    if ($t->slug === $slug) { $audience_terms[] = $t; break; }
  }
}
foreach ($audience_terms_raw as $t) {
  if (!in_array($t->slug, $audience_order, true)) { $audience_terms[] = $t; }
}
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
      <?php if (!empty($audience_terms)): ?>
      <div class="formation-filter-row">
        <span class="formation-filter-label">Who it's for:</span>
        <button type="button" class="pill" data-library-filter="audience" data-filter="all" aria-pressed="true">Anyone</button>
        <?php foreach ($audience_terms as $a): ?>
          <button type="button" class="pill" data-library-filter="audience" data-filter="<?php echo esc_attr($a->slug); ?>" aria-pressed="false"><?php echo esc_html($a->name); ?></button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </nav>

    <div class="formation-library-grid formation-resources__grid" id="formation-library-grid">
      <?php while ($all->have_posts()): $all->the_post();
        $kind_list     = wp_get_object_terms(get_the_ID(), 'resource_kind', ['fields' => 'slugs']);
        $pillar_list   = wp_get_object_terms(get_the_ID(), 'pillar',        ['fields' => 'slugs']);
        $audience_list = wp_get_object_terms(get_the_ID(), 'audience',      ['fields' => 'slugs']);
        if (is_wp_error($kind_list))     { $kind_list = []; }
        if (is_wp_error($pillar_list))   { $pillar_list = []; }
        if (is_wp_error($audience_list)) { $audience_list = []; }
        $kind_attr     = implode(' ', $kind_list);
        $pillar_attr   = implode(' ', $pillar_list);
        $audience_attr = implode(' ', $audience_list);
      ?>
        <div class="formation-library-grid__item"
             data-kind="<?php echo esc_attr($kind_attr); ?>"
             data-pillar="<?php echo esc_attr($pillar_attr); ?>"
             data-audience="<?php echo esc_attr($audience_attr); ?>">
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
