<?php
/**
 * Homepage Template (publication-first redesign).
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main">

  <?php if (have_posts()): while (have_posts()): the_post(); ?>

    <?php get_template_part('template-parts/home/hero'); ?>
    <?php get_template_part('template-parts/home/formation-preview'); ?>
    <?php get_template_part('template-parts/home/direct-help'); ?>
    <?php get_template_part('template-parts/home/blog-teaser'); ?>
    <?php get_template_part('template-parts/home/subscribe'); ?>

  <?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>
