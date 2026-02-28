<?php
/**
 * Homepage Template — بدون API، كل شيء يتعرض مباشرة من PHP
 */

$img        = get_template_directory_uri() . '/assets/images/';
$brands_dir = get_template_directory() . '/assets/brands/';
$brands_url = get_template_directory_uri() . '/assets/brands/';

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
  HERO
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
    <h1 class="hero-tagline" id="hero-tagline">
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
  SOCIAL SECTION — مباشرة بدون API
============================================================ -->
<section id="social" class="social-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">تابعونا على حساباتنا في شبكات التواصل الاجتماعي</h2>
    </div>
    <div class="social-grid" id="social-grid">
      <?php
      $social_items = [
        [
          'url'         => 'https://www.instagram.com/makhazenalenaya/',
          'platform'    => 'انستقرام',
          'username'    => '@makhazenalenaya',
          'icon'        => 'fa-instagram',
          'icon_color'  => '#E1306C',
          'icon_bg'     => 'rgba(225,48,108,0.1)',
          'icon_border' => 'rgba(225,48,108,0.25)',
        ],
        [
          'url'         => 'https://www.tiktok.com/@makhazenalenaya',
          'platform'    => 'تيك توك',
          'username'    => '@makhazenalenaya',
          'icon'        => 'fa-tiktok',
          'icon_color'  => '#000000',
          'icon_bg'     => 'rgba(0,0,0,0.07)',
          'icon_border' => 'rgba(0,0,0,0.15)',
        ],
        [
          'url'         => 'https://www.snapchat.com/add/makhazenalenaya',
          'platform'    => 'سناب شات',
          'username'    => 'makhazenalenaya',
          'icon'        => 'fa-snapchat',
          'icon_color'  => '#c9a200',
          'icon_bg'     => 'rgba(255,207,6,0.15)',
          'icon_border' => 'rgba(255,207,6,0.35)',
        ],
        [
          'url'         => 'https://x.com/makhazenalenaya',
          'platform'    => 'تويتر / X',
          'username'    => '@makhazenalenaya',
          'icon'        => 'fa-x-twitter',
          'icon_color'  => '#000000',
          'icon_bg'     => 'rgba(0,0,0,0.07)',
          'icon_border' => 'rgba(0,0,0,0.15)',
        ],
        [
          'url'         => 'https://wa.me/966920029921',
          'platform'    => 'واتساب',
          'username'    => '920029921',
          'icon'        => 'fa-whatsapp',
          'icon_color'  => '#1a9e50',
          'icon_bg'     => 'rgba(37,211,102,0.1)',
          'icon_border' => 'rgba(37,211,102,0.25)',
        ],
      ];
      foreach ($social_items as $s) :
      ?>
      <a href="<?php echo esc_url($s['url']); ?>" target="_blank" rel="noopener noreferrer" class="social-card" style="opacity:1;transform:none">
        <div class="social-icon" style="background:<?php echo $s['icon_bg']; ?>;color:<?php echo $s['icon_color']; ?>;border:1.5px solid <?php echo $s['icon_border']; ?>">
          <i class="fab <?php echo esc_attr($s['icon']); ?>"></i>
        </div>
        <div class="social-info">
          <div class="social-platform"><?php echo esc_html($s['platform']); ?></div>
          <div class="social-username"><?php echo esc_html($s['username']); ?></div>
        </div>
        <i class="fas fa-arrow-left social-arrow"></i>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================
  BRANCHES SECTION — ذهبي، الفروع يتحملوا من JS
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
      <div class="contact-phones">
        <div class="contact-phone-item">
          <span class="label">خدمة العملاء</span>
          <a href="tel:920029921">920029921</a>
        </div>
      </div>
      <div class="contact-actions">
        <a href="tel:920029921" class="btn-contact btn-call">
          <i class="fas fa-phone-alt"></i> اتصل الآن
        </a>
        <a href="https://wa.me/966920029921" target="_blank" rel="noopener" class="btn-contact btn-whatsapp">
          <i class="fab fa-whatsapp"></i> واتساب
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
  BRANDS SECTION — أوف وايت، الصور مباشرة من الثيم
============================================================ -->
<section id="brands" class="brands-section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">
        الوكيل الرسمي لأهم العلامات التجارية العالمية في عالم التجميل والعناية والعطور
      </h2>
      <div class="title-line"></div>
    </div>
    <div class="brands-grid" id="brands-grid">
      <?php
      // قراءة صور البراندات مباشرة من المجلد
      $brand_files = [];
      if (is_dir($brands_dir)) {
          $all_files = scandir($brands_dir);
          foreach ($all_files as $file) {
              $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
              if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                  $brand_files[] = $file;
              }
          }
          sort($brand_files);
      }

      foreach ($brand_files as $file) :
          $file_url = esc_url($brands_url . '/' . $file);
          $alt      = esc_attr(pathinfo($file, PATHINFO_FILENAME));
      ?>
      <div class="brand-card" style="opacity:1;transform:none">
        <div class="brand-logo-wrap">
          <img src="<?php echo $file_url; ?>" alt="<?php echo $alt; ?>" class="brand-logo-img" loading="lazy" />
        </div>
      </div>
      <?php endforeach; ?>

      <?php if (empty($brand_files)) : ?>
        <!-- Placeholder عند غياب الصور -->
        <?php
        $placeholder_brands = ['L\'Oréal','Maybelline','NYX','MAC','Fenty','Charlotte Tilbury','Urban Decay','NARS','Lancôme','YSL'];
        $icons = ['✦','◆','★','❋','✿','◈','⬡','❖','✤','✵'];
        foreach ($placeholder_brands as $i => $name) :
        ?>
        <div class="brand-card" style="opacity:1;transform:none">
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

<!-- ============================================================
  ARTICLES / BLOGS SECTION — ذهبي (نفس شكل الفروع)
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
      $wp_q = new WP_Query([
          'post_type'      => 'post',
          'posts_per_page' => 6,
          'post_status'    => 'publish',
          'orderby'        => 'date',
          'order'          => 'DESC',
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
          <div class="blog-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></div>
          <a href="<?php the_permalink(); ?>" class="blog-read-more">
            اقرأ المزيد <i class="fas fa-arrow-left"></i>
          </a>
        </div>
      </div>
      <?php
          endwhile;
          wp_reset_postdata();
      endif;
      ?>
    </div>

    <?php if (isset($wp_q) && $wp_q->found_posts > 6) : ?>
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
