<?php
session_start();
header('Content-Type: application/json');
require __DIR__ . '/../../database/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['cart_count' => 0]);
    exit;
}

$pdo = getConnection();
$stmt = $pdo->prepare('SELECT COALESCE(SUM(quantity), 0) AS total FROM cart_item WHERE user_id = :user_id');
$stmt->bindValue(':user_id', $_SESSION['user_id']);
$stmt->execute();

echo json_encode(['cart_count' => (int) $stmt->fetch()['total']]);