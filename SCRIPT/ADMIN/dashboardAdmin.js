document.addEventListener("DOMContentLoaded", function () {
  // Delete button confirmation
  const deleteButtons = document.querySelectorAll(".delete-button");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      const confirmed = confirm("Apakah Anda yakin ingin menghapus akun ini?");
      if (!confirmed) {
        event.preventDefault();
      }
    });
  });

  // Update button functionality
  const updateButtons = document.querySelectorAll(".update-button");
  updateButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();

      const row = button.closest("tr");
      const isSeller = row
        .closest("table")
        .querySelector("thead")
        .innerHTML.includes("Nama Warung");
      const cells = Array.from(row.cells).slice(0, -2);
      const username = row.querySelector('input[name="username"]').value;

      // Store original values and make cells editable
      cells.forEach((cell, index) => {
        // Skip making the image cell editable for sellers
        if (!(isSeller && index === 3)) {
          // Skip the photo cell for sellers
          cell.dataset.originalValue = cell.textContent;
          cell.contentEditable = true;
          cell.style.backgroundColor = "#fff3cd";
        }

        // If this is a seller and it's the photo cell, add file input
        if (isSeller && index === 3) {
          const originalContent = cell.innerHTML;
          cell.dataset.originalValue = originalContent;
          cell.innerHTML = `
            ${originalContent}
            <input type="file" class="edit-photo" accept="image/*" style="display: block; margin-top: 5px;">
          `;
        }
      });

      // Hide delete and update buttons
      const deleteButton = row.querySelector(".delete-button");
      const updateButton = row.querySelector(".update-button");
      deleteButton.style.display = "none";
      updateButton.style.display = "none";

      // Show save and cancel buttons
      const saveButton = row.querySelector(".save-button");
      const cancelButton = row.querySelector(".cancel-button");
      saveButton.style.display = "inline";
      cancelButton.style.display = "inline";

      // Add event listener for save button
      saveButton.addEventListener("click", async function (event) {
        event.preventDefault();

        try {
          const formData = new FormData();
          formData.append("username", username);

          if (isSeller) {
            formData.append("new_username", cells[1].textContent);
            formData.append("email", cells[2].textContent);
            formData.append("Nama_Warung", cells[0].textContent);

            const fileInput = row.querySelector(".edit-photo");
            if (fileInput && fileInput.files[0]) {
              formData.append("Gambar_Warung", fileInput.files[0]);
            }
          } else {
            formData.append("new_username", cells[0].textContent);
            formData.append("email", cells[1].textContent);
          }

          const response = await fetch("updateUser.php", {
            method: "POST",
            body: formData,
          });

          let result;
          try {
            const text = await response.text();
            console.log("Raw server response:", text); // Debug line
            result = JSON.parse(text);
          } catch (e) {
            console.error("Failed to parse server response:", e);
            throw new Error("Server response was not valid JSON");
          }

          if (result.success) {
            alert("Data berhasil diperbarui!");
            location.reload();
          } else {
            throw new Error(result.message || "Gagal memperbarui data");
          }
        } catch (error) {
          console.error("Error details:", error);
          alert("Terjadi kesalahan: " + error.message);
          restoreOriginalValues();
        }
      });

      // Add event listener for cancel button
      cancelButton.addEventListener("click", function (event) {
        event.preventDefault();
        restoreOriginalValues();
      });

      function restoreOriginalValues() {
        cells.forEach((cell) => {
          cell.innerHTML = cell.dataset.originalValue;
          cell.contentEditable = false;
          cell.style.backgroundColor = "";
        });

        deleteButton.style.display = "inline";
        updateButton.style.display = "inline";
        saveButton.style.display = "none";
        cancelButton.style.display = "none";
      }
    });
  });
});

function showForm(role) {
  document.getElementById("form_buyer").style.display = "none";
  document.getElementById("form_seller").style.display = "none";

  if (role === "buyer") {
    document.getElementById("form_buyer").style.display = "block";
  } else if (role === "seller") {
    document.getElementById("form_seller").style.display = "block";
  }
}
