<?php
// Lists files in data/uploads/ for the portal's file table.
header('Content-Type: application/json; charset=utf-8');

$uploadDir = __DIR__ . '/data/uploads/';
$files = [];
if (is_dir($uploadDir)) {
    foreach (scandir($uploadDir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $p = $uploadDir . $f;
        if (!is_file($p)) continue;
        $files[] = [
            'name'  => $f,
            'exp'   => preg_replace('/-\d{8}-\d{6}.*$/', '', $f),
            'size'  => round(filesize($p) / 1024, 1) . ' KB',
            'mtime' => date('Y-m-d H:i', filemtime($p)),
        ];
    }
    usort($files, function ($a, $b) { return strcmp($b['mtime'], $a['mtime']); });
}
echo json_encode(['ok' => true, 'files' => $files]);
