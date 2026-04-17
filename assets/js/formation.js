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

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        var match = targets.find(function (t) { return t.el === entry.target; });
        if (!match) return;
        if (entry.isIntersecting) {
          targets.forEach(function (t) { t.link.parentElement.classList.remove('is-active'); });
          match.link.parentElement.classList.add('is-active');
        }
      });
    }, { rootMargin: '-20% 0px -70% 0px', threshold: 0 });

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

  if (document.readyState !== 'loading') {
    initToC();
    initFilterPills();
  } else {
    document.addEventListener('DOMContentLoaded', function () {
      initToC();
      initFilterPills();
    });
  }
})();
