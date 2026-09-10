document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("authModalOverlay");
  if (!overlay) return;

  const closeBtn = document.getElementById("authModalClose");
  const tabLogin = document.getElementById("tabLogin");
  const tabSignup = document.getElementById("tabSignup");
  const loginPanel = document.getElementById("loginPanel");
  const signupPanel = document.getElementById("signupPanel");
  const returnToFields = document.querySelectorAll(".return-to-field");

  // So a failed login/register redirect sends the user back to the page
  // they were actually on, not always the homepage.
  returnToFields.forEach((field) => {
    field.value = window.location.pathname;
  });

  function openModal(panel) {
    overlay.hidden = false;
    document.body.style.overflow = "hidden";

    const showSignup = panel === "signup";
    tabSignup.classList.toggle("is-active", showSignup);
    tabLogin.classList.toggle("is-active", !showSignup);
    signupPanel.classList.toggle("hidden", !showSignup);
    loginPanel.classList.toggle("hidden", showSignup);
  }

  function closeModal() {
    overlay.hidden = true;
    document.body.style.overflow = "";
  }

  // Any element with data-open-auth="login" or data-open-auth="signup"
  // opens the modal to that tab. Add this attribute to the header's
  // login button, and to "Add to cart" buttons when the user isn't logged in.
  document.querySelectorAll("[data-open-auth]").forEach((trigger) => {
    trigger.addEventListener("click", (event) => {
      event.preventDefault();
      openModal(trigger.dataset.openAuth || "login");
    });
  });

  closeBtn.addEventListener("click", closeModal);

  overlay.addEventListener("click", (event) => {
    if (event.target === overlay) closeModal();
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !overlay.hidden) closeModal();
  });

  tabLogin.addEventListener("click", () => openModal("login"));
  tabSignup.addEventListener("click", () => openModal("signup"));

  document.querySelectorAll(".show-password").forEach((button) => {
    button.addEventListener("click", () => {
      const input = document.getElementById(button.dataset.target);
      if (!input) return;
      const isHidden = input.type === "password";
      input.type = isHidden ? "text" : "password";
      button.textContent = isHidden ? "Hide" : "Show";
    });
  });

  // If the server redirected back here after a failed login/register,
  // reopen the modal on the right tab so the error is visible.
  const params = new URLSearchParams(window.location.search);
  const authParam = params.get("auth");
  if (authParam === "login" || authParam === "signup") {
    openModal(authParam);
  }
});
