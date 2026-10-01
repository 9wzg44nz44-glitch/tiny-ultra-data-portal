<?php
// TinySA Ultra data upload endpoint.
// Saves uploaded CSV/S2P files into data/uploads/ with an experiment-ID-based filename.
// Requires PHP with write access to data/uploads/ (standard on most shared hosts).

header('Content-Type: application/json; charset=utf-8');

$uploadDir = __DIR__ . '/data/uploads/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
        echo json_encode(['ok' => false, 'error' => 'Upload directory missing and could not be created.']);
        exit;
    }
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $err = $_FILES['file']['error'] ?? 'no file';
    echo json_encode(['ok' => false, 'error' => 'No file received (error ' . $err . ').']);
    exit;
}

$tmp  = $_FILES['file']['tmp_name'];
$orig = $_FILES['file']['name'];
$ext  = strrchr($orig, '.');
$ext  = $ext ? str_replace(['.', ' '], '', str_replace(['..'], '', $ext)) : '';
$ext  = str_replace(['/', '\\'], '', $ext);

if (!in_array(str_replace(['.', ' '], '', $ext), ['csv', 's2p'], true)) {
    echo json_encode(['ok' => false, 'error' => 'Only .csv and .s2p files are accepted.']);
    exit;
}

$exp = preg_replace('/[^A-Za-z0-9._-]/', '_', (string)($_POST['exp'] ?? 'unknown'));
if ($exp === '') $exp = 'unknown';
$freq = preg_replace('/[^A-Za-z0-9._-]/', '_', (string)($_POST['freq'] ?? ''));
$notes = preg_replace('/[^A-Za-z0-9._-]/', '_', (string)($_POST['notes'] ?? ''));

$stamp = date('Ymd-His');
$parts = [$exp, $stamp];
if ($freq !== '') $parts[] = $freq;
if ($notes !== '') $parts[] = substr($notes, 0, 40);
$filename = implode('-', $parts) . '.' . $ext;

// Avoid clobbering an existing file.
$i = 2;
$base = $filename;
while (file_exists($uploadDir . $filename)) {
    $filename = preg_replace('/\.[^.]+$/', '', $base) . '-' . $i . '.' . $ext;
    $i++;
}

if (!move_uploaded_file($tmp, $uploadDir . $filename)) {
    echo json_encode(['ok' => false, 'error' => 'Failed to move uploaded file. Check directory permissions.']);
    exit;
}

echo json_encode(['ok' => true, 'filename' => $filename, 'bytes' => filesize($uploadDir . $filename)]);
