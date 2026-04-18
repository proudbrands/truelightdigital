/**
 * Formation — client-side behaviours.
 *
 * 1. ToC scrollspy: highlights the currently-visible heading in .formation-toc
 * 2. Filter pills: toggles visibility of piece cards by data-piece-type
 *
 * Vanilla JS, no jQuery dependency.
 */
(function () {
  'use strict';

  // --- ToC scrollspy ---
  function initToC() {
    var toc = document.querySelector('.formation-toc');
    if (!toc) return;

    var links = toc.querySelectorAll('a[href^="#"]');
    if (!links.length) return;

    var targets = [];
    links.forEach(function (a) {
      var id = a.getAttribute('href').slice(1);
      var el = document.getElementById(id);
      if (el) targets.push({ id: id, link: a, el: el });
    });
    if (!targets.length) return;

    if (!('IntersectionObserver' in window)) return;

    // rootMargin top matches scroll-margin-top on headings (+ a small buffer)
    // so the section becomes "active" the moment its heading settles below
    // the sticky nav rather than 20% into the viewport.
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        var match = targets.find(function (t) { return t.el === entry.target; });
        if (!match) return;
        if (entry.isIntersecting) {
          targets.forEach(function (t) { t.link.parentElement.classList.remove('is-active'); });
          match.link.parentElement.classList.add('is-active');
        }
      });
    }, { rootMargin: '-140px 0px -55% 0px', threshold: 0 });

    targets.forEach(function (t) { observer.observe(t.el); });
  }

  // --- Filter pills ---
  function initFilterPills() {
    var container = document.querySelector('.formation-filter-pills');
    if (!container) return;

    var pills = container.querySelectorAll('.pill');
    var grid = document.querySelector('.formation-pieces-grid');
    if (!grid) return;

    pills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        var filter = pill.getAttribute('data-filter');
        pills.forEach(function (p) { p.setAttribute('aria-pressed', p === pill ? 'true' : 'false'); });

        var cards = grid.querySelectorAll('[data-piece-type]');
        cards.forEach(function (card) {
          if (filter === 'all' || card.getAttribute('data-piece-type') === filter) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  // --- Resource preview modal ---
  var modalState = {
    lastFocus: null,
    isOpen: false,
  };

  function getModal() {
    return document.getElementById('tld-preview-modal');
  }

  function openPreview(url, title) {
    var modal = getModal();
    if (!modal) return;

    var iframe = modal.querySelector('.tld-preview-modal__iframe');
    var titleEl = modal.querySelector('.tld-preview-modal__title');
    if (!iframe) return;

    if (title && titleEl) titleEl.textContent = title;

    // On each load, inject a "reading mode" stylesheet into the iframe. This
    // normalises documents whose HTML lacks the .page wrapper — they get
    // proper padding + max-width so content isn't flush against the modal.
    var injectOnce = function () {
      iframe.removeEventListener('load', injectOnce);
      try {
        var doc = iframe.contentDocument;
        if (!doc || !doc.head) return;
        // Only inject if the document doesn't already have a .page wrapper
        if (doc.querySelector('.page')) return;
        var style = doc.createElement('style');
        style.textContent =
          '@media screen {' +
          '  body { padding: 32px 40px !important; max-width: 820px; margin: 0 auto !important; box-sizing: border-box; }' +
          '  @media (max-width: 640px) { body { padding: 20px 18px !important; } }' +
          '}';
        doc.head.appendChild(style);
      } catch (e) {
        // Cross-origin or other access failure — ignore, iframe will still render
      }
    };
    iframe.addEventListener('load', injectOnce);

    iframe.src = url;

    modalState.lastFocus = document.activeElement;
    modalState.isOpen = true;

    modal.hidden = false;
    // Double-RAF ensures the `hidden` removal has applied before we add the visible class
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        modal.classList.add('is-open');
      });
    });
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('tld-preview-open');

    // Focus the close button for keyboard users
    var closeBtn = modal.querySelector('.tld-preview-modal__close');
    if (closeBtn) closeBtn.focus();
  }

  function closePreview() {
    var modal = getModal();
    if (!modal || !modalState.isOpen) return;

    modalState.isOpen = false;
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('tld-preview-open');

    // Wait for transition then hide and blank the iframe (releases memory + stops audio/video)
    window.setTimeout(function () {
      modal.hidden = true;
      var iframe = modal.querySelector('.tld-preview-modal__iframe');
      if (iframe) iframe.src = 'about:blank';
    }, 250);

    if (modalState.lastFocus && typeof modalState.lastFocus.focus === 'function') {
      modalState.lastFocus.focus();
    }
  }

  function trapFocus(e) {
    if (!modalState.isOpen) return;
    if (e.key !== 'Tab') return;
    var modal = getModal();
    if (!modal) return;
    var focusables = modal.querySelectorAll('button, [href], iframe, [tabindex]:not([tabindex="-1"])');
    if (!focusables.length) return;
    var first = focusables[0];
    var last  = focusables[focusables.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault(); last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault(); first.focus();
    }
  }

  function initPreview() {
    // Delegated click on any Preview button
    document.addEventListener('click', function (e) {
      var trigger = e.target.closest('[data-tld-preview-url]');
      if (trigger) {
        e.preventDefault();
        openPreview(
          trigger.getAttribute('data-tld-preview-url'),
          trigger.getAttribute('data-tld-preview-title') || 'Preview'
        );
        return;
      }
      var closer = e.target.closest('[data-tld-preview-close]');
      if (closer) {
        e.preventDefault();
        closePreview();
      }
    });

    // ESC closes
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modalState.isOpen) {
        closePreview();
      } else {
        trapFocus(e);
      }
    });
  }

  if (document.readyState !== 'loading') {
    initToC();
    initFilterPills();
    initPreview();
  } else {
    document.addEventListener('DOMContentLoaded', function () {
      initToC();
      initFilterPills();
      initPreview();
    });
  }
})();
