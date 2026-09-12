<?php
session_start();
header('Content-Type: application/json');
require __DIR__ . '/../../database/config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

$cartItemId = (int) ($_POST['cart_item_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($cartItemId <= 0 || !in_array($action, ['increase', 'decrease', 'remove'], true)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_request']);
    exit;
}

$pdo = getConnection();

// Make sure this cart item actually belongs to the logged-in user
$check = $pdo->prepare('SELECT quantity FROM cart_item WHERE id = :id AND user_id = :user_id');
$check->bindValue(':id', $cartItemId);
$check->bindValue(':user_id', $_SESSION['user_id']);
$check->execute();
$row = $check->fetch();

if (!$row) {
    http_response_code(404);
    echo json_encode(['error' => 'not_found']);
    exit;
}

if ($action === 'remove') {
    $del = $pdo->prepare('DELETE FROM cart_item WHERE id = :id');
    $del->bindValue(':id', $cartItemId);
    $del->execute();
    echo json_encode(['success' => true, 'removed' => true]);
    exit;
}

$newQuantity = $action === 'increase' ? $row['quantity'] + 1 : $row['quantity'] - 1;

if ($newQuantity <= 0) {
    $del = $pdo->prepare('DELETE FROM cart_item WHERE id = :id');
    $del->bindValue(':id', $cartItemId);
    $del->execute();
    echo json_encode(['success' => true, 'removed' => true]);
    exit;
}

$update = $pdo->prepare('UPDATE cart_item SET quantity = :quantity WHERE id = :id');
$update->bindValue(':quantity', $newQuantity);
$update->bindValue(':id', $cartItemId);
$update->execute();

echo json_encode(['success' => true, 'quantity' => $newQuantity]);