<?php
/**
 * Makhazen Alenayah Theme Functions
 */

// ============================================================
// Theme Setup
// ============================================================
function makhzan_setup() {
    load_theme_textdomain('makhzan', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption', 'script', 'style']);
    add_theme_support('custom-logo', ['height' => 100, 'width' => 300, 'flex-height' => true, 'flex-width' => true]);
    register_nav_menus(['primary' => 'القائمة الرئيسية', 'footer' => 'قائمة الفوتر']);
}
add_action('after_setup_theme', 'makhzan_setup');

// ============================================================
// Enqueue Scripts & Styles
// ============================================================
function makhzan_enqueue() {
    wp_enqueue_style('makhzan-fonts',
        'https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap', [], null);
    wp_enqueue_style('font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', [], '6.5.1');
    wp_enqueue_style('makhzan-style', get_stylesheet_uri(), [], '1.2.0');

    wp_enqueue_script('gsap',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], '3.12.5', true);
    wp_enqueue_script('gsap-scroll-trigger',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', ['gsap'], '3.12.5', true);
    wp_enqueue_script('makhzan-main',
        get_template_directory_uri() . '/assets/js/main.js', ['gsap', 'gsap-scroll-trigger'], '1.2.0', true);

    // تمرير بيانات الفروع مباشرة إلى JS بدون API call
    wp_localize_script('makhzan-main', 'makhzanData', [
        'branches' => makhzan_get_branches_data(),
        'themeUrl' => get_template_directory_uri(),
        'siteUrl'  => home_url(),
        'phone'    => get_option('makhzan_phone',    '920029921'),
        'whatsapp' => get_option('makhzan_whatsapp', '966920029921'),
    ]);
}
add_action('wp_enqueue_scripts', 'makhzan_enqueue');

// ============================================================
// Custom Post Types
// ============================================================
function makhzan_register_cpts() {
    // الفروع
    register_post_type('branch', [
        'labels' => [
            'name'          => 'الفروع',
            'singular_name' => 'فرع',
            'add_new_item'  => 'إضافة فرع جديد',
            'edit_item'     => 'تعديل الفرع',
            'new_item'      => 'فرع جديد',
            'all_items'     => 'جميع الفروع',
        ],
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_rest'      => false,
        'menu_icon'         => 'dashicons-location',
        'supports'          => ['title', 'page-attributes'],
        'menu_position'     => 5,
        'show_in_nav_menus' => false,
    ]);

    // البراندات
    register_post_type('brand', [
        'labels' => [
            'name'          => 'البراندات',
            'singular_name' => 'براند',
            'add_new_item'  => 'إضافة براند جديد',
            'edit_item'     => 'تعديل البراند',
            'new_item'      => 'براند جديد',
            'all_items'     => 'جميع البراندات',
        ],
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_rest'      => false,
        'menu_icon'         => 'dashicons-tag',
        'supports'          => ['title', 'thumbnail', 'page-attributes'],
        'menu_position'     => 6,
        'show_in_nav_menus' => false,
    ]);
}
add_action('init', 'makhzan_register_cpts');

// ============================================================
// META BOXES — الفروع
// ============================================================
function makhzan_add_meta_boxes() {
    add_meta_box('branch_details', '📍 تفاصيل الفرع',
        'makhzan_branch_meta_box', 'branch', 'normal', 'high');
    add_meta_box('brand_details',  '🏷️ تفاصيل البراند',
        'makhzan_brand_meta_box',  'brand',  'normal', 'high');
}
add_action('add_meta_boxes', 'makhzan_add_meta_boxes');

function makhzan_branch_meta_box($post) {
    wp_nonce_field('makhzan_meta_save', 'makhzan_meta_nonce');
    $city    = get_post_meta($post->ID, 'city_ar',    true);
    $addr    = get_post_meta($post->ID, 'address_ar', true);
    $phone   = get_post_meta($post->ID, 'phone',      true);
    $map_url = get_post_meta($post->ID, 'map_url',    true);
    ?>
    <style>
    .makhzan-box{direction:rtl;font-family:Arial,sans-serif}
    .makhzan-box table{width:100%;border-collapse:collapse}
    .makhzan-box td{padding:8px 6px;vertical-align:top}
    .makhzan-box td:first-child{width:130px;font-weight:700;padding-top:10px;color:#444}
    .makhzan-box input[type=text],.makhzan-box textarea{width:100%;padding:6px 10px;border:1px solid #ddd;border-radius:4px;font-size:14px;font-family:Arial,sans-serif}
    .makhzan-box textarea{resize:vertical}
    .makhzan-box .hint{font-size:11px;color:#888;margin-top:3px}
    </style>
    <div class="makhzan-box">
    <table>
        <tr>
            <td><label>المدينة</label></td>
            <td>
                <input type="text" name="city_ar" value="<?php echo esc_attr($city); ?>" placeholder="مثال: الرياض" />
                <p class="hint">اكتب المدينة بنفس الصيغة لتفعيل فلتر المدن (مثل: الرياض، حول المملكة)</p>
            </td>
        </tr>
        <tr>
            <td><label>العنوان</label></td>
            <td><textarea name="address_ar" rows="2" placeholder="الحي، الشارع، المدينة"><?php echo esc_textarea($addr); ?></textarea></td>
        </tr>
        <tr>
            <td><label>رقم الهاتف</label></td>
            <td><input type="text" name="phone" value="<?php echo esc_attr($phone); ?>" placeholder="920029921" /></td>
        </tr>
        <tr>
            <td><label>رابط Google Maps</label></td>
            <td>
                <input type="text" name="map_url" value="<?php echo esc_attr($map_url); ?>" placeholder="https://maps.app.goo.gl/..." />
                <p class="hint">افتح الفرع في Google Maps → شارك → انسخ الرابط</p>
            </td>
        </tr>
    </table>
    <p style="color:#888;font-size:12px;margin-top:8px">💡 اسم الفرع يُكتب في حقل العنوان (Title) في الأعلى</p>
    </div>
    <?php
}

function makhzan_brand_meta_box($post) {
    wp_nonce_field('makhzan_meta_save', 'makhzan_meta_nonce');
    $name_ar     = get_post_meta($post->ID, 'name_ar',     true);
    $website_url = get_post_meta($post->ID, 'website_url', true);
    ?>
    <div class="makhzan-box">
    <table>
        <tr>
            <td><label>الاسم بالعربي</label></td>
            <td><input type="text" name="name_ar" value="<?php echo esc_attr($name_ar); ?>" placeholder="مثال: لوريال باريس" /></td>
        </tr>
        <tr>
            <td><label>رابط الموقع</label></td>
            <td><input type="text" name="website_url" value="<?php echo esc_attr($website_url); ?>" placeholder="https://..." /></td>
        </tr>
    </table>
    <p style="color:#888;font-size:12px;margin-top:8px">💡 اسم البراند (إنجليزي) في حقل العنوان (Title) — اللوجو من الصورة المميزة (Featured Image)</p>
    </div>
    <?php
}

// ============================================================
// SAVE META
// ============================================================
function makhzan_save_meta($post_id) {
    if (!isset($_POST['makhzan_meta_nonce']) ||
        !wp_verify_nonce($_POST['makhzan_meta_nonce'], 'makhzan_meta_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $pt = get_post_type($post_id);

    if ($pt === 'branch') {
        $text_fields = ['city_ar', 'phone', 'map_url'];
        foreach ($text_fields as $f) {
            if (isset($_POST[$f])) {
                update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
            }
        }
        if (isset($_POST['address_ar'])) {
            update_post_meta($post_id, 'address_ar', sanitize_textarea_field($_POST['address_ar']));
        }
    }

    if ($pt === 'brand') {
        $text_fields = ['name_ar', 'website_url'];
        foreach ($text_fields as $f) {
            if (isset($_POST[$f])) {
                update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
            }
        }
    }
}
add_action('save_post', 'makhzan_save_meta');

// ============================================================
// ADMIN COLUMNS — الفروع
// ============================================================
add_filter('manage_branch_posts_columns', function($cols) {
    return [
        'cb'      => $cols['cb'],
        'title'   => 'اسم الفرع',
        'city_ar' => 'المدينة',
        'phone'   => 'الهاتف',
        'map'     => 'الخريطة',
        'order'   => 'الترتيب',
    ];
});

add_action('manage_branch_posts_custom_column', function($col, $post_id) {
    if ($col === 'city_ar') echo esc_html(get_post_meta($post_id, 'city_ar', true));
    if ($col === 'phone')   echo esc_html(get_post_meta($post_id, 'phone',   true));
    if ($col === 'map') {
        $url = get_post_meta($post_id, 'map_url', true);
        if ($url) echo '<a href="' . esc_url($url) . '" target="_blank">🗺️ عرض</a>';
        else      echo '<span style="color:#bbb">—</span>';
    }
    if ($col === 'order') echo esc_html(get_post_field('menu_order', $post_id));
}, 10, 2);

add_filter('manage_edit-branch_sortable_columns', function($cols) {
    $cols['city_ar'] = 'city_ar';
    return $cols;
});

// ============================================================
// ADMIN COLUMNS — البراندات
// ============================================================
add_filter('manage_brand_posts_columns', function($cols) {
    return [
        'cb'      => $cols['cb'],
        'logo'    => 'اللوجو',
        'title'   => 'الاسم (إنجليزي)',
        'name_ar' => 'الاسم (عربي)',
        'website' => 'الموقع',
        'order'   => 'الترتيب',
    ];
});

add_action('manage_brand_posts_custom_column', function($col, $post_id) {
    if ($col === 'logo') {
        $img = get_the_post_thumbnail($post_id, [48, 48]);
        echo $img ?: '<div style="width:48px;height:48px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:18px;">✦</div>';
    }
    if ($col === 'name_ar') echo esc_html(get_post_meta($post_id, 'name_ar', true) ?: '—');
    if ($col === 'website') {
        $url = get_post_meta($post_id, 'website_url', true);
        if ($url) echo '<a href="' . esc_url($url) . '" target="_blank">🔗</a>';
        else      echo '<span style="color:#bbb">—</span>';
    }
    if ($col === 'order') echo esc_html(get_post_field('menu_order', $post_id));
}, 10, 2);

// ============================================================
// SOCIAL LINKS — إعدادات التواصل الاجتماعي
// ============================================================
// البيانات الثابتة للمنصات
function makhzan_platforms() {
    return [
        'instagram' => ['ar' => 'انستقرام',  'icon' => 'fa-instagram',  'ic' => '#E1306C', 'bg' => 'rgba(225,48,108,0.1)',   'border' => 'rgba(225,48,108,0.25)'],
        'tiktok'    => ['ar' => 'تيك توك',   'icon' => 'fa-tiktok',     'ic' => '#000000', 'bg' => 'rgba(0,0,0,0.07)',       'border' => 'rgba(0,0,0,0.15)'     ],
        'snapchat'  => ['ar' => 'سناب شات',  'icon' => 'fa-snapchat',   'ic' => '#c9a200', 'bg' => 'rgba(255,207,6,0.15)',   'border' => 'rgba(255,207,6,0.35)' ],
        'twitter'   => ['ar' => 'تويتر / X', 'icon' => 'fa-x-twitter',  'ic' => '#000000', 'bg' => 'rgba(0,0,0,0.07)',       'border' => 'rgba(0,0,0,0.15)'     ],
        'whatsapp'  => ['ar' => 'واتساب',    'icon' => 'fa-whatsapp',   'ic' => '#1a9e50', 'bg' => 'rgba(37,211,102,0.1)',   'border' => 'rgba(37,211,102,0.25)'],
        'youtube'   => ['ar' => 'يوتيوب',    'icon' => 'fa-youtube',    'ic' => '#CC0000', 'bg' => 'rgba(255,0,0,0.08)',     'border' => 'rgba(255,0,0,0.2)'    ],
        'facebook'  => ['ar' => 'فيسبوك',    'icon' => 'fa-facebook-f', 'ic' => '#1877F2', 'bg' => 'rgba(24,119,242,0.1)',   'border' => 'rgba(24,119,242,0.25)'],
    ];
}

// Helper: قراءة روابط التواصل الاجتماعي من الإعدادات
function makhzan_get_social_links() {
    $platforms = makhzan_platforms();
    $result    = [];

    foreach ($platforms as $key => $info) {
        $url      = get_option("makhzan_{$key}_url",      '');
        $username = get_option("makhzan_{$key}_username", '');
        $enabled  = get_option("makhzan_{$key}_enabled",  '0');
        if ($enabled === '1' && $url) {
            $result[] = [
                'key'         => $key,
                'platform_ar' => $info['ar'],
                'icon'        => $info['icon'],
                'ic'          => $info['ic'],
                'bg'          => $info['bg'],
                'border'      => $info['border'],
                'url'         => $url,
                'username'    => $username,
            ];
        }
    }

    // Fallback إذا لم تُضبط الإعدادات بعد
    if (empty($result)) {
        $result = [
            ['platform_ar'=>'انستقرام',  'icon'=>'fa-instagram',  'ic'=>'#E1306C', 'bg'=>'rgba(225,48,108,0.1)',  'border'=>'rgba(225,48,108,0.25)', 'url'=>'https://www.instagram.com/makhazenalenaya/', 'username'=>'@makhazenalenaya'],
            ['platform_ar'=>'تيك توك',   'icon'=>'fa-tiktok',     'ic'=>'#000000', 'bg'=>'rgba(0,0,0,0.07)',      'border'=>'rgba(0,0,0,0.15)',      'url'=>'https://www.tiktok.com/@makhazenalenaya',    'username'=>'@makhazenalenaya'],
            ['platform_ar'=>'سناب شات',  'icon'=>'fa-snapchat',   'ic'=>'#c9a200', 'bg'=>'rgba(255,207,6,0.15)', 'border'=>'rgba(255,207,6,0.35)',  'url'=>'https://www.snapchat.com/add/makhazenalenaya','username'=>'makhazenalenaya'],
            ['platform_ar'=>'تويتر / X', 'icon'=>'fa-x-twitter',  'ic'=>'#000000', 'bg'=>'rgba(0,0,0,0.07)',      'border'=>'rgba(0,0,0,0.15)',      'url'=>'https://x.com/makhazenalenaya',              'username'=>'@makhazenalenaya'],
            ['platform_ar'=>'واتساب',    'icon'=>'fa-whatsapp',   'ic'=>'#1a9e50', 'bg'=>'rgba(37,211,102,0.1)', 'border'=>'rgba(37,211,102,0.25)', 'url'=>'https://wa.me/966920029921',                 'username'=>'920029921'],
        ];
    }

    return $result;
}

// ============================================================
// ADMIN MENU — لوحة التحكم
// ============================================================
function makhzan_admin_menu() {
    add_menu_page(
        'مخازن العناية',
        'مخازن العناية',
        'manage_options',
        'makhzan-settings',
        'makhzan_social_page',
        'dashicons-store',
        4
    );
    add_submenu_page(
        'makhzan-settings',
        'التواصل الاجتماعي',
        'التواصل الاجتماعي',
        'manage_options',
        'makhzan-settings',
        'makhzan_social_page'
    );
    add_submenu_page(
        'makhzan-settings',
        'إعدادات الاتصال',
        'الاتصال والواتساب',
        'manage_options',
        'makhzan-contact',
        'makhzan_contact_page'
    );
}
add_action('admin_menu', 'makhzan_admin_menu');

// ============================================================
// SETTINGS PAGE — التواصل الاجتماعي
// ============================================================
function makhzan_social_page() {
    $platforms = makhzan_platforms();

    if (isset($_POST['makhzan_social_save']) && check_admin_referer('makhzan_social_nonce')) {
        foreach ($platforms as $key => $info) {
            update_option("makhzan_{$key}_url",      sanitize_url(trim($_POST["{$key}_url"]      ?? '')));
            update_option("makhzan_{$key}_username", sanitize_text_field(trim($_POST["{$key}_username"] ?? '')));
            update_option("makhzan_{$key}_enabled",  isset($_POST["{$key}_enabled"]) ? '1' : '0');
        }
        echo '<div class="updated notice"><p>✅ تم الحفظ بنجاح!</p></div>';
    }
    ?>
    <div class="wrap" dir="rtl" style="max-width:800px">
        <h1 style="display:flex;align-items:center;gap:10px">
            📱 إعدادات التواصل الاجتماعي
        </h1>
        <p style="color:#666">أضف روابط حساباتك — فقط الحسابات المفعّلة ستظهر في الموقع.</p>

        <form method="post" style="margin-top:20px">
            <?php wp_nonce_field('makhzan_social_nonce'); ?>

            <table class="wp-list-table widefat striped" style="border-radius:8px;overflow:hidden">
                <thead>
                    <tr>
                        <th style="width:40px;text-align:center">تفعيل</th>
                        <th style="width:130px">المنصة</th>
                        <th>رابط الحساب</th>
                        <th style="width:180px">اسم المستخدم</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($platforms as $key => $info) :
                        $url      = get_option("makhzan_{$key}_url",      '');
                        $username = get_option("makhzan_{$key}_username",  '');
                        $enabled  = get_option("makhzan_{$key}_enabled",   '0') === '1';
                    ?>
                    <tr style="<?php echo $enabled ? 'background:#fffef0' : ''; ?>">
                        <td style="text-align:center">
                            <input type="checkbox" name="<?php echo $key; ?>_enabled"
                                   <?php checked($enabled); ?>
                                   style="width:18px;height:18px;cursor:pointer" />
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <i class="fab <?php echo esc_attr($info['icon']); ?>"
                                   style="font-size:20px;color:<?php echo esc_attr($info['ic']); ?>"></i>
                                <strong><?php echo esc_html($info['ar']); ?></strong>
                            </div>
                        </td>
                        <td>
                            <input type="url" name="<?php echo $key; ?>_url"
                                   value="<?php echo esc_attr($url); ?>"
                                   placeholder="https://..."
                                   style="width:100%;padding:6px 10px;border:1px solid #ddd;border-radius:4px" />
                        </td>
                        <td>
                            <input type="text" name="<?php echo $key; ?>_username"
                                   value="<?php echo esc_attr($username); ?>"
                                   placeholder="@username"
                                   style="width:100%;padding:6px 10px;border:1px solid #ddd;border-radius:4px" />
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p style="margin-top:20px">
                <button type="submit" name="makhzan_social_save" class="button button-primary button-large">
                    💾 حفظ الإعدادات
                </button>
            </p>
        </form>
    </div>
    <?php
}

// ============================================================
// SETTINGS PAGE — الاتصال
// ============================================================
function makhzan_contact_page() {
    if (isset($_POST['makhzan_contact_save']) && check_admin_referer('makhzan_contact_nonce')) {
        update_option('makhzan_phone',    sanitize_text_field(trim($_POST['makhzan_phone']    ?? '')));
        update_option('makhzan_whatsapp', sanitize_text_field(trim($_POST['makhzan_whatsapp'] ?? '')));
        echo '<div class="updated notice"><p>✅ تم الحفظ بنجاح!</p></div>';
    }
    $phone    = get_option('makhzan_phone',    '920029921');
    $whatsapp = get_option('makhzan_whatsapp', '966920029921');
    ?>
    <div class="wrap" dir="rtl" style="max-width:600px">
        <h1>📞 إعدادات الاتصال</h1>
        <form method="post" style="margin-top:20px">
            <?php wp_nonce_field('makhzan_contact_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th><label>رقم خدمة العملاء</label></th>
                    <td>
                        <input type="text" name="makhzan_phone" value="<?php echo esc_attr($phone); ?>"
                               class="regular-text" placeholder="920029921" />
                        <p class="description">رقم الاتصال المباشر</p>
                    </td>
                </tr>
                <tr>
                    <th><label>رقم واتساب</label></th>
                    <td>
                        <input type="text" name="makhzan_whatsapp" value="<?php echo esc_attr($whatsapp); ?>"
                               class="regular-text" placeholder="966920029921" />
                        <p class="description">بدون + أو مسافات (مثال: 966920029921)</p>
                    </td>
                </tr>
            </table>
            <p>
                <button type="submit" name="makhzan_contact_save" class="button button-primary button-large">
                    💾 حفظ
                </button>
            </p>
        </form>
    </div>
    <?php
}

// ============================================================
// DATA HELPERS
// ============================================================

// بيانات الفروع لإرسالها مباشرة إلى JS
function makhzan_get_branches_data() {
    $q = new WP_Query([
        'post_type'      => 'branch',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);

    if (!$q->have_posts()) {
        return makhzan_default_branches();
    }

    $branches = [];
    foreach ($q->posts as $post) {
        $branches[] = [
            'name_ar'       => $post->post_title,
            'city_ar'       => get_post_meta($post->ID, 'city_ar',    true),
            'address_ar'    => get_post_meta($post->ID, 'address_ar', true),
            'phone'         => get_post_meta($post->ID, 'phone',      true),
            'map_url'       => get_post_meta($post->ID, 'map_url',    true),
            'working_hours' => [],
        ];
    }
    return $branches;
}

// بيانات البراندات لـ WP_Query (لو في CPT مضافة)
function makhzan_get_brands_cpt() {
    $q = new WP_Query([
        'post_type'      => 'brand',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);

    if (!$q->have_posts()) return null;

    $brands = [];
    foreach ($q->posts as $post) {
        $logo_id = get_post_thumbnail_id($post->ID);
        $brands[] = [
            'name_en'     => $post->post_title,
            'name_ar'     => get_post_meta($post->ID, 'name_ar',     true),
            'logo_url'    => $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '',
            'website_url' => get_post_meta($post->ID, 'website_url', true),
            'post_id'     => $post->ID,
        ];
    }
    return $brands;
}

// الفروع الافتراضية (fallback)
function makhzan_default_branches() {
    return [
        ['name_ar'=>'مخازن العناية - حي الياسمين',        'city_ar'=>'الرياض',      'address_ar'=>'حي الياسمين، الرياض',                                                'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/DPWjJY7h9BfELBK79?g_st=ic','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - الدائري الشرقي',     'city_ar'=>'الرياض',      'address_ar'=>'الدائري الشرقي، الرياض',                                             'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/AxrTiYWXsfBPZs2C9?g_st=ic','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حي الحمراء',         'city_ar'=>'الرياض',      'address_ar'=>'حي الحمراء، الرياض',                                                 'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/tfDei6ktpz8f42zJA?g_st=ic','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حي الربيع',          'city_ar'=>'الرياض',      'address_ar'=>'طريق الملك عبدالعزيز، حي الربيع، الرياض 13315',                     'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/uw1WGNYuJByYqppU7?g_st=ic','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حي المحمدية',        'city_ar'=>'الرياض',      'address_ar'=>'طريق التخصصي، الغربية، حي المحمدية، الرياض 12362',                 'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/3iWqnAPt8EFPVtH1A?g_st=ic','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حي الفيحاء',         'city_ar'=>'الرياض',      'address_ar'=>'شارع خالد بن الوليد، حي الفيحاء، الرياض 14254',                    'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/WBePwEkF5ESpTLVn7?g_st=ic','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - ظهرة لبن',           'city_ar'=>'الرياض',      'address_ar'=>'حي ظهرة لبن، الرياض',                                                'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/cjpPyW774zMmYkLi7?g_st=ic','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - مكة - الشرائع',      'city_ar'=>'حول المملكة', 'address_ar'=>'Al Muhandes Umar Qadi, Makkah',                                       'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/3bzfVB1pDvXQtqc46','working_hours'=>[['day_type'=>'all','day_label'=>'يومياً','opens_at'=>'09:00','closes_at'=>'22:00','is_closed'=>false]]],
        ['name_ar'=>'مخازن العناية - جدة - ابحر',         'city_ar'=>'حول المملكة', 'address_ar'=>'Khalid Ibn Al Walid St، طريق الإمام سعود',                           'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/XwYU2Mpf8ipFkzxa7?g_st=ic','working_hours'=>[['day_type'=>'all','day_label'=>'يومياً','opens_at'=>'09:00','closes_at'=>'22:00','is_closed'=>false]]],
        ['name_ar'=>'مخازن العناية - حفر الباطن',         'city_ar'=>'حول المملكة', 'address_ar'=>'2811 طريق فيصل بن عبدالعزيز، Hafar Al Batin',                        'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/M7b9e9q28zkFV8H67?g_st=ipc','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - جازان',               'city_ar'=>'حول المملكة', 'address_ar'=>'Corniche Rd, Jizan',                                                  'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/byk6aX6Mko4kestT9?g_st=ipc','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - خميس مشيط',          'city_ar'=>'حول المملكة', 'address_ar'=>'Prince Sultan Rd, Khamis Mushait',                                    'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/EjWLixBzPt6SnnC18?g_st=ipc','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - تبوك',                'city_ar'=>'حول المملكة', 'address_ar'=>'طريق الملك عبدالله، الفيصلية الشمالية، تبوك 47913',                 'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/P2RdZHZCvifsMxTr7','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - الخبر',               'city_ar'=>'حول المملكة', 'address_ar'=>'EKGA7484، Al Khobar',                                                 'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/NB1uMntPLAiGfbCL6','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حائل',                'city_ar'=>'حول المملكة', 'address_ar'=>'شارع الأمير سعود بن عبدالمحسن، النقرة، حائل 55211',                  'phone'=>'920029921','map_url'=>'https://maps.google.com/?q=مخازن+العناية،+حائل','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - مكة المكرمة',        'city_ar'=>'حول المملكة', 'address_ar'=>'شارع الخمسين، طريق الملك خالد',                                       'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/kiwaqfbjFSaCCzW1A','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - الأحساء',             'city_ar'=>'حول المملكة', 'address_ar'=>'4359 طريق عين نجم، Al Mubarraz',                                      'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/mZcuxXo8ZqqQqtaA8','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - الطائف',              'city_ar'=>'حول المملكة', 'address_ar'=>'شارع الخمسين، طريق الملك خالد، At Taif',                             'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/q79uN4MrVHdcqroz6','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - جدة',                 'city_ar'=>'حول المملكة', 'address_ar'=>'مقابل سبار ماركت، Anas Ibn Malik Rd',                                 'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/3zw2UDqX7orh4UqG6?g_st=ic','working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - الدمام، حي النزهة',  'city_ar'=>'حول المملكة', 'address_ar'=>'طريق الأمير محمد بن فهد، حي النزهة، الدمام 32252',                   'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/WWsEmVSRFJfezer4A?g_st=ic','working_hours'=>[]],
    ];
}

// ============================================================
// Helpers for templates
// ============================================================
function makhzan_whatsapp_link() {
    $num = get_option('makhzan_whatsapp', '966920029921');
    return 'https://wa.me/' . preg_replace('/\D/', '', $num);
}
function makhzan_phone() {
    return get_option('makhzan_phone', '920029921');
}
function makhzan_get_thumbnail_url($post_id = null, $size = 'large') {
    $id = get_post_thumbnail_id($post_id ?: get_the_ID());
    return $id ? wp_get_attachment_image_url($id, $size) : '';
}
