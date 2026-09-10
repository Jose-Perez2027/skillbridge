/* =======================================================
   PERFIL JS | SKILLBRIDGE
   Alertas y mensajes dinámicos EN/ES
======================================================= */

document.addEventListener("DOMContentLoaded", () => {
    const downloadResumeButton = document.getElementById("downloadResumeButton");
    const barraFill = document.getElementById("barraFill");
    const porcentajeTexto = document.getElementById("porcentajeTexto");
    const profileAlert = document.querySelector(".profile-alert");

    const translations = {
        en: {
            no_resume_uploaded: "No résumé has been uploaded yet.",
            no_resume_alert: "No résumé has been uploaded yet. Upload it from Edit profile.",
            profile_updated: "Your profile was updated successfully.",
            document_opened: "Document link opened.",
            language_en: "English",
            language_es: "Spanish"
        },
        es: {
            no_resume_uploaded: "Aún no se ha subido un currículum.",
            no_resume_alert: "Aún no se ha subido un currículum. Súbelo desde Editar perfil.",
            profile_updated: "Tu perfil se actualizó correctamente.",
            document_opened: "Enlace de documento abierto.",
            language_en: "Inglés",
            language_es: "Español"
        }
    };

    function getCookieValue(name) {
        const cookies = document.cookie ? document.cookie.split(";") : [];

        for (const cookie of cookies) {
            const [rawKey, ...rawValue] = cookie.trim().split("=");
            const key = decodeURIComponent(rawKey || "");

            if (key === name) {
                return decodeURIComponent(rawValue.join("=") || "");
            }
        }

        return "";
    }

    function getCurrentLanguage() {
        const params = new URLSearchParams(window.location.search);
        const urlLanguage = (params.get("lang") || "").toLowerCase();

        if (urlLanguage === "es" || urlLanguage === "en") {
            return urlLanguage;
        }

        const savedLanguage = (localStorage.getItem("skillbridgeLanguage") || "").toLowerCase();

        if (savedLanguage === "es" || savedLanguage === "en") {
            return savedLanguage;
        }

        const cookieLanguage = (getCookieValue("skillbridgeLanguage") || "").toLowerCase();

        if (cookieLanguage === "es" || cookieLanguage === "en") {
            return cookieLanguage;
        }

        const htmlLanguage = (document.documentElement.getAttribute("lang") || "").toLowerCase();

        if (htmlLanguage.startsWith("es")) {
            return "es";
        }

        return "en";
    }

    function t(key) {
        const language = getCurrentLanguage();
        return translations[language][key] || translations.en[key] || key;
    }

    function announceMessage(message) {
        const messageBox = document.getElementById("accessibilityMessage");

        if (messageBox) {
            messageBox.textContent = message;
        }
    }

    function cleanText(value) {
        return String(value || "")
            .replace(/\s+/g, " ")
            .trim();
    }

    /* BOTÓN ANTIGUO DE CV, POR SI QUEDA EN ALGUNA PÁGINA */

    if (downloadResumeButton) {
        downloadResumeButton.addEventListener("click", () => {
            announceMessage(t("no_resume_uploaded"));
            alert(t("no_resume_alert"));
        });
    }

    /* ANIMACIÓN DE BARRA DE PERFIL */

    if (barraFill && porcentajeTexto) {
        const porcentaje = cleanText(porcentajeTexto.textContent);

        barraFill.style.width = "0%";

        setTimeout(() => {
            barraFill.style.width = porcentaje;
        }, 300);
    }

    /* MENSAJE DE PERFIL ACTUALIZADO */

    if (profileAlert) {
        const visibleAlertText = cleanText(profileAlert.textContent);
        announceMessage(visibleAlertText || t("profile_updated"));

        setTimeout(() => {
            profileAlert.style.opacity = "0";
            profileAlert.style.transform = "translateY(-8px)";
        }, 4000);

        setTimeout(() => {
            profileAlert.style.display = "none";
        }, 4500);
    }

    /* ACCESIBILIDAD PARA ENLACES DE DOCUMENTOS */

    const documentLinks = document.querySelectorAll(".descargar-btn");

    documentLinks.forEach((link) => {
        link.addEventListener("click", () => {
            const text = cleanText(link.textContent || link.getAttribute("aria-label"));
            announceMessage(text || t("document_opened"));
        });
    });
});
