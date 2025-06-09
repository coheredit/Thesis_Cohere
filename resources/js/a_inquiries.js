document.addEventListener("DOMContentLoaded", function () {
  applyStatusColors();
  attachEventListeners();
});

function applyStatusColors() {
  document.querySelectorAll(".status-dropdown").forEach((select) => {
    updateStatusColor(select);
    select.addEventListener("change", function () {
      updateStatusColor(this);
      saveStatusToDatabase(this, this.value);
    });
  });
}

function updateStatusColor(select) {
  const value = select.value;
  select.style.color = "black";
  select.style.backgroundColor = "white";
  select.style.fontWeight = "normal";

  switch (value) {
    case "Pending":
      select.style.backgroundColor = "#ffc107";
      select.style.color = "black";
      select.style.fontWeight = "bold";
      break;
    case "In Progress":
      select.style.backgroundColor = "#17a2b8";
      select.style.color = "white";
      select.style.fontWeight = "bold";
      break;
    case "Completed":
      select.style.backgroundColor = "#28a745";
      select.style.color = "white";
      select.style.fontWeight = "bold";
      break;
    case "Cancelled":
      select.style.backgroundColor = "#dc3545";
      select.style.color = "white";
      select.style.fontWeight = "bold";
      break;
    default:
      select.style.backgroundColor = "#f8f9fa";
      select.style.color = "#6c757d";
      break;
  }
}

function attachEventListeners() {
  document.querySelectorAll(".reply-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const index = this.getAttribute("data-index");
      alert("Replying to inquiry #" + (parseInt(index) + 1));
    });
  });

  document.querySelectorAll(".undo-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const inquiryId = this.getAttribute("data-inquiry-id");
      if (confirm("Are you sure you want to undo this reservation?")) {
        fetch("undo_reservation.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ inquiry_id: inquiryId }),
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              alert("Undo successful. Please refresh the page.");
              location.reload();
            } else {
              alert("Undo failed: " + data.message);
            }
          })
          .catch((error) => {
            console.error("Undo error:", error);
            alert("Something went wrong.");
          });
      }
    });
  });
}

function saveStatusToDatabase(selectElement, newStatus) {
  const inquiryId = selectElement.closest("tr").getAttribute("data-inquiry-id");

  fetch(`/admin/inquiries/${inquiryId}/update-status`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
      status: newStatus
    }),
  })
    .then(async (response) => {
      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`Server error ${response.status}: ${errorText}`);
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        console.log("Status updated successfully.");
      } else {
        console.error("Failed to update status:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error.message);
    });
}

