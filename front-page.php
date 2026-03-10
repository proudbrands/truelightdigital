<?php
/**
 * Homepage Template
 *
 * Hardcoded structure with 7 template-parts.
 * Content is stable and changes are rare — no ACF fields needed.
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main">

  <?php get_template_part('template-parts/home/hero'); ?>
  <?php get_template_part('template-parts/home/pathways'); ?>
  <?php get_template_part('template-parts/home/proof-strip'); ?>
  <?php get_template_part('template-parts/home/services'); ?>
  <?php get_template_part('template-parts/home/values'); ?>
  <?php get_template_part('template-parts/home/blog-teaser'); ?>
  <?php get_template_part('template-parts/home/cta'); ?>

</main>

<?php get_footer(); ?>
