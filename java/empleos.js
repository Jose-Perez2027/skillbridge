/* =======================================================
   EMPLEOS JS | SKILLBRIDGE
   Corrección: textos dinámicos, alertas y filtros EN/ES
======================================================= */

document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("jobSearchInput");
    const searchButton = document.getElementById("searchButton");
    const categorySelect = document.getElementById("categorySelect");
    const salaryRange = document.getElementById("salaryRange");
    const salaryValue = document.getElementById("salaryValue");
    const clearFiltersButton = document.getElementById("clearFiltersButton");
    const jobsGrid = document.getElementById("jobsGrid");
    const noResults = document.getElementById("noResults");
    const jobsCount = document.getElementById("jobsCount");
    const activeFilters = document.getElementById("activeFilters");
    const sortSelect = document.getElementById("sortSelect");

    const filterCheckboxes = Array.from(document.querySelectorAll(".filter-checkbox"));
    const quickFilters = Array.from(document.querySelectorAll(".quick-filter"));
    const viewButtons = Array.from(document.querySelectorAll(".view-button"));
    const filterTitles = Array.from(document.querySelectorAll(".filter-title"));
    const jobCards = Array.from(document.querySelectorAll(".employment-job-card"));

    const savedJobsKey = "skillbridgeSavedJobs";
    const maxSalaryValue = salaryRange ? Number(salaryRange.max || 3000) : 3000;

    let currentVisibleCards = [];
    let savedJobs = [];

    const messages = {
        en: {
            any: "Any",
            search: "Search",
            maxSalary: "Max salary",
            inclusiveJobs: "Inclusive jobs",
            technology: "Technology",
            design: "Design",
            sales: "Sales",
            administration: "Administration",
            customerService: "Customer service",
            humanResources: "Human Resources",
            remote: "Remote",
            hybrid: "Hybrid",
            onsite: "On-site",
            noExperience: "No experience",
            junior: "Junior",
            midLevel: "Mid-level",
            senior: "Senior",
            removeFilter: "Remove filter {filter}",
            oneJobFound: "1 job opening found.",
            manyJobsFound: "{count} job openings found.",
            viewChangedGrid: "Job view changed to grid.",
            viewChangedList: "Job view changed to list.",
            filtersCleared: "Filters cleared.",
            saveJob: "Save job",
            removeSavedJob: "Remove saved job",
            jobSaved: "{job} was saved.",
            jobRemoved: "{job} was removed from saved jobs.",
            filtersUpdated: "Filters updated.",
            filterGroupExpanded: "Filter group expanded.",
            filterGroupCollapsed: "Filter group collapsed."
        },
        es: {
            any: "Cualquiera",
            search: "Búsqueda",
            maxSalary: "Salario máximo",
            inclusiveJobs: "Empleos inclusivos",
            technology: "Tecnología",
            design: "Diseño",
            sales: "Ventas",
            administration: "Administración",
            customerService: "Atención al cliente",
            humanResources: "Recursos humanos",
            remote: "Remoto",
            hybrid: "Híbrido",
            onsite: "Presencial",
            noExperience: "Sin experiencia",
            junior: "Junior",
            midLevel: "Intermedio",
            senior: "Senior",
            removeFilter: "Quitar filtro {filter}",
            oneJobFound: "1 vacante encontrada.",
            manyJobsFound: "{count} vacantes encontradas.",
            viewChangedGrid: "Vista cambiada a cuadrícula.",
            viewChangedList: "Vista cambiada a lista.",
            filtersCleared: "Filtros limpiados.",
            saveJob: "Guardar empleo",
            removeSavedJob: "Quitar empleo guardado",
            jobSaved: "{job} fue guardado.",
            jobRemoved: "{job} fue eliminado de empleos guardados.",
            filtersUpdated: "Filtros actualizados.",
            filterGroupExpanded: "Grupo de filtros expandido.",
            filterGroupCollapsed: "Grupo de filtros contraído."
        }
    };

    function getCookie(name) {
        const cookies = document.cookie ? document.cookie.split(";") : [];

        for (const cookie of cookies) {
            const parts = cookie.trim().split("=");
            const cookieName = parts.shift();

            if (cookieName === name) {
                return decodeURIComponent(parts.join("="));
            }
        }

        return "";
    }

    function getCurrentLanguage() {
        const params = new URLSearchParams(window.location.search);
        const urlLang = params.get("lang");

        if (urlLang === "es" || urlLang === "en") {
            return urlLang;
        }

        if (
            window.SkillBridgeUserPreferences &&
            (window.SkillBridgeUserPreferences.language === "es" ||
                window.SkillBridgeUserPreferences.language === "en")
        ) {
            return window.SkillBridgeUserPreferences.language;
        }

        const storedLanguage = localStorage.getItem("skillbridgeLanguage");

        if (storedLanguage === "es" || storedLanguage === "en") {
            return storedLanguage;
        }

        const cookieLanguage = getCookie("skillbridgeLanguage");

        if (cookieLanguage === "es" || cookieLanguage === "en") {
            return cookieLanguage;
        }

        const htmlLanguage = document.documentElement.getAttribute("lang");

        if (htmlLanguage && htmlLanguage.toLowerCase().startsWith("es")) {
            return "es";
        }

        return "en";
    }

    function translate(key, replacements = {}) {
        const language = getCurrentLanguage();
        const dictionary = messages[language] || messages.en;
        let text = dictionary[key] || messages.en[key] || key;

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

    function normalize(text) {
        return String(text || "")
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .trim();
    }

    function normalizeCategory(value) {
        const text = normalize(value);

        if (["tecnologia", "technology", "tech"].includes(text)) {
            return "tecnologia";
        }

        if (["diseno", "design", "design and creativity", "creativity"].includes(text)) {
            return "diseno";
        }

        if (["ventas", "sales"].includes(text)) {
            return "ventas";
        }

        if (["administracion", "administration", "admin"].includes(text)) {
            return "administracion";
        }

        if (["atencion", "customer service", "atencion al cliente", "customer support"].includes(text)) {
            return "atencion";
        }

        if (["recursos humanos", "human resources", "rrhh", "hr"].includes(text)) {
            return "recursos-humanos";
        }

        return text;
    }

    function normalizeMode(value) {
        const text = normalize(value);

        if (["remoto", "remote"].includes(text)) {
            return "remoto";
        }

        if (["hibrido", "hybrid"].includes(text)) {
            return "hibrido";
        }

        if (["presencial", "on-site", "onsite", "in-person", "in person"].includes(text)) {
            return "presencial";
        }

        return text;
    }

    function normalizeExperience(value) {
        const text = normalize(value);

        if (["sin experiencia", "no experience", "without experience"].includes(text)) {
            return "no-experience";
        }

        if (["junior", "entry", "entry level", "entry-level"].includes(text)) {
            return "junior";
        }

        if (["intermedio", "mid-level", "mid level", "middle"].includes(text)) {
            return "mid-level";
        }

        if (["senior", "advanced", "avanzado"].includes(text)) {
            return "senior";
        }

        return text;
    }

    function getUrlParams() {
        const params = new URLSearchParams(window.location.search);

        return {
            search: params.get("search") || params.get("q") || params.get("busqueda") || "",
            category: params.get("category") || params.get("categoria") || "",
            mode: params.get("mode") || params.get("modalidad") || ""
        };
    }

    function setSelectValueByNormalized(selectElement, value, type) {
        if (!selectElement || value === "") {
            return;
        }

        const normalizedValue = type === "category"
            ? normalizeCategory(value)
            : normalizeMode(value);

        let matchedValue = "";

        Array.from(selectElement.options).forEach((option) => {
            const optionValue = type === "category"
                ? normalizeCategory(option.value)
                : normalizeMode(option.value);

            const optionText = type === "category"
                ? normalizeCategory(option.textContent)
                : normalizeMode(option.textContent);

            if (optionValue === normalizedValue || optionText === normalizedValue) {
                matchedValue = option.value;
            }
        });

        if (matchedValue !== "") {
            selectElement.value = matchedValue;
        }
    }

    function checkFilterByNormalized(filterName, value, type) {
        if (value === "") {
            return;
        }

        const normalizedValue = type === "category"
            ? normalizeCategory(value)
            : normalizeMode(value);

        filterCheckboxes.forEach((checkbox) => {
            if (checkbox.dataset.filter !== filterName) {
                return;
            }

            const checkboxValue = type === "category"
                ? normalizeCategory(checkbox.value)
                : normalizeMode(checkbox.value);

            const labelText = checkbox.closest("label")
                ? checkbox.closest("label").textContent
                : "";

            const checkboxLabel = type === "category"
                ? normalizeCategory(labelText)
                : normalizeMode(labelText);

            if (checkboxValue === normalizedValue || checkboxLabel === normalizedValue) {
                checkbox.checked = true;
            }
        });

        quickFilters.forEach((button) => {
            const buttonType = button.dataset.filterType || "";
            const buttonValue = type === "category"
                ? normalizeCategory(button.dataset.value)
                : normalizeMode(button.dataset.value);

            if (buttonType === filterName && buttonValue === normalizedValue) {
                button.classList.add("active");
            }
        });
    }

    function applyUrlFilters() {
        const urlFilters = getUrlParams();

        if (searchInput && urlFilters.search !== "") {
            searchInput.value = urlFilters.search;
        }

        if (urlFilters.category !== "") {
            setSelectValueByNormalized(categorySelect, urlFilters.category, "category");
            checkFilterByNormalized("category", urlFilters.category, "category");
        }

        if (urlFilters.mode !== "") {
            checkFilterByNormalized("modality", urlFilters.mode, "mode");
        }
    }

    function getCheckedValues(filterName) {
        return filterCheckboxes
            .filter((checkbox) => checkbox.dataset.filter === filterName && checkbox.checked)
            .map((checkbox) => checkbox.value);
    }

    function updateSalaryText() {
        if (!salaryRange || !salaryValue) {
            return;
        }

        const value = Number(salaryRange.value);
        const limit = Number(salaryRange.max || maxSalaryValue || 3000);

        salaryValue.textContent = value >= limit ? translate("any") : `$${value}`;
    }

    function getCategoryLabel(value) {
        const normalized = normalizeCategory(value);

        if (normalized === "tecnologia") return translate("technology");
        if (normalized === "diseno") return translate("design");
        if (normalized === "ventas") return translate("sales");
        if (normalized === "administracion") return translate("administration");
        if (normalized === "atencion") return translate("customerService");
        if (normalized === "recursos-humanos") return translate("humanResources");

        return value;
    }

    function getModeLabel(value) {
        const normalized = normalizeMode(value);

        if (normalized === "remoto") return translate("remote");
        if (normalized === "hibrido") return translate("hybrid");
        if (normalized === "presencial") return translate("onsite");

        return value;
    }

    function getExperienceLabel(value) {
        const normalized = normalizeExperience(value);

        if (normalized === "no-experience") return translate("noExperience");
        if (normalized === "junior") return translate("junior");
        if (normalized === "mid-level") return translate("midLevel");
        if (normalized === "senior") return translate("senior");

        return value;
    }

    function getJobsCountText(count) {
        if (count === 1) {
            return translate("oneJobFound");
        }

        return translate("manyJobsFound", { count });
    }

    function renderActiveFilters() {
        if (!activeFilters) {
            return;
        }

        activeFilters.innerHTML = "";

        const searchTerm = searchInput ? searchInput.value.trim() : "";
        const selectedCategory = categorySelect ? categorySelect.value : "";
        const selectedModality = getCheckedValues("modality");
        const selectedExperience = getCheckedValues("experience");
        const checkedCategories = getCheckedValues("category");
        const inclusiveChecked = getCheckedValues("inclusive");
        const maxSalary = salaryRange ? Number(salaryRange.value) : maxSalaryValue;
        const limit = salaryRange ? Number(salaryRange.max || maxSalaryValue) : maxSalaryValue;

        const tags = [];

        if (searchTerm !== "") {
            tags.push({
                label: `${translate("search")}: ${searchTerm}`,
                clear: () => {
                    searchInput.value = "";
                }
            });
        }

        if (selectedCategory !== "") {
            tags.push({
                label: getCategoryLabel(selectedCategory),
                clear: () => {
                    categorySelect.value = "";
                }
            });
        }

        selectedModality.forEach((value) => {
            tags.push({
                label: getModeLabel(value),
                clear: () => {
                    filterCheckboxes.forEach((checkbox) => {
                        if (checkbox.dataset.filter === "modality" && checkbox.value === value) {
                            checkbox.checked = false;
                        }
                    });

                    quickFilters.forEach((button) => {
                        if (button.dataset.filterType === "modality" && button.dataset.value === value) {
                            button.classList.remove("active");
                        }
                    });
                }
            });
        });

        selectedExperience.forEach((value) => {
            tags.push({
                label: getExperienceLabel(value),
                clear: () => {
                    filterCheckboxes.forEach((checkbox) => {
                        if (checkbox.dataset.filter === "experience" && checkbox.value === value) {
                            checkbox.checked = false;
                        }
                    });

                    quickFilters.forEach((button) => {
                        if (button.dataset.filterType === "experience" && button.dataset.value === value) {
                            button.classList.remove("active");
                        }
                    });
                }
            });
        });

        checkedCategories.forEach((value) => {
            tags.push({
                label: getCategoryLabel(value),
                clear: () => {
                    filterCheckboxes.forEach((checkbox) => {
                        if (checkbox.dataset.filter === "category" && checkbox.value === value) {
                            checkbox.checked = false;
                        }
                    });

                    quickFilters.forEach((button) => {
                        if (button.dataset.filterType === "category" && button.dataset.value === value) {
                            button.classList.remove("active");
                        }
                    });
                }
            });
        });

        if (inclusiveChecked.length > 0) {
            tags.push({
                label: translate("inclusiveJobs"),
                clear: () => {
                    filterCheckboxes.forEach((checkbox) => {
                        if (checkbox.dataset.filter === "inclusive") {
                            checkbox.checked = false;
                        }
                    });

                    quickFilters.forEach((button) => {
                        if (button.dataset.filterType === "inclusive") {
                            button.classList.remove("active");
                        }
                    });
                }
            });
        }

        if (maxSalary < limit) {
            tags.push({
                label: `${translate("maxSalary")}: $${maxSalary}`,
                clear: () => {
                    salaryRange.value = String(limit);
                    updateSalaryText();
                }
            });
        }

        tags.forEach((tag) => {
            const item = document.createElement("span");
            item.className = "active-filter-tag";

            const button = document.createElement("button");
            button.type = "button";
            button.setAttribute("aria-label", translate("removeFilter", { filter: tag.label }));
            button.setAttribute("title", translate("removeFilter", { filter: tag.label }));
            button.innerHTML = `<i class="fa-solid fa-xmark" aria-hidden="true"></i>`;

            button.addEventListener("click", () => {
                tag.clear();
                updateFilters(false);
            });

            item.append(document.createTextNode(tag.label));
            item.append(button);

            activeFilters.appendChild(item);
        });
    }

    function sortVisibleJobs(visibleCards) {
        if (!sortSelect || !jobsGrid) {
            return;
        }

        const sortValue = sortSelect.value;

        visibleCards.sort((a, b) => {
            const titleA = a.dataset.title || "";
            const titleB = b.dataset.title || "";
            const salaryA = Number(a.dataset.salary || 0);
            const salaryB = Number(b.dataset.salary || 0);
            const postedA = Number(a.dataset.posted || 0);
            const postedB = Number(b.dataset.posted || 0);

            if (sortValue === "salary-high") {
                return salaryB - salaryA;
            }

            if (sortValue === "salary-low") {
                return salaryA - salaryB;
            }

            if (sortValue === "title") {
                return titleA.localeCompare(titleB, getCurrentLanguage() === "es" ? "es" : "en");
            }

            return postedB - postedA;
        });

        visibleCards.forEach((card) => {
            jobsGrid.appendChild(card);
        });
    }

    function getCardCategory(card) {
        return normalizeCategory(card.dataset.category || "");
    }

    function getCardModality(card) {
        return normalizeMode(card.dataset.modality || card.dataset.mode || "");
    }

    function getCardExperience(card) {
        return normalizeExperience(card.dataset.experience || "");
    }

    function updateFilters(announce = true) {
        const searchTerm = normalize(searchInput ? searchInput.value : "");
        const selectedCategory = categorySelect ? normalizeCategory(categorySelect.value) : "";
        const checkedCategories = getCheckedValues("category").map(normalizeCategory);
        const selectedModalities = getCheckedValues("modality").map(normalizeMode);
        const selectedExperience = getCheckedValues("experience").map(normalizeExperience);
        const inclusiveChecked = getCheckedValues("inclusive");
        const maxSalary = salaryRange ? Number(salaryRange.value) : maxSalaryValue;
        const limit = salaryRange ? Number(salaryRange.max || maxSalaryValue) : maxSalaryValue;

        let visibleJobs = 0;
        const visibleCards = [];

        jobCards.forEach((card) => {
            const searchContent = normalize(card.dataset.search);
            const title = normalize(card.dataset.title);
            const category = getCardCategory(card);
            const modality = getCardModality(card);
            const experience = getCardExperience(card);
            const salary = Number(card.dataset.salary || 0);
            const inclusive = card.dataset.inclusive || "0";

            const matchesSearch =
                searchTerm === "" ||
                searchContent.includes(searchTerm) ||
                title.includes(searchTerm);

            const matchesTopCategory =
                selectedCategory === "" ||
                selectedCategory === "todos" ||
                selectedCategory === "all" ||
                category === selectedCategory;

            const matchesSideCategory =
                checkedCategories.length === 0 ||
                checkedCategories.includes(category);

            const matchesModality =
                selectedModalities.length === 0 ||
                selectedModalities.includes(modality);

            const matchesExperience =
                selectedExperience.length === 0 ||
                selectedExperience.includes(experience);

            const matchesInclusive =
                inclusiveChecked.length === 0 ||
                inclusive === "1" ||
                inclusive === "true";

            const matchesSalary =
                maxSalary >= limit ||
                salary === 0 ||
                salary <= maxSalary;

            const shouldShow =
                matchesSearch &&
                matchesTopCategory &&
                matchesSideCategory &&
                matchesModality &&
                matchesExperience &&
                matchesInclusive &&
                matchesSalary;

            card.classList.toggle("hidden", !shouldShow);

            if (shouldShow) {
                visibleJobs++;
                visibleCards.push(card);
            }
        });

        currentVisibleCards = visibleCards;
        sortVisibleJobs(currentVisibleCards);

        const countText = getJobsCountText(visibleJobs);

        if (jobsCount) {
            jobsCount.textContent = countText;
        }

        if (noResults) {
            noResults.classList.toggle("hidden", visibleJobs !== 0);
        }

        if (jobsGrid) {
            jobsGrid.classList.toggle("hidden", visibleJobs === 0);
        }

        renderActiveFilters();

        if (announce) {
            announceMessage(countText);
        }
    }

    function clearUrlParams() {
        if (window.history && window.history.replaceState) {
            const params = new URLSearchParams(window.location.search);
            const lang = params.get("lang");
            const newUrl = lang ? `${window.location.pathname}?lang=${encodeURIComponent(lang)}` : window.location.pathname;

            window.history.replaceState({}, document.title, newUrl);
        }
    }

    filterTitles.forEach((button) => {
        button.addEventListener("click", () => {
            const options = button.nextElementSibling;

            if (!options) {
                return;
            }

            button.classList.toggle("collapsed");
            options.classList.toggle("collapsed");

            const isCollapsed = button.classList.contains("collapsed");
            button.setAttribute("aria-expanded", isCollapsed ? "false" : "true");

            announceMessage(
                isCollapsed
                    ? translate("filterGroupCollapsed")
                    : translate("filterGroupExpanded")
            );
        });
    });

    quickFilters.forEach((button) => {
        button.addEventListener("click", () => {
            const type = button.dataset.filterType;
            const value = button.dataset.value;

            button.classList.toggle("active");

            if (type === "inclusive") {
                let checkbox = filterCheckboxes.find((item) => item.dataset.filter === "inclusive");

                if (!checkbox) {
                    checkbox = document.createElement("input");
                    checkbox.type = "checkbox";
                    checkbox.className = "filter-checkbox";
                    checkbox.dataset.filter = "inclusive";
                    checkbox.value = "1";
                    checkbox.style.display = "none";
                    document.body.appendChild(checkbox);
                    filterCheckboxes.push(checkbox);

                    checkbox.addEventListener("change", () => updateFilters(true));
                }

                checkbox.checked = button.classList.contains("active");
                updateFilters(true);
                return;
            }

            filterCheckboxes.forEach((checkbox) => {
                if (checkbox.dataset.filter === type && normalize(checkbox.value) === normalize(value)) {
                    checkbox.checked = button.classList.contains("active");
                }
            });

            updateFilters(true);
        });
    });

    viewButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const view = button.dataset.view;

            viewButtons.forEach((item) => {
                item.classList.remove("active");
                item.setAttribute("aria-pressed", "false");
            });

            button.classList.add("active");
            button.setAttribute("aria-pressed", "true");

            if (jobsGrid) {
                jobsGrid.classList.toggle("list-view", view === "list");
            }

            localStorage.setItem("skillbridgeJobsView", view);

            announceMessage(
                view === "list"
                    ? translate("viewChangedList")
                    : translate("viewChangedGrid")
            );
        });
    });

    const savedView = localStorage.getItem("skillbridgeJobsView");

    if (savedView === "list" && jobsGrid) {
        jobsGrid.classList.add("list-view");

        viewButtons.forEach((button) => {
            const isActive = button.dataset.view === "list";

            button.classList.toggle("active", isActive);
            button.setAttribute("aria-pressed", isActive ? "true" : "false");
        });
    } else {
        viewButtons.forEach((button) => {
            const isActive = button.classList.contains("active");

            button.setAttribute("aria-pressed", isActive ? "true" : "false");
        });
    }

    if (searchInput) {
        searchInput.addEventListener("input", () => updateFilters(true));

        searchInput.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                updateFilters(true);
            }
        });
    }

    if (searchButton) {
        searchButton.addEventListener("click", () => updateFilters(true));
    }

    if (categorySelect) {
        categorySelect.addEventListener("change", () => updateFilters(true));
    }

    filterCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", () => updateFilters(true));
    });

    if (salaryRange) {
        salaryRange.addEventListener("input", () => {
            updateSalaryText();
            updateFilters(true);
        });
    }

    if (sortSelect) {
        sortSelect.addEventListener("change", () => updateFilters(true));
    }

    if (clearFiltersButton) {
        clearFiltersButton.addEventListener("click", () => {
            if (searchInput) searchInput.value = "";
            if (categorySelect) categorySelect.value = "";
            if (salaryRange) salaryRange.value = String(salaryRange.max || maxSalaryValue);

            filterCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });

            quickFilters.forEach((button) => {
                button.classList.remove("active");
            });

            clearUrlParams();
            updateSalaryText();
            updateFilters(false);
            announceMessage(translate("filtersCleared"));
        });
    }

    try {
        savedJobs = JSON.parse(localStorage.getItem(savedJobsKey)) || [];
    } catch (error) {
        savedJobs = [];
    }

    function saveJobs() {
        localStorage.setItem(savedJobsKey, JSON.stringify(savedJobs));
    }

    function getJobName(button) {
        if (button.dataset.job) {
            return button.dataset.job;
        }

        const card = button.closest(".employment-job-card");

        if (!card) {
            return "";
        }

        const title = card.querySelector("h3");

        return title ? title.textContent.trim() : "";
    }

    function updateSaveButton(button, isSaved) {
        const icon = button.querySelector("i");

        button.classList.toggle("saved", isSaved);

        if (icon) {
            icon.className = isSaved
                ? "fa-solid fa-bookmark"
                : "fa-regular fa-bookmark";
        }

        button.setAttribute(
            "aria-label",
            isSaved ? translate("removeSavedJob") : translate("saveJob")
        );

        button.setAttribute(
            "title",
            isSaved ? translate("removeSavedJob") : translate("saveJob")
        );
    }

    document.querySelectorAll(".save-job-button").forEach((button) => {
        const jobName = getJobName(button);

        if (!jobName) {
            return;
        }

        updateSaveButton(button, savedJobs.includes(jobName));

        button.addEventListener("click", () => {
            const index = savedJobs.indexOf(jobName);

            if (index === -1) {
                savedJobs.push(jobName);
                updateSaveButton(button, true);
                announceMessage(translate("jobSaved", { job: jobName }));
            } else {
                savedJobs.splice(index, 1);
                updateSaveButton(button, false);
                announceMessage(translate("jobRemoved", { job: jobName }));
            }

            saveJobs();
        });
    });

    window.addEventListener("skillbridge-language-changed", () => {
        updateSalaryText();
        updateFilters(false);

        document.querySelectorAll(".save-job-button").forEach((button) => {
            const jobName = getJobName(button);

            if (jobName) {
                updateSaveButton(button, savedJobs.includes(jobName));
            }
        });
    });

    applyUrlFilters();
    updateSalaryText();
    updateFilters(false);
});
