document.addEventListener("DOMContentLoaded", () => {
  const sidebarLinks = document.querySelectorAll(".sidebar-link");
  const panels = document.querySelectorAll(".admin-panel");

  sidebarLinks.forEach((link) => {
    link.addEventListener("click", () => {
      const targetPanel = link.dataset.panel;

      sidebarLinks.forEach((l) => l.classList.remove("is-active"));
      panels.forEach((p) => p.classList.remove("is-active"));

      link.classList.add("is-active");
      document.getElementById(`panel-${targetPanel}`).classList.add("is-active");
    });
  });
});