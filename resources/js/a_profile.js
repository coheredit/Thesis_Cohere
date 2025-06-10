console.log("a_profile.js loaded ✅");

// Admin Profile JavaScript with Enhanced History System
document.addEventListener("DOMContentLoaded", function () {
    // Get modal elements
    const editModal = document.getElementById("edit-modal");
    const passwordModal = document.getElementById("password-modal");
    const successModal = document.getElementById("success-modal");
    const confirmModal = document.getElementById("confirm-modal");

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
    const thisWeekActivitiesCount = document.getElementById("this-week-activities");

    // History system variables
    let adminHistory = [];
    let displayedHistoryCount = 0;
    const ITEMS_PER_PAGE = 10;
    let currentFilter = "all";
    let confirmCallback = null;

    // Initialize history system
    initializeHistory();

    // History Management Functions
    async function initializeHistory() {
        try {
            const res = await fetch(`/admin/activities?filter=${currentFilter}`);
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
                console.error("Error fetching admin history:", data.error || data);
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

        // Keep only last 100 entries to prevent memory issues
        if (adminHistory.length > 100) {
            adminHistory = adminHistory.slice(0, 100);
        }

        displayHistory();
        updateHistoryStats();
    }

    function generateId() {
        return "hist_" + Date.now() + "_" + Math.random().toString(36).substr(2, 9);
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
        const historyToShow = filteredHistory.slice(0, displayedHistoryCount + ITEMS_PER_PAGE);

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

        // Show/hide load more button
        if (filteredHistory.length > displayedHistoryCount) {
            loadMoreBtn.style.display = "block";
        } else {
            loadMoreBtn.style.display = "none";
        }
    }

    function filterHistory() {
        if (currentFilter === "all") {
            return adminHistory;
        }
        return adminHistory.filter((entry) => entry.type === currentFilter);
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
        if (thisWeekActivitiesCount) thisWeekActivitiesCount.textContent = thisWeek;
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
                addToHistory("system", "History cleared", "All admin history entries were deleted");
            }
        );
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
                    addToHistory("login", "Admin logout", "Admin logged out of the system");
                    
                    // Find the form and submit it
                    const form = logoutBtn.closest("form");
                    if (form) {
                        console.log("Form found, submitting..."); // Debug log
                        form.submit();
                    } else {
                        console.error("Logout form not found!");
                        // Fallback: redirect to logout route
                        window.location.href = '/admin/logout';
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

    // Profile Picture Change
    if (changePicBtn && profilePicInput) {
        changePicBtn.addEventListener("click", function () {
            profilePicInput.click();
        });

        profilePicInput.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (profilePic) {
                        profilePic.src = e.target.result;
                        addToHistory("profile", "Profile picture updated", "Changed admin profile picture");
                        showSuccessModal("Profile picture updated successfully!");
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
            if (editEmail && adminEmail) editEmail.value = adminEmail.textContent;
            if (editPhone && adminPhone) editPhone.value = adminPhone.textContent;
            if (editUsername && adminUsername) editUsername.value = adminUsername.textContent;

            editModal.style.display = "block";
            addToHistory("system", "Edit profile form opened", "Started editing admin profile information");
        });
    }

    // Change Password Link Click
    if (changePasswordLink && passwordModal) {
        changePasswordLink.addEventListener("click", function (e) {
            e.preventDefault();
            passwordModal.style.display = "block";
            addToHistory("security", "Password change initiated", "Opened password change form");
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
            addToHistory("system", "Profile edit cancelled", "Cancelled profile editing without saving");
        });
    }

    if (cancelPassword && passwordModal) {
        cancelPassword.addEventListener("click", function () {
            passwordModal.style.display = "none";
            addToHistory("security", "Password change cancelled", "Cancelled password change without saving");
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
            if (adminEmail && editEmail) adminEmail.textContent = editEmail.value;
            if (adminPhone && editPhone) adminPhone.textContent = editPhone.value;
            if (adminUsername && editUsername) adminUsername.textContent = editUsername.value;

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
                changeDetails.push(`Email: "${oldEmail}" → "${editEmail.value}"`);
            }
            if (editPhone && oldPhone !== editPhone.value) {
                changes.push("phone");
                changeDetails.push(`Phone: "${oldPhone}" → "${editPhone.value}"`);
            }
            if (editUsername && oldUsername !== editUsername.value) {
                changes.push("username");
                changeDetails.push(`Username: "${oldUsername}" → "${editUsername.value}"`);
            }

            // Add specific activity
            if (changes.length > 0) {
                const changeText = `Profile updated: ${changes.join(", ")}`;
                const detailText = changeDetails.join("; ");
                addToHistory("profile", changeText, detailText);
            } else {
                addToHistory("profile", "Profile form submitted", "No changes were made to profile information");
            }

            // Show success modal
            showSuccessModal("Profile updated successfully!");
        });
    }

    // Change Password Form Submit
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
                addToHistory("security", "Password change failed", "Password confirmation mismatch");
                return;
            }

            if (newPassword.value.length < 6) {
                showErrorMessage("Password must be at least 6 characters long!", passwordModal);
                addToHistory("security", "Password change failed", "Password too short (minimum 6 characters)");
                return;
            }

            if (currentPassword.value === "") {
                showErrorMessage("Please enter your current password!", passwordModal);
                addToHistory("security", "Password change failed", "Current password not provided");
                return;
            }

            // Close modal and clear form
            if (passwordModal) passwordModal.style.display = "none";
            passwordForm.reset();

            // Add activity
            addToHistory("security", "Password changed successfully", "Admin account password updated");

            // Show success modal
            showSuccessModal("Password changed successfully!");
        });
    }

    // Helper function to show success modal
    function showSuccessModal(message) {
        const successMessageText = document.getElementById("success-message-text");
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

        // Insert in modal
        const modalContent = modal.querySelector(".modal-content");
        const firstChild = modalContent.querySelector("h3");
        if (modalContent && firstChild) {
            modalContent.insertBefore(errorDiv, firstChild.nextSibling);
        }

        // Remove message after 5 seconds
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
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
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
                addToHistory("system", "Validation error", `Invalid email format entered: ${value}`);
            }
        }

        if (input.name === "phone" && value !== "") {
            const phoneRegex = /^\d{4}-\d{3}-\d{4}$/;
            if (!phoneRegex.test(value) && value.replace(/\D/g, "").length > 0) {
                input.style.borderColor = "#dc3545";
                addToHistory("system", "Validation error", `Invalid phone format entered: ${value}`);
            }
        }

        if (input.type === "password" && value !== "" && value.length < 6) {
            input.style.borderColor = "#dc3545";
            addToHistory("security", "Validation error", "Password too short during input");
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
                entry.relativeTime = `${diffMins} min${diffMins > 1 ? "s" : ""} ago`;
            } else if (diffHours < 24) {
                entry.relativeTime = `${diffHours} hour${diffHours > 1 ? "s" : ""} ago`;
            } else {
                entry.relativeTime = `${diffDays} day${diffDays > 1 ? "s" : ""} ago`;
            }
        });

        // Re-display history with updated timestamps if needed
        if (adminHistory.length > 0) {
            displayHistory();
        }
    }, 60000); // Update every minute

    // Export history functionality (for future use)
    function exportHistory() {
        const historyData = {
            exportDate: new Date().toISOString(),
            totalEntries: adminHistory.length,
            entries: adminHistory,
        };

        const dataStr = JSON.stringify(historyData, null, 2);
        const dataUri = "data:application/json;charset=utf-8," + encodeURIComponent(dataStr);

        const exportFileDefaultName = `admin_history_${new Date().toISOString().split("T")[0]}.json`;

        const linkElement = document.createElement("a");
        linkElement.setAttribute("href", dataUri);
        linkElement.setAttribute("download", exportFileDefaultName);
        linkElement.click();

        addToHistory("system", "History exported", `Exported ${adminHistory.length} history entries to JSON file`);
    }

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

    console.log("Admin Profile with Enhanced History System loaded successfully!");
});