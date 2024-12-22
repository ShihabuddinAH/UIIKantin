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

// Tambahkan event listener ke semua tombol "Add"
/*
addButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const card = button.closest(".card");
    const title = card.querySelector(".text-content .title").textContent;
    const quantitySpan = card.querySelector(".quantity span");
    const quantity = parseInt(quantitySpan.textContent, 10);

    // Periksa apakah item sudah ada di keranjang
    let existingItem = Array.from(cartItems.children).find(
      (item) => item.dataset.title === title
    );

    if (existingItem) {
      // Jika item sudah ada, perbarui kuantitasnya
      const currentQuantity = existingItem.querySelector(".cart-item-quantity");
      currentQuantity.textContent =
        parseInt(currentQuantity.textContent, 10) + quantity;
    } else {
      // Jika item belum ada, tambahkan ke keranjang
      const newItem = document.createElement("li");
      newItem.dataset.title = title;
      newItem.innerHTML = `
     <span>${title}</span>
     <span class="cart-item-quantity">${quantity}</span>
   `;
      cartItems.appendChild(newItem);
    }

    // Pastikan tombol keranjang terlihat
    cartButton.style.display = "flex";
  });
});
*/
