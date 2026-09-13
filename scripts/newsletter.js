document.addEventListener("DOMContentLoaded", () => {
  const basePath = window.emberNewsletterBasePath || "";

  document.querySelectorAll(".subscribe-form").forEach((form) => {
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      const input = form.querySelector('input[type="email"]');
      const button = form.querySelector('button[type="submit"]');
      const successMessage = form.parentElement.querySelector(".success-message");

      if (!input || !input.value) return;

      const originalText = button.textContent;
      button.disabled = true;
      button.textContent = "Submitting…";

      fetch(`${basePath}newsletter/newsletter-subscribe.php`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `email=${encodeURIComponent(input.value)}`,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            input.value = "";
            if (successMessage) {
              successMessage.hidden = false;
            } else {
              button.textContent = "Subscribed ✓";
              setTimeout(() => { button.textContent = originalText; }, 1500);
            }
          }
        })
        .catch(() => {})
        .finally(() => {
          button.disabled = false;
          if (successMessage) button.textContent = originalText;
        });
    });
  });
});