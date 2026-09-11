document.addEventListener("DOMContentLoaded", () => {
  const cartCountEl = document.getElementById("cartCount");

  function setCartCount(count) {
    if (!cartCountEl) return;
    cartCountEl.textContent = count;
    cartCountEl.hidden = count === 0;
  }
  
  const inPagesFolder = window.location.pathname.includes("/pages/");
  const cartBasePath = inPagesFolder ? "cart/" : "pages/cart/";

  fetch(cartBasePath + "cart-count.php")
    .then((res) => res.json())
    .then((data) => setCartCount(data.cart_count))
    .catch(() => {});

  document.querySelectorAll(".add-button[data-id]").forEach((button) => {
    button.addEventListener("click", (event) => {
      if (button.dataset.openAuth) return;
      event.preventDefault();
      const productId = button.dataset.id;
      const originalText = button.textContent;
      button.disabled = true;
      button.textContent = "Adding…";

      fetch(cartBasePath + "cart-add.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${encodeURIComponent(productId)}`,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            setCartCount(data.cart_count);
            button.textContent = "Added ✓";
          } else {
            button.textContent = "Try again";
          }
          setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
          }, 1200);
        })
        .catch(() => {
          button.textContent = "Error";
          setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
          }, 1200);
        });
    });
  });
});