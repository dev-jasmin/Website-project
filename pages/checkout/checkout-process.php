<?php
session_start();
require __DIR__ . '/../../database/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}

$paymentMethod = $_POST['payment_method'] ?? 'cod';
if (!in_array($paymentMethod, ['cod', 'gcash', 'maya'], true)) {
    $paymentMethod = 'cod';
}

$recipientName = trim($_POST['recipient_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$notes = trim($_POST['notes'] ?? '');

if ($recipientName === '' || $phone === '' || $address === '') {
    header('Location: checkout.php?message=' . urlencode('Please fill in all required delivery details.'));
    exit;
}

$pdo = getConnection();
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    'SELECT ci.quantity, p.id AS product_id, p.price
     FROM cart_item ci
     JOIN product p ON p.id = ci.product_id
     WHERE ci.user_id = :user_id'
);
$stmt->bindValue(':user_id', $userId);
$stmt->execute();
$items = $stmt->fetchAll();

if (empty($items)) {
    header('Location: /website/pages/cart/cart.php');
    exit;
}

$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$deliveryFee = $subtotal >= 500 ? 0 : 60;
$total = $subtotal + $deliveryFee;

try {
    $pdo->beginTransaction();

    $orderStmt = $pdo->prepare(
    'INSERT INTO `order` (user_id, recipient_name, phone, address, notes, total, status, payment_method)
     VALUES (:user_id, :recipient_name, :phone, :address, :notes, :total, :status, :payment_method)'
    );
    $orderStmt->bindValue(':user_id', $userId);
    $orderStmt->bindValue(':recipient_name', $recipientName);
    $orderStmt->bindValue(':phone', $phone);
    $orderStmt->bindValue(':address', $address);
    $orderStmt->bindValue(':notes', $notes !== '' ? $notes : null);
    $orderStmt->bindValue(':total', $total);
    $orderStmt->bindValue(':status', 'placed');
    $orderStmt->bindValue(':payment_method', $paymentMethod);
    $orderStmt->execute();

    $orderId = $pdo->lastInsertId();

    $itemStmt = $pdo->prepare(
        'INSERT INTO order_item (order_id, product_id, quantity, price)
         VALUES (:order_id, :product_id, :quantity, :price)'
    );
    $bumpStmt = $pdo->prepare(
        'UPDATE product SET total_orders = total_orders + :quantity WHERE id = :product_id'
    );

    foreach ($items as $item) {
        $itemStmt->bindValue(':order_id', $orderId);
        $itemStmt->bindValue(':product_id', $item['product_id']);
        $itemStmt->bindValue(':quantity', $item['quantity']);
        $itemStmt->bindValue(':price', $item['price']);
        $itemStmt->execute();

        $bumpStmt->bindValue(':quantity', $item['quantity']);
        $bumpStmt->bindValue(':product_id', $item['product_id']);
        $bumpStmt->execute();
    }

    $clearStmt = $pdo->prepare('DELETE FROM cart_item WHERE user_id = :user_id');
    $clearStmt->bindValue(':user_id', $userId);
    $clearStmt->execute();

    $pdo->commit();

    header('Location: checkout.php?order_id=' . $orderId . '&payment_method=' . $paymentMethod . '&total=' . $total);
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    header('Location: checkout.php?message=' . urlencode('Something went wrong placing your order. Please try again.'));
    exit;
}