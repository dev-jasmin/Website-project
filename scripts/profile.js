document.addEventListener("DOMContentLoaded", () => {
  const avatarInput = document.getElementById("avatarInput");
  const avatarImg = document.getElementById("profileAvatar");
  if (!avatarInput || !avatarImg) return;

  avatarInput.addEventListener("change", () => {
    const file = avatarInput.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("profile_picture", file);

    fetch("profile-picture-upload.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          avatarImg.src = data.path + "?t=" + Date.now(); // cache-bust so the new image shows immediately
        } else {
          alert("Couldn't update your photo. Please try a JPG, PNG, or WEBP under 3MB.");
        }
      })
      .catch(() => {
        alert("Something went wrong uploading your photo.");
      });
  });
});