document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("reviewModalOverlay");
  const closeBtn = document.getElementById("reviewModalClose");
  if (!overlay || !closeBtn) return;

  const productNameEl = document.getElementById("reviewModalProductName");
  const starButtons = document.querySelectorAll(".star-pick");
  const commentEl = document.getElementById("reviewComment");
  const statusEl = document.getElementById("reviewStatus");
  const submitButton = document.getElementById("reviewSubmitButton");

  let currentProductId = null;
  let selectedRating = 0;

  function renderStars() {
    starButtons.forEach((btn) => {
      const value = parseInt(btn.dataset.value, 10);
      btn.classList.toggle("is-selected", value <= selectedRating);
    });
  }

  function openModal(button) {
    currentProductId = button.dataset.productId;
    productNameEl.textContent = button.dataset.productName;
    selectedRating = parseInt(button.dataset.existingRating, 10) || 0;
    commentEl.value = button.dataset.existingComment || "";
    statusEl.textContent = "";
    renderStars();

    overlay.hidden = false;
    document.body.style.overflow = "hidden";
  }

  function closeModal() {
    overlay.hidden = true;
    document.body.style.overflow = "";
  }

  document.querySelectorAll(".rate-button").forEach((button) => {
    button.addEventListener("click", () => openModal(button));
  });

  starButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      selectedRating = parseInt(btn.dataset.value, 10);
      renderStars();
    });
  });

  closeBtn.addEventListener("click", closeModal);
  overlay.addEventListener("click", (event) => {
    if (event.target === overlay) closeModal();
  });

  submitButton.addEventListener("click", () => {
    if (selectedRating < 1) {
      statusEl.textContent = "Please pick a star rating first.";
      return;
    }

    submitButton.disabled = true;
    submitButton.textContent = "Submitting…";

    fetch("review-submit.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `product_id=${encodeURIComponent(currentProductId)}&rating=${selectedRating}&comment=${encodeURIComponent(commentEl.value)}`,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          statusEl.textContent = "Thanks for your review!";
          setTimeout(() => {
            closeModal();
            location.reload();
          }, 800);
        } else {
          statusEl.textContent = "Something went wrong. Please try again.";
        }
      })
      .catch(() => {
        statusEl.textContent = "Something went wrong. Please try again.";
      })
      .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = "Submit review";
      });
  });
});