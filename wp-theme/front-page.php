<?php
/**
 * Homepage Template — مع الباترن والأنيميشن والفروع
 */

// مسار الصور في الثيم
$img = get_template_directory_uri() . '/assets/images/';

get_header();
?>

<!-- ============================================================
  PRELOADER
============================================================ -->
<div id="preloader" class="preloader">
  <div class="preloader-inner">
    <img src="<?php echo esc_url($img . 'logob.webp'); ?>" alt="مخازن العناية" class="preloader-logo" />
    <div class="preloader-dots">
      <span></span><span></span><span></span>
    </div>
  </div>
</div>

<!-- ============================================================
  HERO SECTION — ذهبي
  pattern-1: أبيض على أسود → multiply على الذهبي (فوق)
  pattern-2: أبيض على أسود → multiply على الذهبي (تحت)
============================================================ -->
<section id="hero" class="hero-section">
  <div class="hero-pattern-top">
    <img src="<?php echo esc_url($img . 'pattern-1.webp'); ?>" alt="" aria-hidden="true" />
  </div>
  <div class="hero-particles" id="hero-particles"></div>

  <div class="hero-content">
    <div class="hero-logo-wrap" id="hero-logo">
      <img src="<?php echo esc_url($img . 'logob.webp'); ?>" alt="مخازن العناية" class="hero-logo-img" />
    </div>
    <h1 style="color:#000" class="section-title" id="hero-tagline">
      <?php echo esc_html(get_option('makhzan_hero_tagline', 'العروض القوية ما تلقاها اونلاين تشوفها بعينك بمخازن العناية')); ?>
    </h1>
    <div class="hero-stats" id="hero-stats">
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

  <div class="hero-pattern-bottom">
    <img src="<?php echo esc_url($img . 'pattern-2.webp'); ?>" alt="" aria-hidden="true" />
  </div>
</section>

<!-- ============================================================
  SOCIAL SECTION — أوف وايت
============================================================ -->
<section id="social" class="social-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">تابعونا على حساباتنا في شبكات التواصل الاجتماعي</h2>
    </div>
    <div class="social-grid" id="social-grid">
      <div class="social-skeleton"></div>
      <div class="social-skeleton"></div>
      <div class="social-skeleton"></div>
      <div class="social-skeleton"></div>
      <div class="social-skeleton"></div>
      <div class="social-skeleton"></div>
    </div>
  </div>
</section>

<!-- ============================================================
  BRANCHES SECTION — ذهبي
  pattern-3: خلفية سوداء وألوان → multiply على الذهبي
============================================================ -->
<section id="branches" class="branches-section">
  <div class="section-pattern-accent">
    <img src="<?php echo esc_url($img . 'pattern-3.webp'); ?>" alt="" aria-hidden="true" />
  </div>

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
  CONTACT SECTION — أسود
  pattern-4: خلفية سوداء → screen
============================================================ -->
<section id="contact" class="contact-section">
  <div class="contact-bg-pattern">
    <img src="<?php echo esc_url($img . 'pattern-4.webp'); ?>" alt="" aria-hidden="true" />
  </div>
  <div class="container">
    <div class="contact-card" id="contact-card">
      <div class="contact-icon-wrap">
        <i class="fas fa-headset"></i>
      </div>
      <h2 class="contact-title">خدمة العملاء</h2>
      <p class="contact-sub">نحن هنا لمساعدتك على مدار الساعة</p>
      <div class="contact-phones" id="contact-phones"></div>
      <div class="contact-actions" id="contact-actions"></div>
    </div>
  </div>
</section>

<!-- ============================================================
  BRANDS SECTION — أوف وايت
  pattern-6: خلفية بيضاء باترن أسود → multiply
============================================================ -->
<section id="brands" class="brands-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">
        الوكيل الرسمي لأهم العلامات التجارية العالمية في عالم التجميل والعناية والعطور
      </h2>
      <div class="title-line"></div>
    </div>
    <div class="brands-grid" id="brands-grid"></div>
  </div>
  <div class="brands-pattern-bottom">
    <img src="<?php echo esc_url($img . 'pattern-6.webp'); ?>" alt="" aria-hidden="true" />
  </div>
</section>

<!-- ============================================================
  ARTICLES SECTION — ذهبي (نفس شكل الفروع)
============================================================ -->
<section id="blogs" class="blogs-section">
  <div class="section-pattern-accent">
    <img src="<?php echo esc_url($img . 'pattern-3.webp'); ?>" alt="" aria-hidden="true" />
  </div>

  <div class="container">
    <div class="section-header">
      <span class="section-badge">اقرأ وتعلم</span>
      <h2 class="section-title">المقالات</h2>
      <div class="title-line"></div>
    </div>

    <div class="blogs-grid" id="blogs-grid">
      <?php
      // Server-side render للـ SEO
      $wp_q = new WP_Query([
          'post_type'      => 'post',
          'posts_per_page' => 6,
          'post_status'    => 'publish',
      ]);
      if ($wp_q->have_posts()) :
          while ($wp_q->have_posts()) : $wp_q->the_post();
              $cover    = get_the_post_thumbnail_url(null, 'large');
              $cats     = get_the_category();
              $cat_name = !empty($cats) ? esc_html($cats[0]->name) : '';
      ?>
      <div class="blog-card" style="opacity:1;transform:none">
        <?php if ($cover) : ?>
          <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>" class="blog-cover" loading="lazy" />
        <?php else : ?>
          <div class="blog-cover-placeholder">✦</div>
        <?php endif; ?>
        <div class="blog-body">
          <div class="blog-meta">
            <?php if ($cat_name) : ?>
              <span class="blog-category"><?php echo $cat_name; ?></span>
            <?php else : ?>
              <span></span>
            <?php endif; ?>
            <span class="blog-date"><?php echo esc_html(get_the_date('j F Y')); ?></span>
          </div>
          <div class="blog-title"><?php the_title(); ?></div>
          <div class="blog-excerpt"><?php echo esc_html(get_the_excerpt()); ?></div>
          <a href="<?php the_permalink(); ?>" class="blog-read-more">
            اقرأ المزيد <i class="fas fa-arrow-left"></i>
          </a>
        </div>
      </div>
      <?php
          endwhile;
          wp_reset_postdata();
      else :
          // بلاك هولدر للـ JS
      endif;
      ?>
    </div>

    <?php if ($wp_q->found_posts > 6) : ?>
    <div style="text-align:center;margin-top:28px">
      <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>"
         class="show-more-btn" style="display:inline-flex;text-decoration:none">
        <i class="fas fa-chevron-down"></i> عرض كل المقالات
      </a>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
