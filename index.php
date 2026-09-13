<?php 
session_start(); 

require __DIR__ . '/database/config.php';
$pdo = getConnection();
$topSellers = $pdo->query('SELECT * FROM product WHERE is_active = 1 ORDER BY total_orders DESC, id ASC LIMIT 10')->fetchAll();

require_once __DIR__ . '/includes/current-user.php';

$profilePicture = null;
if (isset($_SESSION['user_id'])) {
    $profilePicture = getCurrentUserAvatar($pdo, $_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ember</title>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="./styles/index.css">
  <link rel="stylesheet" href="./styles/auth.css">
  <link rel="stylesheet" href="./styles/auth-modal.css">
</head>
<body>
    <main class="homepage">
      <header class="homepage-header">
        <img src="images/logo/logo-cream white.png" alt="">

        <nav class="main-nav">
          <a class="active" href="#top">Home</a>
          <a href="./pages/shop.php"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Shop</a>
          <a href="./pages/best-sellers.php"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Best Sellers</a>
          <a href="./pages/about.php"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>About</a>
          <a href="./pages/contact.php"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Contact</a>
        </nav>

        <div class="header-actions">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a class="cart" href="./pages/cart/cart.php">
              <img src="images/icon/cart.png" alt="">
              <span class="cart-count" id="cartCount" hidden>0</span>
            </a>

            <a class="profile" href="./pages/profile/profile.php">
              <img src="<?= $profilePicture ? htmlspecialchars($profilePicture) : 'images/profiles/default-profile.jpg' ?>" alt="">
            </a>
          <?php else: ?>
            <button class="login-button" type="button" data-open-auth="login">
              Login / Register
            </button>
          <?php endif; ?>
        </div>
      </header>

      <section class="hero">
        <div class="hero-text">
          <h1>Find<br />Your <em>Warmth</em></h1>
          <a class="button button-champagne" href="#bestsellers" <?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Shop now</a>
        </div>

        <div class="hero-art">
          <img class="image-1" src="images/products/1-transparent.png" alt="">
          <img class = "image-2" src="images/products/11-transparent.png" alt="">
        </div>  
    </section>

    <section class="trust-strip">
      <div class="trust-item">
        <div class="trust-icon">
          <img src="images/icon/delivery.png" alt="">
        </div>
      <div>
        <strong>Free delivery</strong>
        <small>On all orders over &#8369;500</small>
      </div>
    </div>
      
      <div class="trust-item">
        <div class="trust-icon">
          <img src="images/icon/leaf.png" alt="">
        </div>
        <div>
          <strong>Premium ingredients</strong>
          <small>Made with natural extracts</small>
        </div>
      </div>
      
      <div class="trust-item">
        <div class="trust-icon">
          <img src="images/icon/check.png" alt="">
        </div>
        <div>
          <strong>Secure checkout</strong>
          <small>100% protected payments</small>
        </div>
      </div>
    </section>

    <section class="loyalty-section">
      <div class="loyalty-card">
        <p class="eyebrow">Loyalty program</p>
        <h2>Good choices<br />
          <em>deserve rewards.</em>
        </h2>
        <p>Earn points on every order and unlock exclusive perks.
        </p>
        <a class="button button-dark" href="<?php echo isset($_SESSION['user_id']) ? './pages/profile/profile.php' : '#'; ?>"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>
          Join now
        </a>
      </div>
      
      <div class="perks">
        <div class="perk">
          <div class="perk-icon">
            <img src="images/icon/star.png" alt="">
          </div>
          <strong>Earn points</strong>
          <small>For every peso spent</small>
        </div>
      
        <div class="perk">
          <div class="perk-icon">
            <img src="images/icon/gifts.png" alt="">
          </div>
          <strong>Unlock gifts</strong
          ><small>Exclusive discounts & gifts</small>
        </div>
        
        <div class="perk">
          <div class="perk-icon">
            <img src="images/icon/crown.png" alt="">
          </div>
          <strong>VIP access</strong>
          <small>Early access to new drops</small>
        </div>
      </div>
    </section>

    <section class="catalog-section" id="bestsellers">
    <div class="section-heading">
      <div>
        <h2>Top sellers</h2>
      </div>

      <a class="circle-link" id="topSellersNext" href="./pages/best-sellers.php" aria-label="See more best sellers">
        <img src="images/icon/arrow.png" alt="">
      </a>
    </div>

  <div class="track-wrapper">
    <div class="pages-track" id="topSellersTrack">

      <div class="product-grid page">
        <?php foreach (array_slice($topSellers, 0, 6) as $product): ?>
          <article class="product-card" data-id="<?= $product['id'] ?>">
            <a class="image-placeholder" href="./pages/best-sellers.php">
              <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </a>
            <div class="product-info">
              <h3><?= htmlspecialchars($product['name']) ?></h3>
              <strong>&#8369;<?= number_format($product['price']) ?></strong>
              <button class="add-button"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?> data-id="<?= $product['id'] ?>">Add to cart</button>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="product-grid page">
        <?php foreach (array_slice($topSellers, 6, 4) as $product): ?>
          <article class="product-card" data-id="<?= $product['id'] ?>">
            <a class="image-placeholder" href="./pages/best-sellers.php">
              <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </a>
            <div class="product-info">
              <h3><?= htmlspecialchars($product['name']) ?></h3>
              <strong>&#8369;<?= number_format($product['price']) ?></strong>
              <button class="add-button"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?> data-id="<?= $product['id'] ?>">Add to cart</button>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>

    <section class="offers-section">
      <article>
        <p class="eyebrow">Exclusive offer</p>
        <h2>Get 15% Off<br />Your First Order</h2>
        <button class="button button-champagne"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>
          shop now
        </button>
      </article>
      
      <div class="image-placeholder">
        <img src="images/products/1-transparent.png" alt="">
      </div>      
      
      <article>
        <p class="eyebrow">limited edition</p>
        <h2>Luxury Collection<br />Just For You</h2>
        <button class="button button-outline"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>
          discover now
        </button>
      </article>
      
      <div class="image-placeholder">
        <img src="images/products/4-transparent.png" alt="">
      </div>
    </section>

    <section class="campaign-section">
      <div class="image-placeholder">
        <img src="images/products/group-products.png" alt="">
      </div>

      <div class="campaign-copy">
        <p class="eyebrow">Find your scent</p>
        <h2>Collection<br /><em>Sale 15% Off</em></h2>
        <p>Start from Dec 25 to Dec 27</p>
        <a class="button button-outline" href="#bestsellers"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Shop now</a>
      </div>
    </section>

    <section class="about-section">
      <div class="image-placeholder">
        <img src="images/products/4.png" alt="">
      </div>
      
      <div class="about-copy">
        <p class="eyebrow">About the brand</p>
        <h2>Warmth, bottled.</h2>
        <p>Ember is a unique fragrance house built on warmth, authenticity, and timeless elegance. We create scents that speak without words designed for those who move through life with quiet confidence.
        </p>
        <a class="button button-outline" href="#contact"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Discover our story </a>
      </div>
      
      <div class="subscribe-card">
        <div class="image-placeholder">
          <img src="images/logo/logo-charcoal black.png" alt="">
        </div>

        <h4>Get more specific Deals, <br>Events and Promotions</h4>
        <form class="subscribe-form">         
        <input type="email" placeholder="Your email here" required />
        
        <button class="button button-champagne" type="submit"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Submit</button>
        </form>

        <p class="success-message" hidden>You're on the list. Keep an eye on your inbox.</p>
      </div>
    </section>

    <section class="testimonial-section">
      <div class="section-heading centered">
        <div>
          <h2>Testimonials</h2>
        </div>
      </div>
      
      <div class="testimonial-grid">
        <article>
          <div class="image-placeholder">
            <img src="images/products/6.png" alt="">
          </div>
          
          <div>
            <div class="testimonial-profile">
              <img src="./images/profiles/IMG_20251002_214606_012.jpg" alt="">
              <small>Lee Jasmin</small>
            </div>
            
            <p>“Ember doesn't just smell good, it feels amazing.”</p>
          </div>
        </article>
        
        <article>
          <div class="image-placeholder">
            <img src="images/products/2.png" alt="">
          </div>
          
          <div>
             <div class="testimonial-profile">
              <img src="./images/profiles/Jennie Fan Club - 3_100.jpg" alt="">
              <small>Kim Jennie</small>
            </div>
            
            <p>“It gave me warm scent that finally feels like me.”</p>
          </div>
        </article>
      </div>
    </section>

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
          <img src="images/logo/logo-charcoal black.png" alt="">
        </a>

        <p>Private notes, early drops,<br />and scent stories.</p>

          <form class="subscribe-form">
            <input type="email" placeholder="Your email here" required />
            
            <button class="button button-champagne" type="submit"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Submit
            </button>
          </form>
        
        <div class="socials">
          <img src="images/icon/facebook.png" alt="">
          <img src="images/icon/twitter.png" alt="">
          <img src="images/icon/instagram.png" alt="">
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
  </main>

  <?php require __DIR__ . '/includes/auth-modal.php'; ?>
  <script>window.emberNewsletterBasePath = "";</script>
  <script src="scripts/newsletter.js"></script>
  <script src="scripts/auth-modal.js"></script>
  <script src="./scripts/index.js"></script>
  <script src="./scripts/cart.js"></script>
</body>
</html>
