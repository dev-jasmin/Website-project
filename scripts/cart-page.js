document.addEventListener("DOMContentLoaded", () => {
  const cartItems = document.getElementById("cartItems");
  const subtotalEl = document.getElementById("cartSubtotal");
  if (!cartItems) return;

  function recalcSubtotal() {
    let subtotal = 0;
    cartItems.querySelectorAll(".cart-row").forEach((row) => {
      const price = parseFloat(row.dataset.price);
      const qty = parseInt(row.querySelector(".qty-value").textContent, 10);
      const rowTotal = price * qty;
      row.querySelector(".row-total").textContent = rowTotal.toLocaleString();
      subtotal += rowTotal;
    });
    if (subtotalEl) subtotalEl.textContent = subtotal.toLocaleString();

    if (cartItems.children.length === 0) {
      location.reload(); // shows the "empty cart" state
    }
  }

  function sendUpdate(cartItemId, action) {
    return fetch("cart-update.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `cart_item_id=${encodeURIComponent(cartItemId)}&action=${encodeURIComponent(action)}`,
    }).then((res) => res.json());
  }

  cartItems.addEventListener("click", (event) => {
    const row = event.target.closest(".cart-row");
    if (!row) return;
    const cartItemId = row.dataset.cartItemId;

    if (event.target.matches(".qty-button")) {
      const action = event.target.dataset.action;
      sendUpdate(cartItemId, action).then((data) => {
        if (data.removed) {
          row.remove();
        } else if (data.success) {
          row.querySelector(".qty-value").textContent = data.quantity;
        }
        recalcSubtotal();
      });
    }

    if (event.target.matches(".remove-button")) {
      sendUpdate(cartItemId, "remove").then((data) => {
        if (data.success) {
          row.remove();
          recalcSubtotal();
        }
      });
    }
  });
});