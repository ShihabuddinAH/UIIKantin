// Fungsi untuk melakukan update
function updateQuantity(button) {
  // Mendapatkan data dari button yang diklik
  const cartId = button.dataset.cartid;
  const action = button.dataset.action;

  console.log(`Updating cart ID: ${cartId} with action: ${action}`);

  fetch("update_cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `cart_id=${cartId}&action=${action}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Update tampilan jumlah
        const quantitySpan = document.getElementById(`quantity-${cartId}`);
        const totalSpan = document.getElementById(`total-${cartId}`);

        if (quantitySpan) {
          let currentQty = parseInt(quantitySpan.textContent);
          if (action === "increase") {
            currentQty++;
          } else if (action === "decrease" && currentQty > 1) {
            currentQty--;
          }
          quantitySpan.textContent = currentQty;

          // Update total if provided in response
          if (data.newTotal) {
            totalSpan.textContent = `Rp ${numberFormat(data.newTotal)}`;
            updateOrderSummary();
          }
        }
      } else {
        alert(data.message || "Gagal mengupdate jumlah pesanan");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Terjadi kesalahan saat mengupdate pesanan");
    });
}

// Event listener untuk tombol quantity
document.querySelectorAll(".btn-qty").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    updateQuantity(this);
  });
});

// Event listener untuk tombol delete
document.querySelectorAll(".btn-delete").forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    const cartId = this.dataset.cartid;
    if (confirm("Apakah Anda yakin ingin menghapus item ini?")) {
      removeItem(cartId);
    }
  });
});

// Fungsi untuk menghapus item
function removeItem(cartId) {
  fetch("delete_cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `cart_id=${cartId}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Hapus baris dari tabel
        const row = document
          .querySelector(`[data-cartid="${cartId}"]`)
          .closest("tr");
        if (row) {
          row.remove();
          updateOrderSummary();
        }
      } else {
        alert(data.message || "Gagal menghapus item");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Terjadi kesalahan saat menghapus item");
    });
}

// Fungsi untuk format angka
function numberFormat(number) {
  return new Intl.NumberFormat("id-ID").format(number);
}

// Fungsi untuk update order summary
function updateOrderSummary() {
  // Hitung total dari semua item
  let subtotal = 0;
  document.querySelectorAll('[id^="total-"]').forEach((elem) => {
    const amount = parseInt(elem.textContent.replace(/\D/g, ""));
    subtotal += amount;
  });

  // Update tampilan
  document.getElementById("subtotal").textContent = `Rp ${numberFormat(
    subtotal
  )}`;

  // Admin fee tetap Rp 2.000
  const adminFee = 2000;

  // Update total
  const total = subtotal + adminFee;
  document.getElementById("total").textContent = `Rp ${numberFormat(total)}`;
}

document.addEventListener("DOMContentLoaded", function () {
  const checkoutButton = document.querySelector(".checkout-button");

  checkoutButton.addEventListener("click", function () {
    const confirmation = confirm(
      "Apakah Anda yakin ingin melanjutkan checkout?"
    );
    if (!confirmation) return;

    // Kirim permintaan ke server untuk memproses checkout
    fetch("../../PHP/PEMBELI/checkout.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          alert(data.message);
          location.reload(); // Refresh halaman setelah berhasil checkout
        } else {
          alert(data.message);
        }
      })
      .catch((error) => {
        console.error("Terjadi kesalahan:", error);
      });
  });
});
