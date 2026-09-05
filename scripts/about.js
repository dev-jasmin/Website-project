document.addEventListener("DOMContentLoaded", () => {
  const revealItems = document.querySelectorAll(".reveal");
  const triggers = document.querySelectorAll(".accordion-trigger");

  const showImmediately = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (showImmediately || !("IntersectionObserver" in window)) {
    revealItems.forEach((item) => item.classList.add("is-visible"));
  } else {
    const observer = new IntersectionObserver(
      (entries, currentObserver) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            currentObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.14, rootMargin: "0px 0px -40px" }
    );

    revealItems.forEach((item) => observer.observe(item));
  }

  triggers.forEach((trigger) => {
    trigger.addEventListener("click", () => {
      const isOpen = trigger.getAttribute("aria-expanded") === "true";
      const panel = document.getElementById(trigger.getAttribute("aria-controls"));
      const symbol = trigger.querySelector(".plus");

      trigger.setAttribute("aria-expanded", String(!isOpen));
      panel.hidden = isOpen;
      symbol.textContent = isOpen ? "+" : "−";
    });
  });
});
