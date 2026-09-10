<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shop Ember</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
    <div class="catalog-toolbar">
      <p>Eleven fragrances · eau de parfum</p>

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
        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/7.png" alt="">
          </div>
          
          <div class="product-info">
            <h3>Auburn</h3>
            <strong>&#8369;199</strong>
            <button class="add-button">Add to cart</button>
          </div>
        </article>

        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/9.png" alt="">
          </div>
          <div class="product-info">
            <h3>Shade</h3>
            <strong>&#8369;299</strong>
          <button class="add-button">Add to cart</button>
          </div>
        </article>

        <article class="product-card">
        <div class="image-placeholder">
            <img src="../images/products/6.png" alt="">
        </div>
        <div class="product-info">
          <h3>Bloom</h3>
          <strong>&#8369;345</strong>
        <button class="add-button">Add to cart</button>
        </div>
      </article>
      
        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/8.png" alt="">
          </div>
          <div class="product-info">
            <h3>Pablo Santo</h3>
            <strong>&#8369;299</strong>
          <button class="add-button">Add to cart</button>
          </div>
        </article>
        
        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/10.png" alt="">
          </div>
          <div class="product-info">
          <h3>Coasta Mist</h3>
          <strong>&#8369;345</strong>
          <button class="add-button">Add to cart</button>
          </div>
        </article>
        
        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/1.png" alt="">
          </div>
          <div class="product-info">
            <h3>Fleur de Peau</h3>
            <strong>&#8369;399</strong>
            <button class="add-button">Add to cart</button>
          </div>
        </article>

        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/5.png" alt="">
          </div>
          <div class="product-info">
            <h3>Aurea</h3>
            <strong>&#8369;299</strong>
            <button class="add-button">Add to cart</button>
          </div>
        </article>

        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/2.png" alt="">
          </div>
          <div class="product-info">
            <h3>Sandalwood</h3>
            <strong>&#8369;345</strong>
            <button class="add-button">Add to cart</button>
          </div>
        </article>

        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/3.png" alt="">
          </div>
          <div class="product-info">
            <h3>Ash</h3>
            <strong>&#8369;199</strong>
            <button class="add-button">Add to cart</button>
          </div>
        </article>

        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/4.png" alt="">
          </div>
          <div class="product-info">
            <h3>Cinder</h3>
            <strong>&#8369;299</strong>
            <button class="add-button">Add to cart</button>
          </div>
        </article>

        <article class="product-card">
          <div class="image-placeholder">
            <img src="../images/products/11.png" alt="">
          </div>
          <div class="product-info">
            <h3>Violette 30</h3>
            <strong>&#8369;399</strong>
            <button class="add-button">Add to cart</button>
          </div>
        </article>
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
  <script src="../scripts/shop.js"></script>
</body>
</html>
