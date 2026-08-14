document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;

    /* =========================
       MENÚ MÓVIL
    ========================== */

    const mobileMenuButton = document.getElementById("mobileMenuButton");
    const navLinks = document.getElementById("navLinks");

    if (mobileMenuButton && navLinks) {
        mobileMenuButton.addEventListener("click", () => {
            navLinks.classList.toggle("show");

            const isOpen = navLinks.classList.contains("show");

            mobileMenuButton.innerHTML = isOpen
                ? '<i class="fa-solid fa-xmark"></i>'
                : '<i class="fa-solid fa-bars"></i>';

            mobileMenuButton.setAttribute(
                "aria-label",
                isOpen ? "Cerrar menú de navegación" : "Abrir menú de navegación"
            );
        });
    }

    /* =========================
       PANEL DE ACCESIBILIDAD
    ========================== */

    const accessibilityButton = document.getElementById("accessibilityButton");
    const accessibilityPanel = document.getElementById("accessibilityPanel");
    const closeAccessibility = document.getElementById("closeAccessibility");
    const accessibilityMessage = document.getElementById("accessibilityMessage");

    function announceMessage(message) {
        if (accessibilityMessage) {
            accessibilityMessage.textContent = message;
        }
    }

    if (accessibilityButton && accessibilityPanel) {
        accessibilityButton.addEventListener("click", () => {
            accessibilityPanel.classList.toggle("show");

            const isOpen = accessibilityPanel.classList.contains("show");

            accessibilityButton.setAttribute(
                "aria-label",
                isOpen
                    ? "Cerrar herramientas de accesibilidad"
                    : "Abrir herramientas de accesibilidad"
            );
        });
    }

    if (closeAccessibility && accessibilityPanel) {
        closeAccessibility.addEventListener("click", () => {
            accessibilityPanel.classList.remove("show");
            accessibilityButton.focus();
        });
    }

    /* =========================
       TAMAÑO DE LETRA
    ========================== */

    let fontScale = Number(localStorage.getItem("skillbridgeFontScale")) || 1;

    function applyFontScale() {
        document.documentElement.style.setProperty("--font-scale", fontScale);
        localStorage.setItem("skillbridgeFontScale", fontScale);
    }

    applyFontScale();

    const increaseFont = document.getElementById("increaseFont");
    const decreaseFont = document.getElementById("decreaseFont");

    if (increaseFont) {
        increaseFont.addEventListener("click", () => {
            if (fontScale < 1.25) {
                fontScale += 0.05;
                fontScale = Number(fontScale.toFixed(2));

                applyFontScale();
                announceMessage("El tamaño del texto aumentó.");
            } else {
                announceMessage("El texto ya está en el tamaño máximo.");
            }
        });
    }

    if (decreaseFont) {
        decreaseFont.addEventListener("click", () => {
            if (fontScale > 0.85) {
                fontScale -= 0.05;
                fontScale = Number(fontScale.toFixed(2));

                applyFontScale();
                announceMessage("El tamaño del texto disminuyó.");
            } else {
                announceMessage("El texto ya está en el tamaño mínimo.");
            }
        });
    }

    /* =========================
       MODO OSCURO
    ========================== */

    const darkModeButton = document.getElementById("darkMode");
    const savedDarkMode = localStorage.getItem("skillbridgeDarkMode");

    if (savedDarkMode === "true") {
        body.classList.add("dark-mode");
    }

    if (darkModeButton) {
        darkModeButton.addEventListener("click", () => {
            body.classList.toggle("dark-mode");

            const isDarkMode = body.classList.contains("dark-mode");

            localStorage.setItem("skillbridgeDarkMode", isDarkMode);

            announceMessage(
                isDarkMode
                    ? "Modo oscuro activado."
                    : "Modo oscuro desactivado."
            );
        });
    }

    /* =========================
       ALTO CONTRASTE
    ========================== */

    const highContrastButton = document.getElementById("highContrast");
    const savedHighContrast = localStorage.getItem("skillbridgeHighContrast");

    if (savedHighContrast === "true") {
        body.classList.add("high-contrast");
    }

    if (highContrastButton) {
        highContrastButton.addEventListener("click", () => {
            body.classList.toggle("high-contrast");

            const isHighContrast = body.classList.contains("high-contrast");

            localStorage.setItem("skillbridgeHighContrast", isHighContrast);

            announceMessage(
                isHighContrast
                    ? "Alto contraste activado."
                    : "Alto contraste desactivado."
            );
        });
    }

    /* =========================
       LECTURA POR VOZ
    ========================== */

    const readPageButton = document.getElementById("readPage");
    const stopReadingButton = document.getElementById("stopReading");

    if (readPageButton) {
        readPageButton.addEventListener("click", () => {
            if (!("speechSynthesis" in window)) {
                announceMessage("Tu navegador no permite la lectura por voz.");
                return;
            }

            window.speechSynthesis.cancel();

            const mainContent = document.querySelector("main");

            if (!mainContent) {
                return;
            }

            const textToRead = mainContent.innerText;

            const speech = new SpeechSynthesisUtterance(textToRead);

            speech.lang = "es-SV";
            speech.rate = 0.92;
            speech.pitch = 1;

            window.speechSynthesis.speak(speech);

            announceMessage("Lectura del contenido iniciada.");
        });
    }

    if (stopReadingButton) {
        stopReadingButton.addEventListener("click", () => {
            if ("speechSynthesis" in window) {
                window.speechSynthesis.cancel();
                announceMessage("Lectura detenida.");
            }
        });
    }

    /* =========================
       CONTADORES ANIMADOS
    ========================== */

    const counters = document.querySelectorAll(".counter");

    function animateCounter(counter) {
        const target = Number(counter.dataset.target);
        const duration = 1600;
        const startTime = performance.now();

        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            const value = Math.floor(progress * target);

            counter.textContent = value.toLocaleString("es-SV") + "+";

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString("es-SV") + "+";
            }
        }

        requestAnimationFrame(updateCounter);
    }

    const statsSection = document.querySelector(".stats-section");

    if (statsSection && counters.length > 0) {
        let hasAnimated = false;

        const statsObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting && !hasAnimated) {
                        counters.forEach((counter) => animateCounter(counter));
                        hasAnimated = true;
                    }
                });
            },
            {
                threshold: 0.35
            }
        );

        statsObserver.observe(statsSection);
    }

    /* =========================
       BUSCADOR DE EMPLEOS
    ========================== */

    const jobSearchForm = document.getElementById("jobSearchForm");
    const jobSearch = document.getElementById("jobSearch");
    const jobCategory = document.getElementById("jobCategory");
    const jobMode = document.getElementById("jobMode");

    const jobCards = document.querySelectorAll(".job-card");
    const noResults = document.getElementById("noResults");
    const resultsIndicator = document.getElementById("resultsIndicator");
    const jobsGrid = document.getElementById("jobsGrid");

    if (jobSearchForm) {
        jobSearchForm.addEventListener("submit", (event) => {
            event.preventDefault();

            const searchText = jobSearch.value.trim().toLowerCase();
            const selectedCategory = jobCategory.value;
            const selectedMode = jobMode.value;

            let visibleJobs = 0;

            jobCards.forEach((jobCard) => {
                const jobText = jobCard.dataset.title;
                const category = jobCard.dataset.category;
                const mode = jobCard.dataset.mode;

                const textMatches =
                    searchText === "" || jobText.includes(searchText);

                const categoryMatches =
                    selectedCategory === "todos" ||
                    category === selectedCategory;

                const modeMatches =
                    selectedMode === "todos" ||
                    mode === selectedMode;

                const shouldShow =
                    textMatches &&
                    categoryMatches &&
                    modeMatches;

                jobCard.classList.toggle("hidden", !shouldShow);

                if (shouldShow) {
                    visibleJobs++;
                }
            });

            if (visibleJobs > 0) {
                noResults.classList.add("hidden");

                resultsIndicator.textContent =
                    `Encontramos ${visibleJobs} vacante${visibleJobs !== 1 ? "s" : ""} que coincide${visibleJobs !== 1 ? "n" : ""} con tu búsqueda.`;
            } else {
                noResults.classList.remove("hidden");

                resultsIndicator.textContent =
                    "No se encontraron vacantes con esos filtros.";
            }

            jobsGrid.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        });
    }

    /* =========================
       GUARDAR EMPLEOS
    ========================== */

    const saveButtons = document.querySelectorAll(".save-job-button");

    let savedJobs = JSON.parse(
        localStorage.getItem("skillbridgeSavedJobs")
    ) || [];

    function updateSavedButton(button, isSaved) {
        const icon = button.querySelector("i");

        button.classList.toggle("saved", isSaved);

        icon.className = isSaved
            ? "fa-solid fa-bookmark"
            : "fa-regular fa-bookmark";

        button.setAttribute(
            "aria-label",
            isSaved ? "Quitar empleo guardado" : "Guardar empleo"
        );
    }

    saveButtons.forEach((button) => {
        const jobName = button.dataset.job;

        updateSavedButton(button, savedJobs.includes(jobName));

        button.addEventListener("click", () => {
            const jobIndex = savedJobs.indexOf(jobName);

            if (jobIndex === -1) {
                savedJobs.push(jobName);

                updateSavedButton(button, true);

                announceMessage(`${jobName} fue guardado en tus favoritos.`);
            } else {
                savedJobs.splice(jobIndex, 1);

                updateSavedButton(button, false);

                announceMessage(`${jobName} fue eliminado de tus favoritos.`);
            }

            localStorage.setItem(
                "skillbridgeSavedJobs",
                JSON.stringify(savedJobs)
            );
        });
    });

    /* =========================
       MODAL DE EMPLEOS
    ========================== */

    const jobModal = document.getElementById("jobModal");
    const modalOverlay = document.getElementById("modalOverlay");
    const modalClose = document.getElementById("modalClose");

    const modalJobTitle = document.getElementById("modalJobTitle");
    const modalCompany = document.getElementById("modalCompany");
    const modalLocation = document.getElementById("modalLocation");
    const modalMode = document.getElementById("modalMode");
    const modalDescription = document.getElementById("modalDescription");

    const detailsButtons = document.querySelectorAll(".details-button");
    const applyButton = document.getElementById("applyButton");
    const modalSaveButton = document.getElementById("modalSaveButton");

    let currentModalJob = "";

    function closeModal() {
        jobModal.classList.add("hidden");
        document.body.style.overflow = "";
    }

    function openModal(button) {
        currentModalJob = button.dataset.title;

        modalJobTitle.textContent = button.dataset.title;
        modalCompany.textContent = button.dataset.company;

        modalLocation.innerHTML = `
            <i class="fa-solid fa-location-dot"></i>
            ${button.dataset.location}
        `;

        modalMode.innerHTML = `
            <i class="fa-solid fa-house-laptop"></i>
            ${button.dataset.mode}
        `;

        modalDescription.textContent = button.dataset.description;

        jobModal.classList.remove("hidden");
        document.body.style.overflow = "hidden";

        modalClose.focus();
    }

    detailsButtons.forEach((button) => {
        button.addEventListener("click", () => {
            openModal(button);
        });
    });

    if (modalClose) {
        modalClose.addEventListener("click", closeModal);
    }

    if (modalOverlay) {
        modalOverlay.addEventListener("click", closeModal);
    }

    document.addEventListener("keydown", (event) => {
    if (
        event.key === "Escape" &&
        jobModal &&
        !jobModal.classList.contains("hidden")
    ) {
        closeModal();
    }
});

    if (applyButton) {
        applyButton.addEventListener("click", () => {
            alert(
                `Tu postulación para "${currentModalJob}" fue enviada correctamente.`
            );

            closeModal();
        });
    }

    if (modalSaveButton) {
        modalSaveButton.addEventListener("click", () => {
            if (!currentModalJob) {
                return;
            }

            if (!savedJobs.includes(currentModalJob)) {
                savedJobs.push(currentModalJob);

                localStorage.setItem(
                    "skillbridgeSavedJobs",
                    JSON.stringify(savedJobs)
                );

                modalSaveButton.innerHTML =
                    '<i class="fa-solid fa-bookmark"></i> Empleo guardado';

                announceMessage(`${currentModalJob} fue guardado.`);
            } else {
                announceMessage("Este empleo ya está guardado.");
            }
        });
    }

    /* =========================
       NEWSLETTER
    ========================== */

    const newsletterForm = document.getElementById("newsletterForm");
    const newsletterEmail = document.getElementById("newsletterEmail");
    const newsletterMessage = document.getElementById("newsletterMessage");

    if (newsletterForm) {
        newsletterForm.addEventListener("submit", (event) => {
            event.preventDefault();

            const email = newsletterEmail.value.trim();

            if (email === "") {
                newsletterMessage.textContent =
                    "Escribe un correo válido.";

                return;
            }

            newsletterMessage.textContent =
                "¡Listo! Te has suscrito correctamente.";

            newsletterEmail.value = "";
        });
    }
});