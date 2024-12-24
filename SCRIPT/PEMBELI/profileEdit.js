document.addEventListener("DOMContentLoaded", function () {
  const photoUpload = document.querySelector(".edit-icon");
  const inputFile = document.querySelector("#foto_profile");

  photoUpload.addEventListener("click", function () {
    inputFile.click();
  });

  inputFile.addEventListener("change", function () {
    const file = inputFile.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const profile = document.querySelector(".profile-pic");
        profile.innerHTML = ""; // Menghapus placeholder
        const img = document.createElement("img");
        img.src = e.target.result;
        img.alt = "Uploaded Image";
        img.style.maxWidth = "100%";
        profile.appendChild(img);
      };
      reader.readAsDataURL(file);
    }
  });
});
