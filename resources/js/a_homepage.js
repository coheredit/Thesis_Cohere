import 'bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    const packageModal = new bootstrap.Modal(document.getElementById("packageModal"));
    const addPackageModal = new bootstrap.Modal(document.getElementById("addPackageModal"));
    const editPackageModal = new bootstrap.Modal(document.getElementById("editPackageModal"));
    const successModal = new bootstrap.Modal(document.getElementById("successModal"));

    const packages = {};

    // Fetch packages from database
    fetch("/api/packages")
        .then(res => res.json())
        .then(data => {
            data.forEach(pkg => {
                packages[pkg.name] = pkg;
                const card = createPackageCard(pkg);
                document.getElementById("packagesContainer").appendChild(card);
            });
            updatePackageDetails();
        })
        .catch(err => console.error("Error loading packages:", err));

    function formatPrice(price) {
        return `₱${parseFloat(price).toLocaleString("en-PH", { minimumFractionDigits: 2 })}`;
    }

    function showSuccessModal(message) {
        document.getElementById("successMessage").textContent = message;
        successModal.show();
    }

    function updatePackageDetails() {
        document.querySelectorAll(".package-card").forEach(card => {
            const packageName = card.dataset.package;
            const packageData = packages[packageName];
            if (packageData) {
                const detailsDiv = card.querySelector(".package-details");
                if (detailsDiv) {
                    detailsDiv.innerHTML = `
                        <p class="package-description">${packageData.description.substring(0, 80)}...</p>
                        <p class="package-price">${formatPrice(packageData.price)}</p>
                    `;
                }
            }
        });
    }

    function openPackageModal(packageName) {
        const data = packages[packageName];
        if (!data) return;

        document.getElementById("modalTitle").textContent = data.name;
        document.getElementById("modalDescription").textContent = data.description;
        document.getElementById("modalPrice").textContent = formatPrice(data.price);

        const images = data.images || ["/images/default_package.jpg"];
        document.getElementById("modalMainImage").src = images[0];
        document.getElementById("modalImg1").src = images[0];
        document.getElementById("modalImg2").src = images[1] || images[0];
        document.getElementById("modalImg3").src = images[2] || images[0];

        const inclusionsList = document.getElementById("modalInclusions");
        inclusionsList.innerHTML = "";
        if (data.inclusions) {
            data.inclusions.forEach(inclusion => {
                const li = document.createElement("li");
                li.textContent = inclusion;
                inclusionsList.appendChild(li);
            });
        }

        packageModal.show();
    }

    function createPackageCard(data) {
        const wrapper = document.createElement("div");
        wrapper.className = "package-card";
        wrapper.dataset.package = data.name;

        const image = (data.images && data.images[0]) || "/images/default_package.jpg";

        wrapper.innerHTML = `
            <img src="${image}" alt="${data.name}">
            <div class="package-content">
                <h3>${data.name}</h3>
                <div class="package-details">
                    <p class="package-description">${data.description.substring(0, 80)}...</p>
                    <p class="package-price">${formatPrice(data.price)}</p>
                </div>
                <div class="buttons-row">
                    <button class="btn btn-success btn-sm view-package">View Package</button>
                    <button class="btn btn-warning btn-sm text-white edit-btn">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                </div>
            </div>
        `;

        attachPackageEventListeners(wrapper);
        return wrapper;
    }

    function attachPackageEventListeners(card) {
        const packageName = card.dataset.package;

        card.querySelector(".view-package")?.addEventListener("click", () => openPackageModal(packageName));
        card.querySelector(".edit-btn")?.addEventListener("click", () => {
            const data = packages[packageName];
            if (!data) return;

            document.getElementById("editPackageId").value = packageName;
            document.getElementById("editPackageName").value = data.name;
            document.getElementById("editPackageDescription").value = data.description;
            document.getElementById("editPackagePrice").value = data.price;
            document.getElementById("currentMainImage").src = data.images?.[0] || "/images/default_package.jpg";

            editPackageModal.show();
        });

card.querySelector(".delete-btn")?.addEventListener("click", () => {
    if (!confirm(`Are you sure you want to delete "${packageName}"?`)) return;

    fetch(`/admin/packages/${encodeURIComponent(packageName)}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            'Accept': 'application/json',
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Delete failed');
        return res.json();
    })
    .then(() => {
        card.remove();
        delete packages[packageName];
        showSuccessModal(`"${packageName}" has been deleted successfully!`);
    })
    .catch(err => {
        console.error("Delete failed:", err);
        alert("Failed to delete package.");
    });
});
    }

    // 🟢 ADD PACKAGE with DB Save
    document.getElementById("addPackageForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append("name", document.getElementById("newPackageName").value);
        formData.append("description", document.getElementById("newPackageDescription").value);
        formData.append("price", document.getElementById("newPackagePrice").value);
        formData.append("image", document.getElementById("newPackageImage").files[0]);

        fetch("/admin/packages", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: formData
        })
        .then(res => res.json())
        .then(pkg => {
            packages[pkg.name] = pkg;
            const card = createPackageCard(pkg);
            document.getElementById("packagesContainer").appendChild(card);
            document.getElementById("addPackageForm").reset();
            addPackageModal.hide();
            showSuccessModal(`"${pkg.name}" has been added!`);
        })
        .catch(err => {
            console.error("Error saving package:", err);
            alert("Failed to save package.");
        });
    });

    // Optional: Edit form submit handler
    document.getElementById("editPackageForm").addEventListener("submit", function (e) {
        e.preventDefault();
        alert("Edit functionality with DB not yet implemented.");
    });

    setupThumbnailHandlers();

    function setupThumbnailHandlers() {
        document.getElementById("modalImg1").addEventListener("click", function () {
            document.getElementById("modalMainImage").src = this.src;
        });
        document.getElementById("modalImg2").addEventListener("click", function () {
            document.getElementById("modalMainImage").src = this.src;
        });
        document.getElementById("modalImg3").addEventListener("click", function () {
            document.getElementById("modalMainImage").src = this.src;
        });
    }

    document.querySelectorAll("img").forEach(img => {
        img.addEventListener("error", function () {
            this.src = "/images/default_package.jpg";
        });
    });

    console.log("Admin Package Manager initialized.");
});
