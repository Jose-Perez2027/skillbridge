/* =======================================================
   JAVA JS | SKILLBRIDGE
======================================================= */

document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;


    /* =========================
       LANGUAGE + ALERT HELPERS
    ========================== */

    const languageStorageKey = "skillbridgeLanguage";

    function getCookieValue(name) {
        const cookie = document.cookie
            .split(";")
            .map((item) => item.trim())
            .find((item) => item.startsWith(name + "="));

        return cookie ? decodeURIComponent(cookie.split("=").slice(1).join("=")) : "";
    }

    function getCurrentLanguage() {
        const params = new URLSearchParams(window.location.search);
        const urlLanguage = params.get("lang");
        const pagePreference = window.SkillBridgeUserPreferences?.language;
        const storageLanguage = localStorage.getItem(languageStorageKey);
        const cookieLanguage = getCookieValue(languageStorageKey);
        const htmlLanguage = document.documentElement.getAttribute("lang");

        const language = (urlLanguage || pagePreference || htmlLanguage || storageLanguage || cookieLanguage || "en")
            .toString()
            .toLowerCase()
            .slice(0, 2);

        return ["en", "es"].includes(language) ? language : "en";
    }

    let currentLanguage = getCurrentLanguage();

    document.documentElement.setAttribute("lang", currentLanguage);
    localStorage.setItem(languageStorageKey, currentLanguage);

    function getLocale() {
        return currentLanguage === "es" ? "es-SV" : "en-US";
    }

    function getSpeechLanguage() {
        return currentLanguage === "es" ? "es-SV" : "en-US";
    }

    function escapeHtml(value) {
        const helperElement = document.createElement("div");
        helperElement.textContent = value ?? "";
        return helperElement.innerHTML;
    }

    const interfaceText = {
        en: {
            reset_settings: "Reset settings",
            reading_mode_on: "Reading mode on",
            read_mode: "Read mode",
            selected_option: "Selected option",
            text_field: "Text field.",
            selection_field: "Selection field.",
            button: "Button.",
            image_without_description: "Image without description.",
            remove_saved_job: "Remove saved job",
            save_job: "Save job",
            job_opening: "Job opening",
            company: "Company",
            location_not_specified: "Location not specified",
            work_arrangement_not_specified: "Work arrangement not specified",
            no_description_available: "No description available.",
            job_saved: "Job saved",
            valid_email: "Enter a valid email address.",
            newsletter_success: "All set! You have successfully subscribed.",
            no_results_filters: "No openings were found with those filters.",
            icon_generic: "Icon.",
            password_typing_not_read: "Password typing is not read for privacy.",
            typed_text_reading: "Reading what you typed."
        },
        es: {
            reset_settings: "Restablecer ajustes",
            reading_mode_on: "Modo lectura activado",
            read_mode: "Modo lectura",
            selected_option: "Opción seleccionada",
            text_field: "Campo de texto.",
            selection_field: "Campo de selección.",
            button: "Botón.",
            image_without_description: "Imagen sin descripción.",
            remove_saved_job: "Quitar empleo guardado",
            save_job: "Guardar empleo",
            job_opening: "Vacante",
            company: "Empresa",
            location_not_specified: "Ubicación no especificada",
            work_arrangement_not_specified: "Modalidad no especificada",
            no_description_available: "No hay descripción disponible.",
            job_saved: "Empleo guardado",
            valid_email: "Ingresa un correo electrónico válido.",
            newsletter_success: "¡Listo! Te has suscrito correctamente.",
            no_results_filters: "No se encontraron vacantes con esos filtros.",
            icon_generic: "Ícono.",
            password_typing_not_read: "La escritura de contraseñas no se lee por privacidad.",
            typed_text_reading: "Leyendo lo que escribiste."
        }
    };

    const englishToSpanishMessages = {
        "There is no readable text here.": "No hay texto legible aquí.",
        "Your browser does not support text reading.": "Tu navegador no admite lectura de texto.",
        "Reading selected content.": "Leyendo el contenido seleccionado.",
        "Close navigation menu": "Cerrar menú de navegación",
        "Open navigation menu": "Abrir menú de navegación",
        "Close accessibility tools": "Cerrar herramientas de accesibilidad",
        "Open accessibility tools": "Abrir herramientas de accesibilidad",
        "Accessibility tools opened.": "Herramientas de accesibilidad abiertas.",
        "Accessibility tools closed.": "Herramientas de accesibilidad cerradas.",
        "Text size increased.": "Tamaño de texto aumentado.",
        "Text size is already at the maximum.": "El tamaño de texto ya está al máximo.",
        "Text size decreased.": "Tamaño de texto reducido.",
        "Text size is already at the minimum.": "El tamaño de texto ya está al mínimo.",
        "Dark mode enabled.": "Modo oscuro activado.",
        "Dark mode disabled.": "Modo oscuro desactivado.",
        "High contrast enabled.": "Alto contraste activado.",
        "High contrast disabled.": "Alto contraste desactivado.",
        "Reading mode enabled. Move the cursor over text, images, icons, or controls to hear them.": "Modo lectura activado. Mueve el cursor sobre textos, imágenes, íconos o controles para escucharlos.",
        "Reading mode disabled.": "Modo lectura desactivado.",
        "Accessibility settings were reset.": "Los ajustes de accesibilidad fueron restablecidos.",
        "No openings were found with those filters.": "No se encontraron vacantes con esos filtros.",
        "Enter a valid email address.": "Ingresa un correo electrónico válido.",
        "All set! You have successfully subscribed.": "¡Listo! Te has suscrito correctamente.",
        "Newsletter subscription completed.": "Suscripción al boletín completada.",
        "Image without description.": "Imagen sin descripción.",
        "Text field.": "Campo de texto.",
        "Selection field.": "Campo de selección.",
        "Button.": "Botón.",
        "Icon.": "Ícono.",
        "Accessibility icon.": "Ícono de accesibilidad.",
        "Increase text size icon.": "Ícono para aumentar el tamaño del texto.",
        "Decrease text size icon.": "Ícono para reducir el tamaño del texto.",
        "Dark mode icon.": "Ícono de modo oscuro.",
        "High contrast icon.": "Ícono de alto contraste.",
        "Read mode icon.": "Ícono de modo lectura.",
        "Stop reading icon.": "Ícono para detener lectura.",
        "Reset settings icon.": "Ícono para restablecer ajustes.",
        "Open menu icon.": "Ícono para abrir menú.",
        "Close icon.": "Ícono de cerrar.",
        "Job icon.": "Ícono de empleo.",
        "Company icon.": "Ícono de empresa.",
        "Location icon.": "Ícono de ubicación.",
        "Remote or hybrid work icon.": "Ícono de trabajo remoto o híbrido.",
        "Salary icon.": "Ícono de salario.",
        "Date icon.": "Ícono de fecha.",
        "User icon.": "Ícono de usuario.",
        "Email icon.": "Ícono de correo electrónico.",
        "Password icon.": "Ícono de contraseña.",
        "Phone icon.": "Ícono de teléfono.",
        "Send icon.": "Ícono de enviar.",
        "Success icon.": "Ícono de éxito.",
        "Warning icon.": "Ícono de advertencia.",
        "Save job icon.": "Ícono de guardar empleo.",
        "Continue icon.": "Ícono de continuar.",
        "Log in icon.": "Ícono de iniciar sesión.",
        "Next icon.": "Ícono de siguiente.",
        "Search icon.": "Ícono de búsqueda.",
        "Reading what you typed.": "Leyendo lo que escribiste.",
        "Password typing is not read for privacy.": "La escritura de contraseñas no se lee por privacidad."
    };

    function uiText(key) {
        return interfaceText[currentLanguage]?.[key] || interfaceText.en[key] || key;
    }

    function localizeMessage(message) {
        const originalMessage = (message || "").toString();

        if (currentLanguage !== "es") {
            return originalMessage;
        }

        if (englishToSpanishMessages[originalMessage]) {
            return englishToSpanishMessages[originalMessage];
        }

        let match = originalMessage.match(/^(.+) was saved\.$/);
        if (match) {
            return `${match[1]} fue guardado.`;
        }

        match = originalMessage.match(/^(.+) was removed from saved jobs\.$/);
        if (match) {
            return `${match[1]} fue quitado de empleos guardados.`;
        }

        match = originalMessage.match(/^Details opened for (.+)\.$/);
        if (match) {
            return `Detalles abiertos para ${match[1]}.`;
        }

        match = originalMessage.match(/^Opening selected: (.+)\.$/);
        if (match) {
            return `Vacante seleccionada: ${match[1]}.`;
        }

        match = originalMessage.match(/^(\d+) openings? found\.$/);
        if (match) {
            const total = Number(match[1]);
            return `${total} vacante${total === 1 ? "" : "s"} encontrada${total === 1 ? "" : "s"}.`;
        }

        match = originalMessage.match(/^We found (\d+) openings? that match your search\.$/);
        if (match) {
            const total = Number(match[1]);
            return `Encontramos ${total} vacante${total === 1 ? "" : "s"} que coincide${total === 1 ? "" : "n"} con tu búsqueda.`;
        }

        return originalMessage;
    }

    function setButtonContent(button, iconClass, labelKey) {
        if (!button) {
            return;
        }

        const label = uiText(labelKey);
        button.innerHTML = `
            <i class="${iconClass}" aria-hidden="true"></i>
            <span>${escapeHtml(label)}</span>
        `;
    }

    function setIconAccessibleText(icon, text) {
        if (!icon) {
            return;
        }

        const readableText = localizeMessage(text);
        icon.setAttribute("aria-label", readableText);
        icon.setAttribute("title", readableText);
        icon.setAttribute("role", "img");
    }

    /* =========================
       HELPERS
    ========================== */

    function announceMessage(message) {
        const accessibilityMessage = document.getElementById("accessibilityMessage");
        const localizedMessage = localizeMessage(message);

        if (accessibilityMessage) {
            accessibilityMessage.textContent = localizedMessage;
        }
    }

    function normalizeText(value) {
        return (value || "").toString().trim().toLowerCase();
    }

    function cleanReadableText(text) {
        return (text || "")
            .replace(/\s+/g, " ")
            .replace(/SKILLBRIDGE/gi, "")
            .trim();
    }

    function isHomePage() {
        const path = window.location.pathname.toLowerCase();

        return (
            path.endsWith("/") ||
            path.endsWith("/index.php") ||
            path.endsWith("index.php")
        );
    }

    function setButtonActiveState(button, isActive) {
        if (!button) {
            return;
        }

        button.classList.toggle("active", isActive);
        button.setAttribute("aria-pressed", isActive ? "true" : "false");
    }

    function saveReadModePreferenceInAccount(isEnabled) {
        const payload = JSON.stringify({
            modo_lectura: isEnabled ? 1 : 0
        });

        try {
            if (navigator.sendBeacon) {
                const data = new Blob([payload], { type: "application/json" });
                navigator.sendBeacon("api/preferencias.php", data);
                return;
            }

            fetch("api/preferencias.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: payload,
                credentials: "same-origin",
                keepalive: true
            }).catch(() => {});
        } catch (error) {
            console.warn("SkillBridge read mode preference could not be saved.");
        }
    }

    function stopCurrentReading() {
        if ("speechSynthesis" in window) {
            window.speechSynthesis.cancel();
        }
    }

    function speakText(text) {
        const textToRead = cleanReadableText(text);

        if (textToRead === "") {
            announceMessage("There is no readable text here.");
            return;
        }

        if (!("speechSynthesis" in window)) {
            announceMessage("Your browser does not support text reading.");
            return;
        }

        stopCurrentReading();

        const speech = new SpeechSynthesisUtterance(textToRead);
        speech.lang = getSpeechLanguage();
        speech.rate = 0.92;
        speech.pitch = 1;

        window.speechSynthesis.speak(speech);

        announceMessage("Reading selected content.");
    }

    /* =========================
       MOBILE MENU
    ========================== */

    const mobileMenuButton = document.getElementById("mobileMenuButton");
    const navLinks = document.getElementById("navLinks");

    if (mobileMenuButton && navLinks) {
        mobileMenuButton.setAttribute("aria-expanded", "false");

        mobileMenuButton.addEventListener("click", () => {
            navLinks.classList.toggle("show");

            const isOpen = navLinks.classList.contains("show");

            mobileMenuButton.innerHTML = isOpen
                ? '<i class="fa-solid fa-xmark"></i>'
                : '<i class="fa-solid fa-bars"></i>';

            mobileMenuButton.setAttribute(
                "aria-label",
                localizeMessage(isOpen ? "Close navigation menu" : "Open navigation menu")
            );

            mobileMenuButton.setAttribute("aria-expanded", isOpen ? "true" : "false");
        });

        navLinks.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", () => {
                navLinks.classList.remove("show");

                mobileMenuButton.innerHTML = '<i class="fa-solid fa-bars"></i>';
                mobileMenuButton.setAttribute("aria-label", localizeMessage("Open navigation menu"));
                mobileMenuButton.setAttribute("aria-expanded", "false");
            });
        });
    }

    /* =========================
       ACCESSIBILITY PANEL
    ========================== */

    const accessibilityButton = document.getElementById("accessibilityButton");
    const accessibilityPanel = document.getElementById("accessibilityPanel");
    const closeAccessibility = document.getElementById("closeAccessibility");
    const openAccessibilityFromSection = document.getElementById("openAccessibilityFromSection");
    const accessibilityOptions = document.querySelector(".accessibility-options");

    function openAccessibilityPanel() {
        if (!accessibilityPanel || !accessibilityButton) {
            return;
        }

        accessibilityPanel.classList.add("show");

        accessibilityButton.setAttribute("aria-label", localizeMessage("Close accessibility tools"));
        accessibilityButton.setAttribute("aria-expanded", "true");

        announceMessage("Accessibility tools opened.");
    }

    function closeAccessibilityPanel() {
        if (!accessibilityPanel) {
            return;
        }

        accessibilityPanel.classList.remove("show");

        if (accessibilityButton) {
            accessibilityButton.setAttribute("aria-label", localizeMessage("Open accessibility tools"));
            accessibilityButton.setAttribute("aria-expanded", "false");
        }

        announceMessage("Accessibility tools closed.");
    }

    if (accessibilityButton && accessibilityPanel) {
        accessibilityButton.setAttribute("aria-expanded", "false");

        accessibilityButton.addEventListener("click", (event) => {
            event.stopPropagation();

            const isOpen = accessibilityPanel.classList.contains("show");

            if (isOpen) {
                closeAccessibilityPanel();
            } else {
                openAccessibilityPanel();
            }
        });
    }

    if (closeAccessibility) {
        closeAccessibility.addEventListener("click", (event) => {
            event.stopPropagation();
            closeAccessibilityPanel();
        });
    }

    if (openAccessibilityFromSection) {
        openAccessibilityFromSection.addEventListener("click", openAccessibilityPanel);
    }

    document.addEventListener("keydown", (event) => {
        if (
            event.key === "Escape" &&
            accessibilityPanel &&
            accessibilityPanel.classList.contains("show")
        ) {
            closeAccessibilityPanel();
        }
    });

    /* =========================
       RESET ACCESSIBILITY BUTTON
    ========================== */

    function createResetAccessibilityButton() {
        if (!accessibilityOptions) {
            return null;
        }

        const existingResetButton = document.getElementById("resetAccessibility");

        if (existingResetButton) {
            return existingResetButton;
        }

        const resetButton = document.createElement("button");
        resetButton.type = "button";
        resetButton.className = "accessibility-option reset-accessibility";
        resetButton.id = "resetAccessibility";

        setButtonContent(resetButton, "fa-solid fa-rotate-left", "reset_settings");

        accessibilityOptions.appendChild(resetButton);

        return resetButton;
    }

    const resetAccessibilityButton = createResetAccessibilityButton();

    /* =========================
       FONT SIZE
    ========================== */

    const increaseFont = document.getElementById("increaseFont");
    const decreaseFont = document.getElementById("decreaseFont");

    let fontScale = Number(localStorage.getItem("skillbridgeFontScale")) || 1;

    if (fontScale < 0.85 || fontScale > 1.25) {
        fontScale = 1;
    }

    function applyFontScale(save = true) {
        document.documentElement.style.setProperty("--font-scale", fontScale);

        if (save) {
            localStorage.setItem("skillbridgeFontScale", fontScale);
        }
    }

    applyFontScale(false);

    if (increaseFont) {
        increaseFont.addEventListener("click", () => {
            if (fontScale < 1.25) {
                fontScale += 0.05;
                fontScale = Number(fontScale.toFixed(2));

                applyFontScale();
                announceMessage("Text size increased.");
            } else {
                announceMessage("Text size is already at the maximum.");
            }
        });
    }

    if (decreaseFont) {
        decreaseFont.addEventListener("click", () => {
            if (fontScale > 0.85) {
                fontScale -= 0.05;
                fontScale = Number(fontScale.toFixed(2));

                applyFontScale();
                announceMessage("Text size decreased.");
            } else {
                announceMessage("Text size is already at the minimum.");
            }
        });
    }

    /* =========================
       DARK MODE
    ========================== */

    const darkModeButton = document.getElementById("darkMode");
    const savedDarkMode = localStorage.getItem("skillbridgeDarkMode");

    if (savedDarkMode === "true") {
        body.classList.add("dark-mode");
    }

    setButtonActiveState(darkModeButton, body.classList.contains("dark-mode"));

    if (darkModeButton) {
        darkModeButton.addEventListener("click", () => {
            body.classList.toggle("dark-mode");

            const isDarkModeEnabled = body.classList.contains("dark-mode");

            localStorage.setItem("skillbridgeDarkMode", isDarkModeEnabled);
            setButtonActiveState(darkModeButton, isDarkModeEnabled);

            announceMessage(
                isDarkModeEnabled
                    ? "Dark mode enabled."
                    : "Dark mode disabled."
            );
        });
    }

    /* =========================
       HIGH CONTRAST
    ========================== */

    const highContrastButton = document.getElementById("highContrast");
    const savedHighContrast = localStorage.getItem("skillbridgeHighContrast");

    if (savedHighContrast === "true") {
        body.classList.add("high-contrast");
    }

    setButtonActiveState(highContrastButton, body.classList.contains("high-contrast"));

    if (highContrastButton) {
        highContrastButton.addEventListener("click", () => {
            body.classList.toggle("high-contrast");

            const isHighContrastEnabled = body.classList.contains("high-contrast");

            localStorage.setItem("skillbridgeHighContrast", isHighContrastEnabled);
            setButtonActiveState(highContrastButton, isHighContrastEnabled);

            announceMessage(
                isHighContrastEnabled
                    ? "High contrast enabled."
                    : "High contrast disabled."
            );
        });
    }

    /* =========================
       READING MODE
       One button, hover reading, no click blocking
    ========================== */

    const readButton = document.getElementById("readPage");
    const stopButton = document.getElementById("stopReading");
    const readModeStorageKey = "skillbridgeReadMode";

    let readingModeEnabled = false;
    let lastReadableElement = null;
    let lastReadableText = "";
    let hoverReadingTimer = null;

    function hideStopReadingButton() {
        if (!stopButton) {
            return;
        }

        stopButton.style.display = "none";
        stopButton.setAttribute("aria-hidden", "true");
        stopButton.setAttribute("tabindex", "-1");
    }

    function updateReadButton() {
        if (!readButton) {
            return;
        }

        if (readingModeEnabled) {
            readButton.classList.add("reading-mode-on");
            readButton.classList.add("active");
            readButton.setAttribute("aria-pressed", "true");
            setButtonContent(readButton, "fa-solid fa-volume-high", "reading_mode_on");
        } else {
            readButton.classList.remove("reading-mode-on");
            readButton.classList.remove("active");
            readButton.setAttribute("aria-pressed", "false");
            setButtonContent(readButton, "fa-solid fa-volume-high", "read_mode");
        }
    }

    function enableReadingMode(savePreference = true, showMessage = true) {
        readingModeEnabled = true;
        body.classList.add("reader-mode-enabled");

        if (savePreference) {
            localStorage.setItem(readModeStorageKey, "true");
            saveReadModePreferenceInAccount(true);
        }

        updateReadButton();

        if (showMessage) {
            announceMessage("Reading mode enabled. Move the cursor over text, images, icons, or controls to hear them.");
        }
    }

    function disableReadingMode(savePreference = true, showMessage = true) {
        readingModeEnabled = false;
        body.classList.remove("reader-mode-enabled");

        stopCurrentReading();
        clearTimeout(hoverReadingTimer);
        resetTypingReaderBuffer();

        lastReadableElement = null;
        lastReadableText = "";

        if (savePreference) {
            localStorage.setItem(readModeStorageKey, "false");
            saveReadModePreferenceInAccount(false);
        }

        updateReadButton();

        if (showMessage) {
            announceMessage("Reading mode disabled.");
        }
    }

    function extractSentenceFromText(text, offset) {
        const cleanText = text || "";

        if (cleanText.trim() === "") {
            return "";
        }

        let safeOffset = Number(offset);

        if (Number.isNaN(safeOffset) || safeOffset < 0) {
            safeOffset = 0;
        }

        if (safeOffset > cleanText.length) {
            safeOffset = cleanText.length;
        }

        let start = safeOffset;
        let end = safeOffset;

        const sentenceStartPattern = /[.!?¿¡\n\r]/;
        const sentenceEndPattern = /[.!?\n\r]/;

        while (start > 0 && !sentenceStartPattern.test(cleanText[start - 1])) {
            start--;
        }

        while (end < cleanText.length && !sentenceEndPattern.test(cleanText[end])) {
            end++;
        }

        if (end < cleanText.length) {
            end++;
        }

        const sentence = cleanText.slice(start, end).trim();

        return sentence || cleanText.trim();
    }

    function getTextNodeFromPoint(x, y) {
        if (document.caretPositionFromPoint) {
            const position = document.caretPositionFromPoint(x, y);

            if (
                position &&
                position.offsetNode &&
                position.offsetNode.nodeType === Node.TEXT_NODE
            ) {
                return {
                    node: position.offsetNode,
                    offset: position.offset
                };
            }
        }

        if (document.caretRangeFromPoint) {
            const range = document.caretRangeFromPoint(x, y);

            if (
                range &&
                range.startContainer &&
                range.startContainer.nodeType === Node.TEXT_NODE
            ) {
                return {
                    node: range.startContainer,
                    offset: range.startOffset
                };
            }
        }

        return null;
    }

    function getSentenceFromPointer(event) {
        if (!event || typeof event.clientX !== "number" || typeof event.clientY !== "number") {
            return "";
        }

        const textPoint = getTextNodeFromPoint(event.clientX, event.clientY);

        if (!textPoint || !textPoint.node) {
            return "";
        }

        const sentence = extractSentenceFromText(
            textPoint.node.nodeValue,
            textPoint.offset
        );

        return cleanReadableText(sentence);
    }

    function getLabelTextForControl(element) {
        if (!element) {
            return "";
        }

        if (element.id) {
            const label = document.querySelector(`label[for="${CSS.escape(element.id)}"]`);

            if (label) {
                return cleanReadableText(label.textContent);
            }
        }

        const closestLabel = element.closest("label");

        if (closestLabel) {
            return cleanReadableText(closestLabel.textContent);
        }

        return "";
    }

    function getDirectTextOnly(element) {
        if (!element) {
            return "";
        }

        let text = "";

        element.childNodes.forEach((node) => {
            if (node.nodeType === Node.TEXT_NODE) {
                text += " " + node.textContent;
            }
        });

        return cleanReadableText(text);
    }

    function getIconDescription(icon) {
        if (!icon || !icon.className) {
            return "";
        }

        const classes = icon.className.toString();

        const iconDescriptions = [
            { className: "fa-universal-access", text: "Accessibility icon." },
            { className: "fa-magnifying-glass-plus", text: "Increase text size icon." },
            { className: "fa-magnifying-glass-minus", text: "Decrease text size icon." },
            { className: "fa-moon", text: "Dark mode icon." },
            { className: "fa-circle-half-stroke", text: "High contrast icon." },
            { className: "fa-volume-high", text: "Read mode icon." },
            { className: "fa-volume-xmark", text: "Stop reading icon." },
            { className: "fa-rotate-left", text: "Reset settings icon." },
            { className: "fa-bars", text: "Open menu icon." },
            { className: "fa-xmark", text: "Close icon." },
            { className: "fa-briefcase", text: "Job icon." },
            { className: "fa-building", text: "Company icon." },
            { className: "fa-location-dot", text: "Location icon." },
            { className: "fa-house-laptop", text: "Remote or hybrid work icon." },
            { className: "fa-money-bill-wave", text: "Salary icon." },
            { className: "fa-calendar", text: "Date icon." },
            { className: "fa-user", text: "User icon." },
            { className: "fa-envelope", text: "Email icon." },
            { className: "fa-lock", text: "Password icon." },
            { className: "fa-phone", text: "Phone icon." },
            { className: "fa-paper-plane", text: "Send icon." },
            { className: "fa-circle-check", text: "Success icon." },
            { className: "fa-triangle-exclamation", text: "Warning icon." },
            { className: "fa-bookmark", text: "Save job icon." },
            { className: "fa-arrow-right", text: "Continue icon." },
            { className: "fa-arrow-right-to-bracket", text: "Log in icon." },
            { className: "fa-chevron-right", text: "Next icon." },
            { className: "fa-search", text: "Search icon." },
            { className: "fa-magnifying-glass", text: "Search icon." }
        ];

        const match = iconDescriptions.find((item) => classes.includes(item.className));

        return match ? localizeMessage(match.text) : uiText("icon_generic");
    }

    function getReadableTextFromElement(element, event = null) {
        if (!element) {
            return "";
        }

        const tagName = element.tagName ? element.tagName.toLowerCase() : "";

        if (tagName === "i" || tagName === "svg") {
            return cleanReadableText(
                element.getAttribute("aria-label") ||
                element.getAttribute("title") ||
                getIconDescription(element)
            );
        }

        if (tagName === "img") {
            return cleanReadableText(
                element.getAttribute("alt") ||
                element.getAttribute("title") ||
                element.getAttribute("aria-label") ||
                uiText("image_without_description")
            );
        }

        if (tagName === "input" || tagName === "textarea") {
            const labelText = getLabelTextForControl(element);
            const placeholder = element.getAttribute("placeholder") || "";
            const value = element.value || "";

            return cleanReadableText(
                labelText ||
                placeholder ||
                value ||
                uiText("text_field")
            );
        }

        if (tagName === "select") {
            const labelText = getLabelTextForControl(element);
            const selectedText = element.options[element.selectedIndex]
                ? element.options[element.selectedIndex].text
                : "";

            return cleanReadableText(
                labelText && selectedText
                    ? `${labelText}. ${uiText("selected_option")}: ${selectedText}.`
                    : labelText || selectedText || uiText("selection_field")
            );
        }

        if (tagName === "button" || tagName === "a") {
            return cleanReadableText(
                element.getAttribute("aria-label") ||
                element.textContent ||
                element.getAttribute("title") ||
                uiText("button")
            );
        }

        const sentence = getSentenceFromPointer(event);

        if (sentence !== "") {
            return sentence;
        }

        const directText = getDirectTextOnly(element);

        if (directText !== "") {
            return directText;
        }

        return cleanReadableText(element.textContent || "");
    }

    function getExactReadableElement(target) {
        if (!target) {
            return null;
        }

        if (target.closest("#accessibilityButton")) {
            return target.closest("#accessibilityButton");
        }

        if (target.tagName && target.tagName.toLowerCase() === "i") {
            return target;
        }

        const exactElement = target.closest(
            "img, " +
            "input, " +
            "textarea, " +
            "select, " +
            "button, " +
            "a, " +
            "label, " +
            "i, " +
            "svg, " +
            "h1, " +
            "h2, " +
            "h3, " +
            "h4, " +
            "h5, " +
            "h6, " +
            "p, " +
            "li, " +
            "span, " +
            "small, " +
            "strong, " +
            "em, " +
            "figcaption"
        );

        return exactElement || target;
    }

    function scheduleReading(element, event = null) {
        if (!readingModeEnabled || !element) {
            return;
        }

        const textToRead = getReadableTextFromElement(element, event);

        if (textToRead === "") {
            return;
        }

        if (lastReadableElement === element && lastReadableText === textToRead) {
            return;
        }

        clearTimeout(hoverReadingTimer);

        hoverReadingTimer = setTimeout(() => {
            lastReadableElement = element;
            lastReadableText = textToRead;
            speakText(textToRead);
        }, 260);
    }

    /* =========================
       REAL-TIME TYPING READER
       Reads typed text only when reading mode is enabled.
    ========================== */

    const typingReaderStorageKey = "skillbridgeTypingReader";
    const typingLastValues = new WeakMap();
    let typingPendingText = "";
    let typingActiveElement = null;
    let typingReadingTimer = null;
    let typingCompositionActive = false;

    function isTypingReaderEnabled() {
        return localStorage.getItem(typingReaderStorageKey) !== "false";
    }

    function isReadableTypingField(element) {
        if (!element || !element.tagName) {
            return false;
        }

        if (element.isContentEditable) {
            return true;
        }

        const tagName = element.tagName.toLowerCase();

        if (tagName === "textarea") {
            return true;
        }

        if (tagName !== "input") {
            return false;
        }

        const inputType = (element.getAttribute("type") || "text").toLowerCase();
        const readableTypes = [
            "text",
            "search",
            "email",
            "tel",
            "url",
            "number"
        ];

        return readableTypes.includes(inputType);
    }

    function isPasswordTypingField(element) {
        if (!element || !element.tagName) {
            return false;
        }

        return element.tagName.toLowerCase() === "input" &&
            (element.getAttribute("type") || "text").toLowerCase() === "password";
    }

    function getTypingFieldValue(element) {
        if (!element) {
            return "";
        }

        if (element.isContentEditable) {
            return element.textContent || "";
        }

        return element.value || "";
    }

    function getInsertedTypingText(event, element, previousValue, currentValue) {
        const inputType = event.inputType || "";

        if (inputType.startsWith("delete")) {
            return "";
        }

        if (typeof event.data === "string" && event.data !== "") {
            return event.data;
        }

        if (currentValue.length > previousValue.length && currentValue.startsWith(previousValue)) {
            return currentValue.slice(previousValue.length);
        }

        if (currentValue !== previousValue && inputType.includes("insert")) {
            return currentValue;
        }

        return "";
    }

    function clearTypingReadingTimer() {
        clearTimeout(typingReadingTimer);
        typingReadingTimer = null;
    }

    function resetTypingReaderBuffer() {
        clearTypingReadingTimer();
        typingPendingText = "";
        typingActiveElement = null;
    }

    function readPendingTypedText() {
        const textToRead = cleanReadableText(typingPendingText);

        resetTypingReaderBuffer();

        if (!readingModeEnabled || textToRead === "") {
            return;
        }

        speakText(textToRead);
        announceMessage(uiText("typed_text_reading"));
    }

    function scheduleTypedTextReading(insertedText) {
        clearTypingReadingTimer();

        const shouldReadSoon = /[.!?¿¡\n\r]$/.test(insertedText || "");

        const delay = shouldReadSoon ? 280 : 700;

        typingReadingTimer = setTimeout(readPendingTypedText, delay);
    }

    document.addEventListener(
        "focusin",
        (event) => {
            const field = event.target;

            if (isReadableTypingField(field) || isPasswordTypingField(field)) {
                typingLastValues.set(field, getTypingFieldValue(field));
            }
        },
        true
    );

    document.addEventListener(
        "compositionstart",
        (event) => {
            if (isReadableTypingField(event.target)) {
                typingCompositionActive = true;
            }
        },
        true
    );

    document.addEventListener(
        "compositionend",
        (event) => {
            if (!isReadableTypingField(event.target)) {
                return;
            }

            typingCompositionActive = false;
            typingLastValues.set(event.target, getTypingFieldValue(event.target));
        },
        true
    );

    document.addEventListener(
        "input",
        (event) => {
            if (!readingModeEnabled || !isTypingReaderEnabled()) {
                return;
            }

            const field = event.target;

            if (isPasswordTypingField(field)) {
                announceMessage(uiText("password_typing_not_read"));
                typingLastValues.set(field, getTypingFieldValue(field));
                return;
            }

            if (!isReadableTypingField(field) || typingCompositionActive) {
                return;
            }

            const currentValue = getTypingFieldValue(field);
            const previousValue = typingLastValues.get(field) ?? currentValue.slice(0, Math.max(0, currentValue.length - 1));
            const insertedText = getInsertedTypingText(event, field, previousValue, currentValue);

            typingLastValues.set(field, currentValue);

            if (insertedText === "") {
                return;
            }

            if (typingActiveElement !== field) {
                resetTypingReaderBuffer();
                typingActiveElement = field;
            }

            typingPendingText += insertedText;
            scheduleTypedTextReading(insertedText);
        },
        true
    );

    hideStopReadingButton();

    if (localStorage.getItem(readModeStorageKey) === "true") {
        enableReadingMode(false, false);
    } else {
        updateReadButton();
    }

    if (readButton) {
        readButton.addEventListener("click", (event) => {
            event.preventDefault();
            event.stopPropagation();

            if (readingModeEnabled) {
                disableReadingMode(true, true);
            } else {
                enableReadingMode(true, true);
            }
        });
    }

    document.addEventListener(
        "mousemove",
        (event) => {
            if (!readingModeEnabled) {
                return;
            }

            const readableElement = getExactReadableElement(event.target);

            if (!readableElement) {
                return;
            }

            scheduleReading(readableElement, event);
        },
        true
    );

    document.addEventListener(
        "focusin",
        (event) => {
            if (!readingModeEnabled) {
                return;
            }

            const readableElement = getExactReadableElement(event.target);

            if (!readableElement) {
                return;
            }

            scheduleReading(readableElement, null);
        },
        true
    );

    document.addEventListener(
        "mouseout",
        (event) => {
            if (!readingModeEnabled) {
                return;
            }

            const readableElement = getExactReadableElement(event.target);

            if (readableElement && !readableElement.contains(event.relatedTarget)) {
                clearTimeout(hoverReadingTimer);
            }
        },
        true
    );

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && readingModeEnabled) {
            disableReadingMode(true, true);
        }
    });

    window.addEventListener("beforeunload", () => {
        stopCurrentReading();
    });

    /* =========================
       RESET ACCESSIBILITY ACTION
    ========================== */

    if (resetAccessibilityButton) {
        resetAccessibilityButton.addEventListener("click", () => {
            body.classList.remove("dark-mode");
            body.classList.remove("high-contrast");
            body.classList.remove("reader-mode-enabled");

            fontScale = 1;
            applyFontScale(false);

            localStorage.removeItem("skillbridgeDarkMode");
            localStorage.removeItem("skillbridgeHighContrast");
            localStorage.removeItem("skillbridgeFontScale");
            localStorage.removeItem("skillbridgeReadMode");
            localStorage.removeItem("skillbridgeTypingReader");

            localStorage.removeItem("skillbridge-dark-mode");
            localStorage.removeItem("skillbridge-high-contrast");
            localStorage.removeItem("skillbridge-font-size");

            setButtonActiveState(darkModeButton, false);
            setButtonActiveState(highContrastButton, false);

            if (readingModeEnabled) {
                disableReadingMode();
            }

            announceMessage("Accessibility settings were reset.");
        });
    }

    /* =========================
       ANIMATED COUNTERS
    ========================== */

    const counters = document.querySelectorAll(".counter");

    function animateCounter(counter) {
        const target = Number(counter.dataset.target || 0);

        if (target <= 0) {
            counter.textContent = "0+";
            return;
        }

        const duration = 1600;
        const startTime = performance.now();

        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const value = Math.floor(progress * target);

            counter.textContent = value.toLocaleString(getLocale()) + "+";

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString(getLocale()) + "+";
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
       HOME JOB SEARCH INTELIGENTE
    ========================== */

    const jobSearchForm = document.getElementById("jobSearchForm");
    const jobSearch = document.getElementById("jobSearch");
    const jobCategory = document.getElementById("jobCategory");
    const jobMode = document.getElementById("jobMode");

    const jobCards = document.querySelectorAll(".job-card");
    const noResults = document.getElementById("noResults");
    const resultsIndicator = document.getElementById("resultsIndicator");
    const jobsGrid = document.getElementById("jobsGrid");

    function getJobCardTitle(jobCard) {
        const titleElement = jobCard.querySelector("h3");

        if (titleElement) {
            return normalizeText(titleElement.textContent);
        }

        return normalizeText(jobCard.dataset.title);
    }

    function getJobCardUrl(jobCard) {
        const detailsButton = jobCard.querySelector(".details-button");
        const directLink = jobCard.querySelector("a[href*='detalle-empleo.php']");

        if (jobCard.dataset.url) {
            return jobCard.dataset.url;
        }

        if (jobCard.dataset.detailUrl) {
            return jobCard.dataset.detailUrl;
        }

        if (detailsButton) {
            if (detailsButton.dataset.url) {
                return detailsButton.dataset.url;
            }

            if (detailsButton.dataset.detailUrl) {
                return detailsButton.dataset.detailUrl;
            }

            if (detailsButton.dataset.id) {
                return `detalle-empleo.php?id=${detailsButton.dataset.id}`;
            }

            if (detailsButton.dataset.jobId) {
                return `detalle-empleo.php?id=${detailsButton.dataset.jobId}`;
            }

            const href = detailsButton.getAttribute("href");

            if (href) {
                return href;
            }
        }

        if (directLink) {
            return directLink.getAttribute("href");
        }

        return "";
    }

    function getMatchingHomeJobs(searchText, selectedCategory, selectedMode) {
        const matches = [];

        jobCards.forEach((jobCard) => {
            const jobText = normalizeText(jobCard.dataset.title);
            const fullSearchText = normalizeText(jobCard.dataset.search);
            const title = getJobCardTitle(jobCard);
            const category = normalizeText(jobCard.dataset.category);
            const mode = normalizeText(jobCard.dataset.mode || jobCard.dataset.modality);
            const url = getJobCardUrl(jobCard);

            const textMatches =
                searchText === "" ||
                title === searchText ||
                title.includes(searchText) ||
                jobText.includes(searchText) ||
                fullSearchText.includes(searchText);

            const categoryMatches =
                selectedCategory === "todos" ||
                selectedCategory === "all" ||
                category === selectedCategory;

            const modeMatches =
                selectedMode === "todos" ||
                selectedMode === "all" ||
                mode === selectedMode;

            if (textMatches && categoryMatches && modeMatches) {
                matches.push({
                    card: jobCard,
                    title,
                    url
                });
            }
        });

        return matches;
    }

    function filterHomeCards(searchText, selectedCategory, selectedMode) {
        const matches = getMatchingHomeJobs(searchText, selectedCategory, selectedMode);
        let visibleJobs = 0;

        jobCards.forEach((jobCard) => {
            const shouldShow = matches.some((match) => match.card === jobCard);

            jobCard.classList.toggle("hidden", !shouldShow);

            if (shouldShow) {
                visibleJobs++;
            }
        });

        if (noResults && resultsIndicator) {
            if (visibleJobs > 0) {
                noResults.classList.add("hidden");

                resultsIndicator.textContent = currentLanguage === "es"
                    ? `Encontramos ${visibleJobs} vacante${visibleJobs !== 1 ? "s" : ""} que coincide${visibleJobs !== 1 ? "n" : ""} con tu búsqueda.`
                    : `We found ${visibleJobs} opening${visibleJobs !== 1 ? "s" : ""} that match your search.`;
            } else {
                noResults.classList.remove("hidden");

                resultsIndicator.textContent = uiText("no_results_filters");
            }
        }

        if (jobsGrid) {
            jobsGrid.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }

        announceMessage(
            `${visibleJobs} opening${visibleJobs !== 1 ? "s" : ""} found.`
        );
    }

    function redirectToJobs(searchText, selectedCategory, selectedMode) {
        const matches = getMatchingHomeJobs(searchText, selectedCategory, selectedMode);

        if (matches.length === 1 && matches[0].url !== "") {
            window.location.href = matches[0].url;
            return;
        }

        const exactMatches = matches.filter((match) => {
            return match.title === searchText && match.url !== "";
        });

        if (exactMatches.length === 1) {
            window.location.href = exactMatches[0].url;
            return;
        }

        const params = new URLSearchParams();

        if (currentLanguage) {
            params.set("lang", currentLanguage);
        }

        if (searchText !== "") {
            params.set("search", searchText);
            params.set("q", searchText);
        }

        if (selectedCategory !== "todos" && selectedCategory !== "all") {
            params.set("category", selectedCategory);
            params.set("categoria", selectedCategory);
        }

        if (selectedMode !== "todos" && selectedMode !== "all") {
            params.set("mode", selectedMode);
            params.set("modalidad", selectedMode);
        }

        const queryString = params.toString();

        window.location.href = queryString
            ? `empleos.php?${queryString}`
            : "empleos.php";
    }

    if (jobSearchForm && jobSearch && jobCategory && jobMode) {
        jobSearchForm.addEventListener("submit", (event) => {
            event.preventDefault();

            const searchText = normalizeText(jobSearch.value);
            const selectedCategory = normalizeText(jobCategory.value || "todos");
            const selectedMode = normalizeText(jobMode.value || "todos");

            if (isHomePage()) {
                redirectToJobs(searchText, selectedCategory, selectedMode);
                return;
            }

            filterHomeCards(searchText, selectedCategory, selectedMode);
        });
    }

    /* =========================
       SAVE JOBS
    ========================== */

    const saveButtons = document.querySelectorAll(".save-job-button");

    let savedJobs = [];

    try {
        savedJobs = JSON.parse(
            localStorage.getItem("skillbridgeSavedJobs")
        ) || [];
    } catch (error) {
        savedJobs = [];
    }

    function saveJobsInStorage() {
        localStorage.setItem(
            "skillbridgeSavedJobs",
            JSON.stringify(savedJobs)
        );
    }

    function updateSavedButton(button, isSaved) {
        const icon = button.querySelector("i");

        button.classList.toggle("saved", isSaved);

        if (icon) {
            icon.className = isSaved
                ? "fa-solid fa-bookmark"
                : "fa-regular fa-bookmark";
        }

        button.setAttribute(
            "aria-label",
            isSaved ? uiText("remove_saved_job") : uiText("save_job")
        );
    }

    function getJobNameFromButton(button) {
        if (button.dataset.job) {
            return button.dataset.job;
        }

        const card = button.closest(".job-card, .employment-job-card");

        if (card) {
            const title = card.querySelector("h3");

            if (title) {
                return title.textContent.trim();
            }
        }

        return "";
    }

    saveButtons.forEach((button) => {
        const jobName = getJobNameFromButton(button);

        if (!jobName) {
            return;
        }

        updateSavedButton(button, savedJobs.includes(jobName));

        button.addEventListener("click", () => {
            const jobIndex = savedJobs.indexOf(jobName);

            if (jobIndex === -1) {
                savedJobs.push(jobName);

                updateSavedButton(button, true);

                announceMessage(`${jobName} was saved.`);
            } else {
                savedJobs.splice(jobIndex, 1);

                updateSavedButton(button, false);

                announceMessage(`${jobName} was removed from saved jobs.`);
            }

            saveJobsInStorage();
        });
    });

    /* =========================
       JOB MODAL
       Solo intercepta .details-button si existe #jobModal.
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
    let currentModalUrl = "empleos.php";

    function setIconText(container, iconClass, text) {
        if (!container) {
            return;
        }

        container.innerHTML = "";

        const icon = document.createElement("i");
        icon.className = iconClass;
        setIconAccessibleText(icon, text);

        const span = document.createElement("span");
        span.textContent = text;

        container.appendChild(icon);
        container.appendChild(span);
    }

    function closeModal() {
        if (!jobModal) {
            return;
        }

        jobModal.classList.add("hidden");
        document.body.style.overflow = "";
    }

    function openModal(button) {
        if (
            !jobModal ||
            !modalJobTitle ||
            !modalCompany ||
            !modalLocation ||
            !modalMode ||
            !modalDescription
        ) {
            return;
        }

        currentModalJob = button.dataset.title || uiText("job_opening");
        currentModalUrl =
            button.dataset.url ||
            button.dataset.detailUrl ||
            button.getAttribute("href") ||
            "empleos.php";

        modalJobTitle.textContent = button.dataset.title || uiText("job_opening");
        modalCompany.textContent = button.dataset.company || uiText("company");

        setIconText(
            modalLocation,
            "fa-solid fa-location-dot",
            button.dataset.location || uiText("location_not_specified")
        );

        setIconText(
            modalMode,
            "fa-solid fa-house-laptop",
            button.dataset.mode || button.dataset.modality || uiText("work_arrangement_not_specified")
        );

        modalDescription.textContent =
            button.dataset.description || uiText("no_description_available");

        if (applyButton) {
            applyButton.href = currentModalUrl;
        }

        if (modalSaveButton) {
            const isSaved = savedJobs.includes(currentModalJob);

            modalSaveButton.innerHTML = isSaved
                ? `<i class="fa-solid fa-bookmark" aria-hidden="true"></i> ${escapeHtml(uiText("job_saved"))}`
                : `<i class="fa-regular fa-bookmark" aria-hidden="true"></i> ${escapeHtml(uiText("save_job"))}`;
        }

        jobModal.classList.remove("hidden");
        document.body.style.overflow = "hidden";

        if (modalClose) {
            modalClose.focus();
        }

        announceMessage(`Details opened for ${currentModalJob}.`);
    }

    if (jobModal) {
        detailsButtons.forEach((button) => {
            button.addEventListener("click", (event) => {
                if (button.tagName.toLowerCase() === "a") {
                    event.preventDefault();
                }

                openModal(button);
            });
        });
    }

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
            if (currentModalJob) {
                announceMessage(`Opening selected: ${currentModalJob}.`);
            }

            closeModal();
        });
    }

    if (modalSaveButton) {
        modalSaveButton.addEventListener("click", () => {
            if (!currentModalJob) {
                return;
            }

            const jobIndex = savedJobs.indexOf(currentModalJob);

            if (jobIndex === -1) {
                savedJobs.push(currentModalJob);

                modalSaveButton.innerHTML =
                    `<i class="fa-solid fa-bookmark" aria-hidden="true"></i> ${escapeHtml(uiText("job_saved"))}`;

                announceMessage(`${currentModalJob} was saved.`);
            } else {
                savedJobs.splice(jobIndex, 1);

                modalSaveButton.innerHTML =
                    `<i class="fa-regular fa-bookmark" aria-hidden="true"></i> ${escapeHtml(uiText("save_job"))}`;

                announceMessage(`${currentModalJob} was removed from saved jobs.`);
            }

            saveJobsInStorage();

            saveButtons.forEach((button) => {
                const jobName = getJobNameFromButton(button);

                if (jobName === currentModalJob) {
                    updateSavedButton(button, savedJobs.includes(currentModalJob));
                }
            });
        });
    }

    /* =========================
       NEWSLETTER
    ========================== */

    const newsletterForm = document.getElementById("newsletterForm");
    const newsletterEmail = document.getElementById("newsletterEmail");
    const newsletterMessage = document.getElementById("newsletterMessage");

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    if (newsletterForm && newsletterEmail && newsletterMessage) {
        newsletterForm.addEventListener("submit", (event) => {
            event.preventDefault();

            const email = newsletterEmail.value.trim();

            if (!isValidEmail(email)) {
                newsletterMessage.textContent = uiText("valid_email");
                newsletterMessage.classList.add("error");
                newsletterMessage.classList.remove("success");

                announceMessage("Enter a valid email address.");
                return;
            }

            newsletterMessage.textContent =
                uiText("newsletter_success");

            newsletterMessage.classList.add("success");
            newsletterMessage.classList.remove("error");

            newsletterEmail.value = "";

            announceMessage("Newsletter subscription completed.");
        });
    }

    function refreshDynamicLanguageLabels() {
        if (resetAccessibilityButton) {
            setButtonContent(resetAccessibilityButton, "fa-solid fa-rotate-left", "reset_settings");
        }

        updateReadButton();

        document.querySelectorAll("i[class*='fa-']").forEach((icon) => {
            if (!icon.getAttribute("aria-label") && !icon.getAttribute("title")) {
                setIconAccessibleText(icon, getIconDescription(icon));
            }
        });
    }

    refreshDynamicLanguageLabels();

});