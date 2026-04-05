jQuery(function ($) {

  // ── Respect reduced motion preference ──
  var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ── Smooth scroll for anchor links ──
  $('a[href*="#"]:not([href="#"])').on('click', function (e) {
    var target = $(this.hash);
    if (target.length) {
      e.preventDefault();
      if (prefersReduced) {
        window.scrollTo(0, target.offset().top - 80);
      } else {
        $('html, body').animate({ scrollTop: target.offset().top - 80 }, 500);
      }
    }
  });

  // ── IntersectionObserver for scroll-triggered reveals ──
  // Supports 3 variants: .tld-reveal (translateY), .tld-reveal-fade (opacity only), .tld-reveal-left (translateX)
  var revealEls = document.querySelectorAll('.tld-reveal, .tld-reveal-fade, .tld-reveal-left');
  var delays = { 'tld-reveal-d1': 50, 'tld-reveal-d2': 100, 'tld-reveal-d3': 150 };

  function getRevealType(el) {
    if (el.classList.contains('tld-reveal-fade')) return 'fade';
    if (el.classList.contains('tld-reveal-left')) return 'left';
    return 'up';
  }

  function showElement(el) {
    el.style.opacity = '1';
    el.style.transform = 'translate(0)';
    el.classList.remove('tld-reveal', 'tld-reveal-fade', 'tld-reveal-left');
  }

  function revealElement(el) {
    // Skip animation for reduced motion preference or background tabs
    if (prefersReduced || document.hidden) {
      showElement(el);
      return;
    }

    var type = getRevealType(el);
    var duration = 400;
    var startTime = null;
    var done = false;

    function step(timestamp) {
      if (done) return;
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);

      el.style.opacity = String(eased);
      if (type === 'fade') {
        // No transform — just opacity
      } else if (type === 'left') {
        el.style.transform = 'translateX(' + (-12 * (1 - eased)).toFixed(1) + 'px)';
      } else {
        el.style.transform = 'translateY(' + (12 * (1 - eased)).toFixed(1) + 'px)';
      }

      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        done = true;
        showElement(el);
      }
    }

    requestAnimationFrame(step);

    // Safety net: force visible if rAF stalls unexpectedly
    setTimeout(function () {
      if (!done) {
        done = true;
        showElement(el);
      }
    }, duration + 200);
  }

  function triggerReveal(el) {
    // Gold divider: special width animation (brand craft detail)
    if (el.classList.contains('tld-gold-divider')) {
      el.classList.remove('tld-reveal');
      el.classList.add('tld-gold-divider-animate');
      return;
    }

    var delay = 0;
    for (var cls in delays) {
      if (el.classList.contains(cls)) { delay = delays[cls]; break; }
    }
    if (delay && !prefersReduced) {
      setTimeout(function () { revealElement(el); }, delay);
    } else {
      revealElement(el);
    }
  }

  if (revealEls.length && 'IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          triggerReveal(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });

    if (document.hidden) {
      // Page loaded in a background tab — IO won't fire, show all now.
      // When the tab becomes visible, re-observe remaining elements for animation.
      revealEls.forEach(function (el) { showElement(el); });
      document.addEventListener('visibilitychange', function onVisible() {
        if (!document.hidden) {
          document.removeEventListener('visibilitychange', onVisible);
          // Re-observe any .tld-reveal still in DOM (future-proof)
          document.querySelectorAll('.tld-reveal').forEach(function (el) {
            observer.observe(el);
          });
        }
      });
    } else {
      revealEls.forEach(function (el) {
        observer.observe(el);
      });
    }
  } else {
    // Fallback: show everything immediately
    revealEls.forEach(function (el) { showElement(el); });
  }

  // ── Animated counter for hero metrics ──
  var counters = document.querySelectorAll('.tld-metric-number[data-count]');

  function showCounter(el) {
    el.textContent = el.getAttribute('data-count');
  }

  function animateCounter(el) {
    if (prefersReduced || document.hidden) { showCounter(el); return; }

    var target = parseInt(el.getAttribute('data-count'), 10);
    var duration = 800;
    var startTime = null;
    var done = false;

    function step(timestamp) {
      if (done) return;
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.floor(eased * target);
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        done = true;
        el.textContent = target;
      }
    }

    requestAnimationFrame(step);
    setTimeout(function () { if (!done) { done = true; showCounter(el); } }, duration + 200);
  }

  if (counters.length && 'IntersectionObserver' in window) {
    var counterObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          counterObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    if (document.hidden) {
      counters.forEach(function (el) { showCounter(el); });
    } else {
      counters.forEach(function (el) { counterObserver.observe(el); });
    }
  }

  // ── Resource Library: Category Filter ──
  var filterBtns = document.querySelectorAll('.tld-resource-filter');
  var resourceItems = document.querySelectorAll('.tld-resource-item');

  if (filterBtns.length && resourceItems.length) {
    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var filter = btn.getAttribute('data-filter');

        // Update active state
        filterBtns.forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');

        // Filter items
        resourceItems.forEach(function (item) {
          if (filter === 'all') {
            item.classList.remove('tld-hidden');
          } else {
            var cats = item.getAttribute('data-categories') || '';
            if (cats.split(' ').indexOf(filter) !== -1) {
              item.classList.remove('tld-hidden');
            } else {
              item.classList.add('tld-hidden');
            }
          }
        });
      });
    });
  }

}); // jQuery End
