document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.querySelector(".menu-toggle");
  const siteNav = document.querySelector("#site-nav");
  const form = document.querySelector("#contact-form");
  const status = document.querySelector("#form-status");

  if (menuToggle && siteNav) {
    menuToggle.addEventListener("click", () => {
      const isOpen = menuToggle.getAttribute("aria-expanded") === "true";
      menuToggle.setAttribute("aria-expanded", String(!isOpen));
      siteNav.classList.toggle("is-open", !isOpen);
    });

    siteNav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        menuToggle.setAttribute("aria-expanded", "false");
        siteNav.classList.remove("is-open");
      });
    });
  }

  const setError = (field, message) => {
    const wrapper = field.closest(".form-field");
    wrapper.classList.toggle("has-error", Boolean(message));
    wrapper.querySelector(".error").textContent = message;
  };

  form.addEventListener("submit", (event) => {
    event.preventDefault();
    status.textContent = "";

    const name = form.elements.name;
    const email = form.elements.email;
    const subject = form.elements.subject;
    const message = form.elements.message;
    let valid = true;

    if (!name.value.trim()) { setError(name, "Please enter your name."); valid = false; } else setError(name, "");

    if (!email.value.trim()) {
      setError(email, "Please enter your email."); valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
      setError(email, "Please check your email address."); valid = false;
    } else setError(email, "");

    if (!subject.value) { setError(subject, "Please choose a subject."); valid = false; } else setError(subject, "");
    if (!message.value.trim()) { setError(message, "Please write a message."); valid = false; } else setError(message, "");

    if (!valid) {
      form.querySelector(".has-error input, .has-error select, .has-error textarea")?.focus();
      return;
    }

    const submitButton = form.querySelector(".send-button");
    submitButton.disabled = true;
    submitButton.innerHTML = "Sending <span aria-hidden='true'>…</span>";

    window.setTimeout(() => {
      form.reset();
      form.querySelectorAll(".form-field").forEach((field) => field.classList.remove("has-error"));
      form.querySelectorAll(".error").forEach((error) => { error.textContent = ""; });
      submitButton.disabled = false;
      submitButton.innerHTML = "Message sent <span aria-hidden='true'>✓</span>";
      status.textContent = "Thank you — your note is on its way to us.";
    }, 650);
  });
});