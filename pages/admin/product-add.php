<?php
session_start();
require __DIR__ . '/../../database/config.php';
require_once __DIR__ . '/../../includes/current-user.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}

$pdo = getConnection();

if (!isCurrentUserAdmin($pdo, $_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$price = (float) ($_POST['price'] ?? 0);
$category = $_POST['category'] ?? '';
$description = trim($_POST['description'] ?? '');

if ($name === '' || $price <= 0 || !in_array($category, ['floral', 'wood', 'fresh'], true)) {
    header('Location: admin.php?message=' . urlencode('Please fill in all required fields correctly.'));
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    header('Location: admin.php?message=' . urlencode('Please upload a product image.'));
    exit;
}

$file = $_FILES['image'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
$mimeType = mime_content_type($file['tmp_name']);

if (!in_array($mimeType, $allowedTypes, true)) {
    header('Location: admin.php?message=' . urlencode('Image must be a JPG, PNG, or WEBP file.'));
    exit;
}

$extension = match ($mimeType) {
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
};

$filename = 'product-' . time() . '-' . uniqid() . '.' . $extension;
$destination = __DIR__ . '/../../images/products/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    header('Location: admin.php?message=' . urlencode('Failed to save the uploaded image.'));
    exit;
}

$relativePath = 'images/products/' . $filename;

$stmt = $pdo->prepare(
    'INSERT INTO product (name, price, image, category, description, is_active)
     VALUES (:name, :price, :image, :category, :description, 1)'
);
$stmt->bindValue(':name', $name);
$stmt->bindValue(':price', $price);
$stmt->bindValue(':image', $relativePath);
$stmt->bindValue(':category', $category);
$stmt->bindValue(':description', $description !== '' ? $description : null);
$stmt->execute();

header('Location: admin.php?message=' . urlencode('Product added successfully.'));
exit;