<?php
/**
 * Single Article / Post Template
 */
get_header();

while (have_posts()) : the_post();
    $cover     = get_the_post_thumbnail_url(null, 'full');
    $cats      = get_the_category();
    $cat_name  = !empty($cats) ? esc_html($cats[0]->name) : '';
    $author    = get_the_author();
    $date      = get_the_date('j F Y');
    $views     = intval(get_post_meta(get_the_ID(), 'view_count', true));

    // Increment view count
    update_post_meta(get_the_ID(), 'view_count', $views + 1);
?>

<!-- HERO -->
<section class="single-article-hero">
  <div class="container">
    <a href="<?php echo esc_url(home_url('/#articles')); ?>" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:rgba(0,0,0,0.6);margin-bottom:20px;text-decoration:none">
      <i class="fas fa-arrow-right"></i> العودة للمقالات
    </a>

    <?php if ($cat_name) : ?>
      <div><span class="article-category-badge"><?php echo $cat_name; ?></span></div>
    <?php endif; ?>

    <h1 class="single-article-title"><?php the_title(); ?></h1>

    <div class="single-article-meta">
      <span><i class="fas fa-calendar-alt"></i> <?php echo $date; ?></span>
      <?php if ($author) : ?>
        <span><i class="fas fa-user"></i> <?php echo esc_html($author); ?></span>
      <?php endif; ?>
      <?php if ($views > 0) : ?>
        <span><i class="fas fa-eye"></i> <?php echo number_format($views); ?> مشاهدة</span>
      <?php endif; ?>
    </div>

    <?php if ($cover) : ?>
    <div class="single-article-cover">
      <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>" />
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- CONTENT -->
<section class="single-article-content">
  <div class="container">
    <div class="entry-content">
      <?php the_content(); ?>
    </div>

    <!-- Tags -->
    <?php
    $tags = get_the_tags();
    if ($tags) :
    ?>
    <div style="max-width:780px;margin:24px auto 0;display:flex;flex-wrap:wrap;gap:8px">
      <?php foreach ($tags as $tag) : ?>
        <a href="<?php echo esc_url(get_tag_link($tag)); ?>"
           style="font-size:12px;font-weight:600;padding:4px 12px;background:rgba(255,207,6,0.12);border:1px solid rgba(201,162,0,0.3);border-radius:20px;color:var(--gold-dim);text-decoration:none">
          #<?php echo esc_html($tag->name); ?>
        </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Back link -->
    <div style="max-width:780px;margin:32px auto 0;text-align:center">
      <a href="<?php echo esc_url(home_url('/#articles')); ?>"
         style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--gold);color:var(--black);border-radius:30px;font-weight:700;font-size:14px;text-decoration:none">
        <i class="fas fa-arrow-right"></i> العودة للمقالات
      </a>
    </div>
  </div>
</section>

<?php
endwhile;
get_footer();
?>
