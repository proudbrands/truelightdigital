<?php
/**
 * Formation landing: role grid.
 *
 * Six hardcoded reading pathways, one per `audience` taxonomy term.
 * Rendered directly in the template (not a Gutenberg block) because the
 * list is fixed, small, and needs a bespoke card layout.
 *
 * Each role's image is pulled from an ACF field on the Formation landing
 * page (`role_image_<slug>`). If the field is empty, the Unsplash
 * placeholder in `fallback` renders so the page never ships blank.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

// ACF field names map to audience slugs but swap the hyphen for an underscore.
function tld_formation_role_image($slug, $fallback) {
  if (!function_exists('get_field')) return $fallback;
  $field_name = 'role_image_' . str_replace('-', '_', $slug);
  $url = get_field($field_name);
  return $url ?: $fallback;
}

$roles = [
  [
    'slug'     => 'new-curator',
    'title'    => "I'm new to parish communications",
    'subtitle' => 'Start from zero.',
    'fallback' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&q=70',
  ],
  [
    'slug'     => 'parish-secretary',
    'title'    => "I'm the parish secretary",
    'subtitle' => 'Carrying this for years.',
    'fallback' => 'https://images.unsplash.com/photo-1552960562-daf630e9278b?w=600&q=70',
  ],
  [
    'slug'     => 'priest',
    'title'    => "I'm the priest",
    'subtitle' => 'Forming a champion, or backing one.',
    'fallback' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=600&q=70',
  ],
  [
    'slug'     => 'ppc-chair',
    'title'    => "I'm on the PPC or I chair it",
    'subtitle' => 'Supporting the work structurally.',
    'fallback' => 'https://images.unsplash.com/photo-1543269664-76bc3997d9ea?w=600&q=70',
  ],
  [
    'slug'     => 'diocesan-staff',
    'title'    => "I'm at the diocese",
    'subtitle' => 'Looking at this for a whole diocese.',
    'fallback' => 'https://images.unsplash.com/photo-1497486751825-1233686d5d80?w=600&q=70',
  ],
  [
    'slug'     => 'agency',
    'title'    => "I'm an agency or consultant",
    'subtitle' => 'Entering parish work.',
    'fallback' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&q=70',
  ],
];
foreach ($roles as &$role) {
  $role['image'] = tld_formation_role_image($role['slug'], $role['fallback']);
}
unset($role);
?>
<div class="formation-role-grid">
  <?php foreach ($roles as $role): ?>
    <a class="formation-role-card" href="<?php echo esc_url(home_url('/formation/for/' . $role['slug'] . '/')); ?>">
      <span class="formation-role-card__img" aria-hidden="true" style="background-image: url('<?php echo esc_url($role['image']); ?>');"></span>
      <span class="formation-role-card__seam" aria-hidden="true"></span>
      <span class="formation-role-card__row">
        <span class="formation-role-card__body">
          <span class="formation-role-card__title"><?php echo esc_html($role['title']); ?></span>
          <span class="formation-role-card__subtitle"><?php echo esc_html($role['subtitle']); ?></span>
        </span>
        <span class="formation-role-card__chev" aria-hidden="true">&rarr;</span>
      </span>
    </a>
  <?php endforeach; ?>
</div>
