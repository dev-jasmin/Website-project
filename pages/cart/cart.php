<?php
session_start();
require __DIR__ . '/../../database/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
}

$pdo = getConnection();
$stmt = $pdo->prepare(
    'SELECT cart_item.quantity, product.name, product.price, product.image
     FROM cart_item
     INNER JOIN product ON product.id = cart_item.product_id
     WHERE cart_item.user_id = :user_id
     ORDER BY cart_item.product_id'
);
$stmt->bindValue(':user_id', $_SESSION['user_id']);
$stmt->execute();
$items = $stmt->fetchAll();
$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart | Ember</title>
  <link rel="stylesheet" href="../../styles/shop.css">
  <style>
    .cart-page { max-width: 960px; margin: 0 auto; padding: 72px 24px; }
    .cart-page h1 { font-family: var(--serif); font-size: clamp(42px, 7vw, 72px); font-weight: 400; margin: 0 0 36px; }
    .cart-item { display: flex; align-items: center; gap: 20px; padding: 18px 0; border-bottom: 1px solid var(--line); }
    .cart-item img { width: 84px; height: 84px; object-fit: contain; background: var(--paper); border-radius: 8px; }
    .cart-item-info { flex: 1; }
    .cart-item-info h2 { margin: 0 0 6px; font-size: 16px; font-weight: 500; }
    .cart-item-info p { margin: 0; color: var(--champagne); }
    .cart-total { display: flex; justify-content: space-between; margin-top: 28px; font-size: 18px; }
    .empty-cart { color: var(--champagne); }
  </style>
</head>
<body>
  <main class="cart-page">
    <a href="../../index.php">Back to Ember</a>
    <h1>Your cart</h1>
    <?php if (!$items): ?>
      <p class="empty-cart">Your cart is empty.</p>
    <?php else: ?>
      <?php foreach ($items as $item): ?>
        <article class="cart-item">
          <img src="../../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          <div class="cart-item-info">
            <h2><?= htmlspecialchars($item['name']) ?></h2>
            <p><?= (int) $item['quantity'] ?> × &#8369;<?= number_format($item['price']) ?></p>
          </div>
          <strong>&#8369;<?= number_format($item['price'] * $item['quantity']) ?></strong>
        </article>
      <?php endforeach; ?>
      <div class="cart-total">
        <strong>Total</strong>
        <strong>&#8369;<?= number_format($total) ?></strong>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>