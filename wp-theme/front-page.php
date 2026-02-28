<?php
/**
 * Homepage Template
 */

$img        = get_template_directory_uri()  . '/assets/images/';
$brands_dir = get_template_directory()      . '/assets/brands/';
$brands_url = get_template_directory_uri()  . '/assets/brands/';
$phone      = makhzan_phone();
$wa_link    = makhzan_whatsapp_link();

// بيانات السوشيال من الإعدادات
$social_links = makhzan_get_social_links();

// براندات — CPT أولاً، filesystem ثانياً
$brands_cpt   = makhzan_get_brands_cpt(); // null إذا مافي CPT

// براندات من filesystem
$brand_files  = [];
if (!$brands_cpt && is_dir($brands_dir)) {
    $raw = scandir($brands_dir);
    foreach ($raw as $f) {
        if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), ['png','jpg','jpeg','webp','svg'])) {
            $brand_files[] = $f;
        }
    }
    sort($brand_files);
}

get_header();
?>

<!-- PRELOADER -->
<div id="preloader" class="preloader">
  <div class="preloader-inner">
    <img src="<?php echo esc_url($img . 'logob.webp'); ?>" alt="مخازن العناية" class="preloader-logo" />
    <div class="preloader-dots"><span></span><span></span><span></span></div>
  </div>
</div>

<!-- ===================================================
     HERO
=================================================== -->
<section id="hero" class="hero-section">
  <div class="hero-pattern-top">
    <img src="<?php echo esc_url($img . 'pattern-1.webp'); ?>" alt="" aria-hidden="true" />
  </div>
  <div class="hero-particles" id="hero-particles"></div>

  <div class="hero-content">
    <div class="hero-logo-wrap" id="hero-logo">
      <img src="<?php echo esc_url($img . 'logob.webp'); ?>" alt="مخازن العناية" class="hero-logo-img" />
    </div>
    <h1 class="hero-tagline" id="hero-tagline">
      <?php echo esc_html(get_option('makhzan_hero_tagline', 'العروض القوية ما تلقاها اونلاين تشوفها بعينك بمخازن العناية')); ?>
    </h1>
    <div class="hero-stats" id="hero-stats">
      <div class="stat-item"><span class="stat-num">22+</span><span class="stat-label">فرع حول المملكة</span></div>
      <div class="stat-divider"></div>
      <div class="stat-item"><span class="stat-num">252+</span><span class="stat-label">براند عالمي</span></div>
      <div class="stat-divider"></div>
      <div class="stat-item"><span class="stat-num">+745 ألف</span><span class="stat-label">عميل راضي</span></div>
    </div>
  </div>

  <div class="hero-pattern-bottom">
    <img src="<?php echo esc_url($img . 'pattern-2.webp'); ?>" alt="" aria-hidden="true" />
  </div>
</section>

<!-- ===================================================
     SOCIAL — من إعدادات لوحة التحكم
=================================================== -->
<section id="social" class="social-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">تابعونا على حساباتنا في شبكات التواصل الاجتماعي</h2>
    </div>
    <div class="social-grid" id="social-grid">
      <?php foreach ($social_links as $s) : ?>
      <a href="<?php echo esc_url($s['url']); ?>" target="_blank" rel="noopener noreferrer" class="social-card">
        <div class="social-icon"
             style="background:<?php echo esc_attr($s['bg']); ?>;
                    color:<?php echo esc_attr($s['ic']); ?>;
                    border:1.5px solid <?php echo esc_attr($s['border']); ?>">
          <i class="fab <?php echo esc_attr($s['icon']); ?>"></i>
        </div>
        <div class="social-info">
          <div class="social-platform"><?php echo esc_html($s['platform_ar']); ?></div>
          <div class="social-username"><?php echo esc_html($s['username']); ?></div>
        </div>
        <i class="fas fa-arrow-left social-arrow"></i>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===================================================
     BRANCHES — JS يرسمها من makhzanData.branches
=================================================== -->
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

<!-- ===================================================
     CONTACT — من إعدادات لوحة التحكم
=================================================== -->
<section id="contact" class="contact-section">
  <div class="contact-bg-pattern">
    <img src="<?php echo esc_url($img . 'pattern-4.webp'); ?>" alt="" aria-hidden="true" />
  </div>
  <div class="container">
    <div class="contact-card" id="contact-card">
      <div class="contact-icon-wrap"><i class="fas fa-headset"></i></div>
      <h2 class="contact-title">خدمة العملاء</h2>
      <p class="contact-sub">نحن هنا لمساعدتك على مدار الساعة</p>
      <div class="contact-phones">
        <div class="contact-phone-item">
          <span class="label">خدمة العملاء</span>
          <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
        </div>
      </div>
      <div class="contact-actions">
        <a href="tel:<?php echo esc_attr($phone); ?>" class="btn-contact btn-call">
          <i class="fas fa-phone-alt"></i> اتصل الآن
        </a>
        <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="btn-contact btn-whatsapp">
          <i class="fab fa-whatsapp"></i> واتساب
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ===================================================
     BRANDS — CPT أو filesystem
=================================================== -->
<section id="brands" class="brands-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">الوكيل الرسمي لأهم العلامات التجارية العالمية في عالم التجميل والعناية والعطور</h2>
      <div class="title-line"></div>
    </div>
    <div class="brands-grid" id="brands-grid">

      <?php if ($brands_cpt) : ?>
        <?php foreach ($brands_cpt as $brand) : ?>
        <div class="brand-card"
             <?php if ($brand['website_url']) : ?>
               onclick="window.open('<?php echo esc_url($brand['website_url']); ?>','_blank','noopener')"
               style="cursor:pointer"
             <?php endif; ?>>
          <?php if ($brand['logo_url']) : ?>
            <div class="brand-logo-wrap">
              <img src="<?php echo esc_url($brand['logo_url']); ?>"
                   alt="<?php echo esc_attr($brand['name_en']); ?>"
                   class="brand-logo-img" loading="lazy" />
            </div>
            <div class="brand-name-en"><?php echo esc_html($brand['name_en']); ?></div>
          <?php else : ?>
            <div class="brand-logo-placeholder">✦</div>
            <div class="brand-name-en"><?php echo esc_html($brand['name_en']); ?></div>
            <?php if ($brand['name_ar']) : ?>
              <div class="brand-name-ar"><?php echo esc_html($brand['name_ar']); ?></div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>

      <?php elseif ($brand_files) : ?>
        <?php foreach ($brand_files as $file) : ?>
        <div class="brand-card">
          <div class="brand-logo-wrap">
            <img src="<?php echo esc_url($brands_url . '/' . $file); ?>"
                 alt="<?php echo esc_attr(pathinfo($file, PATHINFO_FILENAME)); ?>"
                 class="brand-logo-img" loading="lazy" />
          </div>
        </div>
        <?php endforeach; ?>

      <?php else : ?>
        <?php
        $fallback_brands = ["L'Oréal",'Maybelline','NYX','MAC','Fenty Beauty','Charlotte Tilbury','Urban Decay','NARS','Lancôme','YSL'];
        $icons = ['✦','◆','★','❋','✿','◈','⬡','❖','✤','✵'];
        foreach ($fallback_brands as $i => $name) :
        ?>
        <div class="brand-card">
          <div class="brand-logo-placeholder"><?php echo $icons[$i % count($icons)]; ?></div>
          <div class="brand-name-en"><?php echo esc_html($name); ?></div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </div>
  <div class="brands-pattern-bottom">
    <img src="<?php echo esc_url($img . 'pattern-6.webp'); ?>" alt="" aria-hidden="true" />
  </div>
</section>

<!-- ===================================================
     ARTICLES — WP_Query
=================================================== -->
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
      $blog_q = new WP_Query([
          'post_type'      => 'post',
          'posts_per_page' => 6,
          'post_status'    => 'publish',
          'orderby'        => 'date',
          'order'          => 'DESC',
      ]);
      if ($blog_q->have_posts()) :
          while ($blog_q->have_posts()) : $blog_q->the_post();
              $cover    = get_the_post_thumbnail_url(null, 'large');
              $cats     = get_the_category();
              $cat_name = !empty($cats) ? esc_html($cats[0]->name) : '';
      ?>
      <a href="<?php the_permalink(); ?>" class="blog-card">
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
            <span class="blog-date"><?php echo get_the_date('j F Y'); ?></span>
          </div>
          <div class="blog-title"><?php the_title(); ?></div>
          <div class="blog-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></div>
          <span class="blog-read-more">اقرأ المزيد <i class="fas fa-arrow-left"></i></span>
        </div>
      </a>
      <?php
          endwhile;
          wp_reset_postdata();
      endif;
      ?>
    </div>

    <?php if (isset($blog_q) && $blog_q->found_posts > 6) : ?>
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
