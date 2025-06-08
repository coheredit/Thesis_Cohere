document.addEventListener("DOMContentLoaded", function () {
    // Initialize Bootstrap modals
    const packageModal = new bootstrap.Modal(document.getElementById("packageModal"));
    const addPackageModal = new bootstrap.Modal(document.getElementById("addPackageModal"));
    const editPackageModal = new bootstrap.Modal(document.getElementById("editPackageModal"));
    const successModal = new bootstrap.Modal(document.getElementById("successModal"));

    // Package data with complete information
    const packages = {
        "Baptism Package": {
            name: "Baptism Package",
            description: "A beautiful celebration for your child's baptism with elegant decorations and professional coordination to make this sacred moment memorable.",
            price: "15000.00",
            images: ["/images/baptismal1.jpg", "/images/baptismal2.jpg", "/images/baptismal3.jpg"],
            inclusions: [
                "Professional event coordination",
                "Elegant baptismal decorations",
                "Photography coverage (4 hours)",
                "Sound system setup",
                "Catering service for 50 guests"
            ]
        },
        "Debut Package": {
            name: "Debut Package",
            description: "Make her 18th birthday unforgettable with our comprehensive debut package featuring glamorous decorations and professional services.",
            price: "25000.00",
            images: ["/images/debut1.jpg", "/images/debut2.jpg", "/images/debut3.jpg"],
            inclusions: [
                "Professional event coordination",
                "Glamorous debut decorations",
                "Photography and videography (8 hours)",
                "DJ and sound system",
                "Catering service for 100 guests",
                "18 roses ceremony setup"
            ]
        },
        "Wedding Package": {
            name: "Wedding Package",
            description: "Make your dream wedding come true with our premium wedding package that includes everything you need for your perfect day.",
            price: "50000.00",
            images: ["/images/wedding1.jpg", "/images/wedding2.jpg", "/images/wedding3.jpg"],
            inclusions: [
                "Full wedding coordination",
                "Bridal car decoration",
                "Church/venue decorations",
                "Photography and videography (full day)",
                "Reception setup and catering",
                "Wedding cake and flowers"
            ]
        },
        "Kiddie Package": {
            name: "Kiddie Package",
            description: "Fun-filled birthday celebration for kids with colorful decorations, entertainment, and delicious treats that will make their special day magical.",
            price: "12000.00",
            images: ["/images/kiddie1.jpg", "/images/kiddie2.jpg", "/images/kiddie3.jpg"],
            inclusions: [
                "Colorful party decorations",
                "Clown or mascot entertainment",
                "Games and activities",
                "Photography coverage (3 hours)",
                "Party favors and giveaways",
                "Kiddie meal catering for 30 children"
            ]
        }
    };

    // Helper functions
    function formatPrice(price) {
        return `₱${parseFloat(price).toLocaleString("en-PH", { minimumFractionDigits: 2 })}`;
    }

    function generatePackageId() {
        return 'package_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
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

    // Modal functions
    function openPackageModal(packageName) {
        const data = packages[packageName];
        if (!data) return;

        // Update modal content
        document.getElementById("modalTitle").textContent = data.name;
        document.getElementById("modalDescription").textContent = data.description;
        document.getElementById("modalPrice").textContent = formatPrice(data.price);
        
        // Update images
        document.getElementById("modalMainImage").src = data.images[0];
        document.getElementById("modalImg1").src = data.images[0];
        document.getElementById("modalImg2").src = data.images[1];
        document.getElementById("modalImg3").src = data.images[2];
        
        // Update inclusions
        const inclusionsList = document.getElementById("modalInclusions");
        inclusionsList.innerHTML = "";
        data.inclusions.forEach(inclusion => {
            const li = document.createElement("li");
            li.textContent = inclusion;
            inclusionsList.appendChild(li);
        });

        packageModal.show();
    }

    function openEditPackageModal(packageName) {
        const data = packages[packageName];
        if (!data) return;

        document.getElementById("editPackageId").value = packageName;
        document.getElementById("editPackageName").value = data.name;
        document.getElementById("editPackageDescription").value = data.description;
        document.getElementById("editPackagePrice").value = data.price;
        document.getElementById("currentMainImage").src = data.images[0];

        editPackageModal.show();
    }

    function createPackageCard(data) {
        const wrapper = document.createElement("div");
        wrapper.className = "package-card";
        wrapper.dataset.package = data.name;

        wrapper.innerHTML = `
            <img src="${data.images[0]}" alt="${data.name}">
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

        // View package
        card.querySelector(".view-package")?.addEventListener("click", () => {
            openPackageModal(packageName);
        });

        // Edit package
        card.querySelector(".edit-btn")?.addEventListener("click", () => {
            openEditPackageModal(packageName);
        });

        // Delete package
        card.querySelector(".delete-btn")?.addEventListener("click", () => {
            if (confirm(`Are you sure you want to delete "${packageName}"?`)) {
                card.remove();
                delete packages[packageName];
                showSuccessModal(`"${packageName}" has been deleted successfully!`);
            }
        });
    }

    // Form handlers
    function handleAddPackage(event) {
        event.preventDefault();
        
        const name = document.getElementById("newPackageName").value.trim();
        const description = document.getElementById("newPackageDescription").value.trim();
        const price = document.getElementById("newPackagePrice").value;
        const imageFile = document.getElementById("newPackageImage").files[0];

        if (!name || !price) {
            alert("Please fill in all required fields.");
            return;
        }

        if (packages[name]) {
            alert("A package with this name already exists.");
            return;
        }

        // Create default image URL (in real app, you'd handle file upload)
        const defaultImage = "/images/default_package.jpg";
        const imageUrl = imageFile ? URL.createObjectURL(imageFile) : defaultImage;

        const newPackage = {
            name: name,
            description: description || "No description provided.",
            price: price,
            images: [imageUrl, imageUrl, imageUrl],
            inclusions: [
                "Professional event coordination",
                "Basic decorations",
                "Photography coverage",
                "Sound system setup"
            ]
        };

        // Add to packages object
        packages[name] = newPackage;

        // Create and add card to container
        const newCard = createPackageCard(newPackage);
        document.getElementById("packagesContainer").appendChild(newCard);

        // Reset form and close modal
        document.getElementById("addPackageForm").reset();
        addPackageModal.hide();
        
        showSuccessModal(`"${name}" has been added successfully!`);
    }

    function handleEditPackage(event) {
        event.preventDefault();
        
        const originalName = document.getElementById("editPackageId").value;
        const newName = document.getElementById("editPackageName").value.trim();
        const description = document.getElementById("editPackageDescription").value.trim();
        const price = document.getElementById("editPackagePrice").value;

        if (!newName || !price) {
            alert("Please fill in all required fields.");
            return;
        }

        // Update package data
        const packageData = packages[originalName];
        if (packageData) {
            packageData.name = newName;
            packageData.description = description || packageData.description;
            packageData.price = price;

            // If name changed, update the packages object
            if (originalName !== newName) {
                packages[newName] = packageData;
                delete packages[originalName];
            }

            // Update the DOM
            const card = document.querySelector(`[data-package="${originalName}"]`);
            if (card) {
                card.dataset.package = newName;
                card.querySelector("h3").textContent = newName;
                updatePackageDetails();
            }
        }

        editPackageModal.hide();
        showSuccessModal(`Package has been updated successfully!`);
    }

    // Thumbnail click handlers for modal
    function setupThumbnailHandlers() {
        document.getElementById("modalImg1").addEventListener("click", function() {
            document.getElementById("modalMainImage").src = this.src;
        });
        
        document.getElementById("modalImg2").addEventListener("click", function() {
            document.getElementById("modalMainImage").src = this.src;
        });
        
        document.getElementById("modalImg3").addEventListener("click", function() {
            document.getElementById("modalMainImage").src = this.src;
        });
    }

    // Event listeners
    document.getElementById("addPackageForm").addEventListener("submit", handleAddPackage);
    document.getElementById("editPackageForm").addEventListener("submit", handleEditPackage);

    // Initialize existing package cards
    document.querySelectorAll(".package-card").forEach(card => {
        attachPackageEventListeners(card);
    });

    // Update package details on page load
    updatePackageDetails();
    
    // Setup thumbnail handlers
    setupThumbnailHandlers();

    // Add some error handling for missing images
    document.querySelectorAll("img").forEach(img => {
        img.addEventListener("error", function() {
            this.src = "/images/default_package.jpg";
        });
    });

    console.log("Package management system loaded successfully.");
});