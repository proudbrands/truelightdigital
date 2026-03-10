<?php
/**
 * About Page: Mission Statement — Bold centred statement
 *
 * @package TrueLightDigital
 */

defined('ABSPATH') || exit;

$statement = $args['statement'] ?? '';
$text      = $args['text'] ?? '';

if (!$statement) {
  return;
}
?>

<section class="tld-section tld-problem-statement">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9 text-center">
        <h2 class="tld-problem-heading tld-reveal"><?= wp_kses_post($statement); ?></h2>
        <?php if ($text) : ?>
          <p class="tld-problem-text tld-reveal tld-reveal-d1"><?= esc_html($text); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
