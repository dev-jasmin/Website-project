<div class="modal-overlay" id="productModalOverlay" hidden>
  <div class="auth-card product-modal-card" role="dialog" aria-modal="true" aria-labelledby="productModalTitle">
    <button class="modal-close" id="productModalClose" type="button" aria-label="Close">&times;</button>

    <div class="product-modal-body">
      <div class="product-modal-image">
        <img id="productModalImage" src="" alt="">
      </div>

      <div class="product-modal-info">
        <h2 id="productModalTitle"></h2>
        <strong id="productModalPrice"></strong>

        <div class="product-modal-rating">
          <span class="stars" id="productModalStars"></span>
          <span class="rating-text" id="productModalRatingText"></span>
        </div>

        <p class="product-modal-sales" id="productModalSales"></p>
        <p class="product-modal-description" id="productModalDescription"></p>

        <button class="auth-button product-modal-add" id="productModalAddButton" type="button" >
          Add to cart
        </button>
      </div>
    </div>
  </div>
</div>