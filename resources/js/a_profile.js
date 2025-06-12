console.log("a_profile.js loaded ✅");

function generateId(length = 12) {
    const chars =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let id = "";
    for (let i = 0; i < length; i++) {
        id += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return id;
}
// Admin Profile JavaScript with Enhanced History System
document.addEventListener("DOMContentLoaded", function () {
    // Get modal elements
    const editModal = document.getElementById("edit-modal");
    const passwordModal = document.getElementById("password-modal");
    const successModal = document.getElementById("success-modal");
    const confirmModal = document.getElementById("confirm-modal");
    // Note: imageSelectionModal will be created dynamically

    // Get button elements
    const editBtn = document.getElementById("edit-profile-btn");
    const logoutBtn = document.getElementById("logout-btn");
    const changePasswordLink = document.getElementById("change-password-link");
    const changePicBtn = document.getElementById("change-pic-btn");
    const profilePicInput = document.getElementById("profile-pic-input");
    const successOkBtn = document.getElementById("success-ok-btn");
    const clearHistoryBtn = document.getElementById("clear-history-btn");
    const loadMoreBtn = document.getElementById("load-more-btn");

    // Get close buttons (these may not exist in HTML, so we'll handle gracefully)
    const closeModal = document.getElementById("close-modal");
    const closePasswordModal = document.getElementById("close-password-modal");
    const cancelEdit = document.getElementById("cancel-edit");
    const cancelPassword = document.getElementById("cancel-password");
    const confirmCancel = document.getElementById("confirm-cancel");
    const confirmOk = document.getElementById("confirm-ok");

    // Get forms (these may not exist in HTML, so we'll handle gracefully)
    const editForm = document.getElementById("edit-form");
    const passwordForm = document.getElementById("password-form");

    // Profile data elements
    const adminName = document.getElementById("admin-name");
    const adminEmail = document.getElementById("admin-email");
    const adminPhone = document.getElementById("admin-phone");
    const adminUsername = document.getElementById("admin-username");
    const profilePic = document.getElementById("profile-pic");

    // Form input elements (these may not exist in HTML, so we'll handle gracefully)
    const editName = document.getElementById("edit-name");
    const editEmail = document.getElementById("edit-email");
    const editPhone = document.getElementById("edit-phone");
    const editUsername = document.getElementById("edit-username");

    // History elements
    const historyList = document.getElementById("history-list");
    const historyEmpty = document.getElementById("history-empty");
    const historyFilter = document.getElementById("history-filter");
    const totalActivitiesCount = document.getElementById("total-activities");
    const todayActivitiesCount = document.getElementById("today-activities");
    const thisWeekActivitiesCount = document.getElementById(
        "this-week-activities"
    );

    // History system variables
    let adminHistory = [];
    let displayedHistoryCount = 0;
    const ITEMS_PER_PAGE = 10;
    let currentFilter = "all";
    let confirmCallback = null;

    const availableProfilePictures = [
        { id: "boy.png", name: "Default Admin", src: "/images/boy.png" },
        { id: "boy1.png", name: "Professional", src: "/images/boy1.png" },
        { id: "boy2.png", name: "Casual", src: "/images/boy2.png" },
        { id: "girl.png", name: "Formal", src: "/images/girl.png" },
        { id: "girl1.png", name: "Modern", src: "/images/girl1.png" },
        { id: "girl2.png", name: "Classic", src: "/images/gril2.png" },
    ];

    // Initialize history system
    initializeHistory();

    // History Management Functions
    async function initializeHistory() {
        try {
            const res = await fetch(
                `/admin/activities?filter=${currentFilter}`
            );
            const data = await res.json();

            if (Array.isArray(data)) {
                adminHistory = data.map((entry) => ({
                    ...entry,
                    type: mapActivityType(entry.activity_type),
                    action: entry.activity_type,
                    details: entry.description,
                    timestamp: new Date(entry.created_at),
                    date: new Date(entry.created_at).toLocaleDateString(),
                    time: new Date(entry.created_at).toLocaleTimeString(),
                }));

                displayedHistoryCount = 0;
                displayHistory();
                updateHistoryStats();
            } else {
                console.error(
                    "Error fetching admin history:",
                    data.error || data
                );
            }
        } catch (error) {
            console.error("Failed to fetch admin history", error);
        }
    }

    function mapActivityType(activityType) {
        if (!activityType) return "system";
        const type = activityType.toLowerCase();

        if (type.includes("login") || type.includes("logout")) return "login";
        if (type.includes("profile")) return "profile";
        if (type.includes("security")) return "security";
        return "system";
    }

    function formatTypeForBackend(type, action) {
        if (type === "login")
            return action.includes("logout") ? "Logout" : "Login";
        if (type === "profile") return "Profile Change";
        if (type === "security") return "Security";
        return "System Action";
    }

    function addToHistory(type, action, details = "") {
        const historyEntry = {
            id: generateId(),
            type: type, // login, profile, system, security
            action: action,
            details: details,
            timestamp: new Date(),
            date: new Date().toLocaleDateString(),
            time: new Date().toLocaleTimeString(),
        };

        adminHistory.unshift(historyEntry);

        if (adminHistory.length > 100) {
            adminHistory = adminHistory.slice(0, 100);
        }

        displayHistory();
        updateHistoryStats();

        // Persist to backend
        fetch("/admin/activities/store", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                activity_type: formatTypeForBackend(type, action),
                description: details,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (!data.success) {
                    console.error("Failed to store activity log", data);
                }
            })
            .catch((err) => {
                console.error("Error sending activity log to server", err);
            });
    }

    function filterHistory() {
        if (currentFilter === "all") {
            return adminHistory;
        }
        return adminHistory.filter((entry) => entry.type === currentFilter);
    }

    function getHistoryIcon(type) {
        const icons = {
            login: "🔐",
            profile: "👤",
            system: "⚙️",
            security: "🛡️",
        };
        return icons[type] || "📝";
    }

    function displayHistory() {
        const filteredHistory = filterHistory();
        const historyToShow = filteredHistory.slice(
            0,
            displayedHistoryCount + ITEMS_PER_PAGE
        );

        historyList.innerHTML = "";

        if (historyToShow.length === 0) {
            historyEmpty.style.display = "block";
            loadMoreBtn.style.display = "none";
            return;
        }

        historyEmpty.style.display = "none";

        historyToShow.forEach((entry) => {
            const li = document.createElement("li");
            li.innerHTML = `
                <div class="history-icon ${entry.type}">
                    ${getHistoryIcon(entry.type)}
                </div>
                <div class="history-content">
                    <div class="history-action">${entry.action}</div>
                    <div class="history-details">${entry.details}</div>
                </div>
                <div class="history-time">
                    <div>${entry.date}</div>
                    <div>${entry.time}</div>
                </div>
            `;
            historyList.appendChild(li);
        });

        displayedHistoryCount = historyToShow.length;

        if (filteredHistory.length > displayedHistoryCount) {
            loadMoreBtn.style.display = "block";
        } else {
            loadMoreBtn.style.display = "none";
        }
    }

    function updateHistoryStats() {
        const total = adminHistory.length;
        const today = adminHistory.filter((entry) => {
            const entryDate = new Date(entry.timestamp).toDateString();
            const todayDate = new Date().toDateString();
            return entryDate === todayDate;
        }).length;

        const thisWeek = adminHistory.filter((entry) => {
            const entryDate = new Date(entry.timestamp);
            const weekAgo = new Date();
            weekAgo.setDate(weekAgo.getDate() - 7);
            return entryDate >= weekAgo;
        }).length;

        if (totalActivitiesCount) totalActivitiesCount.textContent = total;
        if (todayActivitiesCount) todayActivitiesCount.textContent = today;
        if (thisWeekActivitiesCount)
            thisWeekActivitiesCount.textContent = thisWeek;
    }

    function clearHistory() {
        showConfirmModal(
            "Clear History",
            "Are you sure you want to clear all history? This action cannot be undone.",
            () => {
                adminHistory = [];
                displayedHistoryCount = 0;
                displayHistory();
                updateHistoryStats();
                showSuccessModal("History cleared successfully!");
                addToHistory(
                    "system",
                    "History cleared",
                    "All admin history entries were deleted"
                );
            }
        );
    }

    // FIXED FUNCTION: Create and show image selection modal
    function createImageSelectionModal() {
        // Check if modal already exists by looking for the actual element in DOM
        const existingModal = document.getElementById("image-selection-modal");
        if (existingModal) {
            console.log("Image selection modal already exists");
            return existingModal;
        }

        console.log("Creating new image selection modal");

        // Create modal HTML
        const modalHTML = `
            <div id="image-selection-modal" class="modal" style="display: none;">
                <div class="modal-content" style="max-width: 600px;">
                    <div class="modal-header">
                        <h3>Select Profile Picture</h3>
                        <span class="close" id="close-image-modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="image-grid" id="image-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; padding: 20px 0;">
                            ${availableProfilePictures
                                .map(
                                    (img) => `
                                <div class="image-option" data-src="${img.src}" style="text-align: center; cursor: pointer; padding: 10px; border: 2px solid transparent; border-radius: 8px; transition: all 0.3s ease;">
                                    <img src="${img.src}" alt="${img.name}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; margin-bottom: 8px;">
                                    <p style="margin: 0; font-size: 14px; color: #666;">${img.name}</p>
                                </div>
                            `
                                )
                                .join("")}
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Insert modal into document
        document.body.insertAdjacentHTML("beforeend", modalHTML);

        // Get the newly created modal elements
        const newImageModal = document.getElementById("image-selection-modal");
        const newCloseImageModal = document.getElementById("close-image-modal");
        const imageGrid = document.getElementById("image-grid");

        if (!newImageModal) {
            console.error("Failed to create image selection modal");
            return null;
        }

        console.log("Image selection modal created successfully");

        // Add event listeners for the new modal
        if (newCloseImageModal) {
            newCloseImageModal.addEventListener("click", function () {
                console.log("Close button clicked");
                newImageModal.style.display = "none";
            });
        }

        // Add click listeners to image options
        const imageOptions = imageGrid.querySelectorAll(".image-option");
        console.log(`Found ${imageOptions.length} image options`);

        imageOptions.forEach((option, index) => {
            option.addEventListener("click", function () {
                const selectedSrc = this.getAttribute("data-src");
                const selectedName = this.querySelector("p").textContent;

                console.log(
                    `Image ${
                        index + 1
                    } selected: ${selectedName} (${selectedSrc})`
                );

                // Update profile picture
                if (profilePic) {
                    profilePic.src = selectedSrc;
                    addToHistory(
                        "profile",
                        "Profile picture updated",
                        `Changed to: ${selectedName}`
                    );
                    showSuccessModal("Profile picture updated successfully!");
                } else {
                    console.error("Profile picture element not found");
                }

                // Close modal
                newImageModal.style.display = "none";
            });

            // Add hover effects
            option.addEventListener("mouseenter", function () {
                this.style.borderColor = "#007bff";
                this.style.backgroundColor = "#f8f9fa";
            });

            option.addEventListener("mouseleave", function () {
                this.style.borderColor = "transparent";
                this.style.backgroundColor = "transparent";
            });
        });

        // Close modal when clicking outside
        newImageModal.addEventListener("click", function (event) {
            if (event.target === newImageModal) {
                console.log("Modal background clicked, closing modal");
                newImageModal.style.display = "none";
            }
        });

        return newImageModal;
    }

    // FIXED LOGOUT FUNCTION
    console.log("logoutBtn:", logoutBtn); // Debug log

    if (logoutBtn) {
        logoutBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Logout button clicked!"); // Debug log

            showConfirmModal(
                "Confirm Logout",
                "Are you sure you want to logout?",
                () => {
                    console.log("Logout confirmed!"); // Debug log

                    // Add to history before logout
                    addToHistory(
                        "login",
                        "Admin logout",
                        "Admin logged out of the system"
                    );

                    // Find the form and submit it
                    const form = logoutBtn.closest("form");
                    if (form) {
                        console.log("Form found, submitting..."); // Debug log
                        form.submit();
                    } else {
                        console.error("Logout form not found!");
                        // Fallback: redirect to logout route
                        window.location.href = "/admin/logout";
                    }
                }
            );
        });
    } else {
        console.warn("logout-btn element not found in DOM");
    }

    // Event Listeners
    if (historyFilter) {
        historyFilter.addEventListener("change", function () {
            currentFilter = this.value;
            initializeHistory(); // re-fetch from server
        });
    }

    if (clearHistoryBtn) {
        clearHistoryBtn.addEventListener("click", clearHistory);
    }

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener("click", function () {
            displayHistory();
        });
    }

    // FIXED Profile Picture Change - Now opens image selection modal
    if (changePicBtn) {
        console.log("Change picture button found, adding event listener");

        changePicBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Change picture button clicked!");

            // Create the modal (it will check if it already exists)
            const imageModal = createImageSelectionModal();

            if (imageModal) {
                console.log("Showing image selection modal");
                imageModal.style.display = "block";
                addToHistory(
                    "system",
                    "Profile picture selection opened",
                    "Opened image selection modal"
                );
            } else {
                console.error("Failed to create or find image selection modal");
            }
        });
    } else {
        console.warn("Change picture button not found in DOM");
    }

    // Keep the original file input functionality as backup (hidden)
    if (profilePicInput) {
        profilePicInput.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (profilePic) {
                        profilePic.src = e.target.result;
                        addToHistory(
                            "profile",
                            "Profile picture updated",
                            "Changed admin profile picture via file upload"
                        );
                        showSuccessModal(
                            "Profile picture updated successfully!"
                        );
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Edit Profile Button Click
    if (editBtn && editModal) {
        editBtn.addEventListener("click", function () {
            // Pre-fill form with current data
            if (editName && adminName) editName.value = adminName.textContent;
            if (editEmail && adminEmail)
                editEmail.value = adminEmail.textContent;
            if (editPhone && adminPhone)
                editPhone.value = adminPhone.textContent;
            if (editUsername && adminUsername)
                editUsername.value = adminUsername.textContent;

            editModal.style.display = "block";
            addToHistory(
                "system",
                "Edit profile form opened",
                "Started editing admin profile information"
            );
        });
    }

    // Change Password Link Click
    if (changePasswordLink && passwordModal) {
        changePasswordLink.addEventListener("click", function (e) {
            e.preventDefault();
            passwordModal.style.display = "block";
            addToHistory(
                "security",
                "Password change initiated",
                "Opened password change form"
            );
        });
    }

    // Close Modal Functions - Only add listeners if elements exist
    if (closeModal && editModal) {
        closeModal.addEventListener("click", function () {
            editModal.style.display = "none";
        });
    }

    if (closePasswordModal && passwordModal) {
        closePasswordModal.addEventListener("click", function () {
            passwordModal.style.display = "none";
        });
    }

    if (cancelEdit && editModal) {
        cancelEdit.addEventListener("click", function () {
            editModal.style.display = "none";
            addToHistory(
                "system",
                "Profile edit cancelled",
                "Cancelled profile editing without saving"
            );
        });
    }

    if (cancelPassword && passwordModal) {
        cancelPassword.addEventListener("click", function () {
            passwordModal.style.display = "none";
            addToHistory(
                "security",
                "Password change cancelled",
                "Cancelled password change without saving"
            );
        });
    }

    if (successOkBtn && successModal) {
        successOkBtn.addEventListener("click", function () {
            successModal.style.display = "none";
        });
    }

    if (confirmCancel && confirmModal) {
        confirmCancel.addEventListener("click", function () {
            confirmModal.style.display = "none";
            confirmCallback = null;
        });
    }

    if (confirmOk && confirmModal) {
        confirmOk.addEventListener("click", function () {
            if (confirmCallback) {
                confirmCallback();
            }
            confirmModal.style.display = "none";
            confirmCallback = null;
        });
    }

    // Close modal when clicking outside
    window.addEventListener("click", function (event) {
        if (editModal && event.target === editModal) {
            editModal.style.display = "none";
        }
        if (passwordModal && event.target === passwordModal) {
            passwordModal.style.display = "none";
        }
        if (successModal && event.target === successModal) {
            successModal.style.display = "none";
        }
        if (confirmModal && event.target === confirmModal) {
            confirmModal.style.display = "none";
            confirmCallback = null;
        }

        // Handle dynamically created image selection modal
        const imageModal = document.getElementById("image-selection-modal");
        if (imageModal && event.target === imageModal) {
            imageModal.style.display = "none";
        }
    });

    // Edit Profile Form Submit
    if (editForm) {
        editForm.addEventListener("submit", function (e) {
            e.preventDefault();

            // Store old values for comparison
            const oldName = adminName ? adminName.textContent : "";
            const oldEmail = adminEmail ? adminEmail.textContent : "";
            const oldPhone = adminPhone ? adminPhone.textContent : "";
            const oldUsername = adminUsername ? adminUsername.textContent : "";

            // Update profile information
            if (adminName && editName) adminName.textContent = editName.value;
            if (adminEmail && editEmail)
                adminEmail.textContent = editEmail.value;
            if (adminPhone && editPhone)
                adminPhone.textContent = editPhone.value;
            if (adminUsername && editUsername)
                adminUsername.textContent = editUsername.value;

            // Close modal
            if (editModal) editModal.style.display = "none";

            // Track specific changes
            let changes = [];
            let changeDetails = [];

            if (editName && oldName !== editName.value) {
                changes.push("name");
                changeDetails.push(`Name: "${oldName}" → "${editName.value}"`);
            }
            if (editEmail && oldEmail !== editEmail.value) {
                changes.push("email");
                changeDetails.push(
                    `Email: "${oldEmail}" → "${editEmail.value}"`
                );
            }
            if (editPhone && oldPhone !== editPhone.value) {
                changes.push("phone");
                changeDetails.push(
                    `Phone: "${oldPhone}" → "${editPhone.value}"`
                );
            }
            if (editUsername && oldUsername !== editUsername.value) {
                changes.push("username");
                changeDetails.push(
                    `Username: "${oldUsername}" → "${editUsername.value}"`
                );
            }

            // Add specific activity
            if (changes.length > 0) {
                const changeText = `Profile updated: ${changes.join(", ")}`;
                const detailText = changeDetails.join("; ");
                addToHistory("profile", changeText, detailText);
            } else {
                addToHistory(
                    "profile",
                    "Profile form submitted",
                    "No changes were made to profile information"
                );
            }

            // Send to backend
            fetch("/admin/profile/update", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    name: editName.value,
                    email: editEmail.value,
                    phone: editPhone.value,
                    username: editUsername.value,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        showSuccessModal(data.message);
                    } else {
                        showErrorMessage(
                            data.message || "Update failed",
                            editModal
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error updating profile:", error);
                    showErrorMessage(
                        "An error occurred while updating profile.",
                        editModal
                    );
                });
        });
    }

    // Change Password Form Submit - FIXED VERSION
    if (passwordForm) {
        passwordForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const currentPassword = document.getElementById("current-password");
            const newPassword = document.getElementById("new-password");
            const confirmPassword = document.getElementById("confirm-password");

            if (!currentPassword || !newPassword || !confirmPassword) {
                console.error("Password form elements not found");
                return;
            }

            // Basic validation
            if (newPassword.value !== confirmPassword.value) {
                showErrorMessage("New passwords do not match!", passwordModal);
                addToHistory(
                    "security",
                    "Password change failed",
                    "Password confirmation mismatch"
                );
                return;
            }

            if (newPassword.value.length < 6) {
                showErrorMessage(
                    "Password must be at least 6 characters long!",
                    passwordModal
                );
                addToHistory(
                    "security",
                    "Password change failed",
                    "Password too short (minimum 6 characters)"
                );
                return;
            }

            if (currentPassword.value === "") {
                showErrorMessage(
                    "Please enter your current password!",
                    passwordModal
                );
                addToHistory(
                    "security",
                    "Password change failed",
                    "Current password not provided"
                );
                return;
            }

            // Show loading state (optional)
            const submitBtn = passwordForm.querySelector(
                'button[type="submit"]'
            );
            const originalText = submitBtn.textContent;
            submitBtn.textContent = "Changing...";
            submitBtn.disabled = true;

            // Send request to backend - FIXED URL
            fetch("/admin/profile/change-password", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    current_password: currentPassword.value,
                    new_password: newPassword.value,
                    new_password_confirmation: confirmPassword.value,
                }),
            })
                .then((response) => {
                    // Reset button state
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;

                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        // Close modal and clear form
                        if (passwordModal) passwordModal.style.display = "none";
                        passwordForm.reset();

                        showSuccessModal(data.message);
                        addToHistory(
                            "security",
                            "Password changed successfully",
                            "Admin account password updated"
                        );
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            let errorMessage = "";
                            Object.values(data.errors).forEach((errors) => {
                                errorMessage += errors.join(", ") + " ";
                            });
                            showErrorMessage(
                                errorMessage.trim(),
                                passwordModal
                            );
                        } else {
                            showErrorMessage(
                                data.message || "Password change failed.",
                                passwordModal
                            );
                        }

                        addToHistory(
                            "security",
                            "Password change failed",
                            data.message || "Unknown error"
                        );
                    }
                })
                .catch((err) => {
                    // Reset button state
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;

                    console.error("Password update error", err);
                    showErrorMessage(
                        "An error occurred while updating password.",
                        passwordModal
                    );
                    addToHistory(
                        "security",
                        "Password change error",
                        "Network or server error occurred"
                    );
                });
        });
    }

    // Helper function to show success modal
    function showSuccessModal(message) {
        const successMessageText = document.getElementById(
            "success-message-text"
        );
        if (successMessageText) {
            successMessageText.textContent = message;
        }
        if (successModal) {
            successModal.style.display = "block";
        }
    }

    // Helper function to show confirm modal
    function showConfirmModal(title, message, callback) {
        if (!confirmModal) {
            console.error("Confirm modal not found");
            return;
        }

        const confirmTitle = confirmModal.querySelector("h3");
        const confirmMessage = document.getElementById("confirm-message");

        if (confirmTitle) confirmTitle.textContent = title;
        if (confirmMessage) confirmMessage.textContent = message;

        confirmCallback = callback;
        confirmModal.style.display = "block";
    }

    // Helper function to show error message
    function showErrorMessage(message, modal) {
        if (!modal) return;

        // Remove existing error messages
        const existingError = modal.querySelector(".error-message");
        if (existingError) {
            existingError.remove();
        }

        // Create error message element
        const errorDiv = document.createElement("div");
        errorDiv.className = "error-message";
        errorDiv.textContent = message;
        errorDiv.style.cssText = `
            color: #dc3545;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 8px 12px;
            border-radius: 4px;
            margin: 10px 0;
            display: block;
        `;

        const modalContent = modal.querySelector(".modal-content");
        const header = modalContent.querySelector("h3");

        if (modalContent) {
            if (header) {
                header.insertAdjacentElement("afterend", errorDiv);
            } else {
                modalContent.appendChild(errorDiv);
            }
        }

        setTimeout(function () {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }

    // Phone number formatting - Fixed to allow editing of all digits
    const phoneInput = document.getElementById("edit-phone");
    if (phoneInput) {
        phoneInput.addEventListener("input", function () {
            let value = this.value.replace(/\D/g, ""); // Remove non-digits

            // Limit to 11 digits (like 09123456789)
            if (value.length > 11) {
                value = value.substring(0, 11);
            }

            // Format based on length
            if (value.length >= 4 && value.length <= 7) {
                value = value.replace(/(\d{4})(\d{0,3})/, "$1-$2");
            } else if (value.length > 7) {
                value = value.replace(/(\d{4})(\d{3})(\d{0,4})/, "$1-$2-$3");
            }

            this.value = value;
        });
    }

    // Form validation for real-time feedback
    const inputs = document.querySelectorAll(
        'input[type="text"], input[type="email"], input[type="password"]'
    );
    inputs.forEach((input) => {
        input.addEventListener("blur", function () {
            validateInput(this);
        });
    });

    function validateInput(input) {
        const value = input.value.trim();

        // Remove existing error styling
        input.style.borderColor = "#ccc";

        // Validate based on input type
        if (input.type === "email" && value !== "") {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                input.style.borderColor = "#dc3545";
                addToHistory(
                    "system",
                    "Validation error",
                    `Invalid email format entered: ${value}`
                );
            }
        }

        if (input.name === "phone" && value !== "") {
            const phoneRegex = /^\d{4}-\d{3}-\d{4}$/;
            if (
                !phoneRegex.test(value) &&
                value.replace(/\D/g, "").length > 0
            ) {
                input.style.borderColor = "#dc3545";
                addToHistory(
                    "system",
                    "Validation error",
                    `Invalid phone format entered: ${value}`
                );
            }
        }

        if (input.type === "password" && value !== "" && value.length < 6) {
            input.style.borderColor = "#dc3545";
            addToHistory(
                "security",
                "Validation error",
                "Password too short during input"
            );
        }
    }

    // Update history timestamps every minute
    setInterval(function () {
        // Update relative timestamps
        adminHistory.forEach((entry) => {
            const now = new Date();
            const entryTime = new Date(entry.timestamp);
            const diffMs = now - entryTime;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 1) {
                entry.relativeTime = "Just now";
            } else if (diffMins < 60) {
                entry.relativeTime = `${diffMins} min${
                    diffMins > 1 ? "s" : ""
                } ago`;
            } else if (diffHours < 24) {
                entry.relativeTime = `${diffHours} hour${
                    diffHours > 1 ? "s" : ""
                } ago`;
            } else {
                entry.relativeTime = `${diffDays} day${
                    diffDays > 1 ? "s" : ""
                } ago`;
            }
        });

        // Re-display history with updated timestamps if needed
        if (adminHistory.length > 0) {
            displayHistory();
        }
    }, 60000); // Update every minute

    // Export history functionality (for future use)
        // Add export button dynamically (optional)
    const exportBtn = document.createElement("button");
    exportBtn.textContent = "Export";
    exportBtn.className = "export-btn";
    exportBtn.style.cssText = `
        background: #28a745;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        font-size: 12px;
        cursor: pointer;
        margin-left: 5px;
    `;
    exportBtn.addEventListener("click", exportHistory);

    // Add export button to history controls
    const historyControls = document.querySelector(".history-controls");
    if (historyControls) {
        historyControls.appendChild(exportBtn);
    }

    console.log(
        "Admin Profile with Enhanced History System loaded successfully!"
    );
});