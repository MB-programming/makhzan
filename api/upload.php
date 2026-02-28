<?php
// ================================================
// Brand Logo Upload
// ================================================
require_once __DIR__ . '/config.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

if (empty($_FILES['logo'])) {
    jsonResponse(['success' => false, 'message' => 'No file uploaded'], 400);
}

$file      = $_FILES['logo'];
$allowed   = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'];
$maxSize   = 2 * 1024 * 1024; // 2MB
$uploadDir = __DIR__ . '/../assets/brands/';

if (!in_array($file['type'], $allowed)) {
    jsonResponse(['success' => false, 'message' => 'نوع الملف غير مدعوم. يُسمح بـ JPG, PNG, WebP, GIF, SVG فقط'], 400);
}

if ($file['size'] > $maxSize) {
    jsonResponse(['success' => false, 'message' => 'حجم الملف يتجاوز 2MB'], 400);
}

$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'brand_' . uniqid() . '.' . strtolower($ext);
$destPath = $uploadDir . $filename;

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    jsonResponse(['success' => false, 'message' => 'فشل رفع الملف'], 500);
}

$publicUrl = 'assets/brands/' . $filename;
jsonResponse(['success' => true, 'url' => $publicUrl, 'message' => 'تم رفع الصورة بنجاح']);
