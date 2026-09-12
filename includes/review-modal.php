<div class="modal-overlay" id="reviewModalOverlay" hidden>
  <div class="auth-card review-modal-card" role="dialog" aria-modal="true" aria-labelledby="reviewModalTitle">
    <button class="modal-close" id="reviewModalClose" type="button" aria-label="Close">&times;</button>

    <h2 id="reviewModalTitle">Rate this product</h2>
    <p class="review-modal-product-name" id="reviewModalProductName"></p>

    <div class="star-picker" id="starPicker">
      <button type="button" class="star-pick" data-value="1">★</button>
      <button type="button" class="star-pick" data-value="2">★</button>
      <button type="button" class="star-pick" data-value="3">★</button>
      <button type="button" class="star-pick" data-value="4">★</button>
      <button type="button" class="star-pick" data-value="5">★</button>
    </div>

    <textarea id="reviewComment" rows="4" placeholder="Share your thoughts (optional)"></textarea>

    <p class="review-status" id="reviewStatus"></p>

    <button class="auth-button" id="reviewSubmitButton" type="button">Submit</button>
  </div>
</div>