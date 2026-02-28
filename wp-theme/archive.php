<?php
/**
 * Archive / Blog Index Template
 */
get_header();
?>

<!-- Archive Hero -->
<section style="background:var(--gold);padding:100px 0 60px">
  <div class="container" style="text-align:center">
    <span class="section-badge">اقرأ وتعلم</span>
    <h1 class="section-title" style="margin-top:12px">
      <?php
      if (is_category()) {
          single_cat_title();
      } elseif (is_tag()) {
          single_tag_title('وسم: ');
      } else {
          echo 'جميع المقالات';
      }
      ?>
    </h1>
    <div class="title-line"></div>
  </div>
</section>

<!-- Articles Grid -->
<section style="background:var(--gold);padding:0 0 80px">
  <div class="container">
    <?php if (have_posts()) : ?>
    <div class="articles-grid">
      <?php while (have_posts()) : the_post();
          $cover    = get_the_post_thumbnail_url(null, 'large');
          $cats     = get_the_category();
          $cat_name = !empty($cats) ? esc_html($cats[0]->name) : '';
      ?>
      <article class="article-card">
        <?php if ($cover) : ?>
          <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>" class="article-card-cover" loading="lazy" />
        <?php else : ?>
          <div style="height:140px;background:rgba(0,0,0,0.06);display:flex;align-items:center;justify-content:center;font-size:32px;color:rgba(0,0,0,0.2)">✦</div>
        <?php endif; ?>
        <div class="article-card-body">
          <div class="article-card-meta">
            <?php if ($cat_name) : ?>
              <span class="article-card-cat"><?php echo $cat_name; ?></span>
            <?php endif; ?>
            <span class="article-card-date"><?php echo esc_html(get_the_date('j F Y')); ?></span>
          </div>
          <h2 class="article-card-title" style="font-size:15px">
            <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none"><?php the_title(); ?></a>
          </h2>
          <div class="article-card-excerpt"><?php echo esc_html(get_the_excerpt()); ?></div>
          <a href="<?php the_permalink(); ?>" class="article-card-link">
            اقرأ المزيد <i class="fas fa-arrow-left"></i>
          </a>
        </div>
      </article>
      <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div style="display:flex;justify-content:center;margin-top:36px;gap:8px">
      <?php
      echo paginate_links([
          'type'      => 'list',
          'prev_text' => '<i class="fas fa-chevron-right"></i>',
          'next_text' => '<i class="fas fa-chevron-left"></i>',
      ]);
      ?>
    </div>

    <?php else : ?>
    <p style="text-align:center;padding:60px 0;color:rgba(0,0,0,0.5)">لا توجد مقالات حالياً</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
