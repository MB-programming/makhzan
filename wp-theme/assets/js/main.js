/* ============================================================
   Makhazen Alenayah — WordPress Theme JS
   GSAP Animations | Branches from makhzanData | No REST API
============================================================ */
"use strict";

gsap.registerPlugin(ScrollTrigger, TextPlugin);

// ============================================================
// FLOATING PARTICLES
// ============================================================
function initParticles() {
  const container = document.getElementById('hero-particles');
  if (!container) return;
  const count = window.innerWidth < 600 ? 20 : 40;
  for (let i = 0; i < count; i++) {
    const dot = document.createElement('div');
    dot.style.cssText = 'position:absolute;border-radius:50%;pointer-events:none;';
    const size   = Math.random() * 4 + 1;
    const isGold = Math.random() > 0.6;
    dot.style.width  = size + 'px';
    dot.style.height = size + 'px';
    dot.style.left   = Math.random() * 100 + '%';
    dot.style.top    = Math.random() * 100 + '%';
    dot.style.background = isGold
      ? `rgba(255,207,6,${Math.random() * 0.6 + 0.1})`
      : `rgba(255,255,255,${Math.random() * 0.15 + 0.03})`;
    container.appendChild(dot);
    gsap.to(dot, {
      y: (Math.random() - 0.5) * 80,
      x: (Math.random() - 0.5) * 40,
      opacity: Math.random() * 0.8 + 0.1,
      duration: Math.random() * 5 + 4,
      repeat: -1, yoyo: true, ease: 'sine.inOut', delay: Math.random() * 4,
    });
  }
}

// ============================================================
// PRELOADER
// ============================================================
function runPreloader(onComplete) {
  const preloader = document.getElementById('preloader');
  if (!preloader) { onComplete(); return; }
  const tl = gsap.timeline({ onComplete });
  tl.to('.preloader-logo', { opacity: 1, scale: 1, duration: 0.4, ease: 'power3.out' })
    .to('.preloader-dots span', { opacity: 1, stagger: { each: 0.1, repeat: 1, yoyo: true }, duration: 0.15 }, '-=0.1')
    .to('.preloader', { opacity: 0, duration: 0.3, ease: 'power2.inOut' }, '+=0.1')
    .set('.preloader', { display: 'none' });
}

// ============================================================
// HERO ENTRANCE
// ============================================================
function heroEntrance() {
  const header = document.getElementById('site-header');
  if (header) {
    gsap.to(header, { y: 0, duration: 0.8, ease: 'power3.out' });
    header.classList.add('visible');
  }
  gsap.timeline()
    .to('.hero-pattern-top, .hero-pattern-bottom', { opacity: 1, duration: 1.5, ease: 'power2.out' }, 0)
    .to('#hero-logo',    { opacity: 1, scale: 1, duration: 1.2, ease: 'power4.out' }, 0.2)
    .to('#hero-tagline', { opacity: 1, y: 0,     duration: 0.9, ease: 'power3.out' }, 0.7)
    .to('#hero-stats',   { opacity: 1, y: 0,     duration: 0.8, ease: 'power3.out' }, 1.0);
}

// ============================================================
// STICKY HEADER
// ============================================================
function initHeader() {
  const header = document.getElementById('site-header');
  if (!header) return;
  ScrollTrigger.create({
    start: 'top -80',
    onUpdate: (self) => header.classList.toggle('scrolled', self.progress > 0),
  });
}

// ============================================================
// SOCIAL — PHP renders cards, GSAP animates entrance
// ============================================================
function animateSocial() {
  if (!document.querySelector('.social-card')) return;
  gsap.from('.social-card', {
    scrollTrigger: { trigger: '.social-section', start: 'top 85%' },
    opacity: 0,
    y: 30,
    stagger: 0.08,
    duration: 0.6,
    ease: 'power3.out',
  });
}

// ============================================================
// BRANCHES — CSS opacity:0, GSAP to opacity:1
// ============================================================
function animateBranches() {
  ScrollTrigger.create({
    trigger: '.branches-section',
    start: 'top 75%',
    onEnter: () => {
      gsap.to('.branch-card:not(.hidden)', {
        opacity: 1, y: 0, stagger: 0.06, duration: 0.7, ease: 'power3.out',
      });
    },
  });
}

// ============================================================
// CONTACT — CSS opacity:0 + scale:0.95, GSAP to final state
// ============================================================
function animateContact() {
  gsap.to('.contact-card', {
    scrollTrigger: { trigger: '.contact-section', start: 'top 75%' },
    opacity: 1, scale: 1, duration: 0.9, ease: 'back.out(1.5)',
  });
  gsap.to('.contact-icon-wrap', {
    boxShadow: '0 0 30px rgba(255,207,6,0.35)',
    repeat: -1, yoyo: true, duration: 2, ease: 'sine.inOut',
  });
}

// ============================================================
// BRANDS — PHP renders cards, GSAP animates entrance
// ============================================================
function animateBrands() {
  if (!document.querySelector('.brand-card')) return;
  gsap.from('.brand-card', {
    scrollTrigger: { trigger: '.brands-section', start: 'top 80%' },
    opacity: 0,
    scale: 0.9,
    stagger: { each: 0.03, from: 'start', grid: 'auto' },
    duration: 0.4,
    ease: 'back.out(1.7)',
  });
}

// ============================================================
// BLOGS — CSS opacity:0 + translateY(30px), GSAP to final
// ============================================================
function animateBlogs() {
  if (!document.querySelector('.blog-card')) return;
  ScrollTrigger.create({
    trigger: '.blogs-section',
    start: 'top 75%',
    onEnter: () => {
      gsap.to('.blog-card', {
        opacity: 1, y: 0, stagger: 0.06, duration: 0.7, ease: 'power3.out',
      });
    },
  });
}

// ============================================================
// SECTION HEADERS
// ============================================================
function initSectionAnimations() {
  gsap.utils.toArray('.section-header').forEach(header => {
    gsap.from(header, {
      scrollTrigger: { trigger: header, start: 'top 85%' },
      opacity: 0, y: 40, duration: 0.8, ease: 'power3.out',
    });
  });
}

// ============================================================
// PARALLAX
// ============================================================
function initParallax() {
  gsap.utils.toArray('.section-pattern-accent, .brands-pattern-bottom, .footer-pattern').forEach(el => {
    gsap.to(el, {
      scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true },
      y: -40, ease: 'none',
    });
  });
  if (document.querySelector('.hero-pattern-top')) {
    gsap.to('.hero-pattern-top', {
      scrollTrigger: { trigger: '.hero-section', start: 'top top', end: 'bottom top', scrub: true },
      y: -80, ease: 'none',
    });
    gsap.to('.hero-pattern-bottom', {
      scrollTrigger: { trigger: '.hero-section', start: 'top top', end: 'bottom top', scrub: true },
      y: 60, ease: 'none',
    });
  }
}

// ============================================================
// HELPER — أوقات الدوام
// ============================================================
function buildHoursHtml(hours) {
  if (!hours || hours.length === 0) return '';
  const defaultLabels = { all: 'يومياً', weekdays: 'السبت - الخميس', weekend: 'الجمعة', custom: '' };
  const rows = hours.map(h => {
    const label    = h.day_label || defaultLabels[h.day_type] || h.day_type;
    const noteHtml = h.note ? `<span class="hours-note">${h.note}</span>` : '';
    if (h.is_closed) {
      return `<div class="hours-row"><span class="hours-day">${label}${noteHtml}</span><span class="hours-time hours-closed">مغلق</span></div>`;
    }
    const fmt = t => {
      if (!t) return '';
      const [hh, mm] = t.split(':').map(Number);
      return `${hh % 12 || 12}${mm ? ':' + String(mm).padStart(2,'0') : ''} ${hh < 12 ? 'ص' : 'م'}`;
    };
    return `<div class="hours-row"><span class="hours-day">${label}${noteHtml}</span><span class="hours-time">${fmt(h.opens_at)} – ${fmt(h.closes_at)}</span></div>`;
  }).join('');
  return `<div class="branch-hours"><div class="hours-header"><i class="fas fa-clock"></i><span>أوقات الدوام</span></div>${rows}</div>`;
}

// ============================================================
// RENDER BRANCHES
// ============================================================
function renderBranches(branches) {
  const grid       = document.getElementById('branches-grid');
  const filterWrap = document.getElementById('city-filter');
  if (!grid || !branches.length) return;
  grid.innerHTML = '';

  // المدن (الرياض أولاً)
  const cities = [];
  branches.forEach(b => { if (b.city_ar && !cities.includes(b.city_ar)) cities.push(b.city_ar); });
  ['الرياض', ...cities.filter(c => c !== 'الرياض')].forEach(city => {
    const btn = document.createElement('button');
    btn.className = 'city-btn'; btn.dataset.city = city; btn.textContent = city;
    filterWrap && filterWrap.appendChild(btn);
  });

  // ترتيب الرياض
  const riyadhOrder = ['الياسمين', 'الدائري', 'الحمراء', 'الربيع', 'المحمدية'];
  const riyadhFirst = riyadhOrder.map(k => branches.find(b => b.city_ar === 'الرياض' && b.name_ar.includes(k))).filter(Boolean);
  const riyadhRest  = branches.filter(b => b.city_ar === 'الرياض' && !riyadhOrder.some(k => b.name_ar.includes(k)));
  const sorted      = [...riyadhFirst, ...riyadhRest, ...branches.filter(b => b.city_ar !== 'الرياض')];

  sorted.forEach(b => {
    const card = document.createElement('div');
    card.className   = 'branch-card';
    card.dataset.city = b.city_ar || '';
    const phone  = b.phone   ? `<div class="branch-phone"><i class="fas fa-phone-alt"></i><a href="tel:${b.phone}">${b.phone}</a></div>` : '';
    const mapBtn = b.map_url ? `<a href="${b.map_url}" target="_blank" rel="noopener" class="branch-map-btn"><i class="fas fa-map-marker-alt"></i> الموقع</a>` : '';
    card.innerHTML = `
      <div class="branch-top">
        <div class="branch-name">${b.name_ar}</div>
        <span class="branch-city-badge">${b.city_ar || ''}</span>
      </div>
      <div class="branch-address"><i class="fas fa-map-pin"></i><span>${b.address_ar || ''}</span></div>
      ${buildHoursHtml(b.working_hours || [])}
      <div class="branch-footer">${phone}${mapBtn}</div>
    `;
    grid.appendChild(card);
  });

  // عرض المزيد
  const STEP = 5;
  let visible = STEP;
  function applyVisibility(city) {
    const all      = [...grid.querySelectorAll('.branch-card')];
    const filtered = all.filter(c => city === 'all' || c.dataset.city === city);
    all.forEach(c => c.classList.add('hidden'));
    filtered.forEach((c, i) => { if (i < visible) c.classList.remove('hidden'); });
    let btn = document.getElementById('show-more-branches');
    if (filtered.length > visible) {
      if (!btn) {
        btn = document.createElement('button');
        btn.id = 'show-more-branches'; btn.className = 'show-more-btn';
        btn.innerHTML = '<i class="fas fa-chevron-down"></i> عرض المزيد';
        grid.parentElement.appendChild(btn);
        btn.addEventListener('click', () => {
          visible += STEP;
          applyVisibility(filterWrap?.querySelector('.city-btn.active')?.dataset.city || 'all');
          gsap.fromTo(grid.querySelectorAll('.branch-card:not(.hidden)'),
            { opacity: 0, y: 20 }, { opacity: 1, y: 0, stagger: 0.05, duration: 0.5, ease: 'power3.out' });
        });
      }
      btn.style.display = 'flex';
    } else if (btn) {
      btn.style.display = 'none';
    }
  }
  applyVisibility('all');

  filterWrap && filterWrap.addEventListener('click', e => {
    const btn = e.target.closest('.city-btn');
    if (!btn) return;
    filterWrap.querySelectorAll('.city-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    visible = STEP;
    applyVisibility(btn.dataset.city);
    gsap.fromTo(grid.querySelectorAll('.branch-card:not(.hidden)'),
      { opacity: 0, y: 20 }, { opacity: 1, y: 0, stagger: 0.05, duration: 0.5, ease: 'power3.out' });
  });

  animateBranches();
}

// ============================================================
// INIT
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
  if (!document.getElementById('branches-grid')) return;

  initParticles();

  runPreloader(() => {
    heroEntrance();
    initHeader();
    initSectionAnimations();
    initParallax();

    // الفروع من wp_localize_script — بدون API call
    const branches = (typeof makhzanData !== 'undefined' && Array.isArray(makhzanData.branches))
      ? makhzanData.branches
      : [];
    renderBranches(branches);

    // أنيمت الأقسام اللي PHP رسمتها
    animateSocial();
    animateBrands();
    animateContact();
    animateBlogs();
  });
});
