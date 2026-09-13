<?php
session_start();
require __DIR__ . '/../database/config.php';
require_once __DIR__ . '/../includes/current-user.php';

$pdo = getConnection();
$products = $pdo->query('SELECT * FROM product WHERE is_active = 1 ORDER BY id')->fetchAll();

$profilePicture = null;
if (isset($_SESSION['user_id'])) {
    $profilePicture = getCurrentUserAvatar($pdo, $_SESSION['user_id']);
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shop Ember</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../styles/auth-modal.css" />
  <link rel="stylesheet" href="../styles/shop.css" />
</head>
<body>
  <div class="shop-page">
    <header class="shop-header">
      <img src="../images/logo/logo-cream white.png" alt="">
      
      <nav class="main-nav">
        <a href="../index.php">Home</a>
        <a class="active" href="#bestsellers">Shop</a>
        <a href="./best-sellers.php">Best Sellers</a>
        <a href="./about.php">About</a>
        <a href="./contact.php">Contact</a>
      </nav>

      <div class="header-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a class="cart" href="./cart/cart.php">
            <img src="../images/icon/cart.png" alt="">
            <span class="cart-count" id="cartCount" hidden>0</span>
          </a>
          <a class="profile" href="./profile/profile.php">
            <img src="<?= $profilePicture ? '../' . htmlspecialchars($profilePicture) : '../images/profiles/default-profile.jpg' ?>" alt="">
          </a>
        <?php else: ?>
          <button class="login-button" type="button" data-open-auth="login">
            Login / Register
          </button>
        <?php endif; ?>
      </div>
    </header>
    
<main>
  <section class="page-content">
    <div class="catalog-toolbar">
      <p>Ember fragrances · eau de parfum</p>

      <div class="filter-buttons" aria-label="Filter fragrances">
        <button class="filter-button is-selected" data-filter="all">
          All
        </button>
        
        <button class="filter-button" data-filter="floral">
          Floral
        </button>
        
        <button class="filter-button" data-filter="wood">
          Wood
        </button>
        
        <button class="filter-button" data-filter="fresh">
          Fresh
        </button>
      </div>
    </div>

      <div class="product-grid">
        <?php foreach ($products as $product): ?>
          <article class="product-card" data-id="<?= $product['id'] ?>" data-category="<?= htmlspecialchars($product['category']) ?>">
            <div class="image-placeholder" data-open-product="<?= $product['id'] ?>">
              <img src="../<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-info">
              <h3><?= htmlspecialchars($product['name']) ?></h3>
              <strong>&#8369;<?= number_format($product['price']) ?></strong>
              <button class="add-button" data-id="<?= $product['id'] ?>">Add to cart</button>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
  </section>
</main>

<footer class="site-footer">
  <div class="footer-column">
    <strong>Quick links</strong>
    <a href="./shop.php">Shop All</a>
    <a href="./best-sellers.php">Best sellers</a>
    <a href="./about.php">Our Story</a>
  </div>
  
  <div class="footer-signup">
    <a class="brand footer-brand" href="#top">
      <img src="../images/logo/logo-charcoal black.png" alt="">
    </a>

    <p>Private notes, early drops,<br />and scent stories.</p>

      <form class="subscribe-form">
        <input type="email" placeholder="Your email here" required />
        
        <button class="button button-champagne" type="submit">Submit
        </button>
      </form>
    
    <div class="socials">
      <a href="https://facebook.com/Ember Fragrance" target="_blank" rel="noopener noreferrer" aria-label="Ember on Facebook">
        <img src="../images/icon/facebook.png" alt="Facebook">
      </a>
      <a href="https://twitter.com/EmberFragrance" target="_blank" rel="noopener noreferrer" aria-label="Ember on Twitter">
        <img src="../images/icon/twitter.png" alt="Twitter">
      </a>
      <a href="https://instagram.com/EmberFragrance" target="_blank" rel="noopener noreferrer" aria-label="Ember on Instagram">
        <img src="../images/icon/instagram.png" alt="Instagram">
      </a>
    </div>
  </div> 
    
  <div class="footer-column">
    <strong>Information</strong>
    <a href="./contact.php">Contact us</a>
    <a href="./delivery.php">Delivery</a>
    <a href="./return-policy.php">Return Policy</a>
  </div>
  
  <p class="copyright">Copyright © 2026 Ember. All rights reserved.</p>
</footer>

  <?php require __DIR__ . '/../includes/product-modal.php'; ?>
  <script>window.emberImageBasePath = "../";</script>
  <script>window.emberNewsletterBasePath = "../";</script>
  <script src="../scripts/newsletter.js"></script>
  <script src="../scripts/cart.js"></script>
  <script src="../scripts/product-modal.js"></script>
  <script src="../scripts/shop.js"></script>
</body>
</html>
