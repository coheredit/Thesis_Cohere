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

    // Get button elements
    const editBtn = document.getElementById("edit-profile-btn");
    const logoutBtn = document.getElementById("logout-btn");
    const changePasswordLink = document.getElementById("change-password-link");
    const changePicBtn = document.getElementById("change-pic-btn");
    const profilePicInput = document.getElementById("profile-pic-input");
    const successOkBtn = document.getElementById("success-ok-btn");
    const clearHistoryBtn = document.getElementById("clear-history-btn");
    const loadMoreBtn = document.getElementById("load-more-btn");

    // Get close buttons
    const closeModal = document.getElementById("close-modal");
    const closePasswordModal = document.getElementById("close-password-modal");
    const cancelEdit = document.getElementById("cancel-edit");
    const cancelPassword = document.getElementById("cancel-password");
    const confirmCancel = document.getElementById("confirm-cancel");
    const confirmOk = document.getElementById("confirm-ok");

    // Get forms
    const editForm = document.getElementById("edit-form");
    const passwordForm = document.getElementById("password-form");

    // Profile data elements
    const adminName = document.getElementById("admin-name");
    const adminEmail = document.getElementById("admin-email");
    const adminPhone = document.getElementById("admin-phone");
    const adminUsername = document.getElementById("admin-username");
    const profilePic = document.getElementById("profile-pic");

    // Form input elements
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

    initializeHistory();

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
            type,
            action,
            details,
            timestamp: new Date(),
            date: new Date().toLocaleDateString(),
            time: new Date().toLocaleTimeString(),
        };
        adminHistory.unshift(historyEntry);
        if (adminHistory.length > 100) adminHistory = adminHistory.slice(0, 100);
        displayHistory();
        updateHistoryStats();

        fetch("/admin/activities/store", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
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
        if (currentFilter === "all") return adminHistory;
        return adminHistory.filter((entry) => entry.type === currentFilter);
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
                <div class="history-icon ${entry.type}">${getHistoryIcon(entry.type)}</div>
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
        loadMoreBtn.style.display = filteredHistory.length > displayedHistoryCount ? "block" : "none";
    }

    function getHistoryIcon(type) {
        const icons = { login: "🔐", profile: "👤", system: "⚙️", security: "🛡️" };
        return icons[type] || "📝";
    }

    function updateHistoryStats() {
        const total = adminHistory.length;
        const today = adminHistory.filter(entry => new Date(entry.timestamp).toDateString() === new Date().toDateString()).length;
        const thisWeek = adminHistory.filter(entry => new Date(entry.timestamp) >= new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)).length;
        if (totalActivitiesCount) totalActivitiesCount.textContent = total;
        if (todayActivitiesCount) todayActivitiesCount.textContent = today;
        if (thisWeekActivitiesCount) thisWeekActivitiesCount.textContent = thisWeek;
    }

    function clearHistory() {
        showConfirmModal("Clear History", "Are you sure you want to clear all history? This action cannot be undone.", () => {
            adminHistory = [];
            displayedHistoryCount = 0;
            displayHistory();
            updateHistoryStats();
            showSuccessModal("History cleared successfully!");
            addToHistory("system", "History cleared", "All admin history entries were deleted");
        });
    }

    if (historyFilter) {
        historyFilter.addEventListener("change", function () {
            currentFilter = this.value;
            initializeHistory();
        });
    }

    if (clearHistoryBtn) clearHistoryBtn.addEventListener("click", clearHistory);
    if (loadMoreBtn) loadMoreBtn.addEventListener("click", () => displayHistory());

    console.log("Admin Profile with Enhanced History System loaded successfully!");
});