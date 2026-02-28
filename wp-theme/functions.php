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
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Navigation menus
    register_nav_menus([
        'primary' => __('القائمة الرئيسية', 'makhzan'),
        'footer'  => __('قائمة الفوتر', 'makhzan'),
    ]);
}
add_action('after_setup_theme', 'makhzan_setup');

// ============================================================
// Enqueue Scripts & Styles
// ============================================================
function makhzan_enqueue() {
    // Google Fonts
    wp_enqueue_style(
        'makhzan-fonts',
        'https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap',
        [],
        null
    );

    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        [],
        '6.5.1'
    );

    // Theme stylesheet
    wp_enqueue_style('makhzan-style', get_stylesheet_uri(), [], '1.0.0');

    // GSAP
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], '3.12.5', true);
    wp_enqueue_script('gsap-scroll-trigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', ['gsap'], '3.12.5', true);

    // Theme main JS
    wp_enqueue_script('makhzan-main', get_template_directory_uri() . '/assets/js/main.js', ['gsap', 'gsap-scroll-trigger'], '1.0.0', true);

    // Pass data to JS
    wp_localize_script('makhzan-main', 'makhzanData', [
        'ajaxurl'  => admin_url('admin-ajax.php'),
        'themeUrl' => get_template_directory_uri(),
        'siteUrl'  => home_url(),
        'restUrl'  => rest_url('makhzan/v1/'),
        'nonce'    => wp_create_nonce('wp_rest'),
    ]);
}
add_action('wp_enqueue_scripts', 'makhzan_enqueue');

// ============================================================
// Custom Post Types
// ============================================================

// --- Branches ---
function makhzan_register_cpts() {
    register_post_type('branch', [
        'labels' => [
            'name'          => 'الفروع',
            'singular_name' => 'فرع',
            'add_new_item'  => 'إضافة فرع',
            'edit_item'     => 'تعديل الفرع',
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-location',
        'supports'      => ['title', 'custom-fields'],
        'menu_position' => 5,
    ]);

    // --- Brands ---
    register_post_type('brand', [
        'labels' => [
            'name'          => 'البراندات',
            'singular_name' => 'براند',
            'add_new_item'  => 'إضافة براند',
            'edit_item'     => 'تعديل البراند',
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-tag',
        'supports'      => ['title', 'thumbnail', 'custom-fields'],
        'menu_position' => 6,
    ]);
}
add_action('init', 'makhzan_register_cpts');

// ============================================================
// REST API Endpoints
// ============================================================
function makhzan_register_routes() {
    // Branches
    register_rest_route('makhzan/v1', '/branches', [
        'methods'             => 'GET',
        'callback'            => 'makhzan_get_branches',
        'permission_callback' => '__return_true',
    ]);

    // Brands
    register_rest_route('makhzan/v1', '/brands', [
        'methods'             => 'GET',
        'callback'            => 'makhzan_get_brands',
        'permission_callback' => '__return_true',
    ]);

    // Articles
    register_rest_route('makhzan/v1', '/articles', [
        'methods'             => 'GET',
        'callback'            => 'makhzan_get_articles',
        'permission_callback' => '__return_true',
    ]);

    // Settings (social, contact)
    register_rest_route('makhzan/v1', '/settings', [
        'methods'             => 'GET',
        'callback'            => 'makhzan_get_settings',
        'permission_callback' => '__return_true',
    ]);

    // ALL IN ONE — endpoint واحد يرجع كل الداتا (مثل get_data.php)
    register_rest_route('makhzan/v1', '/all', [
        'methods'             => 'GET',
        'callback'            => 'makhzan_get_all',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'makhzan_register_routes');

// ============================================================
// الفروع الافتراضية الحقيقية
// ============================================================
function makhzan_default_branches() {
    return [
        // ===== الرياض =====
        ['name_ar'=>'مخازن العناية - حي الياسمين',       'city_ar'=>'الرياض',       'address_ar'=>'حي الياسمين، الرياض',                                              'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/DPWjJY7h9BfELBK79?g_st=ic',                           'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية- الدائري الشرقي',     'city_ar'=>'الرياض',       'address_ar'=>'الدائري الشرقي، الرياض',                                           'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/AxrTiYWXsfBPZs2C9?g_st=ic',                           'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حي الحمراء',        'city_ar'=>'الرياض',       'address_ar'=>'حي الحمراء، الرياض',                                               'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/tfDei6ktpz8f42zJA?g_st=ic',                          'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حي الربيع',         'city_ar'=>'الرياض',       'address_ar'=>'طريق الملك عبدالعزيز، حي الربيع، الرياض 13315',                   'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/uw1WGNYuJByYqppU7?g_st=ic',                           'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حي المحمدية',       'city_ar'=>'الرياض',       'address_ar'=>'طريق التخصصي، الغربية، حي المحمدية، الرياض 12362',                'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/3iWqnAPt8EFPVtH1A?g_st=ic',                           'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حي الفيحاء',        'city_ar'=>'الرياض',       'address_ar'=>'شارع خالد بن الوليد (طريق هارون الرشيد)، حي الفيحاء، الرياض 14254','phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/WBePwEkF5ESpTLVn7?g_st=ic',               'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - ظهرة لبن',          'city_ar'=>'الرياض',       'address_ar'=>'حي ظهرة لبن، الرياض',                                              'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/cjpPyW774zMmYkLi7?g_st=ic',                          'working_hours'=>[]],
        // ===== حول المملكة =====
        ['name_ar'=>'مخازن العناية - مكة - (الشرائع)',   'city_ar'=>'حول المملكة',  'address_ar'=>'Al Muhandes Umar Qadi, Makkah',                                     'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/3bzfVB1pDvXQtqc46',                                   'working_hours'=>[['day_type'=>'all','day_label'=>'يومياً','opens_at'=>'09:00','closes_at'=>'22:00','is_closed'=>false]]],
        ['name_ar'=>'مخازن العناية - جدة - ( ابحر )',    'city_ar'=>'حول المملكة',  'address_ar'=>'حي, Khalid Ibn Al Walid St، طريق الإمام سعود',                     'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/XwYU2Mpf8ipFkzxa7?g_st=ic',                          'working_hours'=>[['day_type'=>'all','day_label'=>'يومياً','opens_at'=>'09:00','closes_at'=>'22:00','is_closed'=>false]]],
        ['name_ar'=>'مخازن العناية - حفر الباطن',        'city_ar'=>'حول المملكة',  'address_ar'=>'2811 طريق فيصل بن عبدالعزيز، CNDF7745، Hafar Al Batin',           'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/M7b9e9q28zkFV8H67?g_st=ipc',                         'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - جازان',              'city_ar'=>'حول المملكة',  'address_ar'=>'Corniche Rd, Jizan',                                                'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/byk6aX6Mko4kestT9?g_st=ipc',                         'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - خميس مشيط',         'city_ar'=>'حول المملكة',  'address_ar'=>'7PF9+PF8, Prince Sultan Rd, Khamis Mushait',                        'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/EjWLixBzPt6SnnC18?g_st=ipc',                         'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - تبوك',               'city_ar'=>'حول المملكة',  'address_ar'=>'طريق الملك عبدالله، الفيصلية الشمالية، تبوك 47913',               'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/P2RdZHZCvifsMxTr7',                                  'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - الخبر',              'city_ar'=>'حول المملكة',  'address_ar'=>'EKGA7484، Al Khobar',                                               'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/NB1uMntPLAiGfbCL6',                                   'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - حائل',               'city_ar'=>'حول المملكة',  'address_ar'=>'شارع الأمير سعود بن عبدالمحسن آل سعود، النقرة، حائل 55211',      'phone'=>'920029921','map_url'=>'https://maps.google.com/?q=مخازن+العناية،+حائل',                             'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - مكة المكرمة',       'city_ar'=>'حول المملكة',  'address_ar'=>'شارع الخمسين، طريق الملك خالد',                                    'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/kiwaqfbjFSaCCzW1A',                                   'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - الأحساء',            'city_ar'=>'حول المملكة',  'address_ar'=>'4359 طريق عين نجم، حي FMDA7293، Al Mubarraz',                      'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/mZcuxXo8ZqqQqtaA8',                                  'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - الطائف',             'city_ar'=>'حول المملكة',  'address_ar'=>'شارع الخمسين، طريق الملك خالد، At Taif',                          'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/q79uN4MrVHdcqroz6',                                   'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - جدة',                'city_ar'=>'حول المملكة',  'address_ar'=>'مقابل سبار ماركت، Anas Ibn Malik Rd',                              'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/3zw2UDqX7orh4UqG6?g_st=ic',                          'working_hours'=>[]],
        ['name_ar'=>'مخازن العناية - الدمام ، حي النزهة','city_ar'=>'حول المملكة',  'address_ar'=>'8354 2965 طريق الأمير محمد بن فهد، حي النزهة، الدمام 32252',      'phone'=>'920029921','map_url'=>'https://maps.app.goo.gl/WWsEmVSRFJfezer4A?g_st=ic',                          'working_hours'=>[]],
    ];
}

function makhzan_default_contact() {
    return [
        ['type' => 'phone',    'value' => '920029921',     'label_ar' => 'خدمة العملاء'],
        ['type' => 'whatsapp', 'value' => '+966920029921', 'label_ar' => 'واتساب'      ],
    ];
}

// ============================================================
// /all — كل البيانات في طلب واحد
// ============================================================
function makhzan_get_all($request) {
    $branches_res = makhzan_get_branches($request);
    $brands_res   = makhzan_get_brands($request);
    $articles_res = makhzan_get_articles($request);
    $settings_res = makhzan_get_settings($request);

    $branches = $branches_res->get_data()['data'] ?? [];
    $brands   = $brands_res->get_data()['data']   ?? [];
    $articles = $articles_res->get_data()['data'] ?? [];
    $settings = $settings_res->get_data()['data'] ?? [];

    return rest_ensure_response([
        'success'  => true,
        'branches' => empty($branches) ? makhzan_default_branches() : $branches,
        'brands'   => $brands,
        'articles' => $articles,
        'social'   => $settings['social']  ?? [],
        'contact'  => $settings['contact'] ?? makhzan_default_contact(),
    ]);
}

function makhzan_get_branches($request) {
    $query = new WP_Query([
        'post_type'      => 'branch',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);

    $branches = [];
    foreach ($query->posts as $post) {
        $branches[] = [
            'id'             => $post->ID,
            'name_ar'        => $post->post_title,
            'name_en'        => get_post_meta($post->ID, 'name_en', true),
            'city_ar'        => get_post_meta($post->ID, 'city_ar', true),
            'city_en'        => get_post_meta($post->ID, 'city_en', true),
            'address_ar'     => get_post_meta($post->ID, 'address_ar', true),
            'phone'          => get_post_meta($post->ID, 'phone', true),
            'map_url'        => get_post_meta($post->ID, 'map_url', true),
            'working_hours'  => json_decode(get_post_meta($post->ID, 'working_hours', true) ?: '[]', true),
        ];
    }

    // لو مافي CPT مضافة، استخدم الافتراضية
    if (empty($branches)) {
        $branches = makhzan_default_branches();
    }

    return rest_ensure_response(['success' => true, 'data' => $branches]);
}

function makhzan_get_brands($request) {
    $query = new WP_Query([
        'post_type'      => 'brand',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);

    $brands = [];
    foreach ($query->posts as $post) {
        $logo_id  = get_post_thumbnail_id($post->ID);
        $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '';
        $brands[] = [
            'id'          => $post->ID,
            'name_en'     => $post->post_title,
            'name_ar'     => get_post_meta($post->ID, 'name_ar', true),
            'logo_url'    => $logo_url,
            'website_url' => get_post_meta($post->ID, 'website_url', true),
        ];
    }

    return rest_ensure_response(['success' => true, 'data' => $brands]);
}

function makhzan_get_articles($request) {
    $limit  = min(intval($request->get_param('limit') ?? 20), 100);
    $offset = intval($request->get_param('offset') ?? 0);

    $query = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => $limit,
        'offset'         => $offset,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    $articles = [];
    foreach ($query->posts as $post) {
        $cover_id = get_post_thumbnail_id($post->ID);
        $articles[] = [
            'id'           => $post->ID,
            'title'        => $post->post_title,
            'slug'         => $post->post_name,
            'excerpt'      => get_the_excerpt($post),
            'cover_image'  => $cover_id ? wp_get_attachment_image_url($cover_id, 'large') : '',
            'category'     => implode(', ', wp_get_post_terms($post->ID, 'category', ['fields' => 'names'])),
            'published_at' => $post->post_date,
            'permalink'    => get_permalink($post->ID),
            'is_featured'  => intval(get_post_meta($post->ID, 'is_featured', true)),
        ];
    }

    return rest_ensure_response(['success' => true, 'data' => $articles, 'total' => $query->found_posts]);
}

function makhzan_get_settings($request) {
    return rest_ensure_response([
        'success' => true,
        'data'    => [
            'social'  => json_decode(get_option('makhzan_social', '[]'), true),
            'contact' => json_decode(get_option('makhzan_contact', '[]'), true),
        ],
    ]);
}

// ============================================================
// Theme Options Page (Admin)
// ============================================================
function makhzan_admin_menu() {
    add_theme_page(
        'إعدادات مخازن العناية',
        'إعدادات الثيم',
        'manage_options',
        'makhzan-settings',
        'makhzan_settings_page'
    );
}
add_action('admin_menu', 'makhzan_admin_menu');

function makhzan_settings_page() {
    if (isset($_POST['makhzan_save'])) {
        check_admin_referer('makhzan_settings');
        update_option('makhzan_social',  stripslashes($_POST['makhzan_social']  ?? '[]'));
        update_option('makhzan_contact', stripslashes($_POST['makhzan_contact'] ?? '[]'));
        update_option('makhzan_phone',   sanitize_text_field($_POST['makhzan_phone']   ?? ''));
        update_option('makhzan_whatsapp', sanitize_text_field($_POST['makhzan_whatsapp'] ?? ''));
        echo '<div class="updated"><p>تم الحفظ بنجاح</p></div>';
    }

    $social   = get_option('makhzan_social',   '[]');
    $contact  = get_option('makhzan_contact',  '[]');
    $phone    = get_option('makhzan_phone',    '');
    $whatsapp = get_option('makhzan_whatsapp', '');
    ?>
    <div class="wrap" dir="rtl">
        <h1>إعدادات مخازن العناية</h1>
        <form method="post">
            <?php wp_nonce_field('makhzan_settings'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="makhzan_phone">رقم الهاتف</label></th>
                    <td><input type="text" id="makhzan_phone" name="makhzan_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="makhzan_whatsapp">رقم واتساب</label></th>
                    <td><input type="text" id="makhzan_whatsapp" name="makhzan_whatsapp" value="<?php echo esc_attr($whatsapp); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="makhzan_social">بيانات التواصل الاجتماعي (JSON)</label></th>
                    <td><textarea id="makhzan_social" name="makhzan_social" rows="8" class="large-text code"><?php echo esc_textarea($social); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="makhzan_contact">بيانات التواصل (JSON)</label></th>
                    <td><textarea id="makhzan_contact" name="makhzan_contact" rows="6" class="large-text code"><?php echo esc_textarea($contact); ?></textarea></td>
                </tr>
            </table>
            <?php submit_button('حفظ الإعدادات', 'primary', 'makhzan_save'); ?>
        </form>
    </div>
    <?php
}

// ============================================================
// Helper: Post thumbnail URL
// ============================================================
function makhzan_get_thumbnail_url($post_id = null, $size = 'large') {
    $id = get_post_thumbnail_id($post_id ?: get_the_ID());
    return $id ? wp_get_attachment_image_url($id, $size) : '';
}

// ============================================================
// WhatsApp number from options
// ============================================================
function makhzan_whatsapp_link() {
    $num = get_option('makhzan_whatsapp', '966920029921');
    return 'https://wa.me/' . preg_replace('/\D/', '', $num);
}
function makhzan_phone() {
    return get_option('makhzan_phone', '920029921');
}
