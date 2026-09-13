<?php
session_start();
require __DIR__ . '/../../database/config.php';
require_once __DIR__ . '/../../includes/current-user.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}

$pdo = getConnection();

$isAdmin = isCurrentUserAdmin($pdo, $_SESSION['user_id']);

$userStmt = $pdo->prepare('SELECT username, email, created_at, profile_picture, loyalty_points FROM user WHERE id = :id');
$userStmt->bindValue(':id', $_SESSION['user_id']);
$userStmt->execute();
$user = $userStmt->fetch();

$ordersStmt = $pdo->prepare(
    'SELECT id, total, status, payment_method, created_at
     FROM `order`
     WHERE user_id = :user_id
     ORDER BY created_at DESC'
);
$ordersStmt->bindValue(':user_id', $_SESSION['user_id']);
$ordersStmt->execute();
$orders = $ordersStmt->fetchAll();

// Grab item previews for each order (product names + images) in one extra query
$orderIds = array_column($orders, 'id');
$itemsByOrder = [];

$reviewStmt = $pdo->prepare('SELECT product_id, rating, comment FROM review WHERE user_id = :user_id');
$reviewStmt->bindValue(':user_id', $_SESSION['user_id']);
$reviewStmt->execute();

$reviewsByProduct = [];
foreach ($reviewStmt->fetchAll() as $row) {
    $reviewsByProduct[$row['product_id']] = $row;
}

if (!empty($orderIds)) {
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $itemsStmt = $pdo->prepare(
      "SELECT oi.order_id, oi.quantity, p.id AS product_id, p.name, p.image
      FROM order_item oi
      JOIN product p ON p.id = oi.product_id
      WHERE oi.order_id IN ($placeholders)"
    );
    $itemsStmt->execute($orderIds);

    foreach ($itemsStmt->fetchAll() as $row) {
        $itemsByOrder[$row['order_id']][] = $row;
    }
}

$statusLabels = [
    'placed' => 'Order placed',
    'processing' => 'Processing',
    'shipped' => 'Shipped',
    'delivered' => 'Delivered',
    'cancelled' => 'Cancelled',
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your Profile Ember</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../../styles/auth-modal.css" />
  <link rel="stylesheet" href="../../styles/profile.css" />
</head>
<body>
  <div class="profile-page">
    <header class="profile-header">
      <a class="logo-link" href="/website/index.php">
        <img src="../../images/logo/logo-cream white.png" alt="Ember">
      </a>

      <a class="logout-link" href="/website/logout/logout.php">Log out</a>
    </header>

    <main>
      <section class="page-content">
        <p class="eyebrow">Your account</p>
        <h2><?= htmlspecialchars($user['username']) ?></h2>

        <div class="profile-layout">
          <div class="profile-card">
            <div class="profile-avatar-wrap">
              <img
                class="profile-avatar"
                id="profileAvatar"
                src="<?= !empty($user['profile_picture']) ? '../../' . htmlspecialchars($user['profile_picture']) : '../../images/profiles/default-profile.jpg' ?>"
                alt="Your profile picture"
              >
              <label class="avatar-edit-button" for="avatarInput" title="Change photo">
                <span aria-hidden="true">✎</span>
              </label>
              <input type="file" id="avatarInput" accept="image/png, image/jpeg, image/webp" hidden>
            </div>

            <h3>Account details</h3>
            <div class="profile-detail">
              <span>Username</span>
              <strong><?= htmlspecialchars($user['username']) ?></strong>
            </div>
            <div class="profile-detail">
              <span>Email</span>
              <strong><?= htmlspecialchars($user['email']) ?></strong>
            </div>
            <div class="profile-detail">
              <span>Member since</span>
              <strong><?= date('F Y', strtotime($user['created_at'])) ?></strong>
            </div>

            <div class="profile-detail">
              <span>Loyalty points</span>
              <strong><?= number_format($user['loyalty_points']) ?> pts</strong>
            </div>

            <?php if ($isAdmin): ?>
              <a class="button button-champagne admin-panel-link" href="../admin/admin.php">
                Go to Admin Panel
              </a>
            <?php endif; ?>
          </div>

          <div class="order-history">
            <h3>Order history</h3>

            <?php if (empty($orders)): ?>
              <div class="empty-orders">
                <p>You haven't placed any orders yet.</p>
                <a class="button button-champagne" href="../shop.php">Start shopping</a>
              </div>
            <?php else: ?>
              <?php foreach ($orders as $order): ?>
                <article class="order-card">
                  <div class="order-card-header">
                    <div>
                      <strong>Order #<?= $order['id'] ?></strong>
                      <span class="order-date"><?= date('M j, Y', strtotime($order['created_at'])) ?></span>
                    </div>
                    <span class="order-status status-<?= htmlspecialchars($order['status']) ?>">
                      <?= htmlspecialchars($statusLabels[$order['status']] ?? ucfirst($order['status'])) ?>
                    </span>
                  </div>

                  <div class="order-items-preview">
                    <?php foreach (($itemsByOrder[$order['id']] ?? []) as $item): ?>
                      <?php $existingReview = $reviewsByProduct[$item['product_id']] ?? null; ?>
                      <div class="order-item-row">
                        <div class="order-item-thumb" title="<?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?>">
                          <img src="../../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        <span class="order-item-name"><?= htmlspecialchars($item['name']) ?></span>
                        <button
                          class="rate-button"
                          data-product-id="<?= $item['product_id'] ?>"
                          data-product-name="<?= htmlspecialchars($item['name']) ?>"
                          data-existing-rating="<?= $existingReview['rating'] ?? 0 ?>"
                          data-existing-comment="<?= htmlspecialchars($existingReview['comment'] ?? '') ?>"
                        >
                          <?= $existingReview ? 'Edit review (' . str_repeat('★', $existingReview['rating']) . str_repeat('☆', 5 - $existingReview['rating']) . ')' : 'Rate this product' ?>
                        </button>
                      </div>
                    <?php endforeach; ?>
                  </div>

                  <div class="order-card-footer">
                    <span class="order-payment"><?= strtoupper($order['payment_method']) ?></span>
                    <strong>&#8369;<?= number_format($order['total']) ?></strong>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </section>
    </main>
  </div>

  <?php require __DIR__ . '/../../includes/review-modal.php'; ?>
  <script src="../../scripts/review-modal.js"></script>
  <script src="../../scripts/profile.js"></script>
</body>
</html>