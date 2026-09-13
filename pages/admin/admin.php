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

$products = $pdo->query('SELECT * FROM product ORDER BY id DESC')->fetchAll();
$statusMessage = $_GET['message'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin — Ember</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../styles/admin.css" />
</head>
<body>
  <div class="admin-page">
    <header class="admin-header">
      <a class="logo-link" href="/website/index.php">
        <img src="../../images/logo/logo-cream white.png" alt="Ember">
      </a>
      <span class="admin-badge">Admin panel</span>
      <a class="logout-link" href="/website/logout/logout.php">Log out</a>
    </header>

    <main class="page-content">
      <p class="eyebrow">Manage catalog</p>
      <h2>Products</h2>

      <?php if ($statusMessage): ?>
        <p class="admin-status"><?= htmlspecialchars($statusMessage) ?></p>
      <?php endif; ?>

      <div class="admin-layout">
        <div class="admin-add-card">
          <h3>Add new product</h3>
          <form action="product-add.php" method="post" enctype="multipart/form-data">
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
                  <button class="admin-action-button" type="submit">
                    <?= $product['is_active'] ? 'Deactivate' : 'Activate' ?>
                  </button>
                </form>

                <form action="product-delete.php" method="post" onsubmit="return confirm('Delete this product permanently? This cannot be undone.');">
                  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                  <button class="admin-action-button admin-delete-button" type="submit">Delete</button>
                </form>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </main>
  </div>
</body>
</html>