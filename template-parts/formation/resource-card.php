<?php
/**
 * Resource Card render.
 *
 * Shared by:
 *  - Auto-populated resources grid (template-rendered from pillar/cornerstone templates)
 *  - Future ACF block (for author-placed manual features)
 *
 * Args (when called as template-part):
 *   - $args['resource_id'] (int, required)
 *   - $args['variant']     (string, default 'default'; also 'compact')
 *
 * Outputs a single <article.tld-resource-card>. Preview button is wired to
 * trigger the global preview modal (assets/js/formation.js handles open/close).
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$resource_id = null;
$variant     = 'default';

if (isset($args) && is_array($args)) {
  $resource_id = $args['resource_id'] ?? null;
  $variant     = $args['variant']     ?? 'default';
}

if (!$resource_id) return;

$r = get_post($resource_id);
if (!$r || $r->post_type !== 'tld_resource') return;

$supplemental_id = get_field('supplemental_id', $resource_id);
$description     = get_field('card_description', $resource_id);
$length          = get_field('length', $resource_id);
$pdf             = get_field('pdf_file', $resource_id);
$docx            = get_field('docx_file', $resource_id);
$html_preview    = get_field('html_preview_file', $resource_id);
$preview_image   = get_field('preview_image', $resource_id);

$kind_terms = get_the_terms($resource_id, 'resource_kind');
$kind       = (!is_wp_error($kind_terms) && !empty($kind_terms)) ? $kind_terms[0] : null;

// Fallback to post excerpt if description ACF field is empty
if (!$description) {
  $description = wp_strip_all_tags(get_the_excerpt($r));
}

$title     = get_the_title($r);
$permalink = get_permalink($r);

$has_pdf     = is_array($pdf)  && !empty($pdf['url']);
$has_docx    = is_array($docx) && !empty($docx['url']);
$has_preview = is_array($html_preview) && !empty($html_preview['url']);
?>
<article class="tld-resource-card tld-resource-card--<?php echo esc_attr($variant); ?>" data-kind="<?php echo esc_attr($kind ? $kind->slug : ''); ?>">

  <?php if ($preview_image && is_array($preview_image) && !empty($preview_image['url'])): ?>
    <div class="tld-resource-card__image">
      <a href="<?php echo esc_url($permalink); ?>"><img src="<?php echo esc_url($preview_image['url']); ?>" alt="" loading="lazy" /></a>
    </div>
  <?php endif; ?>

  <div class="tld-resource-card__body">
    <div class="tld-resource-card__meta">
      <?php if ($kind): ?>
        <span class="tld-resource-card__kind"><?php echo esc_html($kind->name); ?></span>
      <?php endif; ?>
      <?php if ($length): ?>
        <span class="tld-resource-card__length"><?php echo esc_html($length); ?></span>
      <?php endif; ?>
    </div>

    <h3 class="tld-resource-card__title">
      <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
    </h3>

    <?php if ($description): ?>
      <p class="tld-resource-card__description"><?php echo esc_html($description); ?></p>
    <?php endif; ?>

    <div class="tld-resource-card__actions">
      <?php if ($has_preview): ?>
        <button
          type="button"
          class="tld-resource-card__btn tld-resource-card__btn--preview"
          data-tld-preview-url="<?php echo esc_url($html_preview['url']); ?>"
          data-tld-preview-title="<?php echo esc_attr($title); ?>"
          aria-haspopup="dialog"
        >
          <span class="visually-hidden">Preview </span>Preview
        </button>
      <?php endif; ?>

      <?php if ($has_pdf): ?>
        <a
          class="tld-resource-card__btn tld-resource-card__btn--pdf"
          href="<?php echo esc_url($pdf['url']); ?>"
          download
          aria-label="Download <?php echo esc_attr($title); ?> as PDF"
        >Download PDF</a>
      <?php endif; ?>

      <?php if ($has_docx): ?>
        <a
          class="tld-resource-card__btn tld-resource-card__btn--docx"
          href="<?php echo esc_url($docx['url']); ?>"
          download
          aria-label="Download <?php echo esc_attr($title); ?> as DOCX"
        >DOCX</a>
      <?php endif; ?>

      <?php // Fallback: if no Preview/PDF/DOCX buttons would render, offer a Read link to the resource page
      if (!$has_preview && !$has_pdf && !$has_docx): ?>
        <a
          class="tld-resource-card__btn tld-resource-card__btn--preview"
          href="<?php echo esc_url($permalink); ?>"
        >Read</a>
      <?php endif; ?>
    </div>
  </div>
</article>
