<?php
session_start();
require __DIR__ . '/../../database/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}

$pdo = getConnection();

$stmt = $pdo->prepare(
    'SELECT ci.quantity, p.id AS product_id, p.name, p.price, p.image
     FROM cart_item ci
     JOIN product p ON p.id = ci.product_id
     WHERE ci.user_id = :user_id
     ORDER BY ci.created_at DESC'
);
$stmt->bindValue(':user_id', $_SESSION['user_id']);
$stmt->execute();
$items = $stmt->fetchAll();

$orderId = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;

if (empty($items) && $orderId === 0) {
  header('Location: /website/pages/cart/cart.php');
    exit;
}

$subtotal = 0;
foreach ($items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$deliveryFee = $subtotal >= 500 ? 0 : 60;
$total = $subtotal + $deliveryFee;

$errorMessage = $_GET['message'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout Ember</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../styles/auth.css" />
  <link rel="stylesheet" href="../../styles/auth-modal.css" />
  <link rel="stylesheet" href="../../styles/checkout.css"/>
</head>
<body>
  <div class="checkout-page">
    <header class="checkout-header">
      <a class="logo-link" href="/website/index.php">
        <img src="../../images/logo/logo-cream white.png" alt="Ember">
      </a>
      <a class="back-link" href="../cart/cart.php">
        Back to cart
      </a>
    </header>

    <main>
      <section class="page-content">
        <p class="eyebrow">Almost there</p>
        <h2>Checkout</h2>

        <?php if ($errorMessage): ?>
          <p class="checkout-error"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <?php if ($orderId === 0): ?>
        <div class="checkout-layout">
          <form class="checkout-form" action="checkout-process.php" method="post">
            <h3>Delivery details</h3>

            <label class="form-field">
              <span>Recipient name</span>
              <input type="text" name="recipient_name" required>
            </label>

            <label class="form-field">
              <span>Phone number</span>
              <input type="tel" name="phone" required>
            </label>

            <label class="form-field">
              <span>Delivery address</span>
              <textarea name="address" rows="3" required></textarea>
            </label>

            <label class="form-field">
              <span>Notes (optional)</span>
              <textarea name="notes" rows="2" placeholder="Landmark, gate code, delivery instructions..."></textarea>
            </label>

            <div class="delivery-estimate">
              <strong>Estimated delivery:</strong> 3–5 business days
            </div>

            <div class="payment-method">
              <strong>Payment method</strong>

              <div class="payment-options">
                <label class="payment-option">
                  <input type="radio" name="payment_method" value="cod" checked>
                  <span>Cash on delivery</span>
                </label>

                <label class="payment-option">
                  <input type="radio" name="payment_method" value="gcash">
                  <span>GCash</span>
                </label>

                <label class="payment-option">
                  <input type="radio" name="payment_method" value="maya">
                  <span>Maya</span>
                </label>
              </div>

              <p class="payment-instructions" id="codInstructions">
                Pay in person when your order arrives.
              </p>

              <p class="payment-instructions" id="gcashInstructions" hidden>
                Send &#8369;<?= number_format($total) ?> to <strong>0917-123-4567 (Ember Fragrance House)</strong> via GCash,
                then keep your reference number, we'll confirm before shipping.
              </p>

              <p class="payment-instructions" id="mayaInstructions" hidden>
                Send &#8369;<?= number_format($total) ?> to <strong>0917-123-4567 (Ember Fragrance House)</strong> via Maya,
                then keep your reference number, we'll confirm before shipping.
              </p>
            </div>

            <button class="button button-champagne place-order-button" type="submit">
              Place order
            </button>
          </form>

          <div class="checkout-summary">
            <h3>Order summary</h3>
            <div class="summary-items">
              <?php foreach ($items as $item): ?>
                <div class="summary-item">
                  <div class="summary-item-image">
                    <img src="../../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                  </div>
                  <div class="summary-item-info">
                    <span class="summary-item-name"><?= htmlspecialchars($item['name']) ?></span>
                    <span class="summary-item-qty">Qty <?= $item['quantity'] ?></span>
                  </div>
                  <span class="summary-item-price">&#8369;<?= number_format($item['price'] * $item['quantity']) ?></span>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="summary-row">
              <span>Subtotal</span>
              <strong>&#8369;<?= number_format($subtotal) ?></strong>
            </div>
            <div class="summary-row">
              <span>Delivery fee</span>
              <strong><?= $deliveryFee === 0 ? 'Free' : '&#8369;' . number_format($deliveryFee) ?></strong>
            </div>
            <div class="summary-row summary-total">
              <span>Total</span>
              <strong>&#8369;<?= number_format($total) ?></strong>
            </div>
          </div>
        </div>
        <?php else: ?>
          <div class="checkout-placeholder"></div>
        <?php endif; ?>
      </section>
    </main>
  </div>

 <?php
  $assetBasePath = '../../';
  require __DIR__ . '/../../includes/order-confirmation-modal.php';
  ?>
  <script src="../../scripts/checkout.js"></script>
</body>
</html>