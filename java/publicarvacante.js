/* =======================================================
   PUBLICAR VACANTE JS | SKILLBRIDGE
   Alertas, validaciones y textos dinámicos EN/ES
======================================================= */

(function () {
    "use strict";

    const LANG_STORAGE_KEY = "skillbridgeLanguage";

    const translations = {
        en: {
            form_incomplete: "The form has incomplete or incorrect fields.",
            form_alert: "Please fill in all required fields correctly.",
            posting: "Posting...",
            required_field: "This field is required.",
            invalid_date: "The deadline must be today or a future date.",
            invalid_salary: "The salary cannot be negative.",
            valid_field: "Field corrected.",
            date_ready: "The minimum deadline date was configured.",
            icon_spinner: "Loading icon"
        },
        es: {
            form_incomplete: "El formulario tiene campos incompletos o incorrectos.",
            form_alert: "Completa correctamente todos los campos obligatorios.",
            posting: "Publicando...",
            required_field: "Este campo es obligatorio.",
            invalid_date: "La fecha límite debe ser de hoy o una fecha futura.",
            invalid_salary: "El salario no puede ser negativo.",
            valid_field: "Campo corregido.",
            date_ready: "La fecha mínima fue configurada.",
            icon_spinner: "Ícono de carga"
        }
    };

    function getCookie(name) {
        const cookies = document.cookie ? document.cookie.split(";") : [];

        for (const cookie of cookies) {
            const [rawKey, ...rawValue] = cookie.trim().split("=");

            if (decodeURIComponent(rawKey) === name) {
                return decodeURIComponent(rawValue.join("="));
            }
        }

        return "";
    }

    function detectLanguage() {
        const params = new URLSearchParams(window.location.search);
        const urlLang = params.get("lang");
        const storageLang = localStorage.getItem(LANG_STORAGE_KEY);
        const cookieLang = getCookie(LANG_STORAGE_KEY);
        const htmlLang = document.documentElement.getAttribute("lang");

        const candidates = [urlLang, storageLang, cookieLang, htmlLang];
        const selected = candidates.find((value) => value === "es" || value === "en");

        return selected || "en";
    }

    function currentLanguage() {
        return detectLanguage();
    }

    function t(key) {
        const lang = currentLanguage();
        return (translations[lang] && translations[lang][key]) || translations.en[key] || key;
    }

    function mostrarMensajeAccesible(mensaje) {
        const cajaMensaje = document.getElementById("accessibilityMessage");

        if (cajaMensaje) {
            cajaMensaje.textContent = mensaje;
        }
    }

    function localDateString(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");

        return `${year}-${month}-${day}`;
    }

    function configurarFechaMinima(inputFecha) {
        if (!inputFecha) {
            return;
        }

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        inputFecha.setAttribute("min", localDateString(hoy));
    }

    function validarFecha(fechaIngresada) {
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        const partesFecha = String(fechaIngresada || "").split("-");

        if (partesFecha.length !== 3) {
            return false;
        }

        const fecha = new Date(
            Number(partesFecha[0]),
            Number(partesFecha[1]) - 1,
            Number(partesFecha[2])
        );

        if (Number.isNaN(fecha.getTime())) {
            return false;
        }

        fecha.setHours(0, 0, 0, 0);

        return fecha >= hoy;
    }

    function marcarError(campo, mensaje = "") {
        if (!campo) {
            return;
        }

        campo.classList.add("input-error");
        campo.setAttribute("aria-invalid", "true");

        if (mensaje) {
            campo.setAttribute("title", mensaje);
        }
    }

    function limpiarError(campo) {
        if (!campo) {
            return;
        }

        campo.classList.remove("input-error");
        campo.removeAttribute("aria-invalid");
        campo.removeAttribute("title");
    }

    function campoEstaVacio(campo) {
        if (!campo) {
            return true;
        }

        const type = (campo.type || "").toLowerCase();

        if (type === "checkbox" || type === "radio") {
            return !campo.checked;
        }

        if (type === "file") {
            return !campo.files || campo.files.length === 0;
        }

        return String(campo.value || "").trim() === "";
    }

    function enfocarPrimerError(form) {
        const primerError = form.querySelector(".input-error");

        if (primerError && typeof primerError.focus === "function") {
            primerError.focus();
        }
    }

    function configurarLimpiezaEnVivo(form) {
        const campos = form.querySelectorAll("input, textarea, select");

        campos.forEach((campo) => {
            const eventName = campo.tagName.toLowerCase() === "select" || campo.type === "checkbox" || campo.type === "radio"
                ? "change"
                : "input";

            campo.addEventListener(eventName, () => {
                if (!campo.classList.contains("input-error")) {
                    return;
                }

                if (!campoEstaVacio(campo)) {
                    limpiarError(campo);
                }
            });
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("publishVacancyForm");

        if (!form) {
            return;
        }

        const fechaLimite = document.getElementById("fecha_limite");
        const salario = document.getElementById("salario");

        configurarFechaMinima(fechaLimite);
        configurarLimpiezaEnVivo(form);

        form.addEventListener("submit", (event) => {
            const camposObligatorios = form.querySelectorAll("[required]");
            let formularioValido = true;

            camposObligatorios.forEach((campo) => {
                if (campoEstaVacio(campo)) {
                    formularioValido = false;
                    marcarError(campo, t("required_field"));
                } else {
                    limpiarError(campo);
                }
            });

            if (fechaLimite && fechaLimite.value) {
                if (!validarFecha(fechaLimite.value)) {
                    formularioValido = false;
                    marcarError(fechaLimite, t("invalid_date"));
                } else {
                    limpiarError(fechaLimite);
                }
            }

            if (salario && String(salario.value || "").trim() !== "") {
                if (Number(salario.value) < 0) {
                    formularioValido = false;
                    marcarError(salario, t("invalid_salary"));
                } else {
                    limpiarError(salario);
                }
            }

            if (!formularioValido) {
                event.preventDefault();

                mostrarMensajeAccesible(t("form_incomplete"));
                alert(t("form_alert"));
                enfocarPrimerError(form);
                return;
            }

            const submitButton = form.querySelector("button[type='submit']");

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.setAttribute("aria-busy", "true");
                submitButton.innerHTML = `${t("posting")} <i class="fa-solid fa-spinner fa-spin" role="img" aria-label="${t("icon_spinner")}" title="${t("icon_spinner")}"></i>`;
            }
        });
    });
})();
