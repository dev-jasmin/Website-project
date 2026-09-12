<?php
session_start();
header('Content-Type: application/json');
require __DIR__ . '/../../database/config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

$userId = $_SESSION['user_id'];
$productId = (int) ($_POST['product_id'] ?? 0);
$rating = (int) ($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if ($productId <= 0 || $rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_input']);
    exit;
}

$pdo = getConnection();

// Confirm this user actually bought this product before letting them review it
$checkStmt = $pdo->prepare(
    'SELECT 1 FROM order_item oi
     JOIN `order` o ON o.id = oi.order_id
     WHERE o.user_id = :user_id AND oi.product_id = :product_id
     LIMIT 1'
);
$checkStmt->bindValue(':user_id', $userId);
$checkStmt->bindValue(':product_id', $productId);
$checkStmt->execute();

if (!$checkStmt->fetch()) {
    http_response_code(403);
    echo json_encode(['error' => 'not_purchased']);
    exit;
}

$stmt = $pdo->prepare(
    'INSERT INTO review (product_id, user_id, rating, comment)
     VALUES (:product_id, :user_id, :rating, :comment)
     ON DUPLICATE KEY UPDATE rating = :rating2, comment = :comment2'
);
$stmt->bindValue(':product_id', $productId);
$stmt->bindValue(':user_id', $userId);
$stmt->bindValue(':rating', $rating);
$stmt->bindValue(':comment', $comment !== '' ? $comment : null);
$stmt->bindValue(':rating2', $rating);
$stmt->bindValue(':comment2', $comment !== '' ? $comment : null);
$stmt->execute();

echo json_encode(['success' => true, 'rating' => $rating]);