<?php
/**
 * Main index template (fallback)
 */
get_header();
?>
<div class="container" style="padding:100px 20px;text-align:center">
  <h1 style="font-size:32px;font-weight:800;margin-bottom:16px"><?php bloginfo('name'); ?></h1>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article style="margin:24px 0;text-align:right">
      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php the_excerpt(); ?>
    </article>
  <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
