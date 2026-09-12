<?php
session_start();
require __DIR__ . '/../../database/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}

$pdo = getConnection();

$stmt = $pdo->prepare(
    'SELECT ci.id AS cart_item_id, ci.quantity, p.id AS product_id, p.name, p.price, p.image
     FROM cart_item ci
     JOIN product p ON p.id = ci.product_id
     WHERE ci.user_id = :user_id
     ORDER BY ci.created_at DESC'
);

$stmt->bindValue(':user_id', $_SESSION['user_id']);
$stmt->execute();
$items = $stmt->fetchAll();

$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your Cart — Ember</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../styles/cart/cart.css" />
</head>
<body>
  <div class="cart-page">
    <header class="cart-header minimal">
      <a class="logo-link" href="/website/index.php">
        <img src="../../images/logo/logo-cream white.png" alt="Ember">
      </a>
      <a class="back-link" href="../shop.php">
        Continue shopping
      </a>
    </header>

    <main>
      <section class="page-content">
        <p class="eyebrow">Your bag</p>
        <h2>Cart</h2>

        <?php if (empty($items)): ?>
          <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a class="button button-champagne" href="../shop.php">Browse the shop</a>
          </div>
        <?php else: ?>
          <div class="cart-layout">
            <div class="cart-items" id="cartItems">
              <?php foreach ($items as $item): ?>
                <article class="cart-row" data-cart-item-id="<?= $item['cart_item_id'] ?>" data-price="<?= $item['price'] ?>">
                  <div class="cart-row-image">
                    <img src="../../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                  </div>
                  <div class="cart-row-info">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <strong>&#8369;<?= number_format($item['price']) ?></strong>
                  </div>
                  <div class="cart-row-qty">
                    <button class="qty-button" data-action="decrease">−</button>
                    <span class="qty-value"><?= $item['quantity'] ?></span>
                    <button class="qty-button" data-action="increase">+</button>
                  </div>
                  <div class="cart-row-total">
                    &#8369;<span class="row-total"><?= number_format($item['price'] * $item['quantity']) ?></span>
                  </div>
                  <button class="remove-button" aria-label="Remove item">&times;</button>
                </article>
              <?php endforeach; ?>
            </div>

            <div class="cart-summary">
              <h3>Order summary</h3>
              <div class="summary-row">
                <span>Subtotal</span>
                <strong>&#8369;<span id="cartSubtotal"><?= number_format($subtotal) ?></span></strong>
              </div>
              <p class="summary-note">Delivery fee and estimate calculated at checkout.</p>
              <a class="button button-champagne checkout-button" href="../checkout.php">Proceed to checkout</a>
            </div>
          </div>
        <?php endif; ?>
      </section>
    </main>
  </div>

  <script src="../../scripts/cart-page.js"></script>
</body>
</html>