document.addEventListener("DOMContentLoaded", () => {
  const buyerToggle = document.getElementById("buyer-toggle");
  const sellerToggle = document.getElementById("seller-toggle");
  const signupLink = document.getElementById("signup-link");
  const roleInput = document.getElementById("role");

  buyerToggle.addEventListener("click", () => {
    buyerToggle.classList.add("active");
    sellerToggle.classList.remove("active");
    signupLink.href = "/PABW/Proyek/EXPO/PHP/PEMBELI/signupPembeli.php";
    roleInput.value = "buyer";
  });

  sellerToggle.addEventListener("click", () => {
    sellerToggle.classList.add("active");
    buyerToggle.classList.remove("active");
    signupLink.href = "/PABW/Proyek/EXPO/PHP/PENJUAL/signupPenjual.php";
    roleInput.value = "seller";
  });
});
