document.addEventListener("DOMContentLoaded", function () {
    const venueSelect = document.getElementById("venue");
    const otherVenueInput = document.getElementById("other_venue");

    const themeMotifSelect = document.getElementById("theme_motif");
    const otherThemeMotifInput = document.getElementById("other_theme_motif");

    const eventTypeSelect = document.getElementById("event_type");
    const otherEventTypeInput = document.getElementById("other_event_type");

    const form = document.querySelector("form");

    // Agreement Modal Elements
    const agreementModal = document.getElementById("agreementModal");
    const agreementContent = document.getElementById("agreementContent");
    const agreeButton = document.getElementById("agreeButton");

    // Show agreement modal immediately on load
    if (agreementModal) {
        agreementModal.style.display = "flex";
        agreementModal.classList.add("show");

        // Disable form interaction
        if (form) {
            form.classList.add("form-disabled");
        }

        // Disable scroll on body
        document.body.style.overflow = "hidden";
    }

    // Toggle display of "Other" fields
    function toggleOtherInput(selectEl, inputEl, otherValue = "Others") {
        if (!selectEl || !inputEl) return;
        if (selectEl.value === otherValue) {
            inputEl.style.display = "block";
            inputEl.required = true;
        } else {
            inputEl.style.display = "none";
            inputEl.required = false;
            inputEl.value = "";
        }
    }

    venueSelect?.addEventListener("change", () =>
        toggleOtherInput(venueSelect, otherVenueInput)
    );
    themeMotifSelect?.addEventListener("change", () =>
        toggleOtherInput(themeMotifSelect, otherThemeMotifInput)
    );
    eventTypeSelect?.addEventListener("change", () =>
        toggleOtherInput(eventTypeSelect, otherEventTypeInput)
    );

    // On page load
    toggleOtherInput(venueSelect, otherVenueInput);
    toggleOtherInput(themeMotifSelect, otherThemeMotifInput);
    toggleOtherInput(eventTypeSelect, otherEventTypeInput);

    // Form validation
    form?.addEventListener("submit", function (e) {
        const requiredFields = ["name", "email", "contact_number", "date", "time", "message"];
        let hasEmpty = requiredFields.some(
            (id) => !document.getElementById(id)?.value.trim()
        );

        if (hasEmpty) {
            alert("Please fill in all required fields.");
            e.preventDefault();
            return;
        }

        if (
            eventTypeSelect.value === "Others" &&
            !otherEventTypeInput.value.trim()
        ) {
            alert("Please specify the event type.");
            e.preventDefault();
            return;
        }

        if (
            themeMotifSelect.value === "Others" &&
            !otherThemeMotifInput.value.trim()
        ) {
            alert("Please specify the theme/motif.");
            e.preventDefault();
            return;
        }

        if (
            venueSelect.value === "Others" &&
            !otherVenueInput.value.trim()
        ) {
            alert("Please specify the venue.");
            e.preventDefault();
            return;
        }
    });

    // Agreement logic
    let hasScrolledToBottom = false;

    agreementContent?.addEventListener("scroll", () => {
        const { scrollTop, scrollHeight, clientHeight } = agreementContent;
        if (scrollTop + clientHeight >= scrollHeight - 10) {
            hasScrolledToBottom = true;
            if (agreeButton) agreeButton.disabled = false;
        }
    });

    agreeButton?.addEventListener("click", () => {
        if (hasScrolledToBottom) {
            agreementModal.classList.remove("show");
            agreementModal.style.display = "none";

            // Enable form
            form?.classList.remove("form-disabled");
            form?.classList.add("form-enabled");

            // Enable scroll
            document.body.style.overflow = "auto";
        }
    });
});
