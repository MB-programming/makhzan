<?php $img = get_template_directory_uri() . '/assets/images/'; ?>

<footer class="site-footer">
  <div class="footer-pattern">
    <img src="<?php echo esc_url($img . 'pattern-5.webp'); ?>" alt="" aria-hidden="true" />
  </div>
  <div class="container">
    <div class="footer-inner">
      <?php
      if (has_custom_logo()) {
          the_custom_logo();
      } else {
          echo '<img src="' . esc_url($img . 'logob.webp') . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="footer-logo" />';
      }
      ?>
      <p class="footer-copy">
        © <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. جميع الحقوق محفوظة.
      </p>
    </div>
  </div>
</footer>

<!-- WhatsApp Float -->
<a href="<?php echo esc_url(makhzan_whatsapp_link()); ?>" target="_blank" rel="noopener noreferrer"
   id="whatsapp-float"
   style="position:fixed;bottom:24px;right:24px;z-index:9999;width:70px;height:70px;background:#25d366;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(37,211,102,0.4);transition:transform .3s,box-shadow .3s;text-decoration:none;animation:whatsapp-pulse 2s infinite">
  <i class="fa-brands fa-whatsapp" style="color:#fff;font-size:32px"></i>
</a>
<style>
  #whatsapp-float:hover { transform:scale(1.1); box-shadow:0 6px 20px rgba(37,211,102,.6); }
  @keyframes whatsapp-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(37,211,102,0.5); }
    70%  { box-shadow: 0 0 0 14px rgba(37,211,102,0); }
    100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); }
  }
</style>

<?php wp_footer(); ?>
</body>
</html>
