document.addEventListener("DOMContentLoaded", function () {
    // Form logic for 'Others' fields
    const venueSelect = document.getElementById("venue");
    const otherVenueInput = document.getElementById("otherVenue");

    const themeMotifSelect = document.getElementById("theme_motif");
    const otherThemeMotifInput = document.getElementById("otherThemeMotif");

    const eventTypeSelect = document.getElementById("event_type");
    const otherEventTypeInput = document.getElementById("otherEventType");

    const form = document.querySelector("form");

    function toggleOtherInput(selectElement, otherInput, otherValue) {
        if (selectElement && otherInput) {
            otherInput.style.display =
                selectElement.value === otherValue ? "block" : "none";
            otherInput.required = selectElement.value === otherValue;
            if (selectElement.value !== otherValue) {
                otherInput.value = "";
            }
        }
    }

    if (venueSelect)
        venueSelect.addEventListener("change", () =>
            toggleOtherInput(venueSelect, otherVenueInput, "Others")
        );
    if (themeMotifSelect)
        themeMotifSelect.addEventListener("change", () =>
            toggleOtherInput(themeMotifSelect, otherThemeMotifInput, "Others")
        );
    if (eventTypeSelect)
        eventTypeSelect.addEventListener("change", () =>
            toggleOtherInput(eventTypeSelect, otherEventTypeInput, "Others")
        );

    toggleOtherInput(venueSelect, otherVenueInput, "Others");
    toggleOtherInput(themeMotifSelect, otherThemeMotifInput, "Others");
    toggleOtherInput(eventTypeSelect, otherEventTypeInput, "Others");

    form.addEventListener("submit", function (event) {
        const name = document.getElementById("name")?.value.trim();
        const email = document.getElementById("email")?.value.trim();
        const contact = document.getElementById("contact")?.value.trim();
        const message = document.getElementById("message")?.value.trim();
        const date = document.getElementById("date")?.value.trim();
        const time =
            document.getElementById("time")?.value?.trim() ||
            document.getElementById("time_slot")?.value?.trim();

        if (!name || !email || !contact || !message || !date || !time) {
            alert("Please fill in all required fields.");
            event.preventDefault();
            return;
        }

        if (
            !venueSelect.value ||
            (venueSelect.value === "Others" && !otherVenueInput.value.trim())
        ) {
            alert("Please specify the venue.");
            event.preventDefault();
            return;
        }

        if (
            !eventTypeSelect.value ||
            (eventTypeSelect.value === "Others" &&
                !otherEventTypeInput.value.trim())
        ) {
            alert("Please specify the event type.");
            event.preventDefault();
            return;
        }

        if (
            !themeMotifSelect.value ||
            (themeMotifSelect.value === "Others" &&
                !otherThemeMotifInput.value.trim())
        ) {
            alert("Please specify the theme/motif.");
            event.preventDefault();
            return;
        }
    });

    // ---------------------------- //
    //       CALENDAR FUNCTION     //
    // ---------------------------- //
    const calendar = document.getElementById("calendar");
    const monthYear = document.getElementById("month-year");
    const prevMonthBtn = document.getElementById("prevMonth");
    const nextMonthBtn = document.getElementById("nextMonth");
    const modal = document.getElementById("statusModal");
    const saveStatusBtn = document.getElementById("saveStatus");
    const closeModalBtn = document.getElementById("closeModal");
    const statusSelect = document.getElementById("statusSelect");

    let currentDate = new Date();
    let selectedDate = null;
    let dateStatuses = {};

    function renderCalendar() {
        const month = currentDate.getMonth();
        const year = currentDate.getFullYear();
        monthYear.textContent = new Intl.DateTimeFormat("en-US", {
            month: "long",
            year: "numeric",
        }).format(currentDate);

        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month + 1, 0).getDate();

        calendar.innerHTML = "";

        const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        daysOfWeek.forEach((day) => {
            const dayHeader = document.createElement("div");
            dayHeader.classList.add("calendar-day", "day-header");
            dayHeader.textContent = day;
            calendar.appendChild(dayHeader);
        });

        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement("div");
            emptyCell.classList.add("calendar-day", "empty");
            calendar.appendChild(emptyCell);
        }

        for (let day = 1; day <= lastDate; day++) {
            const dayCell = document.createElement("div");
            dayCell.classList.add("calendar-day");
            dayCell.textContent = day;
            const dateKey = `${year}-${month + 1}-${day}`;
            dayCell.setAttribute("data-date", dateKey);

            if (dateStatuses[dateKey]) {
                dayCell.setAttribute("data-status", dateStatuses[dateKey]);
                applyStatusStyle(dayCell, dateStatuses[dateKey]);
            }

            dayCell.addEventListener("click", function () {
                selectedDate = dateKey;
                modal.style.display = "flex";
            });

            calendar.appendChild(dayCell);
        }
    }

    function applyStatusStyle(cell, status) {
        cell.classList.remove("free", "closed", "full");
        if (status === "free") {
            cell.style.background = "green";
            cell.style.color = "white";
        } else if (status === "closed") {
            cell.style.background = "red";
            cell.style.color = "white";
        } else if (status === "full") {
            cell.style.background = "yellow";
            cell.style.color = "black";
        }
    }

    saveStatusBtn.addEventListener("click", function () {
        if (selectedDate) {
            const selectedStatus = statusSelect.value;
            dateStatuses[selectedDate] = selectedStatus;

            const dayCells = document.querySelectorAll(".calendar-day");
            dayCells.forEach((cell) => {
                if (cell.getAttribute("data-date") === selectedDate) {
                    applyStatusStyle(cell, selectedStatus);
                }
            });

            fetch("/admin/availability/toggle", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    date: selectedDate,
                    status: selectedStatus,
                }),
            })
                .then((res) => res.json())
                .then((data) => {
                    console.log("Toggled:", data);
                })
                .catch((err) => {
                    console.error("Error saving availability:", err);
                });

            modal.style.display = "none";
        }
    });

    closeModalBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    prevMonthBtn.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    // ---------------------------- //
    //   AM/PM → Time Slot Filter   //
    // ---------------------------- //
    const periodSelect = document.getElementById("period");
    const timeSlotWrapper = document.getElementById("timeSlotWrapper");
    const timeSlotSelect = document.getElementById("time_slot");

    const timeSlots = {
        AM: ["9am-1pm", "10am-2pm", "11am-3pm"],
        PM: ["4pm-8pm", "5pm-9pm", "6pm-10pm"],
    };

    if (periodSelect && timeSlotSelect && timeSlotWrapper) {
        timeSlotWrapper.style.display = "none";

        periodSelect.addEventListener("change", function () {
            const selectedPeriod = this.value;
            timeSlotSelect.innerHTML =
                '<option value="">Select a time slot</option>';

            if (timeSlots[selectedPeriod]) {
                timeSlots[selectedPeriod].forEach((slot) => {
                    const option = document.createElement("option");
                    option.value = slot;
                    option.textContent = slot;
                    timeSlotSelect.appendChild(option);
                });

                timeSlotWrapper.style.display = "block";
                timeSlotSelect.disabled = false;
            } else {
                timeSlotWrapper.style.display = "none";
                timeSlotSelect.disabled = true;
            }
        });
    }

    fetch("/admin/availability")
        .then((res) => res.json())
        .then((dateStatusMap) => {
            Object.entries(dateStatusMap).forEach(([date, status]) => {
                dateStatuses[date] = status;
            });
            renderCalendar();
        })
        .catch((err) => {
            console.error("Error loading availability:", err);
            renderCalendar();
        });
});
