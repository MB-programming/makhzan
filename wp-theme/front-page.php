<?php
/**
 * Homepage Template
 * يستخدم WordPress REST API لتحميل البيانات ديناميكياً
 */
get_header();
?>

<!-- ============================================================
  HERO SECTION
============================================================ -->
<section id="hero" class="hero-section">
  <div class="hero-content">
    <div class="hero-logo-wrap">
      <?php
      if (has_custom_logo()) {
          the_custom_logo();
      } else {
          echo '<img src="' . esc_url(get_template_directory_uri()) . '/assets/images/logo.webp" alt="' . esc_attr(get_bloginfo('name')) . '" class="hero-logo-img" />';
      }
      ?>
    </div>
    <h1><?php echo esc_html(get_option('makhzan_hero_tagline', 'العروض القوية ما تلقاها اونلاين تشوفها بعينك بمخازن العناية')); ?></h1>
    <div class="hero-stats">
      <div class="stat-item">
        <span class="stat-num">22+</span>
        <span class="stat-label">فرع حول المملكة</span>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <span class="stat-num">252+</span>
        <span class="stat-label">براند عالمي</span>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <span class="stat-num">+745 ألف</span>
        <span class="stat-label">عميل راضي</span>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
  SOCIAL SECTION
============================================================ -->
<section id="social" class="social-section" style="background:var(--off-white);padding:40px 0">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">تابعونا على حساباتنا في شبكات التواصل الاجتماعي</h2>
    </div>
    <div class="social-grid" id="social-grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px">
      <!-- يتحمل ديناميكياً -->
    </div>
  </div>
</section>

<!-- ============================================================
  BRANCHES SECTION — ذهبي
============================================================ -->
<section id="branches" class="branches-section">
  <div class="container">
    <div class="section-header">
      <span class="section-badge">لأن جمالك يستحق</span>
      <h2 class="section-title">أكثر من 20 فرعًا لخدمتك حول المملكة</h2>
      <div class="title-line"></div>
    </div>
    <div class="city-filter" id="city-filter">
      <button class="city-btn active" data-city="all">الكل</button>
    </div>
    <div class="branches-grid" id="branches-grid"></div>
  </div>
</section>

<!-- ============================================================
  BRANDS SECTION — أوف وايت
============================================================ -->
<section id="brands" class="brands-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">الوكيل الرسمي لأهم العلامات التجارية العالمية في عالم التجميل والعناية والعطور</h2>
      <div class="title-line"></div>
    </div>
    <div class="brands-grid" id="brands-grid"></div>
  </div>
</section>

<!-- ============================================================
  ARTICLES SECTION — ذهبي
============================================================ -->
<section id="articles" class="articles-section">
  <div class="container">
    <div class="section-header">
      <span class="section-badge">اقرأ وتعلم</span>
      <h2 class="section-title">المقالات</h2>
      <div class="title-line"></div>
    </div>
    <div class="articles-grid" id="articles-grid">
      <?php
      // Server-side render for SEO — articles from WP posts
      $args = [
          'post_type'      => 'post',
          'posts_per_page' => 6,
          'post_status'    => 'publish',
      ];
      $q = new WP_Query($args);
      if ($q->have_posts()) :
          while ($q->have_posts()) : $q->the_post();
              $cover   = get_the_post_thumbnail_url(null, 'large');
              $cats    = get_the_category();
              $cat_name = !empty($cats) ? esc_html($cats[0]->name) : '';
      ?>
      <article class="article-card">
        <?php if ($cover) : ?>
          <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>" class="article-card-cover" loading="lazy" />
        <?php endif; ?>
        <div class="article-card-body">
          <div class="article-card-meta">
            <?php if ($cat_name) : ?>
              <span class="article-card-cat"><?php echo $cat_name; ?></span>
            <?php endif; ?>
            <span class="article-card-date"><?php echo esc_html(get_the_date('j F Y')); ?></span>
          </div>
          <h3 class="article-card-title"><?php the_title(); ?></h3>
          <div class="article-card-excerpt"><?php echo esc_html(get_the_excerpt()); ?></div>
          <a href="<?php the_permalink(); ?>" class="article-card-link">
            اقرأ المزيد <i class="fas fa-arrow-left"></i>
          </a>
        </div>
      </article>
      <?php
          endwhile;
          wp_reset_postdata();
      else :
          echo '<p style="text-align:center;color:rgba(0,0,0,0.5);padding:40px 0;grid-column:1/-1">لا توجد مقالات حالياً</p>';
      endif;
      ?>
    </div>
    <?php if ($q->found_posts > 6) : ?>
    <div style="text-align:center;margin-top:28px">
      <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>"
         style="display:inline-flex;align-items:center;gap:8px;padding:13px 36px;background:var(--black);border-radius:30px;color:var(--gold);font-size:14px;font-weight:700;text-decoration:none">
        <i class="fas fa-chevron-down"></i> عرض كل المقالات
      </a>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ============================================================
  CONTACT SECTION — أسود
============================================================ -->
<section id="contact" class="contact-section" style="background:var(--black);padding:80px 0">
  <div class="container">
    <div style="background:var(--black-soft);border:1px solid rgba(255,207,6,0.25);border-radius:22px;padding:48px 28px;text-align:center;max-width:560px;margin:0 auto">
      <div style="width:70px;height:70px;background:rgba(255,207,6,0.1);border:1px solid rgba(255,207,6,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--gold);margin:0 auto 20px">
        <i class="fas fa-headset"></i>
      </div>
      <h2 style="font-size:28px;font-weight:800;color:#fff;margin-bottom:8px">خدمة العملاء</h2>
      <p style="font-size:14px;color:rgba(255,255,255,0.5);margin-bottom:28px">نحن هنا لمساعدتك على مدار الساعة</p>
      <div style="margin-bottom:28px">
        <a href="tel:<?php echo esc_attr(makhzan_phone()); ?>" style="color:var(--gold);font-weight:700;font-size:22px;text-decoration:none">
          <?php echo esc_html(makhzan_phone()); ?>
        </a>
      </div>
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="tel:<?php echo esc_attr(makhzan_phone()); ?>"
           style="display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:30px;background:var(--gold);color:#000;font-size:15px;font-weight:700;text-decoration:none">
          <i class="fas fa-phone-alt"></i> اتصل الآن
        </a>
        <a href="<?php echo esc_url(makhzan_whatsapp_link()); ?>" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:30px;border:2px solid #25D366;color:#25D366;font-size:15px;font-weight:700;text-decoration:none">
          <i class="fab fa-whatsapp"></i> واتساب
        </a>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
