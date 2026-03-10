<?php
/**
 * Template Name: About Page
 *
 * Premium multi-section about page with structured ACF-driven sections.
 * Sections: Hero -> Mission -> Story -> Values -> Approach -> Credentials -> Team -> CTA
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

get_header();

// ── ACF Fields ──
$subtitle         = function_exists('get_field') ? get_field('hero_subtitle') : '';
$eyebrow          = function_exists('get_field') ? get_field('hero_eyebrow') : 'About Us';
$mission_stmt     = function_exists('get_field') ? get_field('about_mission_statement') : '';
$mission_text     = function_exists('get_field') ? get_field('about_mission_text') : '';
$story_eyebrow    = function_exists('get_field') ? get_field('about_story_eyebrow') : '';
$story_heading    = function_exists('get_field') ? get_field('about_story_heading') : '';
$story_text       = function_exists('get_field') ? get_field('about_story_text') : '';
$story_image      = function_exists('get_field') ? get_field('about_story_image') : '';
$values_eyebrow   = function_exists('get_field') ? get_field('about_values_eyebrow') : '';
$values_heading   = function_exists('get_field') ? get_field('about_values_heading') : '';
$values_bg        = function_exists('get_field') ? get_field('about_values_bg_image') : '';
$values           = function_exists('get_field') ? get_field('about_values') : [];
$approach_eyebrow = function_exists('get_field') ? get_field('about_approach_eyebrow') : '';
$approach_heading = function_exists('get_field') ? get_field('about_approach_heading') : '';
$approach_items   = function_exists('get_field') ? get_field('about_approach_items') : [];
$stats_heading    = function_exists('get_field') ? get_field('about_stats_heading') : '';
$stats_bg         = function_exists('get_field') ? get_field('about_stats_bg_image') : '';
$stats            = function_exists('get_field') ? get_field('about_stats') : [];
$legal_name       = function_exists('get_field') ? get_field('about_company_legal_name') : '';
$trading_as       = function_exists('get_field') ? get_field('about_company_trading_as') : '';
$company_number   = function_exists('get_field') ? get_field('about_company_number') : '';
$location         = function_exists('get_field') ? get_field('about_company_location') : '';
$market           = function_exists('get_field') ? get_field('about_company_market') : '';
$company_detail   = function_exists('get_field') ? get_field('about_company_detail') : '';
$team_eyebrow     = function_exists('get_field') ? get_field('about_team_eyebrow') : '';
$team_heading     = function_exists('get_field') ? get_field('about_team_heading') : '';
$team_text        = function_exists('get_field') ? get_field('about_team_text') : '';
$team             = function_exists('get_field') ? get_field('about_team') : [];
?>

<main id="primary" class="site-main">

  <!-- ════════════════════════════════════════════════
       SECTION 1: HERO
       ════════════════════════════════════════════════ -->
  <?php tld_render_hero(get_the_title(), $subtitle, $eyebrow); ?>


  <!-- ════════════════════════════════════════════════
       SECTION 2: MISSION STATEMENT
       ════════════════════════════════════════════════ -->
  <?php if ($mission_stmt) :
    get_template_part('template-parts/about/mission', null, [
      'statement' => $mission_stmt,
      'text'      => $mission_text,
    ]);
  endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 3: STORY / ORIGIN
       ════════════════════════════════════════════════ -->
  <?php if ($story_heading || $story_text) :
    get_template_part('template-parts/about/story', null, [
      'eyebrow' => $story_eyebrow,
      'heading'  => $story_heading,
      'text'     => $story_text,
      'image'    => $story_image,
    ]);
  endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 4: VALUES / CONVICTIONS
       ════════════════════════════════════════════════ -->
  <?php if ($values) :
    get_template_part('template-parts/about/values', null, [
      'eyebrow'  => $values_eyebrow,
      'heading'  => $values_heading,
      'bg_image' => $values_bg,
      'items'    => $values,
    ]);
  endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 5: APPROACH PILLARS
       ════════════════════════════════════════════════ -->
  <?php if ($approach_items) :
    get_template_part('template-parts/about/approach', null, [
      'eyebrow' => $approach_eyebrow,
      'heading'  => $approach_heading,
      'items'    => $approach_items,
    ]);
  endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 6: CREDENTIALS (Stats + Company Info)
       ════════════════════════════════════════════════ -->
  <?php
  $has_credentials = $stats || $legal_name || $trading_as;
  if ($has_credentials) :
    get_template_part('template-parts/about/credentials', null, [
      'stats_heading'  => $stats_heading,
      'stats_bg'       => $stats_bg,
      'stats'          => $stats,
      'legal_name'     => $legal_name,
      'trading_as'     => $trading_as,
      'company_number' => $company_number,
      'location'       => $location,
      'market'         => $market,
      'detail'         => $company_detail,
    ]);
  endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 7: TEAM
       ════════════════════════════════════════════════ -->
  <?php if ($team) :
    get_template_part('template-parts/about/team', null, [
      'eyebrow' => $team_eyebrow,
      'heading'  => $team_heading,
      'text'     => $team_text,
      'members'  => $team,
    ]);
  endif; ?>


  <!-- ════════════════════════════════════════════════
       SECTION 8: CTA
       ════════════════════════════════════════════════ -->
  <?php tld_render_cta(); ?>

</main>

<?php get_footer(); ?>
