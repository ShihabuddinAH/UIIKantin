// Seleksi semua kartu produk
const cards = document.querySelectorAll(".card");

// Iterasi setiap kartu untuk menambahkan event listener pada tombol
cards.forEach((card) => {
  const minusButton = card.querySelector(".quantity button:first-child"); // Tombol "-"
  const plusButton = card.querySelector(".quantity button:last-child"); // Tombol "+"
  const quantitySpan = card.querySelector(".quantity span"); // Elemen angka

  // Event untuk tombol "-"
  minusButton.addEventListener("click", () => {
    let currentQuantity = parseInt(quantitySpan.textContent, 10); // Ambil nilai saat ini
    if (currentQuantity > 1) {
      // Pastikan nilai tidak kurang dari 1
      quantitySpan.textContent = currentQuantity - 1;
    }
  });

  // Event untuk tombol "+"
  plusButton.addEventListener("click", () => {
    let currentQuantity = parseInt(quantitySpan.textContent, 10); // Ambil nilai saat ini
    quantitySpan.textContent = currentQuantity + 1; // Tambah nilai
  });
});

// Seleksi elemen
const cartButton = document.getElementById("cart-button");
const cart = document.getElementById("cart");
const cartItems = document.getElementById("cart-items");
const addButtons = document.querySelectorAll(".add-button");
