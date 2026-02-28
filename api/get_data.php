<?php
// ================================================
// Public API - Returns all active data
// ================================================
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$db = getDB();

// Branches
$branches = $db->query("
    SELECT id, name_ar, name_en, city_ar, city_en, address_ar, address_en, phone, map_url, sort_order
    FROM branches WHERE is_active = 1 ORDER BY sort_order ASC, city_ar ASC
")->fetchAll();

// Branch working hours — كل الأوقات دفعة واحدة ثم نربطها في PHP
$hours_rows = $db->query("
    SELECT branch_id, day_type, day_label, opens_at, closes_at, is_closed, note, sort_order
    FROM branch_hours
    WHERE is_active = 1
    ORDER BY branch_id ASC, sort_order ASC, id ASC
")->fetchAll();

// تجميع الأوقات حسب branch_id
$hours_map = [];
foreach ($hours_rows as $h) {
    $hours_map[$h['branch_id']][] = [
        'day_type'  => $h['day_type'],
        'day_label' => $h['day_label'],
        'opens_at'  => substr($h['opens_at'],  0, 5),   // HH:MM
        'closes_at' => substr($h['closes_at'], 0, 5),   // HH:MM
        'is_closed' => (bool)$h['is_closed'],
        'note'      => $h['note'],
    ];
}

// إضافة أوقات الدوام لكل فرع
foreach ($branches as &$branch) {
    $branch['working_hours'] = $hours_map[$branch['id']] ?? [];
}
unset($branch);

// Brands
$brands = $db->query("
    SELECT id, name_ar, name_en, logo_url, website_url, sort_order
    FROM brands WHERE is_active = 1 ORDER BY sort_order ASC, name_en ASC
")->fetchAll();

// Social media — color column included for frontend icon styling
$social = $db->query("
    SELECT id, platform, platform_ar, url, username, icon, color, sort_order
    FROM social_media WHERE is_active = 1 ORDER BY sort_order ASC
")->fetchAll();

// Contact info
$contact = $db->query("
    SELECT id, type, value, label_ar
    FROM contact_info WHERE is_active = 1 ORDER BY sort_order ASC
")->fetchAll();

echo json_encode([
    'success'  => true,
    'branches' => $branches,
    'brands'   => $brands,
    'social'   => $social,
    'contact'  => $contact,
], JSON_UNESCAPED_UNICODE);