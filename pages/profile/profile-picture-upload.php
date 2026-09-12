<?php
session_start();
header('Content-Type: application/json');
require __DIR__ . '/../../database/config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'upload_failed']);
    exit;
}

$file = $_FILES['profile_picture'];

// Only allow real images, and cap the size
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
$maxSizeBytes = 3 * 1024 * 1024; // 3MB

$mimeType = mime_content_type($file['tmp_name']);
if (!in_array($mimeType, $allowedTypes, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_file_type']);
    exit;
}

if ($file['size'] > $maxSizeBytes) {
    http_response_code(400);
    echo json_encode(['error' => 'file_too_large']);
    exit;
}

$extension = match ($mimeType) {
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
};

$userId = $_SESSION['user_id'];
$filename = 'user-' . $userId . '-' . time() . '.' . $extension;
$uploadDir = __DIR__ . '/../../images/profiles/uploads/';
$destination = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    http_response_code(500);
    echo json_encode(['error' => 'save_failed']);
    exit;
}

$pdo = getConnection();

// Delete the old uploaded picture from disk if there was one
$oldStmt = $pdo->prepare('SELECT profile_picture FROM user WHERE id = :id');
$oldStmt->bindValue(':id', $userId);
$oldStmt->execute();
$old = $oldStmt->fetch();

if (!empty($old['profile_picture'])) {
    $oldPath = __DIR__ . '/../../' . $old['profile_picture'];
    if (file_exists($oldPath)) {
        unlink($oldPath);
    }
}

$relativePath = 'images/profiles/uploads/' . $filename;

$updateStmt = $pdo->prepare('UPDATE user SET profile_picture = :path WHERE id = :id');
$updateStmt->bindValue(':path', $relativePath);
$updateStmt->bindValue(':id', $userId);
$updateStmt->execute();

echo json_encode(['success' => true, 'path' => '../../' . $relativePath]);