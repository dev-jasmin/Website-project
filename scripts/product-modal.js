document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("productModalOverlay");
  const closeBtn = document.getElementById("productModalClose");
  if (!overlay || !closeBtn) return;

  const modalImage = document.getElementById("productModalImage");
  const modalTitle = document.getElementById("productModalTitle");
  const modalPrice = document.getElementById("productModalPrice");
  const modalStars = document.getElementById("productModalStars");
  const modalRatingText = document.getElementById("productModalRatingText");
  const modalSales = document.getElementById("productModalSales");
  const modalDescription = document.getElementById("productModalDescription");
  const modalAddButton = document.getElementById("productModalAddButton");

  function renderStars(average) {
    const fullStars = Math.round(average);
    let stars = "";
    for (let i = 1; i <= 5; i++) {
      stars += i <= fullStars ? "★" : "☆";
    }
    return stars;
  }

  function openModal(productId) {
    fetch(`${window.emberImageBasePath}pages/product-details.php?id=${encodeURIComponent(productId)}`)
      .then((res) => res.json())
      .then((data) => {
        if (!data.success) return;
        const p = data.product;

        modalImage.src = window.emberImageBasePath + p.image;
        modalImage.alt = p.name;
        modalTitle.textContent = p.name;
        modalPrice.textContent = "₱" + p.price.toLocaleString();
        modalStars.textContent = renderStars(p.average_rating);
        modalRatingText.textContent = p.review_count > 0
          ? `${p.average_rating} (${p.review_count} review${p.review_count === 1 ? "" : "s"})`
          : "No reviews yet";
        modalSales.textContent = `${p.total_orders.toLocaleString()} sold`;
        modalDescription.textContent = p.description;
        modalAddButton.dataset.id = p.id;

        overlay.hidden = false;
        document.body.style.overflow = "hidden";
      })
      .catch(() => {});
  }

  function closeModal() {
    overlay.hidden = true;
    document.body.style.overflow = "";
  }

  document.querySelectorAll("[data-open-product]").forEach((trigger) => {
    trigger.addEventListener("click", () => {
      openModal(trigger.dataset.openProduct);
    });
  });

  closeBtn.addEventListener("click", closeModal);
  overlay.addEventListener("click", (event) => {
    if (event.target === overlay) closeModal();
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !overlay.hidden) closeModal();
  });

  modalAddButton.addEventListener("click", () => {
    fetch(`${window.emberImageBasePath}pages/cart/cart-add.php`, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `product_id=${encodeURIComponent(modalAddButton.dataset.id)}`,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          modalAddButton.textContent = "Added ✓";
          if (typeof window.updateCartBadge === "function") {
            window.updateCartBadge(data.cart_count);
          }
          setTimeout(() => { modalAddButton.textContent = "Add to cart"; }, 1200);
        }
      });
  });
});