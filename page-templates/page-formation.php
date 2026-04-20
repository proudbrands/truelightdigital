<?php
/**
 * Template Name: Formation Landing
 *
 * Assigned to the WP page at /formation/.
 *
 * Page layout:
 *   1. Full-bleed bokeh-image hero (template-rendered here, not Gutenberg).
 *   2. Gutenberg body (pillar framing copy + 4 pillar cards + role framing copy).
 *   3. Role grid (6 hardcoded audience pathways, partial).
 *
 * The hero copy is hardcoded in this template on purpose: this landing is
 * a one-off, the copy is editorial, and we don't want the author to move
 * it around in Gutenberg. Update via code change + deploy.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();

// Hero image — placeholder Unsplash URL. Replace with attachment in Phase 2.
$hero_image = 'https://images.unsplash.com/photo-1519741497674-611481863552?w=2000&q=75';
?>
<main id="primary" class="site-main formation-landing">

  <?php if (have_posts()): while (have_posts()): the_post(); ?>

    <header class="formation-hero--image" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
      <div class="container">
        <span class="formation-hero--image__eyebrow">The Work &middot; Formation</span>
        <h1 class="formation-hero--image__title"><?php the_title(); ?></h1>
        <p class="formation-hero--image__intro">Formation is our word for the ongoing work of shaping and equipping the people who carry parish communications. Free essays, templates, and frameworks, organised around four pillars. Written for priests, parish secretaries, volunteers, and anyone whose job it is to help a parish speak well, on behalf of something larger than itself.</p>
      </div>
    </header>

    <article>
      <div class="container formation-landing__content py-5">
        <?php the_content(); ?>
        <?php get_template_part('template-parts/formation/role-grid'); ?>
      </div>
    </article>

  <?php endwhile; endif; ?>

</main>
<?php
get_footer();
