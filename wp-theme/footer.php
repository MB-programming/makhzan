<footer class="site-footer">
  <div class="container">
    <div class="footer-inner">
      <?php
      if (has_custom_logo()) {
          the_custom_logo();
      } else {
          echo '<img src="' . esc_url(get_template_directory_uri()) . '/assets/images/logo.webp" alt="' . esc_attr(get_bloginfo('name')) . '" class="footer-logo" />';
      }
      ?>
      <nav class="footer-links">
        <?php
        wp_nav_menu([
            'theme_location' => 'footer',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'fallback_cb'    => false,
        ]);
        ?>
      </nav>
      <p class="footer-copy">
        © <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. جميع الحقوق محفوظة.
      </p>
    </div>
  </div>
</footer>

<!-- WhatsApp Float -->
<a href="<?php echo esc_url(makhzan_whatsapp_link()); ?>" target="_blank" rel="noopener noreferrer"
   style="position:fixed;bottom:24px;right:24px;z-index:9999;width:70px;height:70px;background:#25d366;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(37,211,102,0.4);text-decoration:none;animation:whatsapp-pulse 2s infinite">
  <i class="fa-brands fa-whatsapp" style="color:#fff;font-size:32px"></i>
</a>
<style>
  @keyframes whatsapp-pulse {
    0%   { box-shadow: 0 0 0 0 rgba(37,211,102,0.5); }
    70%  { box-shadow: 0 0 0 14px rgba(37,211,102,0); }
    100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); }
  }
</style>

<?php wp_footer(); ?>
</body>
</html>
