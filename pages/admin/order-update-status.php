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

$orderId = (int) ($_POST['order_id'] ?? 0);
$status = $_POST['status'] ?? '';

$allowedStatuses = ['placed', 'processing', 'shipped', 'delivered', 'cancelled'];

if ($orderId > 0 && in_array($status, $allowedStatuses, true)) {
    $stmt = $pdo->prepare('UPDATE `order` SET status = :status WHERE id = :id');
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':id', $orderId);
    $stmt->execute();
}

header('Location: admin.php?message=' . urlencode('Order status updated.'));
exit;