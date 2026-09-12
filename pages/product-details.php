<?php
session_start();
header('Content-Type: application/json');
require __DIR__ . '/../database/config.php';

$productId = (int) ($_GET['id'] ?? 0);
if ($productId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_product']);
    exit;
}

$pdo = getConnection();

$productStmt = $pdo->prepare('SELECT * FROM product WHERE id = :id AND is_active = 1');
$productStmt->bindValue(':id', $productId);
$productStmt->execute();
$product = $productStmt->fetch();

if (!$product) {
    http_response_code(404);
    echo json_encode(['error' => 'not_found']);
    exit;
}

$ratingStmt = $pdo->prepare(
    'SELECT COUNT(*) AS review_count, COALESCE(AVG(rating), 0) AS average_rating
     FROM review WHERE product_id = :id'
);
$ratingStmt->bindValue(':id', $productId);
$ratingStmt->execute();
$ratingData = $ratingStmt->fetch();

echo json_encode([
    'success' => true,
    'product' => [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => (float) $product['price'],
        'image' => $product['image'],
        'description' => $product['description'] ?: 'No description available yet.',
        'total_orders' => (int) $product['total_orders'],
        'average_rating' => round((float) $ratingData['average_rating'], 1),
        'review_count' => (int) $ratingData['review_count'],
    ],
]);