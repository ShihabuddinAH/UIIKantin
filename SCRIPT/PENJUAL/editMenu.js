document.querySelector(".tambahMenu").addEventListener("click", function () {
  window.location.href = "tambahMenu.php";
});

document.addEventListener("DOMContentLoaded", function () {
  const hapusBawahButton = document.querySelector(".hapus-bawah");
  const cards = document.querySelectorAll(".card");
  const editButtons = document.querySelectorAll(".edit");
  const hapusButtons = document.querySelectorAll(".hapus");

  let hapusMode = false;

  // Fungsi toggle hapusMode
  const toggleHapusMode = () => {
    hapusMode = !hapusMode; // Toggle hapusMode state
    hapusBawahButton.textContent = hapusMode ? "Batal Hapus" : "Hapus";

    editButtons.forEach((button) => {
      button.style.display = hapusMode ? "none" : "block";
    });
    hapusButtons.forEach((button) => {
      button.style.display = hapusMode ? "block" : "none";
    });
  };

  // Event listener tombol Hapus di bawah
  hapusBawahButton.addEventListener("click", toggleHapusMode);

  // Event listener untuk tombol di dalam kartu
  cards.forEach((card) => {
    card.addEventListener("click", function (e) {
      // Pastikan hanya tombol (Edit atau Hapus) yang merespons
      if (e.target.classList.contains("edit")) {
        console.log("Edit button clicked!");
        // Tambahkan logika edit menu di sini
      } else if (e.target.classList.contains("hapus")) {
        console.log("Hapus button clicked!");
        // Tambahkan logika hapus menu di sini
      }
      e.stopPropagation(); // Mencegah klik kartu secara keseluruhan merespons
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const deleteButtons = document.querySelectorAll(".hapus");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const menuId = this.dataset.menuId; // Ambil ID menu dari atribut data
      const confirmation = confirm(
        "Apakah Anda yakin ingin menghapus menu ini?"
      );

      if (confirmation) {
        // Kirim permintaan ke server
        fetch("deleteMenu.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `ID_Menu=${menuId}`,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "success") {
              alert(data.message);
              location.reload(); // Refresh halaman untuk memperbarui daftar menu
            } else {
              alert(data.message);
            }
          })
          .catch((error) => {
            console.error("Terjadi kesalahan:", error);
          });
      }
    });
  });
});
