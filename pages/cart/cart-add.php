<?php
session_start();
header('Content-Type: application/json');
require __DIR__ . '/../../database/config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

$productId = (int) ($_POST['product_id'] ?? 0);
if ($productId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_product']);
    exit;
}

$pdo = getConnection();

$stmt = $pdo->prepare(
    'INSERT INTO cart_item (user_id, product_id, quantity)
     VALUES (:user_id, :product_id, 1)
     ON DUPLICATE KEY UPDATE quantity = quantity + 1'
);
$stmt->bindValue(':user_id', $_SESSION['user_id']);
$stmt->bindValue(':product_id', $productId);
$stmt->execute();

$countStmt = $pdo->prepare('SELECT COALESCE(SUM(quantity), 0) AS total FROM cart_item WHERE user_id = :user_id');
$countStmt->bindValue(':user_id', $_SESSION['user_id']);
$countStmt->execute();
$total = $countStmt->fetch()['total'];

echo json_encode(['success' => true, 'cart_count' => (int) $total]);