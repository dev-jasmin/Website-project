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
    $stmt = $pdo->prepare('UPDATE product SET is_active = NOT is_active WHERE id = :id');
    $stmt->bindValue(':id', $productId);
    $stmt->execute();
}

header('Location: admin.php?message=' . urlencode('Product status updated.'));
exit;