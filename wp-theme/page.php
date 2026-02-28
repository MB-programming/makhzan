<?php
/**
 * Static Page Template
 */
get_header();
while (have_posts()) : the_post();
?>
<section style="padding:100px 0 80px;background:var(--off-white)">
  <div class="container" style="max-width:860px">
    <h1 style="font-size:clamp(24px,5vw,40px);font-weight:800;margin-bottom:32px;color:var(--black)"><?php the_title(); ?></h1>
    <div class="entry-content"><?php the_content(); ?></div>
  </div>
</section>
<?php endwhile; get_footer(); ?>
