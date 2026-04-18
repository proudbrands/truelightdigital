<?php
/**
 * Preview modal markup.
 *
 * Emitted once per Formation page (cornerstone, pillar archive, landing).
 * Shell only — populated by JS (assets/js/formation.js) when a preview button is clicked.
 *
 * ESC closes. Backdrop click closes. X button closes. Focus is trapped.
 *
 * @package TrueLightDigital
 */
defined('ABSPATH') || exit;
?>
<div
  class="tld-preview-modal"
  id="tld-preview-modal"
  role="dialog"
  aria-modal="true"
  aria-labelledby="tld-preview-modal-title"
  aria-hidden="true"
  hidden
>
  <div class="tld-preview-modal__backdrop" data-tld-preview-close></div>
  <div class="tld-preview-modal__panel" role="document">
    <header class="tld-preview-modal__header">
      <h2 class="tld-preview-modal__title" id="tld-preview-modal-title">Preview</h2>
      <button
        type="button"
        class="tld-preview-modal__close"
        data-tld-preview-close
        aria-label="Close preview"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </header>
    <div class="tld-preview-modal__body">
      <iframe
        class="tld-preview-modal__iframe"
        title="Resource preview"
        sandbox="allow-same-origin allow-popups"
        loading="lazy"
      ></iframe>
    </div>
  </div>
</div>
