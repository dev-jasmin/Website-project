<?php
session_start();
require __DIR__ . '/../../database/config.php';
require_once __DIR__ . '/../../includes/current-user.php';
require_once __DIR__ . '/../../includes/csrf.php';
$csrfToken = csrfToken();

if (!isset($_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}

$pdo = getConnection();

if (!isCurrentUserAdmin($pdo, $_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}

$products = $pdo->query('SELECT * FROM product ORDER BY id DESC')->fetchAll();
$totalProductsCount = count($products);
$activeProductsCount = count(array_filter($products, fn($p) => $p['is_active'] == 1));

$orders = $pdo->query(
    'SELECT o.*, u.username
     FROM `order` o
     JOIN user u ON u.id = o.user_id
     ORDER BY o.created_at DESC'
)->fetchAll();
$totalOrdersCount = (int) $pdo->query('SELECT COUNT(*) FROM `order`')->fetchColumn();
$totalRevenue = (float) $pdo->query('SELECT COALESCE(SUM(total), 0) FROM `order`')->fetchColumn();

$subscribers = $pdo->query('SELECT email, subscribed_at FROM newsletter_subscriber ORDER BY subscribed_at DESC')->fetchAll();
$subscriberCount = count($subscribers);

$messages = $pdo->query('SELECT * FROM contact_message ORDER BY created_at DESC')->fetchAll();
$messageCount = count($messages);

$statusMessage = $_GET['message'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin | Ember</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../styles/admin.css" />
</head>
<body>
  <div class="admin-shell">
    <aside class="admin-sidebar">
      <a class="logo-link" href="/website/index.php">
        <img src="../../images/logo/logo-cream white.png" alt="Ember">
      </a>
      <span class="admin-badge">Admin panel</span>

      <nav class="admin-nav">
        <button class="sidebar-link is-active" data-panel="dashboard">Dashboard</button>
        <button class="sidebar-link" data-panel="products">Products</button>
        <button class="sidebar-link" data-panel="orders">Orders</button>
        <button class="sidebar-link" data-panel="subscribers">Subscribers</button>
        <button class="sidebar-link" data-panel="messages">Messages</button>
      </nav>

      <a class="logout-link" href="/website/logout/logout.php">Log out</a>
    </aside>

    <main class="admin-content">
      <?php if ($statusMessage): ?>
        <p class="admin-status"><?= htmlspecialchars($statusMessage) ?></p>
      <?php endif; ?>

      <section class="admin-panel is-active" id="panel-dashboard">
        <p class="eyebrow">Overview</p>
        <h2>Dashboard</h2>

        <div class="stat-row">
          <div class="stat-card">
            <span class="stat-label">Products</span>
            <strong class="stat-value"><?= $activeProductsCount ?> / <?= $totalProductsCount ?></strong>
            <span class="stat-sub">active / total</span>
          </div>
          <div class="stat-card">
            <span class="stat-label">Orders</span>
            <strong class="stat-value"><?= number_format($totalOrdersCount) ?></strong>
            <span class="stat-sub">all time</span>
          </div>
          <div class="stat-card">
            <span class="stat-label">Revenue</span>
            <strong class="stat-value">&#8369;<?= number_format($totalRevenue) ?></strong>
            <span class="stat-sub">all time</span>
          </div>
          <div class="stat-card">
            <span class="stat-label">Subscribers</span>
            <strong class="stat-value"><?= number_format($subscriberCount) ?></strong>
            <span class="stat-sub">newsletter</span>
          </div>
          <div class="stat-card">
            <span class="stat-label">Messages</span>
            <strong class="stat-value"><?= number_format($messageCount) ?></strong>
            <span class="stat-sub">contact form</span>
          </div>
        </div>
      </section>

      <section class="admin-panel" id="panel-products">
        <p class="eyebrow">Manage catalog</p>
        <h2>Products</h2>

        <div class="admin-layout">
          <div class="admin-add-card">
            <h3>Add new product</h3>
            <form action="product-add.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
              <label class="form-field">
                <span>Name</span>
                <input type="text" name="name" required>
              </label>
              <label class="form-field">
                <span>Price (₱)</span>
                <input type="number" name="price" step="0.01" min="0" required>
              </label>
              <label class="form-field">
                <span>Category</span>
                <select name="category" required>
                  <option value="floral">Floral</option>
                  <option value="wood">Wood</option>
                  <option value="fresh">Fresh</option>
                </select>
              </label>
              <label class="form-field">
                <span>Description</span>
                <textarea name="description" rows="3"></textarea>
              </label>
              <label class="form-field">
                <span>Product image</span>
                <input type="file" name="image" accept="image/png, image/jpeg, image/webp" required>
              </label>
              <button class="button button-champagne" type="submit">Add product</button>
            </form>
          </div>

          <div class="admin-product-list">
            <h3>Existing products (<?= count($products) ?>)</h3>
            <?php foreach ($products as $product): ?>
              <article class="admin-product-row <?= $product['is_active'] ? '' : 'is-inactive' ?>">
                <div class="admin-product-thumb">
                  <img src="../../<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="admin-product-info">
                  <strong><?= htmlspecialchars($product['name']) ?></strong>
                  <span>&#8369;<?= number_format($product['price']) ?> · <?= htmlspecialchars($product['category']) ?></span>
                  <span class="admin-product-meta"><?= number_format($product['total_orders']) ?> sold</span>
                </div>
                <div class="admin-product-actions">
                  <form action="product-toggle.php" method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <button class="admin-action-button" type="submit">
                      <?= $product['is_active'] ? 'Deactivate' : 'Activate' ?>
                    </button>
                  </form>
                  <form action="product-delete.php" method="post" onsubmit="return confirm('Delete this product permanently? This cannot be undone.');">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <button class="admin-action-button admin-delete-button" type="submit">Delete</button>
                  </form>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <section class="admin-panel" id="panel-orders">
        <p class="eyebrow">All orders</p>
        <h2>Orders (<?= count($orders) ?>)</h2>

        <div class="admin-order-list">
          <?php foreach ($orders as $order): ?>
            <article class="admin-order-row">
              <div class="admin-order-info">
                <strong>Order #<?= $order['id'] ?></strong>
                <span><?= htmlspecialchars($order['username']) ?></span>
                <span class="admin-order-date"><?= date('M j, Y', strtotime($order['created_at'])) ?></span>
              </div>

              <form class="order-status-form" action="order-update-status.php" method="post">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <select name="status" onchange="this.form.submit()">
                  <option value="placed" <?= $order['status'] === 'placed' ? 'selected' : '' ?>>Order placed</option>
                  <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                  <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                  <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                  <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
              </form>

              <span class="admin-order-payment"><?= strtoupper($order['payment_method']) ?></span>
              <strong class="admin-order-total">&#8369;<?= number_format($order['total']) ?></strong>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="admin-panel" id="panel-subscribers">
        <p class="eyebrow">Mailing list</p>
        <h2>Newsletter subscribers (<?= count($subscribers) ?>)</h2>

        <?php if (empty($subscribers)): ?>
          <p class="admin-empty-note">No subscribers yet.</p>
        <?php else: ?>
          <table class="admin-subscriber-table">
            <thead>
              <tr>
                <th>Email</th>
                <th>Subscribed</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($subscribers as $sub): ?>
                <tr>
                  <td><?= htmlspecialchars($sub['email']) ?></td>
                  <td><?= date('M j, Y', strtotime($sub['subscribed_at'])) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </section>

      <section class="admin-panel" id="panel-messages">
        <p class="eyebrow">Inbox</p>
        <h2>Contact messages (<?= count($messages) ?>)</h2>

        <?php if (empty($messages)): ?>
          <p class="admin-empty-note">No messages yet.</p>
        <?php else: ?>
          <?php foreach ($messages as $msg): ?>
            <article class="admin-message-card">
              <div class="admin-message-header">
                <strong><?= htmlspecialchars($msg['name']) ?></strong>
                <span><?= htmlspecialchars($msg['email']) ?></span>
                <span class="admin-message-date"><?= date('M j, Y g:ia', strtotime($msg['created_at'])) ?></span>
              </div>
              <p class="admin-message-subject"><?= htmlspecialchars(ucfirst($msg['subject'])) ?></p>
              <p class="admin-message-body"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </main>
  </div>

  <script src="../../scripts/admin.js"></script>
</body>
</html>