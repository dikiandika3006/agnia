// AGNIA — interaksi ringan: menu mobile, header saat scroll, animasi masuk.
(function () {
  var header = document.querySelector('.site-header');
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('site-nav');

  function setMenu(open) {
    if (!toggle || !nav) return;
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    nav.classList.toggle('open', open);
    document.body.classList.toggle('nav-open', open);
  }

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      setMenu(toggle.getAttribute('aria-expanded') !== 'true');
    });
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a')) setMenu(false);
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setMenu(false);
    });
    window.matchMedia('(min-width: 1081px)').addEventListener('change', function (m) {
      if (m.matches) setMenu(false);
    });
  }

  function onScroll() {
    if (header) header.classList.toggle('scrolled', window.scrollY > 8);
  }
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Slider "Karya Kami": geser/swipe, panah, titik, dan putar otomatis
  document.querySelectorAll('[data-slider]').forEach(function (root) {
    var track = root.querySelector('.show-track');
    var dots = root.querySelectorAll('.show-dots button');
    var n = dots.length, cur = 0, timer = null;

    function go(i) {
      cur = (i + n) % n;
      track.scrollTo({ left: cur * track.clientWidth, behavior: reduceMotion ? 'auto' : 'smooth' });
    }
    function mark() {
      cur = Math.round(track.scrollLeft / track.clientWidth);
      dots.forEach(function (d, j) {
        if (j === cur) d.setAttribute('aria-current', 'true'); else d.removeAttribute('aria-current');
      });
    }
    function stop() { clearInterval(timer); timer = null; }
    function play() { if (!reduceMotion && !timer) timer = setInterval(function () { go(cur + 1); }, 6000); }

    root.querySelector('.prev').addEventListener('click', function () { go(cur - 1); });
    root.querySelector('.next').addEventListener('click', function () { go(cur + 1); });
    dots.forEach(function (d, j) { d.addEventListener('click', function () { go(j); }); });
    track.addEventListener('scroll', function () { window.requestAnimationFrame(mark); }, { passive: true });
    window.addEventListener('resize', function () { track.scrollLeft = cur * track.clientWidth; });
    ['mouseenter', 'focusin', 'touchstart'].forEach(function (ev) { root.addEventListener(ev, stop, { passive: true }); });
    root.addEventListener('mouseleave', play);
    play();
  });

  // Animasi masuk saat elemen terlihat
  if ('IntersectionObserver' in window && !reduceMotion) {
    var targets = document.querySelectorAll(
      '.sc, .qbar, .svc-card, .type, .showcase, .fs, .dash, .pc, .gate, .bacol, .wc, .cta, .role, .mvc, .sol, .sd, .form-card, .info'
    );
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) {
          en.target.classList.add('in');
          io.unobserve(en.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    targets.forEach(function (el) {
      var r = el.getBoundingClientRect();
      if (r.top < window.innerHeight) return; // sudah terlihat, jangan disembunyikan
      el.classList.add('reveal');
      io.observe(el);
    });
  }
})();
