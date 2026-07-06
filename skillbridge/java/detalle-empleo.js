document.addEventListener("DOMContentLoaded", () => {
    const applicationModal = document.getElementById("applicationModal");
    const applicationModalOverlay = document.getElementById(
        "applicationModalOverlay"
    );

    const openApplicationButtons = document.querySelectorAll(
        ".open-application-modal"
    );

    const closeApplicationModal = document.getElementById(
        "closeApplicationModal"
    );

    const closeSuccessButton = document.getElementById(
        "closeSuccessButton"
    );

    const applicationForm = document.getElementById("applicationForm");
    const applicationFormContainer = document.getElementById(
        "applicationFormContainer"
    );

    const applicationSuccess = document.getElementById(
        "applicationSuccess"
    );

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

    /* =========================
       MODAL DE POSTULACIÓN
    ========================== */

    function openApplicationModal() {
        lastFocusedElement = document.activeElement;

        applicationModal.classList.remove("hidden");
        document.body.style.overflow = "hidden";

        setTimeout(() => {
            document.getElementById("applicantName").focus();
        }, 100);
    }

    function closeModal() {
        applicationModal.classList.add("hidden");
        document.body.style.overflow = "";

        if (lastFocusedElement) {
            lastFocusedElement.focus();
        }
    }

    openApplicationButtons.forEach((button) => {
        button.addEventListener("click", openApplicationModal);
    });

    closeApplicationModal.addEventListener("click", closeModal);

    applicationModalOverlay.addEventListener("click", closeModal);

    closeSuccessButton.addEventListener("click", () => {
        applicationSuccess.classList.add("hidden");
        applicationFormContainer.classList.remove("hidden");
        applicationForm.reset();
        cvFileText.textContent = "Selecciona un archivo PDF o DOCX";

        closeModal();
    });

    document.addEventListener("keydown", (event) => {
        if (
            event.key === "Escape" &&
            !applicationModal.classList.contains("hidden")
        ) {
            closeModal();
        }
    });

    /* =========================
       ARCHIVO CV
    ========================== */

    applicantCV.addEventListener("change", () => {
        const selectedFile = applicantCV.files[0];

        if (!selectedFile) {
            cvFileText.textContent = "Selecciona un archivo PDF o DOCX";
            return;
        }

        const allowedTypes = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        ];

        if (!allowedTypes.includes(selectedFile.type)) {
            cvFileText.textContent = "Formato no permitido. Usa PDF o DOCX.";
            applicantCV.value = "";
            return;
        }

        if (selectedFile.size > 5 * 1024 * 1024) {
            cvFileText.textContent = "El archivo supera el límite de 5 MB.";
            applicantCV.value = "";
            return;
        }

        cvFileText.textContent = selectedFile.name;
    });

    /* =========================
       ENVIAR POSTULACIÓN
    ========================== */

    applicationForm.addEventListener("submit", (event) => {
        event.preventDefault();

        applicationFormContainer.classList.add("hidden");
        applicationSuccess.classList.remove("hidden");
    });

    /* =========================
       GUARDAR EMPLEO
    ========================== */

    if (detailSaveButton) {
        detailSaveButton.addEventListener("click", () => {
            const label = detailSaveButton.querySelector("span");

            setTimeout(() => {
                const isSaved = detailSaveButton.classList.contains("saved");

                label.textContent = isSaved
                    ? "Guardado"
                    : "Guardar";
            }, 0);
        });
    }

    /* =========================
       COMPARTIR
    ========================== */

    function showCopyMessage(message) {
        copyLinkMessage.textContent = message;

        setTimeout(() => {
            copyLinkMessage.textContent = "";
        }, 3000);
    }

    function copyCurrentLink() {
        navigator.clipboard.writeText(window.location.href)
            .then(() => {
                showCopyMessage("Enlace copiado correctamente.");
            })
            .catch(() => {
                showCopyMessage("No fue posible copiar el enlace.");
            });
    }

    copyLinkButton.addEventListener("click", copyCurrentLink);

    shareJobButton.addEventListener("click", () => {
        if (navigator.share) {
            navigator.share({
                title: "Desarrollador Web Jr. | SkillBridge",
                text: "Mira esta oportunidad laboral en SkillBridge.",
                url: window.location.href
            });
        } else {
            copyCurrentLink();
        }
    });

    facebookShare.addEventListener("click", () => {
        const url = encodeURIComponent(window.location.href);

        window.open(
            `https://www.facebook.com/sharer/sharer.php?u=${url}`,
            "_blank"
        );
    });

    linkedinShare.addEventListener("click", () => {
        const url = encodeURIComponent(window.location.href);

        window.open(
            `https://www.linkedin.com/sharing/share-offsite/?url=${url}`,
            "_blank"
        );
    });

    whatsappShare.addEventListener("click", () => {
        const message = encodeURIComponent(
            `Mira esta vacante de Desarrollador Web Jr. en SkillBridge: ${window.location.href}`
        );

        window.open(
            `https://wa.me/?text=${message}`,
            "_blank"
        );
    });
});