<?php
session_start();
require __DIR__ . '/../database/config.php';
require_once __DIR__ . '/../includes/current-user.php';

$pdo = getConnection();

$profilePicture = null;
if (isset($_SESSION['user_id'])) {
    $profilePicture = getCurrentUserAvatar($pdo, $_SESSION['user_id']);
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Ember — a fragrance house built on warmth, authenticity, and timeless elegance." />
    <title>About Ember</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../styles/about.css" />
    
  </head>
  <body>
    <header class="about-header">
      <img src="../images/logo/logo-cream white.png" alt="">
      
      <nav class="main-nav">
        <a href="/website/index.php">Home</a>
        <a href="./shop.php"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Shop</a>
        <a href="./best-sellers.php"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Best Sellers</a>
        <a class="active" href="#about">About</a>
        <a href="./contact.php"<?php if (!isset($_SESSION['user_id'])) echo ' data-open-auth="login"'; ?>>Contact</a>
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

    <main class="about" id="about">
      <section class="about-hero" aria-labelledby="about-title">
        <div class="about-hero_image reveal" style="--delay: 80ms">
          <img src="../images/products/5.png" alt="">

          <span class="image-caption">A study in warmth · 01</span>
        </div>

        <div class="about-hero_copy">
          <p class="eyebrow reveal" style="--delay: 0ms">
            <span></span> About Ember
          </p>

          <h1 id="about-title" class="reveal" style="--delay: 100ms">
            Wear what<br /><em>moves</em> you.
          </h1>

          <p class="hero-lede reveal" style="--delay: 180ms">
            We create scents for the space between who you are and who you are becoming.
          </p>

          <a class="text-link reveal" style="--delay: 260ms" href="#manifesto">
            Read our point of view 
            <span aria-hidden="true">↗</span>
          </a>
        </div>

        <div class="about-hero_rail" aria-hidden="true">
          <span>01</span>
          <i></i>
          <span>04</span>
        </div>
      </section>

      <section class="story" aria-labelledby="story-title">
        <div class="story-intro reveal">
          <p class="eyebrow">
            <span></span> 
            Our story
          </p>

          <h2 id="story-title">
            A quiet kind<br />of <em>confidence.</em>
          </h2>
        </div>

        <div class="story-body reveal">
          <p class="dropcap">
            Ember is a unisex fragrance house built on warmth, authenticity, and timeless elegance. We believe scent is a private language, felt before it is understood.
          </p>

          <p>
            Our compositions are made to live close to the skin, unfolding slowly and leaving something behind. Not a label. Not a rule. Just a trace that feels unmistakably your own.
          </p>
          
          <div class="signature" aria-label="Ember signature">Scent Your <span>Story</span>
          </div>
        </div>
      </section>

      <section class="manifesto" id="manifesto" aria-labelledby="manifesto-title">
        <div class="manifesto-visual reveal">
          <div class="orb orb-one"></div>
          <div class="orb orb-two"></div>
          <div class="manifesto-mark">
            <img src="../images/logo/logo-cream white.png" alt="">
          </div>

          <p>
            Made for every<br />version of you.
          </p>
        </div>

        <div class="manifesto-content">
          <p class="eyebrow reveal">
            <span></span>
             The Ember point of view
          </p>
          
          <h2 id="manifesto-title" class="reveal">
            No boundaries.<br /><em>Only notes.</em>
          </h2>
          
          <div class="accordion reveal">
            <button class="accordion-trigger" aria-expanded="true" aria-controls="panel-1" id="trigger-1">
              <span>01 / Scent is self-expression</span>
              <span class="plus" aria-hidden="true">−</span>
            </button>

            <div class="accordion-panel" id="panel-1" role="region" aria-labelledby="trigger-1">
              We leave the categories behind. A fragrance is not masculine or feminine, it is intimate, instinctive, and entirely yours.
            </div>

            <button class="accordion-trigger" aria-expanded="false" aria-controls="panel-2" id="trigger-2">
              <span>02 / Warmth is our signature</span>
              <span class="plus" aria-hidden="true">+</span>
            </button>

            <div class="accordion-panel" id="panel-2" role="region" aria-labelledby="trigger-2" hidden>
              Our palette is built around skin-warmed woods, glowing resins, soft florals, and the tension between light and shadow.
            </div>

            <button class="accordion-trigger" aria-expanded="false" aria-controls="panel-3" id="trigger-3">
              <span>03 / Less, but more lasting</span>
              <span class="plus" aria-hidden="true">+</span>
            </button>
            
            <div class="accordion-panel" id="panel-3" role="region" aria-labelledby="trigger-3" hidden>
              We edit with intention, choosing memorable materials and considered details over noise, fragrances that stay with you, not overtake you.
            </div>
          </div>
        </div>
      </section>

      <section class="closing" aria-label="Ember closing statement">
        <p class="eyebrow reveal">
          <span></span>
           Find your warmth
        </p>

        <h2 class="reveal">
          Leave a little<br /><em>light behind.</em>
        </h2>

        <a class="button reveal" href="./best-sellers.php">
          Explore the collection 
          <span aria-hidden="true">↗</span>
        </a>
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

    <?php require __DIR__ . '/../includes/auth-modal.php'; ?>
    <script>window.emberNewsletterBasePath = "../";</script>
    <script src="../scripts/newsletter.js"></script>
    <script src="../scripts/auth-modal.js"></script>
    <script src="../scripts/about.js"></script>
    <script src="../scripts/cart.js"></script>
  </body>
</html>
