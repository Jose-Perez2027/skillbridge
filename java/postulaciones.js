/* =======================================================
   POSTULACIONES JS | SKILLBRIDGE
   Alerts and dynamic texts in English / Spanish
======================================================= */

document.addEventListener("DOMContentLoaded", () => {
    const filterButtons = document.querySelectorAll(".filtro");
    const applicationCards = document.querySelectorAll(".postulacion-card");
    const noApplications = document.getElementById("noApplications");
    const statusForms = document.querySelectorAll(".status-form");

    const originalEmptyTitle = noApplications
        ? noApplications.querySelector("h3")?.textContent || t("noApplicationsFound")
        : t("noApplicationsFound");

    const originalEmptyText = noApplications
        ? noApplications.querySelector("p")?.textContent || t("noApplicationsAvailable")
        : t("noApplicationsAvailable");

    function getCurrentLanguage() {
        const params = new URLSearchParams(window.location.search);
        const langFromUrl = params.get("lang");

        if (langFromUrl === "es" || langFromUrl === "en") {
            localStorage.setItem("skillbridgeLanguage", langFromUrl);
            return langFromUrl;
        }

        const storedLanguage = localStorage.getItem("skillbridgeLanguage");

        if (storedLanguage === "es" || storedLanguage === "en") {
            return storedLanguage;
        }

        const cookieMatch = document.cookie.match(/(?:^|; )skillbridgeLanguage=([^;]+)/);

        if (cookieMatch) {
            const cookieLanguage = decodeURIComponent(cookieMatch[1]);

            if (cookieLanguage === "es" || cookieLanguage === "en") {
                localStorage.setItem("skillbridgeLanguage", cookieLanguage);
                return cookieLanguage;
            }
        }

        const htmlLanguage = (document.documentElement.lang || "").toLowerCase();

        if (htmlLanguage.startsWith("es")) {
            return "es";
        }

        return "en";
    }

    function t(key, replacements = {}) {
        const language = getCurrentLanguage();

        const dictionary = {
            en: {
                noApplicationsFound: "No applications found.",
                noApplicationsAvailable: "No applications available.",
                noApplicationsForFilter: "No applications found for this filter.",
                noApplicationsWithStatus: "There are no applications with the status \"{status}\".",
                all: "All",
                pending: "Pending",
                reviewed: "Reviewed",
                accepted: "Accepted",
                rejected: "Rejected",
                applicationsFoundOne: "1 application found.",
                applicationsFoundMany: "{count} applications found.",
                saving: "Saving...",
                savingApplicationStatus: "Saving application status.",
                filterAll: "Show all applications",
                filterStatus: "Filter applications by {status}",
                statusUpdated: "Application status updated."
            },
            es: {
                noApplicationsFound: "No se encontraron postulaciones.",
                noApplicationsAvailable: "No hay postulaciones disponibles.",
                noApplicationsForFilter: "No se encontraron postulaciones para este filtro.",
                noApplicationsWithStatus: "No hay postulaciones con el estado \"{status}\".",
                all: "Todas",
                pending: "Pendiente",
                reviewed: "Revisada",
                accepted: "Aceptada",
                rejected: "Rechazada",
                applicationsFoundOne: "1 postulación encontrada.",
                applicationsFoundMany: "{count} postulaciones encontradas.",
                saving: "Guardando...",
                savingApplicationStatus: "Guardando estado de la postulación.",
                filterAll: "Mostrar todas las postulaciones",
                filterStatus: "Filtrar postulaciones por {status}",
                statusUpdated: "Estado de la postulación actualizado."
            }
        };

        let text = dictionary[language]?.[key] || dictionary.en[key] || key;

        Object.keys(replacements).forEach((name) => {
            text = text.replaceAll(`{${name}}`, replacements[name]);
        });

        return text;
    }

    function announceMessage(message) {
        const messageBox = document.getElementById("accessibilityMessage");

        if (messageBox) {
            messageBox.textContent = message;
        }
    }

    function getStatusLabel(status) {
        if (status === "pendiente") {
            return t("pending");
        }

        if (status === "revisada") {
            return t("reviewed");
        }

        if (status === "aceptada") {
            return t("accepted");
        }

        if (status === "rechazada") {
            return t("rejected");
        }

        return t("all");
    }

    function getApplicationsFoundMessage(count) {
        if (Number(count) === 1) {
            return t("applicationsFoundOne");
        }

        return t("applicationsFoundMany", {
            count: String(count)
        });
    }

    function updateFilterButtonLabels() {
        filterButtons.forEach((button) => {
            const selectedFilter = button.dataset.filter || "all";

            if (selectedFilter === "all") {
                button.setAttribute("aria-label", t("filterAll"));
                return;
            }

            button.setAttribute(
                "aria-label",
                t("filterStatus", {
                    status: getStatusLabel(selectedFilter)
                })
            );
        });
    }

    function updateEmptyState(visibleApplications, selectedFilter) {
        if (!noApplications) {
            return;
        }

        const emptyTitle = noApplications.querySelector("h3");
        const emptyText = noApplications.querySelector("p");

        if (applicationCards.length === 0) {
            if (emptyTitle) {
                emptyTitle.textContent = originalEmptyTitle;
            }

            if (emptyText) {
                emptyText.textContent = originalEmptyText;
            }

            noApplications.classList.remove("hidden");
            return;
        }

        if (visibleApplications === 0) {
            if (emptyTitle) {
                emptyTitle.textContent = t("noApplicationsForFilter");
            }

            if (emptyText) {
                emptyText.textContent = t("noApplicationsWithStatus", {
                    status: getStatusLabel(selectedFilter)
                });
            }

            noApplications.classList.remove("hidden");
        } else {
            noApplications.classList.add("hidden");
        }
    }

    function filterApplications(selectedFilter) {
        let visibleApplications = 0;

        applicationCards.forEach((card) => {
            const status = card.dataset.status || "";

            const shouldShow =
                selectedFilter === "all" ||
                status === selectedFilter;

            card.classList.toggle("hidden", !shouldShow);

            if (shouldShow) {
                visibleApplications++;
            }
        });

        updateEmptyState(visibleApplications, selectedFilter);
        announceMessage(getApplicationsFoundMessage(visibleApplications));
    }

    updateFilterButtonLabels();

    filterButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const selectedFilter = button.dataset.filter || "all";

            filterButtons.forEach((item) => {
                item.classList.remove("activo");
                item.setAttribute("aria-pressed", "false");
            });

            button.classList.add("activo");
            button.setAttribute("aria-pressed", "true");

            filterApplications(selectedFilter);
        });
    });

    statusForms.forEach((form) => {
        form.addEventListener("submit", () => {
            const submitButton = form.querySelector("button[type='submit']");

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.setAttribute("aria-busy", "true");
                submitButton.innerHTML = `
                    <span>${t("saving")}</span>
                    <i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                `;
            }

            announceMessage(t("savingApplicationStatus"));
        });
    });

    filterButtons.forEach((button) => {
        if (button.classList.contains("activo")) {
            button.setAttribute("aria-pressed", "true");
        } else {
            button.setAttribute("aria-pressed", "false");
        }
    });
});
