<?php
/**
 * 404 Template
 */
get_header();
?>
<section style="background:var(--gold);min-height:80vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:80px 20px">
  <div>
    <div style="font-size:120px;font-weight:900;color:var(--black);line-height:1">404</div>
    <h1 style="font-size:28px;font-weight:800;margin:16px 0 8px">الصفحة غير موجودة</h1>
    <p style="color:rgba(0,0,0,0.6);margin-bottom:32px">عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها.</p>
    <a href="<?php echo esc_url(home_url('/')); ?>"
       style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;background:var(--black);color:var(--gold);border-radius:30px;font-weight:700;font-size:15px;text-decoration:none">
      <i class="fas fa-home"></i> الرئيسية
    </a>
  </div>
</section>
<?php get_footer(); ?>
