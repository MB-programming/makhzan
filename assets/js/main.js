/* ============================================================
   Makhazen Alenayah - Main JS
   GSAP Animations + Data Rendering
============================================================ */

"use strict";

// ---- GSAP Setup ----
gsap.registerPlugin(ScrollTrigger, TextPlugin);

// ============================================================
// FLOATING PARTICLES
// ============================================================
function initParticles() {
  const container = document.getElementById('hero-particles');
  const count = window.innerWidth < 600 ? 20 : 40;

  for (let i = 0; i < count; i++) {
    const dot = document.createElement('div');
    dot.style.cssText = `
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
    `;

    const size   = Math.random() * 4 + 1;
    const x      = Math.random() * 100;
    const y      = Math.random() * 100;
    const isGold = Math.random() > 0.6;

    dot.style.width  = size + 'px';
    dot.style.height = size + 'px';
    dot.style.left   = x + '%';
    dot.style.top    = y + '%';
    dot.style.background = isGold
      ? `rgba(255, 207, 6, ${Math.random() * 0.6 + 0.1})`
      : `rgba(255, 255, 255, ${Math.random() * 0.15 + 0.03})`;

    container.appendChild(dot);

    gsap.to(dot, {
      y: (Math.random() - 0.5) * 80,
      x: (Math.random() - 0.5) * 40,
      opacity: Math.random() * 0.8 + 0.1,
      duration: Math.random() * 5 + 4,
      repeat: -1,
      yoyo: true,
      ease: 'sine.inOut',
      delay: Math.random() * 4,
    });
  }
}

// ============================================================
// PRELOADER ANIMATION
// ============================================================
function runPreloader(onComplete) {
  const tl = gsap.timeline({ onComplete });

  tl
    .to('.preloader-logo', {
      opacity: 1, scale: 1,
      duration: 0.4,
      ease: 'power3.out',
    })
    .to('.preloader-dots span', {
      opacity: 1,
      stagger: { each: 0.1, repeat: 1, yoyo: true },
      duration: 0.15,
    }, '-=0.1')
    .to('.preloader', {
      opacity: 0,
      duration: 0.3,
      ease: 'power2.inOut',
    }, '+=0.1')
    .set('.preloader', { display: 'none' });
}

// ============================================================
// HERO ENTRANCE
// ============================================================
function heroEntrance() {
  const tl = gsap.timeline();

  gsap.to('.site-header', { y: 0, duration: 0.8, ease: 'power3.out' });
  document.getElementById('site-header').classList.add('visible');

  tl.to('.hero-pattern-top, .hero-pattern-bottom', {
    opacity: 1,
    duration: 1.5,
    ease: 'power2.out',
  }, 0)

  .to('#hero-logo', {
    opacity: 1,
    scale: 1,
    duration: 1.2,
    ease: 'power4.out',
  }, 0.2)

  .to('.hero-tagline', {
    opacity: 1,
    y: 0,
    duration: 0.9,
    ease: 'power3.out',
  }, 0.7)

  .to('.hero-stats', {
    opacity: 1,
    y: 0,
    duration: 0.8,
    ease: 'power3.out',
  }, 1.0)

  .to('#scroll-hint', {
    opacity: 1,
    duration: 0.8,
    ease: 'power2.out',
  }, 1.3);
}

// ============================================================
// STICKY HEADER ON SCROLL
// ============================================================
function initHeader() {
  const header = document.getElementById('site-header');
  ScrollTrigger.create({
    start: 'top -80',
    onUpdate: (self) => {
      if (self.progress > 0) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    },
  });

  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const target = document.querySelector(link.getAttribute('href'));
      if (target) {
        gsap.to(window, {
          scrollTo: { y: target, offsetY: 60 },
          duration: 1.0,
          ease: 'power3.inOut',
        });
      }
    });
  });
}

// ============================================================
// SOCIAL SECTION ANIMATION
// ============================================================
function animateSocial() {
  gsap.to('.social-card', {
    scrollTrigger: {
      trigger: '.social-section',
      start: 'top 80%',
    },
    opacity: 1,
    y: 0,
    stagger: 0.1,
    duration: 0.7,
    ease: 'power3.out',
  });
}

// ============================================================
// BRANCHES SECTION ANIMATION
// ============================================================
function animateBranches() {
  ScrollTrigger.create({
    trigger: '.branches-section',
    start: 'top 75%',
    onEnter: () => {
      gsap.to('.branch-card:not(.hidden)', {
        opacity: 1,
        y: 0,
        stagger: 0.06,
        duration: 0.7,
        ease: 'power3.out',
      });
    },
  });
}

// ============================================================
// CONTACT SECTION ANIMATION
// ============================================================
function animateContact() {
  gsap.to('.contact-card', {
    scrollTrigger: {
      trigger: '.contact-section',
      start: 'top 75%',
    },
    opacity: 1,
    scale: 1,
    duration: 0.9,
    ease: 'back.out(1.5)',
  });

  gsap.to('.contact-icon-wrap', {
    boxShadow: '0 0 30px rgba(255,207,6,0.35)',
    repeat: -1,
    yoyo: true,
    duration: 2,
    ease: 'sine.inOut',
  });
}

// ============================================================
// BRANDS SECTION ANIMATION
// ============================================================
function animateBrands() {
  ScrollTrigger.create({
    trigger: '.brands-section',
    start: 'top 80%',
    onEnter: () => {
      gsap.to('.brand-card', {
        opacity: 1,
        scale: 1,
        stagger: {
          each: 0.04,
          from: 'start',
          grid: 'auto',
        },
        duration: 0.5,
        ease: 'back.out(1.7)',
      });
    },
  });
}

// ============================================================
// RENDER SOCIAL CARDS — يستخدم اللون من لوحة التحكم (DB)
// ============================================================
function renderSocial(items) {
  const grid = document.getElementById('social-grid');
  grid.innerHTML = '';

  // ألوان افتراضية fallback
  const defaultColors = {
    instagram: { bg: 'rgba(225,48,108,0.1)',  icon: '#E1306C', border: 'rgba(225,48,108,0.25)' },
    tiktok:    { bg: 'rgba(0,0,0,0.07)',       icon: '#000000', border: 'rgba(0,0,0,0.15)'      },
    snapchat:  { bg: 'rgba(255,207,6,0.15)',   icon: '#c9a200', border: 'rgba(255,207,6,0.35)'  },
    twitter:   { bg: 'rgba(0,0,0,0.07)',       icon: '#000000', border: 'rgba(0,0,0,0.15)'      },
    whatsapp:  { bg: 'rgba(37,211,102,0.1)',   icon: '#1a9e50', border: 'rgba(37,211,102,0.25)' },
    youtube:   { bg: 'rgba(255,0,0,0.08)',     icon: '#CC0000', border: 'rgba(255,0,0,0.2)'     },
    facebook:  { bg: 'rgba(24,119,242,0.1)',   icon: '#1877F2', border: 'rgba(24,119,242,0.25)' },
  };

  // hex → rgba
  function hexToRgba(hex, alpha) {
    if (!hex || hex.length < 7) return null;
    const r = parseInt(hex.slice(1,3), 16);
    const g = parseInt(hex.slice(3,5), 16);
    const b = parseInt(hex.slice(5,7), 16);
    return `rgba(${r},${g},${b},${alpha})`;
  }

  // منصات لازم يتغير لونها حتى لو اللون الأصلي فاتح
  const forceDarkIcon = ['snapchat', 'tiktok', 'twitter'];

  items.forEach((item) => {
    const platform = item.platform.toLowerCase();
    const dbColor  = item.color;
    const fallback = defaultColors[platform];

    let colors;

    if (dbColor && dbColor.toLowerCase() !== '#ffffff' && dbColor.toLowerCase() !== '#fff') {
      // لون من لوحة التحكم
      const iconColor = forceDarkIcon.includes(platform)
        ? (fallback?.icon || '#000000')
        : dbColor;
      colors = {
        icon:   iconColor,
        bg:     hexToRgba(dbColor, 0.1)  || fallback?.bg  || 'rgba(255,207,6,0.1)',
        border: hexToRgba(dbColor, 0.25) || fallback?.border || 'rgba(255,207,6,0.2)',
      };
    } else if (fallback) {
      colors = fallback;
    } else {
      // منصة جديدة غير موجودة
      const c = dbColor || '#FFCF06';
      colors = { icon: c, bg: hexToRgba(c, 0.1) || 'rgba(255,207,6,0.1)', border: hexToRgba(c, 0.25) || 'rgba(255,207,6,0.2)' };
    }

    const card = document.createElement('a');
    card.href      = item.url;
    card.target    = '_blank';
    card.rel       = 'noopener noreferrer';
    card.className = 'social-card';
    card.style.setProperty('--card-color', colors.bg);

    card.innerHTML = `
      <div class="social-icon" style="background:${colors.bg}; color:${colors.icon}; border: 1.5px solid ${colors.border}">
        <i class="fab ${item.icon || 'fa-globe'}"></i>
      </div>
      <div class="social-info">
        <div class="social-platform">${item.platform_ar || item.platform}</div>
        <div class="social-username">${item.username || ''}</div>
      </div>
      <i class="fas fa-arrow-left social-arrow"></i>
    `;
    grid.appendChild(card);
  });

  animateSocial();
}

// ============================================================
// HELPER — بناء HTML أوقات الدوام
// ============================================================
function buildHoursHtml(hours) {
  if (!hours || hours.length === 0) return '';

  // التسميات الافتراضية حسب day_type
  const defaultLabels = {
    all:      'يومياً',
    weekdays: 'السبت - الخميس',
    weekend:  'الجمعة',
    custom:   '',
  };

  const rows = hours.map(h => {
    const label = h.day_label || defaultLabels[h.day_type] || h.day_type;
    const noteHtml = h.note ? `<span class="hours-note">${h.note}</span>` : '';

    if (h.is_closed) {
      return `
        <div class="hours-row">
          <span class="hours-day">${label}${noteHtml}</span>
          <span class="hours-time hours-closed">مغلق</span>
        </div>`;
    }

    // تحويل HH:MM إلى 12h عربي
    const fmt = t => {
      if (!t) return '';
      const [hh, mm] = t.split(':').map(Number);
      const suffix = hh < 12 ? 'ص' : 'م';
      const h12    = hh % 12 || 12;
      return `${h12}${mm ? ':' + String(mm).padStart(2,'0') : ''} ${suffix}`;
    };

    return `
      <div class="hours-row">
        <span class="hours-day">${label}${noteHtml}</span>
        <span class="hours-time">${fmt(h.opens_at)} – ${fmt(h.closes_at)}</span>
      </div>`;
  }).join('');

  return `
    <div class="branch-hours">
      <div class="hours-header">
        <i class="fas fa-clock"></i>
        <span>أوقات الدوام</span>
      </div>
      ${rows}
    </div>`;
}

// ============================================================
// RENDER BRANCHES  (مع عرض 5 في البداية + زرار عرض المزيد)
// ============================================================
function renderBranches(branches) {
  const grid       = document.getElementById('branches-grid');
  const filterWrap = document.getElementById('city-filter');
  grid.innerHTML   = '';

  // ---- بناء قائمة المدن مع الرياض أولاً ----
  const cities = [];
  branches.forEach(b => {
    if (b.city_ar && !cities.find(c => c.ar === b.city_ar)) {
      cities.push({ ar: b.city_ar, en: b.city_en });
    }
  });

  const riyadh = cities.find(c => c.ar === 'الرياض');
  const others = cities.filter(c => c.ar !== 'الرياض');
  const orderedCities = riyadh ? [riyadh, ...others] : others;

  orderedCities.forEach(city => {
    const btn = document.createElement('button');
    btn.className    = 'city-btn';
    btn.dataset.city = city.ar;
    btn.textContent  = city.ar;
    filterWrap.appendChild(btn);
  });

  // ---- ترتيب الرياض بترتيب محدد ثم الباقي ----
  const riyadhOrder = ['الياسمين', 'الدائري', 'الحمراء', 'الربيع', 'المحمدية'];

  const riyadhBranches = riyadhOrder
    .map(keyword => branches.find(b => b.city_ar === 'الرياض' && b.name_ar.includes(keyword)))
    .filter(Boolean);

  const riyadhRest = branches.filter(
    b => b.city_ar === 'الرياض' && !riyadhOrder.some(k => b.name_ar.includes(k))
  );

  const sortedBranches = [
    ...riyadhBranches,
    ...riyadhRest,
    ...branches.filter(b => b.city_ar !== 'الرياض'),
  ];

  // ---- رسم كروت الفروع ----
  sortedBranches.forEach(branch => {
    const card = document.createElement('div');
    card.className    = 'branch-card';
    card.dataset.city = branch.city_ar || '';

    const phone = branch.phone ? `
      <div class="branch-phone">
        <i class="fas fa-phone-alt"></i>
        <a href="tel:${branch.phone}">${branch.phone}</a>
      </div>` : '';

    const mapBtn = branch.map_url ? `
      <a href="${branch.map_url}" target="_blank" rel="noopener" class="branch-map-btn">
        <i class="fas fa-map-marker-alt"></i> الموقع
      </a>` : '';

    const hoursHtml = buildHoursHtml(branch.working_hours || []);

    card.innerHTML = `
      <div class="branch-top">
        <div class="branch-name">${branch.name_ar}</div>
        <span class="branch-city-badge">${branch.city_ar || ''}</span>
      </div>
      <div class="branch-address">
        <i class="fas fa-map-pin"></i>
        <span>${branch.address_ar || ''}</span>
      </div>
      ${hoursHtml}
      <div class="branch-footer">
        ${phone}
        ${mapBtn}
      </div>
    `;
    grid.appendChild(card);
  });

  // ---- منطق عرض المزيد ----
  const STEP = 5;
  let visibleCount = STEP;

  function applyVisibility(filterCity) {
    const allCards = [...grid.querySelectorAll('.branch-card')];
    const filtered = allCards.filter(c =>
      filterCity === 'all' || c.dataset.city === filterCity
    );

    // إخفاء الكل أولاً
    allCards.forEach(c => c.classList.add('hidden'));

    // إظهار بقدر visibleCount
    filtered.forEach((c, i) => {
      if (i < visibleCount) c.classList.remove('hidden');
    });

    // زرار عرض المزيد
    let showMoreBtn = document.getElementById('show-more-branches');

    if (filtered.length > visibleCount) {
      if (!showMoreBtn) {
        showMoreBtn = document.createElement('button');
        showMoreBtn.id        = 'show-more-branches';
        showMoreBtn.className = 'show-more-btn';
        showMoreBtn.innerHTML = '<i class="fas fa-chevron-down"></i> عرض المزيد';
        grid.parentElement.appendChild(showMoreBtn);

        showMoreBtn.addEventListener('click', () => {
          visibleCount += STEP;
          const activeCity = filterWrap.querySelector('.city-btn.active')?.dataset.city || 'all';
          applyVisibility(activeCity);

          // أنيميشن للكروت الجديدة
          gsap.fromTo(
            grid.querySelectorAll('.branch-card:not(.hidden)'),
            { opacity: 0, y: 20 },
            { opacity: 1, y: 0, stagger: 0.05, duration: 0.5, ease: 'power3.out' }
          );
        });
      }
      showMoreBtn.style.display = 'flex';
    } else {
      if (showMoreBtn) showMoreBtn.style.display = 'none';
    }
  }

  // تطبيق الأولي
  applyVisibility('all');

  // ---- فلتر المدن ----
  filterWrap.addEventListener('click', (e) => {
    const btn = e.target.closest('.city-btn');
    if (!btn) return;

    filterWrap.querySelectorAll('.city-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    visibleCount = STEP; // إعادة ضبط العداد
    const selectedCity = btn.dataset.city;
    applyVisibility(selectedCity);

    gsap.fromTo(
      grid.querySelectorAll('.branch-card:not(.hidden)'),
      { opacity: 0, y: 20 },
      { opacity: 1, y: 0, stagger: 0.05, duration: 0.5, ease: 'power3.out' }
    );
  });

  animateBranches();
}

// ============================================================
// RENDER CONTACT
// ============================================================
function renderContact(items) {
  const phonesWrap  = document.getElementById('contact-phones');
  const actionsWrap = document.getElementById('contact-actions');
  phonesWrap.innerHTML  = '';
  actionsWrap.innerHTML = '';

  let mainPhone   = null;
  let whatsappNum = null;

  items.forEach(item => {
    if (item.type === 'phone') {
      mainPhone = item.value;
      phonesWrap.innerHTML += `
        <div class="contact-phone-item">
          <span class="label">${item.label_ar}</span>
          <a href="tel:${item.value}">${item.value}</a>
        </div>`;
    } else if (item.type === 'whatsapp') {
      whatsappNum = item.value;
    }
  });

  if (mainPhone) {
    actionsWrap.innerHTML += `
      <a href="tel:${mainPhone}" class="btn-contact btn-call">
        <i class="fas fa-phone-alt"></i> اتصل الآن
      </a>`;
  }
  if (whatsappNum) {
    const waLink = 'https://wa.me/' + whatsappNum.replace(/\D/g, '');
    actionsWrap.innerHTML += `
      <a href="${waLink}" target="_blank" rel="noopener" class="btn-contact btn-whatsapp">
        <i class="fab fa-whatsapp"></i> واتساب
      </a>`;
  }

  animateContact();
}

// ============================================================
// RENDER BRANDS
// ============================================================
function renderBrands(brands) {
  const grid = document.getElementById('brands-grid');
  grid.innerHTML = '';

  const icons = ['✦', '◆', '★', '❋', '✿', '◈', '⬡', '❖', '✤', '✵'];

  brands.forEach((brand, i) => {
    const card = document.createElement('div');

    const logoHtml = brand.logo_url
      ? `<div class="brand-logo-wrap">
           <img src="${brand.logo_url}" alt="${brand.name_en}" class="brand-logo-img" loading="lazy"
                onerror="this.parentElement.remove(); this.closest('.brand-card').classList.remove('has-logo')" />
         </div>`
      : `<div class="brand-logo-placeholder">${icons[i % icons.length]}</div>`;

    card.className = brand.logo_url ? 'brand-card has-logo' : 'brand-card';
    card.innerHTML = `
      ${logoHtml}
      <div class="brand-name-en">${brand.name_en}</div>
      ${brand.name_ar ? `<div class="brand-name-ar">${brand.name_ar}</div>` : ''}
    `;

    if (brand.website_url) {
      card.style.cursor = 'pointer';
      card.addEventListener('click', () => window.open(brand.website_url, '_blank', 'noopener'));
    }

    grid.appendChild(card);
  });

  animateBrands();
}

// ============================================================
// RENDER BLOGS / ARTICLES — نفس خلفية الفروع (ذهبي)
// ============================================================
function renderBlogs(articles) {
  const grid = document.getElementById('blogs-grid');
  if (!grid) return;

  grid.innerHTML = '';

  if (!articles || articles.length === 0) {
    grid.innerHTML = '<p style="text-align:center;color:rgba(0,0,0,0.5);padding:40px 0;grid-column:1/-1">لا توجد مقالات حالياً</p>';
    return;
  }

  const STEP = 6;
  let visibleCount = STEP;

  function formatDate(dateStr) {
    if (!dateStr) return '';
    try {
      return new Date(dateStr).toLocaleDateString('ar-SA', {
        year: 'numeric', month: 'long', day: 'numeric',
      });
    } catch { return ''; }
  }

  function buildCard(article) {
    const card = document.createElement('div');
    card.className = 'blog-card';

    const coverHtml = article.cover_image
      ? `<img src="${article.cover_image}" alt="${article.title}" class="blog-cover" loading="lazy" onerror="this.parentElement.querySelector('.blog-cover-placeholder').style.display='flex';this.style.display='none'">`
      : '';
    const placeholderHtml = `<div class="blog-cover-placeholder" style="${article.cover_image ? 'display:none' : ''}">✦</div>`;

    const categoryHtml = article.category
      ? `<span class="blog-category">${article.category}</span>`
      : '<span></span>';

    card.innerHTML = `
      ${coverHtml}${placeholderHtml}
      <div class="blog-body">
        <div class="blog-meta">
          ${categoryHtml}
          <span class="blog-date">${formatDate(article.published_at)}</span>
        </div>
        <div class="blog-title">${article.title}</div>
        ${article.excerpt ? `<div class="blog-excerpt">${article.excerpt}</div>` : ''}
        <a href="article.html?slug=${article.slug}" class="blog-read-more">
          اقرأ المزيد <i class="fas fa-arrow-left"></i>
        </a>
      </div>
    `;
    return card;
  }

  articles.forEach(article => grid.appendChild(buildCard(article)));

  // ---- منطق عرض المزيد ----
  function applyBlogVisibility() {
    const allCards = [...grid.querySelectorAll('.blog-card')];
    allCards.forEach((c, i) => {
      if (i < visibleCount) {
        c.classList.remove('hidden');
      } else {
        c.classList.add('hidden');
      }
    });

    let showMoreBtn = document.getElementById('show-more-blogs');
    if (allCards.length > visibleCount) {
      if (!showMoreBtn) {
        showMoreBtn = document.createElement('button');
        showMoreBtn.id        = 'show-more-blogs';
        showMoreBtn.className = 'show-more-btn';
        showMoreBtn.innerHTML = '<i class="fas fa-chevron-down"></i> عرض المزيد';
        grid.parentElement.appendChild(showMoreBtn);

        showMoreBtn.addEventListener('click', () => {
          visibleCount += STEP;
          applyBlogVisibility();
          gsap.fromTo(
            grid.querySelectorAll('.blog-card:not(.hidden)'),
            { opacity: 0, y: 20 },
            { opacity: 1, y: 0, stagger: 0.06, duration: 0.5, ease: 'power3.out' }
          );
        });
      }
      showMoreBtn.style.display = 'flex';
    } else if (showMoreBtn) {
      showMoreBtn.style.display = 'none';
    }
  }

  applyBlogVisibility();

  // ---- أنيميشن ----
  ScrollTrigger.create({
    trigger: '.blogs-section',
    start: 'top 75%',
    onEnter: () => {
      gsap.to('.blog-card:not(.hidden)', {
        opacity: 1,
        y: 0,
        stagger: 0.06,
        duration: 0.7,
        ease: 'power3.out',
      });
    },
  });
}

// ============================================================
// FETCH DATA FROM API
// ============================================================
async function loadData() {
  try {
    const res  = await fetch('api/get_data.php');
    const data = await res.json();

    if (data.success) {
      renderSocial(data.social || []);
      renderBranches(data.branches || []);
      renderContact(data.contact || []);
      renderBrands(data.brands || []);
      renderBlogs(data.articles || []);
    } else {
      console.warn('API returned error, using fallback');
      loadFallbackData();
    }
  } catch (err) {
    console.warn('API unavailable, using fallback data');
    loadFallbackData();
  }
}

// ============================================================
// FALLBACK STATIC DATA (works without PHP)
// ============================================================
function loadFallbackData() {
  const social = [
    { platform: 'Instagram', platform_ar: 'انستقرام', url: 'https://www.instagram.com/makhazenalenaya/', username: '@makhazenalenaya', icon: 'fa-instagram' },
    { platform: 'TikTok',    platform_ar: 'تيك توك',  url: 'https://www.tiktok.com/@makhazenalenaya',    username: '@makhazenalenaya', icon: 'fa-tiktok'    },
    { platform: 'Snapchat',  platform_ar: 'سناب شات', url: 'https://www.snapchat.com/add/makhazenalenaya', username: 'makhazenalenaya', icon: 'fa-snapchat'  },
    { platform: 'Twitter',   platform_ar: 'تويتر / X', url: 'https://x.com/makhazenalenaya',             username: '@makhazenalenaya', icon: 'fa-x-twitter' },
    { platform: 'WhatsApp',  platform_ar: 'واتساب',   url: 'https://wa.me/966920029921',                 username: '920029921',        icon: 'fa-whatsapp'  },
  ];

  const branches = [
    { name_ar: 'مكة المكرمة - الشرائع',   city_ar: 'مكة المكرمة', address_ar: 'Al Muhandes Umar Qadi، الشرائع',       phone: '920029921', map_url: 'https://maps.app.goo.gl/3bzfVB1pDvXQtqc46' },
    { name_ar: 'مكة المكرمة - فرع ٢',     city_ar: 'مكة المكرمة', address_ar: 'شارع الخمسين، طريق الملك خالد',        phone: '920029921', map_url: 'https://maps.app.goo.gl/kiwaqfbjFSaCCzW1A' },
    { name_ar: 'جدة - ابحر',              city_ar: 'جدة',          address_ar: 'حي ابحر الشمالية، جدة',                phone: '920029921', map_url: 'https://maps.app.goo.gl/XwYU2Mpf8ipFkzxa7' },
    { name_ar: 'جدة - فرع ٢',             city_ar: 'جدة',          address_ar: 'مقابل سبار ماركت، جدة',                phone: '920029921', map_url: 'https://maps.app.goo.gl/3zw2UDqX7orh4UqG6' },
    { name_ar: 'جدة - حي الحمراء',        city_ar: 'جدة',          address_ar: 'حي الحمراء، جدة',                      phone: '920029921', map_url: null },
    { name_ar: 'الرياض - حي الربيع',      city_ar: 'الرياض',       address_ar: 'حي الربيع، الرياض',                    phone: '920029921', map_url: null },
    { name_ar: 'الرياض - حي المحمدية',    city_ar: 'الرياض',       address_ar: 'حي المحمدية، الرياض',                  phone: '920029921', map_url: null },
    { name_ar: 'الرياض - حي الياسمين',    city_ar: 'الرياض',       address_ar: 'حي الياسمين، الرياض',                  phone: '920029921', map_url: null },
    { name_ar: 'الرياض - الدائري الشرقي', city_ar: 'الرياض',       address_ar: 'الدائري الشرقي، الرياض',               phone: '920029921', map_url: null },
    { name_ar: 'الرياض - ظهرة لبن',       city_ar: 'الرياض',       address_ar: 'حي ظهرة لبن، الرياض',                  phone: '920029921', map_url: null },
    { name_ar: 'الدمام - حي النزهة',      city_ar: 'الدمام',       address_ar: 'حي النزهة، الدمام',                    phone: '920029921', map_url: null },
    { name_ar: 'الدمام - حي الفيحاء',     city_ar: 'الدمام',       address_ar: 'شارع خالد بن الوليد، الفيحاء',         phone: '920029921', map_url: 'https://maps.app.goo.gl/WBePwEkF5ESpTLVn7' },
    { name_ar: 'الخبر',                   city_ar: 'الخبر',        address_ar: 'EKGA7484، الخبر',                      phone: '920029921', map_url: 'https://maps.app.goo.gl/NB1uMntPLAiGfbCL6' },
    { name_ar: 'الطائف',                  city_ar: 'الطائف',       address_ar: 'شارع الخمسين، طريق الملك خالد',        phone: '920029921', map_url: 'https://maps.app.goo.gl/q79uN4MrVHdcqroz6' },
    { name_ar: 'الأحساء',                 city_ar: 'الأحساء',      address_ar: 'طريق عين نجم، المبرز',                 phone: '920029921', map_url: 'https://maps.app.goo.gl/mZcuxXo8ZqqQqtaA8' },
    { name_ar: 'حفر الباطن',              city_ar: 'حفر الباطن',   address_ar: '2811 طريق فيصل بن عبدالعزيز',          phone: '920029921', map_url: 'https://maps.app.goo.gl/M7b9e9q28zkFV8H67' },
    { name_ar: 'خميس مشيط',              city_ar: 'خميس مشيط',   address_ar: 'طريق الأمير سلطان، خميس مشيط',         phone: '920029921', map_url: 'https://maps.app.goo.gl/EjWLixBzPt6SnnC18' },
    { name_ar: 'جازان',                   city_ar: 'جازان',        address_ar: 'كورنيش الملك فهد، جازان',              phone: '920029921', map_url: 'https://maps.app.goo.gl/byk6aX6Mko4kestT9' },
    { name_ar: 'تبوك',                    city_ar: 'تبوك',         address_ar: 'تبوك',                                 phone: '920029921', map_url: 'https://maps.app.goo.gl/P2RdZHZCvifsMxTr7' },
    { name_ar: 'حائل',                    city_ar: 'حائل',         address_ar: 'حائل',                                 phone: '920029921', map_url: null },
  ];

  const contact = [
    { type: 'phone',    value: '920029921',     label_ar: 'خدمة العملاء' },
    { type: 'whatsapp', value: '+966920029921', label_ar: 'واتساب'       },
  ];

  const brandNames = [
    { en: "L'Oréal Paris",     ar: 'لوريال باريس'     },
    { en: 'Maybelline New York', ar: 'ميبيلين نيويورك' },
    { en: 'NYX Professional',  ar: 'ناكس برو ميك أب'  },
    { en: 'MAC Cosmetics',      ar: 'ماك كوزمتيكس'    },
    { en: 'Fenty Beauty',       ar: 'فينتي بيوتي'      },
    { en: 'Charlotte Tilbury',  ar: 'شارلوت تيلبيري'   },
    { en: 'Urban Decay',        ar: 'أربان ديكاي'      },
    { en: 'NARS Cosmetics',     ar: 'نارس كوزمتيكس'    },
    { en: 'Lancôme',            ar: 'لانكوم باريس'     },
    { en: 'YSL Beauty',         ar: 'إيف سان لوران'    },
    { en: 'Dior Beauty',        ar: 'ديور بيوتي'       },
    { en: 'Chanel Beauty',      ar: 'شانيل بيوتي'      },
    { en: 'Givenchy Beauty',    ar: 'جيفنشي بيوتي'     },
    { en: 'Armani Beauty',      ar: 'أرماني بيوتي'     },
    { en: 'Tom Ford Beauty',    ar: 'توم فورد بيوتي'   },
    { en: 'Valentino Beauty',   ar: 'فالنتينو بيوتي'   },
    { en: 'Burberry Beauty',    ar: 'باربيري بيوتي'    },
    { en: 'Jo Malone London',   ar: 'جو مالون لندن'    },
    { en: 'Parfums de Marly',   ar: 'بارفانز دي مارلي' },
    { en: 'Kilian Paris',       ar: 'كيليان باريس'     },
    { en: 'Prada Beauty',       ar: 'برادا بيوتي'      },
    { en: 'Carolina Herrera',   ar: 'كارولينا هيريرا'  },
    { en: 'Viktor&Rolf',        ar: 'فيكتور آند رولف'  },
    { en: 'Narciso Rodriguez',  ar: 'ناركيسو رودريغيز' },
    { en: 'Memo Paris',         ar: 'ميمو باريس'       },
  ];

  const brands = brandNames.map(b => ({ name_en: b.en, name_ar: b.ar, logo_url: null }));

  renderSocial(social);
  renderBranches(branches);
  renderContact(contact);
  renderBrands(brands);
}

// ============================================================
// SECTION HEADERS SCROLL ANIMATION
// ============================================================
function initSectionAnimations() {
  gsap.utils.toArray('.section-header').forEach(header => {
    gsap.from(header, {
      scrollTrigger: {
        trigger: header,
        start: 'top 85%',
      },
      opacity: 0,
      y: 40,
      duration: 0.8,
      ease: 'power3.out',
    });
  });
}

// ============================================================
// PARALLAX ON PATTERNS
// ============================================================
function initParallax() {
  gsap.utils.toArray('.section-pattern-accent, .brands-pattern-bottom, .footer-pattern').forEach(el => {
    gsap.to(el, {
      scrollTrigger: {
        trigger: el,
        start: 'top bottom',
        end: 'bottom top',
        scrub: true,
      },
      y: -40,
      ease: 'none',
    });
  });

  gsap.to('.hero-pattern-top', {
    scrollTrigger: {
      trigger: '.hero-section',
      start: 'top top',
      end: 'bottom top',
      scrub: true,
    },
    y: -80,
    ease: 'none',
  });

  gsap.to('.hero-pattern-bottom', {
    scrollTrigger: {
      trigger: '.hero-section',
      start: 'top top',
      end: 'bottom top',
      scrub: true,
    },
    y: 60,
    ease: 'none',
  });
}

// ============================================================
// SCROLL-TRIGGERED GSAP for ScrollTo (fallback)
// ============================================================
if (gsap.plugins && !gsap.plugins.scrollTo) {
  const _links = document.querySelectorAll('a[href^="#"]');
  _links.forEach(link => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      if (href && href.length > 1) {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    });
  });
}

// ============================================================
// INIT
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
  initParticles();

  runPreloader(() => {
    heroEntrance();
    initHeader();
    initSectionAnimations();
    initParallax();
    loadData();
  });
});