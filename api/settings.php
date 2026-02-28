<?php
// ================================================
// Settings API - Code Injection (header/body)
// ================================================
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// ---- Public GET (used by landing page to inject codes) ----
if ($method === 'GET' && empty($_GET['admin'])) {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    $db   = getDB();
    $rows = $db->query("SELECT `key`, value FROM settings")->fetchAll();
    $out  = [];
    foreach ($rows as $r) $out[$r['key']] = $r['value'];
    echo json_encode(['success' => true, 'settings' => $out], JSON_UNESCAPED_UNICODE);
    exit;
}

// ---- Admin GET (returns all rows with labels) ----
if ($method === 'GET' && !empty($_GET['admin'])) {
    requireAuth();
    $db   = getDB();
    $rows = $db->query("SELECT * FROM settings ORDER BY id ASC")->fetchAll();
    jsonResponse(['success' => true, 'data' => $rows]);
}

// ---- Admin POST (save/update settings) ----
if ($method === 'POST') {
    requireAuth();
    $db   = getDB();
    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    $stmt = $db->prepare("INSERT INTO settings (`key`, value, label_ar)
                          VALUES (:key, :value, :label_ar)
                          ON DUPLICATE KEY UPDATE value = :value2");

    foreach ($body as $key => $value) {
        $allowed = ['header_code', 'body_code'];
        if (!in_array($key, $allowed)) continue;
        $stmt->execute([
            'key'      => $key,
            'value'    => $value,
            'label_ar' => $key === 'header_code' ? 'كود الهيدر' : 'كود البودي',
            'value2'   => $value,
        ]);
    }

    jsonResponse(['success' => true, 'message' => 'تم حفظ الإعدادات بنجاح']);
}

jsonResponse(['success' => false, 'message' => 'Bad request'], 400);
