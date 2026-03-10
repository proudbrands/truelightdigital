<?php
/**
 * ACF Block: FAQ Accordion
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$heading = get_field('faq_heading');
$items   = get_field('faq_items');
$block_id = 'faq-' . ($block['id'] ?? uniqid());

if (!$items) {
  return;
}
?>

<div class="tld-faq-accordion tld-section-sm">
  <?php if ($heading) : ?>
    <h2 class="tld-heading-section mb-4"><?= esc_html($heading); ?></h2>
  <?php endif; ?>

  <div class="accordion" id="<?= esc_attr($block_id); ?>">
    <?php foreach ($items as $i => $item) :
      $item_id = $block_id . '-item-' . $i;
      $collapsed = $i === 0 ? '' : 'collapsed';
      $show = $i === 0 ? 'show' : '';
    ?>
      <div class="accordion-item">
        <h3 class="accordion-header">
          <button class="accordion-button <?= $collapsed; ?>"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#<?= esc_attr($item_id); ?>"
                  aria-expanded="<?= $i === 0 ? 'true' : 'false'; ?>"
                  aria-controls="<?= esc_attr($item_id); ?>">
            <?= esc_html($item['question']); ?>
          </button>
        </h3>
        <div id="<?= esc_attr($item_id); ?>"
             class="accordion-collapse collapse <?= $show; ?>"
             data-bs-parent="#<?= esc_attr($block_id); ?>">
          <div class="accordion-body">
            <?= wp_kses_post($item['answer']); ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
