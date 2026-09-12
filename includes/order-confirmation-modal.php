<?php
$orderId = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
$paymentMethod = $_GET['payment_method'] ?? 'cod';
$orderTotal = isset($_GET['total']) ? (float) $_GET['total'] : 0;
$showOrderModal = $orderId > 0;
$assetBasePath = $assetBasePath ?? '';
?>

<div class="modal-overlay" id="orderModalOverlay" hidden>
  <div class="auth-card order-confirmation-card" role="dialog" aria-modal="true" aria-labelledby="orderModalTitle">
    <button class="modal-close" id="orderModalClose" type="button" aria-label="Close">&times;</button>

    <div class="logo">
      <img src="<?= $assetBasePath ?>/images/logo/logo-cream white.png" alt="Ember">
    </div>

    <h2 id="orderModalTitle">Order placed</h2>

    <p class="order-confirmation-message">
      Your order <strong>#<?= $orderId ?></strong> has been placed and will arrive in 3–5 business days.
      <?php if ($paymentMethod === 'cod'): ?>
        Please have &#8369;<?= number_format($orderTotal) ?> ready for cash on delivery.
      <?php else: ?>
        Please send &#8369;<?= number_format($orderTotal) ?> via <?= strtoupper($paymentMethod) ?> to
        <strong>0917-123-4567</strong> if you haven't already, we'll confirm your payment before shipping.
      <?php endif; ?>
    </p>

    <button class="auth-button" id="orderModalContinue" type="button">Continue shopping</button>
  </div>
</div>

<?php if ($showOrderModal): ?>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.getElementById("orderModalOverlay");
    const closeBtn = document.getElementById("orderModalClose");
    const continueBtn = document.getElementById("orderModalContinue");

    function closeOrderModal() {
      overlay.hidden = true;
      document.body.style.overflow = "";
      window.location.href = "<?= $assetBasePath ?>index.php";
    }

    overlay.hidden = false;
    document.body.style.overflow = "hidden";

    closeBtn.addEventListener("click", closeOrderModal);
    continueBtn.addEventListener("click", closeOrderModal);
    overlay.addEventListener("click", (event) => {
      if (event.target === overlay) closeOrderModal();
    });
  });
</script>
<?php endif; ?>