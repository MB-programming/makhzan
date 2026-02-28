<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <meta name="theme-color" content="#FFCF06" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header" class="site-header">
  <div class="header-inner">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo">
      <?php
      if (has_custom_logo()) {
          the_custom_logo();
      } else {
          $img = get_template_directory_uri() . '/assets/images/';
          echo '<img src="' . esc_url($img . 'logob.webp') . '" alt="' . esc_attr(get_bloginfo('name')) . '" />';
      }
      ?>
    </a>
    <nav class="header-nav">
      <a href="<?php echo esc_url('https://wa.me/' . preg_replace('/\D/', '', get_option('makhzan_whatsapp', '966920029921'))); ?>"
         style="padding:0;margin-bottom:-6px;font-size:19px">
        <i class="fa-brands fa-whatsapp"></i>
      </a>
      <i class="fa-solid fa-phone">
        <a href="tel:<?php echo esc_attr(makhzan_phone()); ?>"><?php echo esc_html(makhzan_phone()); ?></a>
      </i>
    </nav>
  </div>
</header>
