document.addEventListener("DOMContentLoaded", () => {
    const employmentSearchForm = document.getElementById("employmentSearchForm");
    const employmentSearch = document.getElementById("employmentSearch");
    const employmentLocation = document.getElementById("employmentLocation");

    const categoryFilters = document.querySelectorAll(".category-filter");
    const modeFilters = document.querySelectorAll(".mode-filter");
    const experienceFilters = document.querySelectorAll(".experience-filter");

    const salaryRange = document.getElementById("salaryRange");
    const salaryValue = document.getElementById("salaryValue");

    const jobCards = Array.from(
        document.querySelectorAll(".employment-job-card")
    );

    const jobsGrid = document.getElementById("employmentJobsGrid");
    const jobsResultsTitle = document.getElementById("jobsResultsTitle");
    const jobsResultsText = document.getElementById("jobsResultsText");
    const employmentNoResults = document.getElementById("employmentNoResults");

    const activeFilters = document.getElementById("activeFilters");

    const sortJobs = document.getElementById("sortJobs");

    const gridViewButton = document.getElementById("gridViewButton");
    const listViewButton = document.getElementById("listViewButton");

    const clearFiltersButton = document.getElementById("clearFiltersButton");
    const emptyClearFiltersButton = document.getElementById("emptyClearFiltersButton");

    const quickFilters = document.querySelectorAll(".quick-filter");

    const previousPageButton = document.getElementById("previousPageButton");
    const nextPageButton = document.getElementById("nextPageButton");
    const paginationNumbers = document.getElementById("paginationNumbers");
    const jobsPagination = document.getElementById("jobsPagination");

    const filterTitles = document.querySelectorAll(".filter-title");

    let currentPage = 1;
    const jobsPerPage = 6;

    let filteredJobs = [...jobCards];

    /* =========================
       UTILIDADES
    ========================== */

    function getCheckedValues(filterElements) {
        return Array.from(filterElements)
            .filter((filter) => filter.checked)
            .map((filter) => filter.value);
    }

    function getCurrentFilters() {
        return {
            search: employmentSearch.value.trim().toLowerCase(),
            location: employmentLocation.value,
            categories: getCheckedValues(categoryFilters),
            modes: getCheckedValues(modeFilters),
            experiences: getCheckedValues(experienceFilters),
            maxSalary: Number(salaryRange.value)
        };
    }

    function formatSalary(salary) {
        return Number(salary).toLocaleString("en-US");
    }

    /* =========================
       MOSTRAR FILTROS ACTIVOS
    ========================== */

    function createFilterTag(label, action) {
        const tag = document.createElement("div");

        tag.className = "active-filter-tag";

        tag.innerHTML = `
            <span>${label}</span>
            <button aria-label="Quitar filtro ${label}">
                <i class="fa-solid fa-xmark"></i>
            </button>
        `;

        tag.querySelector("button").addEventListener("click", action);

        activeFilters.appendChild(tag);
    }

    function updateActiveFilters() {
        activeFilters.innerHTML = "";

        const filters = getCurrentFilters();

        if (filters.search) {
            createFilterTag(`Búsqueda: ${employmentSearch.value}`, () => {
                employmentSearch.value = "";
                applyFilters();
            });
        }

        if (filters.location !== "todos") {
            const locationText =
                employmentLocation.options[employmentLocation.selectedIndex].text;

            createFilterTag(locationText, () => {
                employmentLocation.value = "todos";
                applyFilters();
            });
        }

        filters.categories.forEach((category) => {
            const filter = document.querySelector(
                `.category-filter[value="${category}"]`
            );

            const label = filter.parentElement.textContent
                .replace(/[0-9]/g, "")
                .trim();

            createFilterTag(label, () => {
                filter.checked = false;
                applyFilters();
            });
        });

        filters.modes.forEach((mode) => {
            const filter = document.querySelector(
                `.mode-filter[value="${mode}"]`
            );

            const label = filter.parentElement.textContent
                .replace(/[0-9]/g, "")
                .trim();

            createFilterTag(label, () => {
                filter.checked = false;
                applyFilters();
            });
        });

        filters.experiences.forEach((experience) => {
            const filter = document.querySelector(
                `.experience-filter[value="${experience}"]`
            );

            const label = filter.parentElement.textContent
                .replace(/[0-9]/g, "")
                .trim();

            createFilterTag(label, () => {
                filter.checked = false;
                applyFilters();
            });
        });

        if (filters.maxSalary < 2000) {
            createFilterTag(`Hasta $${formatSalary(filters.maxSalary)}`, () => {
                salaryRange.value = 2000;
                salaryValue.textContent = "$2,000";
                applyFilters();
            });
        }
    }

    /* =========================
       FILTRAR EMPLEOS
    ========================== */

    function applyFilters() {
        const filters = getCurrentFilters();

        filteredJobs = jobCards.filter((job) => {
            const jobText = job.dataset.title;
            const jobCategory = job.dataset.category;
            const jobMode = job.dataset.mode;
            const jobLocation = job.dataset.location;
            const jobExperience = job.dataset.experience;
            const jobSalary = Number(job.dataset.salary);

            const searchMatches =
                filters.search === "" ||
                jobText.includes(filters.search);

            const locationMatches =
                filters.location === "todos" ||
                jobLocation === filters.location;

            const categoryMatches =
                filters.categories.length === 0 ||
                filters.categories.includes(jobCategory);

            const modeMatches =
                filters.modes.length === 0 ||
                filters.modes.includes(jobMode);

            const experienceMatches =
                filters.experiences.length === 0 ||
                filters.experiences.includes(jobExperience);

            const salaryMatches =
                jobSalary <= filters.maxSalary;

            return (
                searchMatches &&
                locationMatches &&
                categoryMatches &&
                modeMatches &&
                experienceMatches &&
                salaryMatches
            );
        });

        sortFilteredJobs();

        currentPage = 1;

        updateActiveFilters();
        renderJobs();
    }

    /* =========================
       ORDENAR EMPLEOS
    ========================== */

    function sortFilteredJobs() {
        const sortValue = sortJobs.value;

        filteredJobs.sort((a, b) => {
            const salaryA = Number(a.dataset.salary);
            const salaryB = Number(b.dataset.salary);

            const dateA = Number(a.dataset.date);
            const dateB = Number(b.dataset.date);

            const titleA = a.querySelector("h3").textContent;
            const titleB = b.querySelector("h3").textContent;

            if (sortValue === "salary-high") {
                return salaryB - salaryA;
            }

            if (sortValue === "salary-low") {
                return salaryA - salaryB;
            }

            if (sortValue === "alphabetical") {
                return titleA.localeCompare(titleB);
            }

            return dateB - dateA;
        });
    }

    /* =========================
       RENDERIZAR EMPLEOS
    ========================== */

    function renderJobs() {
        const totalJobs = filteredJobs.length;
        const totalPages = Math.ceil(totalJobs / jobsPerPage);

        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }

        jobCards.forEach((job) => {
            job.classList.add("hidden");
        });

        const startIndex = (currentPage - 1) * jobsPerPage;
        const endIndex = startIndex + jobsPerPage;

        const jobsToShow = filteredJobs.slice(startIndex, endIndex);

        jobsToShow.forEach((job) => {
            job.classList.remove("hidden");
            jobsGrid.appendChild(job);
        });

        updateResultsText(totalJobs);
        updateNoResults(totalJobs);
        renderPagination(totalPages);
    }

    function updateResultsText(totalJobs) {
        if (totalJobs === 0) {
            jobsResultsTitle.textContent = "No encontramos empleos";
            jobsResultsText.textContent =
                "Prueba cambiar los filtros para ampliar tu búsqueda.";
            return;
        }

        jobsResultsTitle.textContent =
            `${totalJobs} empleo${totalJobs !== 1 ? "s" : ""} encontrado${totalJobs !== 1 ? "s" : ""}`;

        if (totalJobs === jobCards.length) {
            jobsResultsText.textContent =
                "Explora vacantes según tus intereses profesionales.";
        } else {
            jobsResultsText.textContent =
                "Estos resultados coinciden con los filtros que seleccionaste.";
        }
    }

    function updateNoResults(totalJobs) {
        if (totalJobs === 0) {
            employmentNoResults.classList.remove("hidden");
            jobsPagination.classList.add("hidden");
        } else {
            employmentNoResults.classList.add("hidden");
            jobsPagination.classList.remove("hidden");
        }
    }

    /* =========================
       PAGINACIÓN
    ========================== */

    function renderPagination(totalPages) {
        paginationNumbers.innerHTML = "";

        if (totalPages <= 1) {
            jobsPagination.classList.add("hidden");
            return;
        }

        jobsPagination.classList.remove("hidden");

        for (let page = 1; page <= totalPages; page++) {
            const pageButton = document.createElement("button");

            pageButton.textContent = page;
            pageButton.className = "pagination-number";

            if (page === currentPage) {
                pageButton.classList.add("active");
            }

            pageButton.addEventListener("click", () => {
                currentPage = page;
                renderJobs();

                jobsGrid.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            });

            paginationNumbers.appendChild(pageButton);
        }

        previousPageButton.disabled = currentPage === 1;
        nextPageButton.disabled = currentPage === totalPages;
    }

    previousPageButton.addEventListener("click", () => {
        if (currentPage > 1) {
            currentPage--;
            renderJobs();

            jobsGrid.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }
    });

    nextPageButton.addEventListener("click", () => {
        const totalPages = Math.ceil(filteredJobs.length / jobsPerPage);

        if (currentPage < totalPages) {
            currentPage++;
            renderJobs();

            jobsGrid.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }
    });

    /* =========================
       LIMPIAR FILTROS
    ========================== */

    function clearAllFilters() {
        employmentSearch.value = "";
        employmentLocation.value = "todos";

        categoryFilters.forEach((filter) => {
            filter.checked = false;
        });

        modeFilters.forEach((filter) => {
            filter.checked = false;
        });

        experienceFilters.forEach((filter) => {
            filter.checked = false;
        });

        salaryRange.value = 2000;
        salaryValue.textContent = "$2,000";

        quickFilters.forEach((button) => {
            button.classList.remove("active");
        });

        document
            .querySelector('[data-quick-filter="todos"]')
            .classList.add("active");

        applyFilters();
    }

    clearFiltersButton.addEventListener("click", clearAllFilters);
    emptyClearFiltersButton.addEventListener("click", clearAllFilters);

    /* =========================
       EVENTOS DE FILTROS
    ========================== */

    employmentSearchForm.addEventListener("submit", (event) => {
        event.preventDefault();
        applyFilters();

        jobsGrid.scrollIntoView({
            behavior: "smooth",
            block: "start"
        });
    });

    employmentSearch.addEventListener("input", () => {
        applyFilters();
    });

    employmentLocation.addEventListener("change", applyFilters);

    categoryFilters.forEach((filter) => {
        filter.addEventListener("change", () => {
            quickFilters.forEach((button) => {
                button.classList.remove("active");
            });

            applyFilters();
        });
    });

    modeFilters.forEach((filter) => {
        filter.addEventListener("change", applyFilters);
    });

    experienceFilters.forEach((filter) => {
        filter.addEventListener("change", applyFilters);
    });

    salaryRange.addEventListener("input", () => {
        salaryValue.textContent =
            `$${formatSalary(salaryRange.value)}`;

        applyFilters();
    });

    sortJobs.addEventListener("change", () => {
        sortFilteredJobs();
        currentPage = 1;
        renderJobs();
    });

    /* =========================
       FILTROS RÁPIDOS
    ========================== */

    quickFilters.forEach((button) => {
        button.addEventListener("click", () => {
            const selectedCategory = button.dataset.quickFilter;

            quickFilters.forEach((item) => {
                item.classList.remove("active");
            });

            button.classList.add("active");

            categoryFilters.forEach((filter) => {
                filter.checked = filter.value === selectedCategory;
            });

            if (selectedCategory === "todos") {
                categoryFilters.forEach((filter) => {
                    filter.checked = false;
                });
            }

            applyFilters();
        });
    });

    /* =========================
       VISTA CUADRÍCULA / LISTA
    ========================== */

    gridViewButton.addEventListener("click", () => {
        jobsGrid.classList.remove("list-view");

        gridViewButton.classList.add("active");
        listViewButton.classList.remove("active");

        localStorage.setItem("skillbridgeJobsView", "grid");
    });

    listViewButton.addEventListener("click", () => {
        jobsGrid.classList.add("list-view");

        listViewButton.classList.add("active");
        gridViewButton.classList.remove("active");

        localStorage.setItem("skillbridgeJobsView", "list");
    });

    function loadSavedView() {
        const savedView = localStorage.getItem("skillbridgeJobsView");

        if (savedView === "list") {
            jobsGrid.classList.add("list-view");

            listViewButton.classList.add("active");
            gridViewButton.classList.remove("active");
        }
    }

    /* =========================
       COLLAPSE DE FILTROS
    ========================== */

    filterTitles.forEach((title) => {
        title.addEventListener("click", () => {
            const options = title.nextElementSibling;

            title.classList.toggle("collapsed");
            options.classList.toggle("collapsed");
        });
    });

    /* =========================
       BOTONES POSTULARME
    ========================== */

    const applyButtons = document.querySelectorAll(
        ".employment-card-actions .mini-apply-button"
    );

    applyButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const card = button.closest(".employment-job-card");
            const jobName = card.querySelector("h3").textContent;

            alert(
                `Tu postulación para "${jobName}" fue enviada correctamente.`
            );
        });
    });

    /* =========================
       INICIALIZAR
    ========================== */

    loadSavedView();
    sortFilteredJobs();
    updateActiveFilters();
    renderJobs();
});