"use strict";
/* ============================================================
   Makhazen Alenayah - WordPress Theme JS
   Uses WP REST API + GSAP animations
============================================================ */

// ============================================================
// STICKY HEADER
// ============================================================
(function initHeader() {
  const header = document.getElementById('site-header');
  if (!header) return;

  header.classList.add('visible');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 80) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  }, { passive: true });
})();

// ============================================================
// FORMAT DATE — عربي
// ============================================================
function formatDate(dateStr) {
  if (!dateStr) return '';
  try {
    return new Date(dateStr).toLocaleDateString('ar-SA', {
      year: 'numeric', month: 'long', day: 'numeric',
    });
  } catch { return ''; }
}

// ============================================================
// RENDER SOCIAL CARDS
// ============================================================
function renderSocial(items) {
  const grid = document.getElementById('social-grid');
  if (!grid || !items.length) return;
  grid.innerHTML = '';

  const defaults = {
    instagram: { bg: 'rgba(225,48,108,0.1)', icon: '#E1306C' },
    tiktok:    { bg: 'rgba(0,0,0,0.07)',      icon: '#000' },
    snapchat:  { bg: 'rgba(255,207,6,0.15)',  icon: '#c9a200' },
    whatsapp:  { bg: 'rgba(37,211,102,0.1)',  icon: '#1a9e50' },
    youtube:   { bg: 'rgba(255,0,0,0.08)',    icon: '#CC0000' },
    facebook:  { bg: 'rgba(24,119,242,0.1)',  icon: '#1877F2' },
  };

  items.forEach(item => {
    const p   = (item.platform || '').toLowerCase();
    const clr = defaults[p] || { bg: 'rgba(255,207,6,0.1)', icon: '#c9a200' };
    const a   = document.createElement('a');
    a.href   = item.url || '#';
    a.target = '_blank';
    a.rel    = 'noopener noreferrer';
    a.style.cssText = `display:flex;align-items:center;gap:14px;padding:18px 20px;background:#fff;border:1px solid #e0ddd5;border-radius:14px;text-decoration:none;color:#000;transition:all 0.3s`;
    a.innerHTML = `
      <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;background:${clr.bg};color:${clr.icon}">
        <i class="fab ${item.icon || 'fa-globe'}"></i>
      </div>
      <div>
        <div style="font-size:14px;font-weight:700">${item.platform_ar || item.platform || ''}</div>
        <div style="font-size:12px;color:#666">${item.username || ''}</div>
      </div>
      <i class="fas fa-arrow-left" style="margin-right:auto;color:#999;font-size:12px"></i>`;
    grid.appendChild(a);
  });
}

// ============================================================
// RENDER BRANCHES
// ============================================================
function renderBranches(branches) {
  const grid       = document.getElementById('branches-grid');
  const filterWrap = document.getElementById('city-filter');
  if (!grid) return;
  grid.innerHTML = '';

  // Build cities
  const cities = [];
  branches.forEach(b => {
    if (b.city_ar && !cities.find(c => c.ar === b.city_ar)) {
      cities.push({ ar: b.city_ar });
    }
  });

  cities.forEach(city => {
    const btn = document.createElement('button');
    btn.className    = 'city-btn';
    btn.dataset.city = city.ar;
    btn.textContent  = city.ar;
    filterWrap?.appendChild(btn);
  });

  // Render cards
  branches.forEach(branch => {
    const card = document.createElement('div');
    card.className    = 'branch-card';
    card.dataset.city = branch.city_ar || '';
    card.innerHTML = `
      <div style="font-size:15px;font-weight:700">${branch.name_ar}</div>
      <div style="font-size:13px;color:#666;line-height:1.6">
        <i class="fas fa-map-pin" style="color:#c9a200;margin-left:6px"></i>${branch.address_ar || ''}
      </div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:4px">
        ${branch.phone ? `<a href="tel:${branch.phone}" style="font-size:13px;color:#333;text-decoration:none"><i class="fas fa-phone-alt" style="color:#c9a200;margin-left:4px"></i>${branch.phone}</a>` : ''}
        ${branch.map_url ? `<a href="${branch.map_url}" target="_blank" rel="noopener" class="branch-map-btn"><i class="fas fa-map-marker-alt"></i> الموقع</a>` : ''}
      </div>`;
    grid.appendChild(card);
  });

  // Filter
  if (filterWrap) {
    filterWrap.addEventListener('click', e => {
      const btn = e.target.closest('.city-btn');
      if (!btn) return;
      filterWrap.querySelectorAll('.city-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const city = btn.dataset.city;
      grid.querySelectorAll('.branch-card').forEach(c => {
        c.style.display = city === 'all' || c.dataset.city === city ? '' : 'none';
      });
    });
  }
}

// ============================================================
// RENDER BRANDS
// ============================================================
function renderBrands(brands) {
  const grid = document.getElementById('brands-grid');
  if (!grid) return;
  // SSR already rendered — skip if grid has children
  if (grid.children.length) return;

  brands.forEach(brand => {
    const card = document.createElement('div');
    card.className = 'brand-card';
    const logoHtml = brand.logo_url
      ? `<img src="${brand.logo_url}" alt="${brand.name_en}" class="brand-logo" loading="lazy" />`
      : `<div style="width:44px;height:44px;background:rgba(255,207,6,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;color:#c9a200">✦</div>`;
    card.innerHTML = `${logoHtml}<div class="brand-name">${brand.name_en}</div>`;
    if (brand.website_url) {
      card.style.cursor = 'pointer';
      card.addEventListener('click', () => window.open(brand.website_url, '_blank', 'noopener'));
    }
    grid.appendChild(card);
  });
}

// ============================================================
// RENDER ARTICLES (enhance SSR or render if empty)
// ============================================================
function renderArticles(articles) {
  const grid = document.getElementById('articles-grid');
  if (!grid) return;
  // Already SSR rendered — skip
  if (grid.children.length) return;

  if (!articles.length) {
    grid.innerHTML = '<p style="text-align:center;color:rgba(0,0,0,0.5);padding:40px 0;grid-column:1/-1">لا توجد مقالات حالياً</p>';
    return;
  }

  articles.forEach(article => {
    const card = document.createElement('article');
    card.className = 'article-card';
    const coverHtml = article.cover_image
      ? `<img src="${article.cover_image}" alt="${article.title}" class="article-card-cover" loading="lazy" />`
      : '';
    card.innerHTML = `
      ${coverHtml}
      <div class="article-card-body">
        <div class="article-card-meta">
          ${article.category ? `<span class="article-card-cat">${article.category}</span>` : '<span></span>'}
          <span class="article-card-date">${formatDate(article.published_at)}</span>
        </div>
        <h3 class="article-card-title"><a href="${article.permalink || '#'}" style="color:inherit;text-decoration:none">${article.title}</a></h3>
        ${article.excerpt ? `<div class="article-card-excerpt">${article.excerpt}</div>` : ''}
        <a href="${article.permalink || '#'}" class="article-card-link">اقرأ المزيد <i class="fas fa-arrow-left"></i></a>
      </div>`;
    grid.appendChild(card);
  });
}

// ============================================================
// LOAD DATA FROM WP REST API
// ============================================================
async function loadData() {
  if (typeof makhzanData === 'undefined') return;

  const base = makhzanData.restUrl;

  try {
    const [settingsRes, branchesRes, brandsRes, articlesRes] = await Promise.all([
      fetch(base + 'settings'),
      fetch(base + 'branches'),
      fetch(base + 'brands'),
      fetch(base + 'articles?limit=6'),
    ]);

    const [settings, branchData, brandData, articleData] = await Promise.all([
      settingsRes.json(),
      branchesRes.json(),
      brandsRes.json(),
      articlesRes.json(),
    ]);

    if (settings.success)  renderSocial(settings.data.social  || []);
    if (branchData.success) renderBranches(branchData.data   || []);
    if (brandData.success)  renderBrands(brandData.data      || []);
    if (articleData.success) renderArticles(articleData.data || []);
  } catch (err) {
    console.warn('REST API error:', err);
  }
}

// ============================================================
// INIT
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
  // Only load dynamic data on homepage
  if (document.getElementById('branches-grid')) {
    loadData();
  }
});
