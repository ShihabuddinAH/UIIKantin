document.addEventListener("DOMContentLoaded", function () {
  const cartItemsContainer = document.getElementById("cart-items");
  const subtotalElement = document.getElementById("subtotal");
  const totalElement = document.getElementById("total");

  const adminFees = 2000; // Biaya admin tetap

  // Fetch data keranjang dari server
  fetch("http://localhost:3000/api/cart")
    .then((response) => response.json())
    .then((data) => {
      if (data.length > 0) {
        let subtotal = 0;

        data.forEach((item) => {
          // Hitung subtotal
          subtotal += item.price * item.quantity;

          // Buat elemen untuk item pesanan
          const cartItem = document.createElement("div");
          cartItem.className = "cart-item";
          cartItem.innerHTML = `
                <img src="${item.image_url || "assets/default.png"}" alt="${
            item.name
          }" class="item-img">
                <div class="item-details">
                  <h3 class="item-name">${item.name}</h3>
                  <p class="item-date">${item.date || "-"}</p>
                  <div class="item-quantity">
                    <button class="quantity-button" onclick="decreaseQuantity(${
                      item.id
                    })">-</button>
                    <span>${item.quantity}</span>
                    <button class="quantity-button" onclick="increaseQuantity(${
                      item.id
                    })">+</button>
                  </div>
                </div>
                <span class="item-price">Rp${(
                  item.price * item.quantity
                ).toLocaleString()}</span>
                <button class="delete-button" onclick="removeItem(${
                  item.id
                })">🗑️</button>
              `;
          cartItemsContainer.appendChild(cartItem);
        });

        // Update subtotal dan total
        subtotalElement.innerText = `Rp${subtotal.toLocaleString()}`;
        totalElement.innerText = `Rp${(subtotal + adminFees).toLocaleString()}`;
      } else {
        cartItemsContainer.innerHTML = "<p>Keranjang kosong.</p>";
      }
    })
    .catch((error) => {
      console.error("Error fetching cart data:", error);
      cartItemsContainer.innerHTML = "<p>Gagal memuat keranjang.</p>";
    });
});

// Placeholder untuk fungsi
function decreaseQuantity(id) {
  console.log(`Kurangi jumlah item dengan ID: ${id}`);
}

function increaseQuantity(id) {
  console.log(`Tambah jumlah item dengan ID: ${id}`);
}

function removeItem(id) {
  console.log(`Hapus item dengan ID: ${id}`);
}
