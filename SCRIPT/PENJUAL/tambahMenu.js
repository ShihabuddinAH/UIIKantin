document.addEventListener("DOMContentLoaded", function () {
  const photoUpload = document.querySelector(".photo-upload");
  const inputFile = document.querySelector("#gambar_menu");

  // Event listener untuk klik pada photoUpload
  photoUpload.addEventListener("click", function () {
    inputFile.click(); // Membuka dialog file
  });

  // Menangani ketika file dipilih
  inputFile.addEventListener("change", function () {
    const file = inputFile.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        // Menampilkan gambar yang dipilih di dalam photo-placeholder
        const img = document.createElement("img");
        img.src = e.target.result;
        img.alt = "Uploaded Image";
        img.style.maxWidth = "100%";
        const placeholder = photoUpload.querySelector(".photo-placeholder");
        placeholder.innerHTML = ""; // Menghapus placeholder
        placeholder.appendChild(img);
      };
      reader.readAsDataURL(file);
    }
  });
});
