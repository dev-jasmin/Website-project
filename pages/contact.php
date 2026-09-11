<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Contact Ember fragrance house." />
    <title>Contact Ember</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../styles/contact.css" />
  </head>
  <body>
    <header class="contact-header">
      <img src="../images/logo/logo-cream white.png" alt="">
      
      <nav class="main-nav">
        <a href="../index.php">Home</a>
        <a href="./shop.php">Shop</a>
        <a href="./best-sellers.php">Best Sellers</a>
        <a href="./about.php">About</a>
        <a class="active" href="#contact">Contact</a>
      </nav>

      <div class="header-actions">
        <a class="cart" href="./cart/cart.php">
          <img src="../images/icon/cart.png" alt="">
          <span class="cart-count" id="cartCount" hidden>0</span>
        </a>

        <a class="profile" href="#">
          <img src="../images/profiles/default-profile.jpg" alt="">
        </a>
      </div>
    </header>


      <main id="top">

        <section class="page-intro" aria-labelledby="contact-title">
          <p class="section-kicker">
            Ember fragrance house
          </p>

          <h1 id="contact-title">
            Contact <br>
            <span>us</span>
          </h1>

          <div class="social-row" aria-label="Social links">
            <a href="#">Instagram</a>
            <a href="#">X</a>
            <a href="#">Facebook</a>
            <a href="#">Email</a>
          </div>
        </section>

        <div class="line"></div>

        <section class="contact-main" aria-label="Get in touch and send a message">
          <div class="get-in-touch">
            <p class="eyebrow">Get in touch</p>
            <p class="support-copy">
              Have a question, want scent guidance, or planning something special?<br />We’d love to hear from you.
            </p>

            <div class="contact-list">
              <a href="mailto:hello@emberfragrance.com">
                <span class="contact-icon">✉</span>
                 @emberfragrance.com
                </a>
              <a href="tel:+8801302282813">
                <span class="contact-icon">⌕</span>
                 +94 122 5145
                </a>
              <span><span class="contact-icon">⌖</span>
               Dumaguete, Negros Oriental, Philippines</span>
            </div>
          </div>

          <div class="message-form-wrap">
            <p class="eyebrow">
              Send us a message
            </p>

            <form id="contact-form" novalidate>
              <label class="form-field">
                <span>Full name</span>
                <input type="text" name="name" placeholder="Your name" autocomplete="name" required />
                <small class="error" aria-live="polite"></small>
              </label>

              <label class="form-field">
                <span>Email address</span>
                <input type="email" name="email" placeholder="Your email" autocomplete="email" required />
                <small class="error" aria-live="polite"></small>
              </label>

              <label class="form-field">
                <span>Subject</span>
                <select name="subject" required>
                  <option value="" selected disabled>
                    How can we help?
                  </option>

                  <option value="scent">
                    Finding my scent
                  </option>

                  <option value="order">
                    Order support
                  </option>

                  <option value="press">
                    Collaboration or press
                  </option>
                  
                  <option value="other">
                    Something else
                  </option>
                </select>
                
                <small class="error" aria-live="polite"></small>
              </label>
              
              <label class="form-field">
                <span>Message</span>
                <textarea name="message" rows="5" placeholder="Write your message..." required></textarea>
                
                <small class="error" aria-live="polite"></small>
              </label>

              <button class="send-button" type="submit">
                Send message 
              </button>

              <p id="form-status" class="form-status" role="status" aria-live="polite"></p>
            </form>
          </div>
        </section>

        <div class="line"></div>

        <section class="extra-info" aria-label="Office and customer care information">
          <div>
            <p class="eyebrow">
              Our presence
            </p>

            <h2>Made with warmth,<br /><em>shared everywhere.</em>
            </h2>

            <div class="office-copy">
              <strong>Dhaka</strong>
              <br />North Road, National Highway<br />
              Dumaguete City, Negros Oriental 6200<br />Philippines
            </div>

            <div class="office-copy">
              <strong>Remote</strong>
              <br />We work locally.<br />
              Let’s connect here in Dumaguete.
            </div>
          </div>

          <div class="help-card">
            <p class="eyebrow">
              We’re here to help
            </p>
            <p>Our team typically replies within 24 hours.</p>
            <div class="help-art" aria-hidden="true">
              <img src="../images/background img/business building.jpg" alt="">
            </div>
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

    <script src="../scripts/contact.js"></script>
    <script src="../scripts/cart.js"></script>
  </body>
</html>
