document.addEventListener("DOMContentLoaded", () => {
    const applicationModal = document.getElementById("applicationModal");
    const applicationModalOverlay = document.getElementById("applicationModalOverlay");
    const openApplicationButtons = document.querySelectorAll(".open-application-modal");
    const closeApplicationModal = document.getElementById("closeApplicationModal");
    const closeSuccessButton = document.getElementById("closeSuccessButton");

    const applicationForm = document.getElementById("applicationForm");
    const applicationFormContainer = document.getElementById("applicationFormContainer");
    const applicationSuccess = document.getElementById("applicationSuccess");

    const applicantCV = document.getElementById("applicantCV");
    const cvFileText = document.getElementById("cvFileText");

    const detailSaveButton = document.getElementById("detailSaveButton");
    const shareJobButton = document.getElementById("shareJobButton");

    const copyLinkButton = document.getElementById("copyLinkButton");
    const copyLinkMessage = document.getElementById("copyLinkMessage");

    const facebookShare = document.querySelector(".facebook-share");
    const linkedinShare = document.querySelector(".linkedin-share");
    const whatsappShare = document.querySelector(".whatsapp-share");

    let lastFocusedElement = null;

    const translations = {
        en: {
            application_form_opened: "Application form opened.",
            application_form_closed: "Application form closed.",
            select_cv: "Select a PDF or DOCX file",
            file_format_not_allowed: "File format not allowed. Use PDF, DOC or DOCX.",
            file_too_large: "The file is larger than 5 MB.",
            resume_selected: "Résumé selected.",
            complete_required_fields: "Please complete all required fields.",
            sending: "Sending...",
            application_sent: "Application sent",
            application_sent_success: "Your application was sent successfully.",
            selected_job: "Selected job",
            saved: "Saved",
            save: "Save",
            remove_saved_job: "Remove saved job",
            save_job: "Save job",
            was_saved: "was saved.",
            was_removed: "was removed from saved jobs.",
            copy_unavailable: "Copying is not available in this browser.",
            link_copied: "Link copied successfully.",
            link_not_copied: "The link could not be copied.",
            share_title: "SkillBridge job opportunity",
            share_text: "Check out this job opportunity on SkillBridge.",
            whatsapp_text: "Check out this job opportunity on SkillBridge:",
            close_window: "Close window",
            close_icon: "Close icon",
            success_icon: "Success icon",
            loading_icon: "Loading icon",
            saved_icon: "Saved job icon",
            unsaved_icon: "Unsaved job icon"
        },
        es: {
            application_form_opened: "Formulario de postulación abierto.",
            application_form_closed: "Formulario de postulación cerrado.",
            select_cv: "Selecciona un archivo PDF o DOCX",
            file_format_not_allowed: "Formato de archivo no permitido. Usa PDF, DOC o DOCX.",
            file_too_large: "El archivo pesa más de 5 MB.",
            resume_selected: "Currículum seleccionado.",
            complete_required_fields: "Completa todos los campos obligatorios.",
            sending: "Enviando...",
            application_sent: "Postulación enviada",
            application_sent_success: "Tu postulación fue enviada correctamente.",
            selected_job: "Vacante seleccionada",
            saved: "Guardado",
            save: "Guardar",
            remove_saved_job: "Quitar empleo guardado",
            save_job: "Guardar empleo",
            was_saved: "fue guardado.",
            was_removed: "fue eliminado de empleos guardados.",
            copy_unavailable: "Copiar no está disponible en este navegador.",
            link_copied: "Enlace copiado correctamente.",
            link_not_copied: "No se pudo copiar el enlace.",
            share_title: "Oportunidad laboral de SkillBridge",
            share_text: "Mira esta oportunidad laboral en SkillBridge.",
            whatsapp_text: "Mira esta oportunidad laboral en SkillBridge:",
            close_window: "Cerrar ventana",
            close_icon: "Ícono de cerrar",
            success_icon: "Ícono de éxito",
            loading_icon: "Ícono de carga",
            saved_icon: "Ícono de empleo guardado",
            unsaved_icon: "Ícono de empleo no guardado"
        }
    };

    function getCookie(name) {
        const cookies = document.cookie ? document.cookie.split(";") : [];

        for (const cookie of cookies) {
            const [rawKey, ...rawValue] = cookie.trim().split("=");

            if (rawKey === name) {
                return decodeURIComponent(rawValue.join("="));
            }
        }

        return "";
    }

    function getCurrentLanguage() {
        const params = new URLSearchParams(window.location.search);
        const urlLanguage = params.get("lang");

        if (urlLanguage === "es" || urlLanguage === "en") {
            return urlLanguage;
        }

        const localLanguage = localStorage.getItem("skillbridgeLanguage");

        if (localLanguage === "es" || localLanguage === "en") {
            return localLanguage;
        }

        const cookieLanguage = getCookie("skillbridgeLanguage");

        if (cookieLanguage === "es" || cookieLanguage === "en") {
            return cookieLanguage;
        }

        const htmlLanguage = document.documentElement.lang;

        if (htmlLanguage && htmlLanguage.toLowerCase().startsWith("es")) {
            return "es";
        }

        return "en";
    }

    const currentLanguage = getCurrentLanguage();

    function t(key) {
        return translations[currentLanguage][key] || translations.en[key] || key;
    }

    function iconHtml(className, labelKey) {
        return `<i class="${className}" role="img" aria-label="${t(labelKey)}" title="${t(labelKey)}"></i>`;
    }

    function announceMessage(message) {
        const messageBox = document.getElementById("accessibilityMessage");

        if (messageBox) {
            messageBox.textContent = message;
        }
    }

    function openApplicationModal() {
        if (!applicationModal) {
            return;
        }

        lastFocusedElement = document.activeElement;

        applicationModal.classList.remove("hidden");
        document.body.style.overflow = "hidden";

        setTimeout(() => {
            const applicantName = document.getElementById("applicantName");

            if (applicantName) {
                applicantName.focus();
            }
        }, 100);

        announceMessage(t("application_form_opened"));
    }

    function closeModal() {
        if (!applicationModal) {
            return;
        }

        applicationModal.classList.add("hidden");
        document.body.style.overflow = "";

        if (lastFocusedElement) {
            lastFocusedElement.focus();
        }

        announceMessage(t("application_form_closed"));
    }

    openApplicationButtons.forEach((button) => {
        button.addEventListener("click", () => {
            if (button.disabled) {
                return;
            }

            openApplicationModal();
        });
    });

    if (closeApplicationModal) {
        closeApplicationModal.setAttribute("aria-label", t("close_window"));
        closeApplicationModal.addEventListener("click", closeModal);
    }

    if (applicationModalOverlay) {
        applicationModalOverlay.addEventListener("click", closeModal);
    }

    if (closeSuccessButton) {
        closeSuccessButton.addEventListener("click", () => {
            if (applicationSuccess) {
                applicationSuccess.classList.add("hidden");
            }

            if (applicationFormContainer) {
                applicationFormContainer.classList.remove("hidden");
            }

            if (applicationForm) {
                applicationForm.reset();
            }

            if (cvFileText) {
                cvFileText.textContent = t("select_cv");
            }

            closeModal();
        });
    }

    document.addEventListener("keydown", (event) => {
        if (
            event.key === "Escape" &&
            applicationModal &&
            !applicationModal.classList.contains("hidden")
        ) {
            closeModal();
        }
    });

    if (cvFileText && cvFileText.textContent.trim() === "") {
        cvFileText.textContent = t("select_cv");
    }

    if (applicantCV && cvFileText) {
        applicantCV.addEventListener("change", () => {
            const selectedFile = applicantCV.files[0];

            if (!selectedFile) {
                cvFileText.textContent = t("select_cv");
                return;
            }

            const allowedExtensions = ["pdf", "doc", "docx"];
            const fileExtension = selectedFile.name.split(".").pop().toLowerCase();

            if (!allowedExtensions.includes(fileExtension)) {
                cvFileText.textContent = t("file_format_not_allowed");
                applicantCV.value = "";
                announceMessage(t("file_format_not_allowed"));
                return;
            }

            if (selectedFile.size > 5 * 1024 * 1024) {
                cvFileText.textContent = t("file_too_large");
                applicantCV.value = "";
                announceMessage(t("file_too_large"));
                return;
            }

            cvFileText.textContent = selectedFile.name;
            announceMessage(t("resume_selected"));
        });
    }

    if (applicationForm) {
        applicationForm.addEventListener("submit", (event) => {
            const requiredFields = applicationForm.querySelectorAll("[required]");
            const submitButton = applicationForm.querySelector(".application-submit-button");
            let valid = true;

            requiredFields.forEach((field) => {
                if (field.type === "checkbox" && !field.checked) {
                    valid = false;
                } else if (field.type !== "checkbox" && field.value.trim() === "") {
                    valid = false;
                }
            });

            if (!valid) {
                event.preventDefault();
                announceMessage(t("complete_required_fields"));
                alert(t("complete_required_fields"));
                return;
            }

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <span>${t("sending")}</span>
                    ${iconHtml("fa-solid fa-spinner fa-spin", "loading_icon")}
                `;
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get("apply") === "1") {
        openApplicationModal();
    }

    if (urlParams.get("applied") === "1") {
        openApplicationButtons.forEach((button) => {
            button.disabled = true;
            button.classList.remove("button-primary");
            button.classList.add("button-secondary");
            button.innerHTML = `
                <span>${t("application_sent")}</span>
                ${iconHtml("fa-solid fa-circle-check", "success_icon")}
            `;
        });

        announceMessage(t("application_sent_success"));
    }

    if (detailSaveButton) {
        const jobName = detailSaveButton.dataset.job || t("selected_job");

        let savedJobs = [];

        try {
            savedJobs = JSON.parse(localStorage.getItem("skillbridgeSavedJobs")) || [];
        } catch (error) {
            savedJobs = [];
        }

        function updateSaveButton(isSaved) {
            const icon = detailSaveButton.querySelector("i");
            const label = detailSaveButton.querySelector("span");

            detailSaveButton.classList.toggle("saved", isSaved);

            if (icon) {
                icon.className = isSaved
                    ? "fa-solid fa-bookmark"
                    : "fa-regular fa-bookmark";

                icon.setAttribute("role", "img");
                icon.setAttribute("aria-label", isSaved ? t("saved_icon") : t("unsaved_icon"));
                icon.setAttribute("title", isSaved ? t("saved_icon") : t("unsaved_icon"));
            }

            if (label) {
                label.textContent = isSaved ? t("saved") : t("save");
            }

            detailSaveButton.setAttribute(
                "aria-label",
                isSaved ? t("remove_saved_job") : t("save_job")
            );
        }

        updateSaveButton(savedJobs.includes(jobName));

        detailSaveButton.addEventListener("click", () => {
            const jobIndex = savedJobs.indexOf(jobName);

            if (jobIndex === -1) {
                savedJobs.push(jobName);
                updateSaveButton(true);
                announceMessage(`${jobName} ${t("was_saved")}`);
            } else {
                savedJobs.splice(jobIndex, 1);
                updateSaveButton(false);
                announceMessage(`${jobName} ${t("was_removed")}`);
            }

            localStorage.setItem(
                "skillbridgeSavedJobs",
                JSON.stringify(savedJobs)
            );
        });
    }

    function showCopyMessage(message) {
        if (!copyLinkMessage) {
            return;
        }

        copyLinkMessage.textContent = message;

        setTimeout(() => {
            copyLinkMessage.textContent = "";
        }, 3000);
    }

    function copyCurrentLink() {
        if (!navigator.clipboard) {
            showCopyMessage(t("copy_unavailable"));
            announceMessage(t("copy_unavailable"));
            return;
        }

        navigator.clipboard.writeText(window.location.href)
            .then(() => {
                showCopyMessage(t("link_copied"));
                announceMessage(t("link_copied"));
            })
            .catch(() => {
                showCopyMessage(t("link_not_copied"));
                announceMessage(t("link_not_copied"));
            });
    }

    if (copyLinkButton) {
        copyLinkButton.addEventListener("click", copyCurrentLink);
    }

    if (shareJobButton) {
        shareJobButton.addEventListener("click", () => {
            const pageTitle = document.title || t("share_title");

            if (navigator.share) {
                navigator.share({
                    title: pageTitle,
                    text: t("share_text"),
                    url: window.location.href
                }).catch(() => {
                    copyCurrentLink();
                });
            } else {
                copyCurrentLink();
            }
        });
    }

    if (facebookShare) {
        facebookShare.addEventListener("click", () => {
            const url = encodeURIComponent(window.location.href);

            window.open(
                `https://www.facebook.com/sharer/sharer.php?u=${url}`,
                "_blank",
                "noopener,noreferrer"
            );
        });
    }

    if (linkedinShare) {
        linkedinShare.addEventListener("click", () => {
            const url = encodeURIComponent(window.location.href);

            window.open(
                `https://www.linkedin.com/sharing/share-offsite/?url=${url}`,
                "_blank",
                "noopener,noreferrer"
            );
        });
    }

    if (whatsappShare) {
        whatsappShare.addEventListener("click", () => {
            const message = encodeURIComponent(
                `${t("whatsapp_text")} ${window.location.href}`
            );

            window.open(
                `https://wa.me/?text=${message}`,
                "_blank",
                "noopener,noreferrer"
            );
        });
    }
});
