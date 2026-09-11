document.addEventListener("DOMContentLoaded", () => {
  const track = document.getElementById("topSellersTrack");
  const nextButton = document.getElementById("topSellersNext");
  if (!track || !nextButton) return;

  nextButton.addEventListener("click", (event) => {
    const alreadyShifted = track.classList.contains("is-shifted");

    if (alreadyShifted) return;

    event.preventDefault();
    track.classList.add("is-shifted");
  });
});