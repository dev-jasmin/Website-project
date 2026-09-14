<?php
session_start();
require __DIR__ . '/../../database/config.php';
require_once __DIR__ . '/../../includes/current-user.php';
require_once __DIR__ . '/../../includes/csrf.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}

$pdo = getConnection();

if (!isCurrentUserAdmin($pdo, $_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}


csrfCheck();

$productId = (int) ($_POST['product_id'] ?? 0);

if ($productId > 0) {
    // If this product has never been ordered, delete it cleanly.
    // If it has order history, deactivate instead to protect that history.
    $checkStmt = $pdo->prepare('SELECT COUNT(*) AS count FROM order_item WHERE product_id = :id');
    $checkStmt->bindValue(':id', $productId);
    $checkStmt->execute();
    $hasOrders = $checkStmt->fetch()['count'] > 0;

    if ($hasOrders) {
        $stmt = $pdo->prepare('UPDATE product SET is_active = 0 WHERE id = :id');
        $stmt->bindValue(':id', $productId);
        $stmt->execute();
        header('Location: admin.php?message=' . urlencode('Product has order history, so it was deactivated instead of deleted.'));
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM product WHERE id = :id');
    $stmt->bindValue(':id', $productId);
    $stmt->execute();
}

header('Location: admin.php?message=' . urlencode('Product deleted.'));
exit;