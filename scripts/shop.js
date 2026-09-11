document.addEventListener("DOMContentLoaded", () => {
  const filterButtons = document.querySelectorAll(".filter-button[data-filter]");
  const productCards = document.querySelectorAll(".product-card[data-category]");

  filterButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const selectedFilter = button.dataset.filter;

      filterButtons.forEach((filterButton) => {
        filterButton.classList.toggle("is-selected", filterButton === button);
      });

      productCards.forEach((card) => {
        const matchesFilter = selectedFilter === "all" || card.dataset.category === selectedFilter;
        card.style.display = matchesFilter ? "" : "none";
      });
    });
  });
});