<?php
// ================================================
// Admin CRUD API
// Protected: requires active session
// ================================================
require_once __DIR__ . '/config.php';
requireAuth();

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$table  = clean($_GET['table'] ?? '');
$id     = intval($_GET['id'] ?? 0);

$allowed_tables = ['branches', 'brands', 'social_media', 'contact_info'];
if (!in_array($table, $allowed_tables)) {
    jsonResponse(['success' => false, 'message' => 'Invalid table'], 400);
}

// ------------------------------------------------
// GET - list all records
// ------------------------------------------------
if ($method === 'GET' && !$id) {
    $rows = $db->query("SELECT * FROM `$table` ORDER BY sort_order ASC, id ASC")->fetchAll();
    jsonResponse(['success' => true, 'data' => $rows]);
}

// ------------------------------------------------
// GET single record
// ------------------------------------------------
if ($method === 'GET' && $id) {
    $stmt = $db->prepare("SELECT * FROM `$table` WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) jsonResponse(['success' => false, 'message' => 'Not found'], 404);
    jsonResponse(['success' => true, 'data' => $row]);
}

// ------------------------------------------------
// POST - create
// ------------------------------------------------
if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    switch ($table) {
        case 'branches':
            $stmt = $db->prepare("INSERT INTO branches (name_ar, name_en, city_ar, city_en, address_ar, address_en, phone, map_url, is_active, sort_order)
                                  VALUES (:name_ar,:name_en,:city_ar,:city_en,:address_ar,:address_en,:phone,:map_url,:is_active,:sort_order)");
            $stmt->execute([
                'name_ar'    => clean($body['name_ar'] ?? ''),
                'name_en'    => clean($body['name_en'] ?? ''),
                'city_ar'    => clean($body['city_ar'] ?? ''),
                'city_en'    => clean($body['city_en'] ?? ''),
                'address_ar' => clean($body['address_ar'] ?? ''),
                'address_en' => clean($body['address_en'] ?? ''),
                'phone'      => clean($body['phone'] ?? ''),
                'map_url'    => clean($body['map_url'] ?? ''),
                'is_active'  => intval($body['is_active'] ?? 1),
                'sort_order' => intval($body['sort_order'] ?? 0),
            ]);
            break;

        case 'brands':
            $stmt = $db->prepare("INSERT INTO brands (name_ar, name_en, logo_url, website_url, is_active, sort_order)
                                  VALUES (:name_ar,:name_en,:logo_url,:website_url,:is_active,:sort_order)");
            $stmt->execute([
                'name_ar'     => clean($body['name_ar'] ?? ''),
                'name_en'     => clean($body['name_en'] ?? ''),
                'logo_url'    => clean($body['logo_url'] ?? ''),
                'website_url' => clean($body['website_url'] ?? ''),
                'is_active'   => intval($body['is_active'] ?? 1),
                'sort_order'  => intval($body['sort_order'] ?? 0),
            ]);
            break;

        case 'social_media':
            $stmt = $db->prepare("INSERT INTO social_media (platform, platform_ar, url, username, icon, color, is_active, sort_order)
                                  VALUES (:platform,:platform_ar,:url,:username,:icon,:color,:is_active,:sort_order)");
            $stmt->execute([
                'platform'    => clean($body['platform'] ?? ''),
                'platform_ar' => clean($body['platform_ar'] ?? ''),
                'url'         => clean($body['url'] ?? ''),
                'username'    => clean($body['username'] ?? ''),
                'icon'        => clean($body['icon'] ?? ''),
                'color'       => clean($body['color'] ?? '#ffffff'),
                'is_active'   => intval($body['is_active'] ?? 1),
                'sort_order'  => intval($body['sort_order'] ?? 0),
            ]);
            break;

        case 'contact_info':
            $stmt = $db->prepare("INSERT INTO contact_info (type, value, label_ar, is_active, sort_order)
                                  VALUES (:type,:value,:label_ar,:is_active,:sort_order)");
            $stmt->execute([
                'type'       => clean($body['type'] ?? ''),
                'value'      => clean($body['value'] ?? ''),
                'label_ar'   => clean($body['label_ar'] ?? ''),
                'is_active'  => intval($body['is_active'] ?? 1),
                'sort_order' => intval($body['sort_order'] ?? 0),
            ]);
            break;
    }
    jsonResponse(['success' => true, 'message' => 'تم الإضافة بنجاح', 'id' => $db->lastInsertId()]);
}

// ------------------------------------------------
// PUT - update
// ------------------------------------------------
if ($method === 'PUT' && $id) {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    switch ($table) {
        case 'branches':
            $stmt = $db->prepare("UPDATE branches SET name_ar=:name_ar, name_en=:name_en, city_ar=:city_ar, city_en=:city_en,
                                  address_ar=:address_ar, address_en=:address_en, phone=:phone, map_url=:map_url,
                                  is_active=:is_active, sort_order=:sort_order WHERE id=:id");
            $stmt->execute([
                'name_ar'    => clean($body['name_ar'] ?? ''),
                'name_en'    => clean($body['name_en'] ?? ''),
                'city_ar'    => clean($body['city_ar'] ?? ''),
                'city_en'    => clean($body['city_en'] ?? ''),
                'address_ar' => clean($body['address_ar'] ?? ''),
                'address_en' => clean($body['address_en'] ?? ''),
                'phone'      => clean($body['phone'] ?? ''),
                'map_url'    => clean($body['map_url'] ?? ''),
                'is_active'  => intval($body['is_active'] ?? 1),
                'sort_order' => intval($body['sort_order'] ?? 0),
                'id'         => $id,
            ]);
            break;

        case 'brands':
            $stmt = $db->prepare("UPDATE brands SET name_ar=:name_ar, name_en=:name_en, logo_url=:logo_url,
                                  website_url=:website_url, is_active=:is_active, sort_order=:sort_order WHERE id=:id");
            $stmt->execute([
                'name_ar'     => clean($body['name_ar'] ?? ''),
                'name_en'     => clean($body['name_en'] ?? ''),
                'logo_url'    => clean($body['logo_url'] ?? ''),
                'website_url' => clean($body['website_url'] ?? ''),
                'is_active'   => intval($body['is_active'] ?? 1),
                'sort_order'  => intval($body['sort_order'] ?? 0),
                'id'          => $id,
            ]);
            break;

        case 'social_media':
            $stmt = $db->prepare("UPDATE social_media SET platform=:platform, platform_ar=:platform_ar, url=:url,
                                  username=:username, icon=:icon, color=:color, is_active=:is_active, sort_order=:sort_order WHERE id=:id");
            $stmt->execute([
                'platform'    => clean($body['platform'] ?? ''),
                'platform_ar' => clean($body['platform_ar'] ?? ''),
                'url'         => clean($body['url'] ?? ''),
                'username'    => clean($body['username'] ?? ''),
                'icon'        => clean($body['icon'] ?? ''),
                'color'       => clean($body['color'] ?? '#ffffff'),
                'is_active'   => intval($body['is_active'] ?? 1),
                'sort_order'  => intval($body['sort_order'] ?? 0),
                'id'          => $id,
            ]);
            break;

        case 'contact_info':
            $stmt = $db->prepare("UPDATE contact_info SET type=:type, value=:value, label_ar=:label_ar,
                                  is_active=:is_active, sort_order=:sort_order WHERE id=:id");
            $stmt->execute([
                'type'       => clean($body['type'] ?? ''),
                'value'      => clean($body['value'] ?? ''),
                'label_ar'   => clean($body['label_ar'] ?? ''),
                'is_active'  => intval($body['is_active'] ?? 1),
                'sort_order' => intval($body['sort_order'] ?? 0),
                'id'         => $id,
            ]);
            break;
    }
    jsonResponse(['success' => true, 'message' => 'تم التحديث بنجاح']);
}

// ------------------------------------------------
// DELETE
// ------------------------------------------------
if ($method === 'DELETE' && $id) {
    $stmt = $db->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmt->execute([$id]);
    jsonResponse(['success' => true, 'message' => 'تم الحذف بنجاح']);
}

// ------------------------------------------------
// Toggle active status
// ------------------------------------------------
if ($method === 'PATCH' && $id) {
    $stmt = $db->prepare("UPDATE `$table` SET is_active = NOT is_active WHERE id = ?");
    $stmt->execute([$id]);
    jsonResponse(['success' => true, 'message' => 'تم تغيير الحالة']);
}

jsonResponse(['success' => false, 'message' => 'Bad request'], 400);
