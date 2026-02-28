/* ============================================================
   Makhazen Alenayah - Admin Dashboard JS
============================================================ */

"use strict";

// ============================================================
// STATE
// ============================================================
const state = {
  currentTab: 'branches',
  editId: null,
  editTable: null,
  deleteId: null,
  deleteTable: null,
  data: {
    branches: [],
    brands: [],
    social: [],
    contact: [],
  },
};

// ============================================================
// API HELPERS
// ============================================================
const API_BASE = '../api/';

async function apiGet(table) {
  const res  = await fetch(`${API_BASE}admin_crud.php?table=${table}`);
  const data = await res.json();
  if (!data.success) throw new Error(data.message || 'فشل التحميل');
  return data.data;
}

async function apiPost(table, body) {
  const res  = await fetch(`${API_BASE}admin_crud.php?table=${table}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body),
  });
  return res.json();
}

async function apiPut(table, id, body) {
  const res  = await fetch(`${API_BASE}admin_crud.php?table=${table}&id=${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(body),
  });
  return res.json();
}

async function apiDelete(table, id) {
  const res = await fetch(`${API_BASE}admin_crud.php?table=${table}&id=${id}`, {
    method: 'DELETE',
  });
  return res.json();
}

async function apiToggle(table, id) {
  const res = await fetch(`${API_BASE}admin_crud.php?table=${table}&id=${id}`, {
    method: 'PATCH',
  });
  return res.json();
}

// ============================================================
// TOAST
// ============================================================
function showToast(msg, type = 'success') {
  const toast = document.getElementById('toast');
  toast.textContent = msg;
  toast.className   = `toast show ${type}`;
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('show'), 3000);
}

// ============================================================
// TABS
// ============================================================
function initTabs() {
  document.querySelectorAll('.nav-item').forEach(btn => {
    btn.addEventListener('click', () => {
      const tab = btn.dataset.tab;
      switchTab(tab);
      // Close sidebar on mobile
      closeSidebar();
    });
  });
}

function switchTab(tab) {
  state.currentTab = tab;

  document.querySelectorAll('.nav-item').forEach(b => {
    b.classList.toggle('active', b.dataset.tab === tab);
  });
  document.querySelectorAll('.tab-content').forEach(c => {
    c.classList.toggle('active', c.id === `tab-${tab}`);
  });

  const titles = {
    branches: 'الفروع',
    brands:   'البراندات',
    social:   'التواصل الاجتماعي',
    contact:  'معلومات التواصل',
    tracking: 'كودات التتبع',
  };
  document.getElementById('topbar-title').textContent = titles[tab] || tab;
}

// ============================================================
// RENDER FUNCTIONS
// ============================================================

// --- BRANCHES ---
function renderBranches(rows) {
  const tbody = document.getElementById('branches-tbody');
  tbody.innerHTML = '';

  rows.forEach((row, i) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${i + 1}</td>
      <td><strong style="color:#fff">${row.name_ar}</strong></td>
      <td>${row.city_ar || '-'}</td>
      <td dir="ltr">${row.phone || '-'}</td>
      <td>${statusBadge(row.is_active)}</td>
      <td>
        <div class="actions">
          <button class="btn-icon" title="تعديل" onclick="editRecord('branches', ${row.id})"><i class="fas fa-edit"></i></button>
          <button class="btn-icon" title="تفعيل/تعطيل" onclick="toggleRecord('branches', ${row.id})"><i class="fas fa-toggle-on"></i></button>
          <button class="btn-icon del" title="حذف" onclick="confirmDelete('branches', ${row.id})"><i class="fas fa-trash"></i></button>
        </div>
      </td>`;
    tbody.appendChild(tr);
  });

  document.getElementById('branches-count').textContent = `(${rows.length} فرع)`;
  document.getElementById('badge-branches').textContent = rows.length;
}

// --- BRANDS ---
function renderBrands(rows) {
  const tbody = document.getElementById('brands-tbody');
  tbody.innerHTML = '';

  rows.forEach((row, i) => {
    const logoHtml = row.logo_url
      ? `<img src="../${row.logo_url}" class="brand-thumb" alt="${row.name_en}" />`
      : `<div class="brand-placeholder-icon"><i class="fas fa-tag"></i></div>`;

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${i + 1}</td>
      <td>${logoHtml}</td>
      <td><strong style="color:#fff">${row.name_en}</strong></td>
      <td>${row.name_ar || '-'}</td>
      <td>${statusBadge(row.is_active)}</td>
      <td>
        <div class="actions">
          <button class="btn-icon" title="تعديل" onclick="editRecord('brands', ${row.id})"><i class="fas fa-edit"></i></button>
          <button class="btn-icon" title="تفعيل/تعطيل" onclick="toggleRecord('brands', ${row.id})"><i class="fas fa-toggle-on"></i></button>
          <button class="btn-icon del" title="حذف" onclick="confirmDelete('brands', ${row.id})"><i class="fas fa-trash"></i></button>
        </div>
      </td>`;
    tbody.appendChild(tr);
  });

  document.getElementById('brands-count').textContent = `(${rows.length} براند)`;
  document.getElementById('badge-brands').textContent = rows.length;
}

// --- SOCIAL ---
function renderSocial(rows) {
  const tbody = document.getElementById('social-tbody');
  tbody.innerHTML = '';

  rows.forEach((row, i) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${i + 1}</td>
      <td><strong style="color:#fff">${row.platform}</strong></td>
      <td>${row.platform_ar || '-'}</td>
      <td>${row.username || '-'}</td>
      <td><a href="${row.url}" target="_blank" style="color:#FFCF06;font-size:11px">${truncate(row.url, 30)}</a></td>
      <td>${statusBadge(row.is_active)}</td>
      <td>
        <div class="actions">
          <button class="btn-icon" title="تعديل" onclick="editRecord('social_media', ${row.id})"><i class="fas fa-edit"></i></button>
          <button class="btn-icon" title="تفعيل/تعطيل" onclick="toggleRecord('social_media', ${row.id})"><i class="fas fa-toggle-on"></i></button>
          <button class="btn-icon del" title="حذف" onclick="confirmDelete('social_media', ${row.id})"><i class="fas fa-trash"></i></button>
        </div>
      </td>`;
    tbody.appendChild(tr);
  });

  document.getElementById('social-count').textContent = `(${rows.length} منصة)`;
  document.getElementById('badge-social').textContent = rows.length;
}

// --- CONTACT ---
function renderContact(rows) {
  const tbody = document.getElementById('contact-tbody');
  tbody.innerHTML = '';

  rows.forEach((row, i) => {
    const typeLabels = { phone: 'هاتف', whatsapp: 'واتساب', email: 'بريد إلكتروني' };
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${i + 1}</td>
      <td>${typeLabels[row.type] || row.type}</td>
      <td dir="ltr"><strong style="color:#fff">${row.value}</strong></td>
      <td>${row.label_ar || '-'}</td>
      <td>${statusBadge(row.is_active)}</td>
      <td>
        <div class="actions">
          <button class="btn-icon" title="تعديل" onclick="editRecord('contact_info', ${row.id})"><i class="fas fa-edit"></i></button>
          <button class="btn-icon del" title="حذف" onclick="confirmDelete('contact_info', ${row.id})"><i class="fas fa-trash"></i></button>
        </div>
      </td>`;
    tbody.appendChild(tr);
  });
}

// ============================================================
// LOAD ALL DATA
// ============================================================
async function loadAll() {
  try {
    const [branches, brands, social, contact] = await Promise.all([
      apiGet('branches'),
      apiGet('brands'),
      apiGet('social_media'),
      apiGet('contact_info'),
    ]);

    state.data.branches = branches;
    state.data.brands   = brands;
    state.data.social   = social;
    state.data.contact  = contact;

    renderBranches(branches);
    renderBrands(brands);
    renderSocial(social);
    renderContact(contact);
  } catch (err) {
    showToast('فشل تحميل البيانات: ' + err.message, 'error');
  }
}

// ============================================================
// MODAL FORMS
// ============================================================
const FORMS = {
  branch: (data = {}) => `
    <div class="form-row">
      <div class="modal-form-group">
        <label>الاسم (عربي) *</label>
        <input type="text" class="form-input" id="f-name_ar" value="${data.name_ar || ''}" placeholder="اسم الفرع" />
      </div>
      <div class="modal-form-group">
        <label>الاسم (إنجليزي)</label>
        <input type="text" class="form-input" id="f-name_en" value="${data.name_en || ''}" placeholder="Branch Name" />
      </div>
    </div>
    <div class="form-row">
      <div class="modal-form-group">
        <label>المدينة (عربي) *</label>
        <input type="text" class="form-input" id="f-city_ar" value="${data.city_ar || ''}" placeholder="الرياض" />
      </div>
      <div class="modal-form-group">
        <label>المدينة (إنجليزي)</label>
        <input type="text" class="form-input" id="f-city_en" value="${data.city_en || ''}" placeholder="Riyadh" />
      </div>
    </div>
    <div class="modal-form-group">
      <label>العنوان (عربي)</label>
      <textarea class="form-input" id="f-address_ar" placeholder="العنوان بالتفصيل">${data.address_ar || ''}</textarea>
    </div>
    <div class="form-row">
      <div class="modal-form-group">
        <label>رقم الهاتف</label>
        <input type="text" class="form-input" id="f-phone" value="${data.phone || ''}" placeholder="0112345678" dir="ltr" />
      </div>
      <div class="modal-form-group">
        <label>رابط خرائط</label>
        <input type="url" class="form-input" id="f-map_url" value="${data.map_url || ''}" placeholder="https://maps.google.com/..." dir="ltr" />
      </div>
    </div>
    <div class="form-row">
      <div class="modal-form-group">
        <label>الترتيب</label>
        <input type="number" class="form-input" id="f-sort_order" value="${data.sort_order || 0}" min="0" />
      </div>
      <div class="modal-form-group">
        <label>الحالة</label>
        <select class="form-input" id="f-is_active">
          <option value="1" ${data.is_active != 0 ? 'selected' : ''}>نشط</option>
          <option value="0" ${data.is_active == 0 ? 'selected' : ''}>معطل</option>
        </select>
      </div>
    </div>`,

  brand: (data = {}) => `
    <div class="form-row">
      <div class="modal-form-group">
        <label>الاسم (إنجليزي) *</label>
        <input type="text" class="form-input" id="f-name_en" value="${data.name_en || ''}" placeholder="Brand Name" />
      </div>
      <div class="modal-form-group">
        <label>الاسم (عربي)</label>
        <input type="text" class="form-input" id="f-name_ar" value="${data.name_ar || ''}" placeholder="اسم البراند" />
      </div>
    </div>
    <div class="modal-form-group">
      <label>رابط الموقع</label>
      <input type="url" class="form-input" id="f-website_url" value="${data.website_url || ''}" placeholder="https://brand.com" dir="ltr" />
    </div>
    <div class="modal-form-group">
      <label>شعار البراند</label>
      <div id="logo-zone">
        ${data.logo_url
          ? `<div class="logo-current-wrap">
               <div class="logo-current-preview">
                 <img src="../${data.logo_url}" alt="الشعار الحالي" id="logo-current-img" />
               </div>
               <div class="logo-current-actions">
                 <button type="button" class="btn-change-logo" onclick="document.getElementById('logo-file').click()">
                   <i class="fas fa-sync-alt"></i> تغيير الشعار
                 </button>
                 <button type="button" class="btn-remove-logo" onclick="removeLogo()">
                   <i class="fas fa-trash"></i> حذف الشعار
                 </button>
               </div>
             </div>`
          : `<div class="upload-area" id="upload-area-btn" onclick="document.getElementById('logo-file').click()">
               <i class="fas fa-cloud-upload-alt"></i>
               <p>اضغط لرفع الشعار</p>
               <small style="color:#555">JPG · PNG · WebP · SVG (بحد أقصى 2MB)</small>
             </div>`
        }
        <div class="upload-preview" id="upload-preview" style="${data.logo_url ? 'display:none' : ''}"></div>
      </div>
      <input type="file" id="logo-file" accept="image/*" style="display:none" onchange="previewAndUpload(this)" />
      <input type="hidden" id="f-logo_url" value="${data.logo_url || ''}" />
    </div>
    <div class="form-row">
      <div class="modal-form-group">
        <label>الترتيب</label>
        <input type="number" class="form-input" id="f-sort_order" value="${data.sort_order || 0}" min="0" />
      </div>
      <div class="modal-form-group">
        <label>الحالة</label>
        <select class="form-input" id="f-is_active">
          <option value="1" ${data.is_active != 0 ? 'selected' : ''}>نشط</option>
          <option value="0" ${data.is_active == 0 ? 'selected' : ''}>معطل</option>
        </select>
      </div>
    </div>`,

  social: (data = {}) => `
    <div class="form-row">
      <div class="modal-form-group">
        <label>المنصة (إنجليزي) *</label>
        <input type="text" class="form-input" id="f-platform" value="${data.platform || ''}" placeholder="Instagram" />
      </div>
      <div class="modal-form-group">
        <label>المنصة (عربي)</label>
        <input type="text" class="form-input" id="f-platform_ar" value="${data.platform_ar || ''}" placeholder="انستقرام" />
      </div>
    </div>
    <div class="modal-form-group">
      <label>الرابط *</label>
      <input type="url" class="form-input" id="f-url" value="${data.url || ''}" placeholder="https://instagram.com/..." dir="ltr" />
    </div>
    <div class="form-row">
      <div class="modal-form-group">
        <label>اسم المستخدم</label>
        <input type="text" class="form-input" id="f-username" value="${data.username || ''}" placeholder="@username" dir="ltr" />
      </div>
      <div class="modal-form-group">
        <label>أيقونة Font Awesome</label>
        <input type="text" class="form-input" id="f-icon" value="${data.icon || ''}" placeholder="fa-instagram" dir="ltr" />
      </div>
    </div>
    <div class="form-row">
      <div class="modal-form-group">
        <label>لون المنصة</label>
        <input type="color" class="form-input" id="f-color" value="${data.color || '#ffffff'}" style="height:42px;padding:4px" />
      </div>
      <div class="modal-form-group">
        <label>الترتيب</label>
        <input type="number" class="form-input" id="f-sort_order" value="${data.sort_order || 0}" min="0" />
      </div>
    </div>
    <div class="modal-form-group">
      <label>الحالة</label>
      <select class="form-input" id="f-is_active">
        <option value="1" ${data.is_active != 0 ? 'selected' : ''}>نشط</option>
        <option value="0" ${data.is_active == 0 ? 'selected' : ''}>معطل</option>
      </select>
    </div>`,

  contact: (data = {}) => `
    <div class="form-row">
      <div class="modal-form-group">
        <label>النوع *</label>
        <select class="form-input" id="f-type">
          <option value="phone"    ${data.type === 'phone'    ? 'selected' : ''}>هاتف</option>
          <option value="whatsapp" ${data.type === 'whatsapp' ? 'selected' : ''}>واتساب</option>
          <option value="email"    ${data.type === 'email'    ? 'selected' : ''}>بريد إلكتروني</option>
        </select>
      </div>
      <div class="modal-form-group">
        <label>التسمية</label>
        <input type="text" class="form-input" id="f-label_ar" value="${data.label_ar || ''}" placeholder="خدمة العملاء" />
      </div>
    </div>
    <div class="modal-form-group">
      <label>القيمة *</label>
      <input type="text" class="form-input" id="f-value" value="${data.value || ''}" placeholder="920000000" dir="ltr" />
    </div>
    <div class="form-row">
      <div class="modal-form-group">
        <label>الترتيب</label>
        <input type="number" class="form-input" id="f-sort_order" value="${data.sort_order || 0}" min="0" />
      </div>
      <div class="modal-form-group">
        <label>الحالة</label>
        <select class="form-input" id="f-is_active">
          <option value="1" ${data.is_active != 0 ? 'selected' : ''}>نشط</option>
          <option value="0" ${data.is_active == 0 ? 'selected' : ''}>معطل</option>
        </select>
      </div>
    </div>`,
};

// ============================================================
// OPEN / CLOSE MODAL
// ============================================================
const MODAL_TITLES = {
  branch:  'فرع',
  brand:   'براند',
  social:  'منصة تواصل',
  contact: 'معلومة تواصل',
};

function openModal(type, data = {}) {
  state.editTable = type === 'branch'  ? 'branches'    :
                    type === 'brand'   ? 'brands'      :
                    type === 'social'  ? 'social_media' :
                    type === 'contact' ? 'contact_info' : type;

  const isEdit = !!state.editId;
  document.getElementById('modal-title').textContent = (isEdit ? 'تعديل ' : 'إضافة ') + (MODAL_TITLES[type] || type);
  document.getElementById('modal-body').innerHTML    = FORMS[type](data);
  document.getElementById('modal-overlay').classList.add('open');
}

function closeModal() {
  document.getElementById('modal-overlay').classList.remove('open');
  state.editId    = null;
  state.editTable = null;
}

// ============================================================
// SAVE RECORD
// ============================================================
async function saveRecord() {
  const btn = document.getElementById('modal-save-btn');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';

  try {
    const body = collectFormData();
    let res;

    if (state.editId) {
      res = await apiPut(state.editTable, state.editId, body);
    } else {
      res = await apiPost(state.editTable, body);
    }

    if (res.success) {
      showToast(res.message || 'تم الحفظ بنجاح', 'success');
      closeModal();
      await loadAll();
    } else {
      showToast(res.message || 'حدث خطأ', 'error');
    }
  } catch (err) {
    showToast('فشل الاتصال بالخادم', 'error');
  }

  btn.disabled = false;
  btn.innerHTML = '<i class="fas fa-save"></i> حفظ';
}

function collectFormData() {
  const fields = document.querySelectorAll('#modal-body [id^="f-"]');
  const body = {};
  fields.forEach(el => {
    const key = el.id.replace('f-', '');
    body[key] = el.type === 'checkbox' ? (el.checked ? 1 : 0) : el.value;
  });
  return body;
}

// ============================================================
// EDIT RECORD
// ============================================================
async function editRecord(table, id) {
  try {
    const res = await fetch(`${API_BASE}admin_crud.php?table=${table}&id=${id}`);
    const data = await res.json();
    if (!data.success) throw new Error(data.message);

    state.editId = id;
    const typeMap = {
      branches:    'branch',
      brands:      'brand',
      social_media:'social',
      contact_info:'contact',
    };
    openModal(typeMap[table], data.data);
  } catch (err) {
    showToast('فشل تحميل البيانات: ' + err.message, 'error');
  }
}

// ============================================================
// TOGGLE ACTIVE
// ============================================================
async function toggleRecord(table, id) {
  try {
    const res = await apiToggle(table, id);
    if (res.success) {
      showToast(res.message, 'success');
      await loadAll();
    } else {
      showToast(res.message || 'حدث خطأ', 'error');
    }
  } catch {
    showToast('فشل الاتصال بالخادم', 'error');
  }
}

// ============================================================
// DELETE
// ============================================================
function confirmDelete(table, id) {
  state.deleteId    = id;
  state.deleteTable = table;
  document.getElementById('confirm-overlay').classList.add('open');
}

function closeConfirm() {
  document.getElementById('confirm-overlay').classList.remove('open');
  state.deleteId    = null;
  state.deleteTable = null;
}

document.getElementById('confirm-delete-btn').addEventListener('click', async () => {
  if (!state.deleteId || !state.deleteTable) return;

  try {
    const res = await apiDelete(state.deleteTable, state.deleteId);
    if (res.success) {
      showToast('تم الحذف بنجاح', 'success');
      closeConfirm();
      await loadAll();
    } else {
      showToast(res.message || 'حدث خطأ', 'error');
    }
  } catch {
    showToast('فشل الاتصال بالخادم', 'error');
  }
});

// ============================================================
// BRAND LOGO UPLOAD
// ============================================================
async function previewAndUpload(input) {
  const file = input.files[0];
  if (!file) return;

  // Instant local preview
  const reader = new FileReader();
  reader.onload = (e) => {
    const preview = document.getElementById('upload-preview');
    preview.style.display = 'block';
    preview.innerHTML = `
      <div class="logo-uploading-preview">
        <img src="${e.target.result}" alt="preview" />
        <span class="uploading-badge"><i class="fas fa-spinner fa-spin"></i> جاري الرفع...</span>
      </div>`;

    // Hide the upload button if visible
    const btn = document.getElementById('upload-area-btn');
    if (btn) btn.style.display = 'none';
  };
  reader.readAsDataURL(file);

  // Upload to server
  const formData = new FormData();
  formData.append('logo', file);

  try {
    const res  = await fetch('../api/upload.php', { method: 'POST', body: formData });
    const data = await res.json();
    if (data.success) {
      document.getElementById('f-logo_url').value = data.url;
      // Update preview to show success
      const preview = document.getElementById('upload-preview');
      preview.innerHTML = `
        <div class="logo-uploading-preview">
          <img src="../${data.url}" alt="preview" />
          <span class="uploading-badge success"><i class="fas fa-check"></i> تم الرفع</span>
        </div>`;
      showToast('تم رفع الشعار بنجاح', 'success');
    } else {
      showToast(data.message || 'فشل رفع الشعار', 'error');
      resetUploadArea();
    }
  } catch {
    showToast('فشل رفع الشعار', 'error');
    resetUploadArea();
  }
}

function removeLogo() {
  document.getElementById('f-logo_url').value = '';
  document.getElementById('logo-zone').innerHTML = `
    <div class="upload-area" id="upload-area-btn" onclick="document.getElementById('logo-file').click()">
      <i class="fas fa-cloud-upload-alt"></i>
      <p>اضغط لرفع الشعار</p>
      <small style="color:#555">JPG · PNG · WebP · SVG (بحد أقصى 2MB)</small>
    </div>
    <div class="upload-preview" id="upload-preview"></div>`;
  showToast('سيتم حذف الشعار عند الحفظ', 'success');
}

function resetUploadArea() {
  const btn = document.getElementById('upload-area-btn');
  if (btn) btn.style.display = '';
  const preview = document.getElementById('upload-preview');
  if (preview) { preview.style.display = 'none'; preview.innerHTML = ''; }
}

// ============================================================
// TABLE SEARCH FILTER
// ============================================================
function filterTable(tableId, query) {
  const table = document.getElementById(tableId);
  const rows  = table.querySelectorAll('tbody tr');
  const q     = query.toLowerCase();

  rows.forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}

// ============================================================
// SIDEBAR MOBILE
// ============================================================
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebar-overlay').classList.remove('show');
}

function initSidebar() {
  document.getElementById('menu-toggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebar-overlay').classList.toggle('show');
  });

  document.getElementById('sidebar-overlay').addEventListener('click', closeSidebar);
}

// ============================================================
// TRACKING CODES (Google Analytics / GTM)
// ============================================================
async function loadTrackingCodes() {
  try {
    const res  = await fetch('../api/settings.php?admin=1');
    const data = await res.json();
    if (!data.success) return;
    data.data.forEach(row => {
      const el = document.getElementById(row.key);
      if (el) el.value = row.value || '';
    });
  } catch (_) {}
}

async function saveTrackingCodes() {
  const btn = document.querySelector('#tab-tracking .btn-add');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';

  const body = {
    header_code: document.getElementById('header_code').value,
    body_code:   document.getElementById('body_code').value,
  };

  try {
    const res  = await fetch('../api/settings.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body),
    });
    const data = await res.json();
    if (data.success) {
      showToast(data.message || 'تم الحفظ بنجاح', 'success');
    } else {
      showToast(data.message || 'حدث خطأ', 'error');
    }
  } catch {
    showToast('فشل الاتصال بالخادم', 'error');
  }

  btn.disabled = false;
  btn.innerHTML = '<i class="fas fa-save"></i> حفظ الكودات';
}

// ============================================================
// LOGOUT
// ============================================================
document.getElementById('logout-btn').addEventListener('click', async () => {
  try {
    await fetch('../api/admin_login.php?action=logout');
  } catch (_) {}
  window.location.href = 'index.html';
});

// ============================================================
// CLOSE MODALS ON OVERLAY CLICK
// ============================================================
document.getElementById('modal-overlay').addEventListener('click', (e) => {
  if (e.target === document.getElementById('modal-overlay')) closeModal();
});
document.getElementById('confirm-overlay').addEventListener('click', (e) => {
  if (e.target === document.getElementById('confirm-overlay')) closeConfirm();
});

// ============================================================
// HELPERS
// ============================================================
function statusBadge(active) {
  return active
    ? `<span class="status-badge status-active"><i class="fas fa-check-circle"></i> نشط</span>`
    : `<span class="status-badge status-inactive"><i class="fas fa-times-circle"></i> معطل</span>`;
}

function truncate(str, len) {
  if (!str) return '';
  return str.length > len ? str.substring(0, len) + '...' : str;
}

// ============================================================
// AUTH CHECK & INIT
// ============================================================
async function init() {
  // Check session
  try {
    const res  = await fetch('../api/admin_login.php?action=check');
    const data = await res.json();
    if (!data.success) {
      window.location.href = 'index.html';
      return;
    }
    document.getElementById('user-name').textContent = data.admin?.name || 'مدير';
  } catch {
    window.location.href = 'index.html';
    return;
  }

  initTabs();
  initSidebar();
  await Promise.all([loadAll(), loadTrackingCodes()]);
}

init();
