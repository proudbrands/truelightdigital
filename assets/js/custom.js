jQuery(function ($) {

  // ── Smooth scroll for anchor links ──
  $('a[href*="#"]:not([href="#"])').on('click', function (e) {
    var target = $(this.hash);
    if (target.length) {
      e.preventDefault();
      $('html, body').animate({ scrollTop: target.offset().top - 80 }, 500);
    }
  });

  // ── IntersectionObserver for scroll-triggered reveals ──
  // Uses inline styles to bypass all CSS specificity issues.
  var revealEls = document.querySelectorAll('.tld-reveal');
  var delays = { 'tld-reveal-d1': 150, 'tld-reveal-d2': 300, 'tld-reveal-d3': 450 };

  function showElement(el) {
    el.style.opacity = '1';
    el.style.transform = 'translateY(0)';
    el.classList.remove('tld-reveal');
  }

  function revealElement(el) {
    // Background tabs: rAF is paused and setTimeout is throttled.
    // Show immediately when page isn't visible.
    if (document.hidden) {
      showElement(el);
      return;
    }

    // rAF-driven animation for smooth fade-in in focused tabs.
    var duration = 700;
    var startTime = null;
    var done = false;

    function step(timestamp) {
      if (done) return;
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);

      el.style.opacity = String(eased);
      el.style.transform = 'translateY(' + (30 * (1 - eased)).toFixed(1) + 'px)';

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
    var delay = 0;
    for (var cls in delays) {
      if (el.classList.contains(cls)) { delay = delays[cls]; break; }
    }
    if (delay) {
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
    if (document.hidden) { showCounter(el); return; }

    var target = parseInt(el.getAttribute('data-count'), 10);
    var duration = 1500;
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

}); // jQuery End
