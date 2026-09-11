php
<?php
require __DIR__ . '/../database/config.php';
$pdo = getConnection();
$bestSellers = $pdo->query('SELECT * FROM product WHERE is_active = 1 ORDER BY total_orders DESC, id ASC LIMIT 10')->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Best Sellers Ember</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../styles/best-sellers.css" />
</head>
<body>
  <div class="best-sellers">
    <header class="best-seller-header">
      <img src="../images/logo/logo-cream white.png" alt="">
      
      <nav class="main-nav">
        <a href="../index.php">Home</a>
        <a href="./shop.php">Shop</a>
        <a class="active" href="#bestsellers">Best Sellers</a>
        <a href="./about.php">About</a>
        <a href="./contact.php">Contact</a>
      </nav>

      <div class="header-actions">
        <a class="cart" href="#">
            <img src="../images/icon/cart.png" alt="">
        </a>

        <a class="profile" href="#">
          <img src="../images/profiles/default-profile.jpg" alt="">
        </a>
      </div>
    </header>

    <main>
      <section class="page-content">
        <div class="catalog-heading">
          <div>
            <p class="eyebrow">Top 10 fragrances</p>
            <h2>Best sellers</h2>
          </div> 
        </div>
       
        <div class="product-grid">
          <?php foreach ($bestSellers as $index => $product): ?>
            <article class="product-card bestseller-card">
              <div class="rank-badge"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></div>

              <div class="image-placeholder">
                <img src="../<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
              </div>
              <div class="product-info">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <strong>&#8369;<?= number_format($product['price']) ?></strong>
                <p class="orders"><?= number_format($product['total_orders']) ?> orders</p>
                <button class="add-button" data-id="<?= $product['id'] ?>">Add to cart</button>
              </div>
            </article>
          <?php endforeach; ?>
        </div>              
    </section>
  </main>

    <footer class="site-footer">
      <div class="footer-links"><div>
        <strong>Quick links</strong>
        <a href="#">Shop All</a>
        <a href="#">Best sellers</a>
        <a href="#">Our Story</a>
        <a href="#">New Arrivals</a>
        <a href="#">Loyalty Card</a>
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
          <img src="../images/icon/facebook.png" alt="">
          <img src="../images/icon/twitter.png" alt="">
          <img src="../images/icon/instagram.png" alt="">
        </div>
      </div> 
        
        <div>
          <strong>Information</strong>
          <a href="#">FAQs</a>
          <a href="#">Terms & Conditions</a>
          <a href="#">Delivery</a>
          <a href="#contact">Contact us</a>
          <a href="#">Return Policy</a>
        </div>
      </div>
      
      <p class="copyright">Copyright © 2026 Ember. All rights reserved.</p>
    </footer>
  </div>
  <script src="../scripts/best-sellers.js"></script>
</body>
</html>
