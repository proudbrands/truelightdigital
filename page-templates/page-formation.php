<?php
/**
 * Template Name: Formation Landing
 *
 * Assigned to the WP page at /formation/.
 * Layout is author-composed in Gutenberg — this template just provides the frame.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

get_header();
?>
<main id="primary" class="site-main formation-landing">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article>
      <header class="formation-landing__header py-5" style="background: linear-gradient(180deg, #0F2035 0%, #1C3557 100%); color: #fff;">
        <div class="container">
          <h1 class="formation-landing__title" style="font-family: 'Playfair Display', serif; margin: 0;"><?php the_title(); ?></h1>
        </div>
      </header>
      <div class="container formation-landing__content py-5">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; endif; ?>
</main>
<?php
get_footer();
