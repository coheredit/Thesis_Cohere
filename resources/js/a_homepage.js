document.addEventListener("DOMContentLoaded", function () {
    // Helper: safe event assignment
    function safeAddEventListener(selector, event, handler) {
        const element = document.querySelector(selector);
        if (element) element.addEventListener(event, handler);
    }

    // Modals
    const packageModal = document.getElementById("packageModal");
    const addPackageModal = document.getElementById("addPackageModal");
    const editPackageModal = document.getElementById("editPackageModal");
    const successModal = document.getElementById("successModal");

    const packagesContainer = document.getElementById("packagesContainer");
    const addPackageBtn = document.getElementById("addPackageBtn");
    const addPackageForm = document.getElementById("addPackageForm");
    const editPackageForm = document.getElementById("editPackageForm");

    const packages = {
        "Wedding Package": {
            name: "Wedding Package",
            description: "Make your dream wedding come true",
            price: "50000.00",
            images: ["/images/wedding1.jpg", "/images/wedding2.jpg", "/images/wedding3.jpg"]
        },
        "Kiddie Package": {
            name: "Kiddie Package",
            price: "12000.00",
            images: ["/images/kiddie1.jpg", "/images/kiddie2.jpg", "/images/kiddie3.jpg"]
        },
        "Baptism Package": {
            name: "Baptism Package",
            price: "15000.00",
            images: ["/images/baptismal1.jpg", "/images/baptismal2.jpg", "/images/baptismal3.jpg"]
        },
        "Debut Package": {
            name: "Debut Package",
            price: "25000.00",
            images: ["/images/debut1.jpg", "/images/debut2.jpg", "/images/debut3.jpg"]
        }
    };

    function formatPrice(price) {
        return `₱${parseFloat(price).toLocaleString("en-PH", { minimumFractionDigits: 2 })}`;
    }

    function openPackageModal(packageName) {
        const data = packages[packageName];
        if (!data) return;

        document.getElementById("modalTitle").textContent = data.name;
        document.getElementById("modalPrice").textContent = formatPrice(data.price);
        document.getElementById("modalImg1").src = data.images[0];
        document.getElementById("modalImg2").src = data.images[1];
        document.getElementById("modalImg3").src = data.images[2];
        if (packageModal) packageModal.style.display = "flex";
    }

    function openEditPackageModal(packageName) {
        const data = packages[packageName];
        if (!data) return;

        document.getElementById("editPackageId").value = packageName;
        document.getElementById("editPackageName").value = data.name;
        document.getElementById("editPackagePrice").value = data.price;
        document.getElementById("currentMainImage").src = data.images[0];
        if (editPackageModal) editPackageModal.style.display = "flex";
    }

    function showSuccessModal(message) {
        const el = document.getElementById("successMessage");
        if (el) el.textContent = message;
        if (successModal) successModal.style.display = "flex";
    }

    function createPackageCard(data) {
        const card = document.createElement("div");
        card.className = "package-card";
        card.dataset.package = data.name;

        card.innerHTML = `
            <img src="${data.images[0]}" alt="${data.name}">
            <h3>${data.name}</h3>
            <div class="package-details">
                <p class="package-price">${formatPrice(data.price)}</p>
            </div>
            <div class="buttons">
                <button class="btn view-package">View Package</button>
                <button class="btn edit-btn">Edit</button>
                <button class="btn delete-btn">Delete</button>
            </div>
        `;
        attachPackageEventListeners(card);
        return card;
    }

    function attachPackageEventListeners(card) {
        const name = card.dataset.package;

        card.querySelector(".view-package")?.addEventListener("click", () => openPackageModal(name));
        card.querySelector(".edit-btn")?.addEventListener("click", () => openEditPackageModal(name));
        card.querySelector(".delete-btn")?.addEventListener("click", () => {
            if (confirm(`Delete "${name}"?`)) {
                card.remove();
                delete packages[name];
                showSuccessModal(`"${name}" has been deleted successfully!`);
            }
        });
    }

    // Safe event handlers
    safeAddEventListener("#addPackageBtn", "click", () => {
        if (addPackageModal) addPackageModal.style.display = "flex";
    });

    safeAddEventListener("#closeAddModal", "click", () => {
        addPackageModal.style.display = "none";
        addPackageForm?.reset();
    });

    safeAddEventListener("#closeEditModal", "click", () => {
        editPackageModal.style.display = "none";
        editPackageForm?.reset();
    });

    safeAddEventListener("#cancelAddPackage", "click", () => {
        addPackageModal.style.display = "none";
        addPackageForm?.reset();
    });

    safeAddEventListener("#cancelEditPackage", "click", () => {
        editPackageModal.style.display = "none";
        editPackageForm?.reset();
    });

    safeAddEventListener("#successOkBtn", "click", () => {
        successModal.style.display = "none";
    });

    // Global click for modal background close
    window.addEventListener("click", (e) => {
        if (e.target === packageModal) packageModal.style.display = "none";
        if (e.target === addPackageModal) {
            addPackageModal.style.display = "none";
            addPackageForm?.reset();
        }
        if (e.target === editPackageModal) {
            editPackageModal.style.display = "none";
            editPackageForm?.reset();
        }
        if (e.target === successModal) successModal.style.display = "none";
    });

    // Attach to all cards
    document.querySelectorAll(".package-card").forEach(attachPackageEventListeners);

    console.log("Package management JS loaded successfully.");
});
