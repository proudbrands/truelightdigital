<?php
/**
 * Renders a ToC for the current formation_piece.
 * Consumes $args['headings'] = array of ['level'=>int,'text'=>string,'id'=>string]
 * $args['mobile'] = true renders the mobile <details> variant.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;

$headings = $args['headings'] ?? [];
$mobile   = $args['mobile']   ?? false;
if (empty($headings)) return;

if ($mobile): ?>
<details class="formation-toc-mobile d-lg-none">
  <summary>On this page</summary>
  <nav aria-label="On this page">
    <ul>
      <?php foreach ($headings as $h): ?>
        <li class="is-level-<?php echo (int) $h['level']; ?>"><a href="#<?php echo esc_attr($h['id']); ?>"><?php echo esc_html($h['text']); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>
</details>
<?php else: ?>
<aside class="formation-toc d-none d-lg-block" role="complementary">
  <div class="formation-toc__label">On this page</div>
  <nav aria-label="On this page">
    <ul>
      <?php foreach ($headings as $h): ?>
        <li class="is-level-<?php echo (int) $h['level']; ?>"><a href="#<?php echo esc_attr($h['id']); ?>"><?php echo esc_html($h['text']); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>
</aside>
<?php endif; ?>
