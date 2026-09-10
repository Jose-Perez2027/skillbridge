/* =======================================================
   AUTH JS | SKILLBRIDGE
   Validaciones y alertas bilingües EN/ES
======================================================= */

(function () {
    "use strict";

    const TEXTS = {
        en: {
            show_password: "Show password",
            hide_password: "Hide password",
            registration_errors: "The registration form contains errors. Please review the highlighted fields.",
            login_errors: "The login form contains errors. Please review the highlighted fields.",
            recovery_errors: "The password recovery form contains errors. Please review the highlighted fields.",
            creating_account: "Creating account...",
            logging_in: "Logging in...",
            updating_password: "Updating password...",
            password_strength: "Password strength",
            weak_password: "Weak password",
            medium_password: "Medium password",
            strong_password: "Strong password",
            valid_email: "Enter a valid email address.",
            name_short: "The name must be at least 3 characters long.",
            select_account_type: "Please select an account type.",
            password_short: "The password must be at least 8 characters long.",
            password_empty: "The password cannot be empty.",
            passwords_match: "Passwords must match.",
            terms_required: "You must accept the Terms of Service and Privacy Policy."
        },
        es: {
            show_password: "Mostrar contraseña",
            hide_password: "Ocultar contraseña",
            registration_errors: "El formulario de registro contiene errores. Revisa los campos marcados.",
            login_errors: "El formulario de inicio de sesión contiene errores. Revisa los campos marcados.",
            recovery_errors: "El formulario de recuperación de contraseña contiene errores. Revisa los campos marcados.",
            creating_account: "Creando cuenta...",
            logging_in: "Iniciando sesión...",
            updating_password: "Actualizando contraseña...",
            password_strength: "Seguridad de la contraseña",
            weak_password: "Contraseña débil",
            medium_password: "Contraseña media",
            strong_password: "Contraseña fuerte",
            valid_email: "Ingresa un correo electrónico válido.",
            name_short: "El nombre debe tener al menos 3 caracteres.",
            select_account_type: "Selecciona un tipo de cuenta.",
            password_short: "La contraseña debe tener al menos 8 caracteres.",
            password_empty: "La contraseña no puede estar vacía.",
            passwords_match: "Las contraseñas deben coincidir.",
            terms_required: "Debes aceptar los Términos de Servicio y la Política de Privacidad."
        }
    };

    function getCookie(name) {
        const cookies = document.cookie ? document.cookie.split(";") : [];

        for (const cookie of cookies) {
            const [cookieName, ...cookieValue] = cookie.trim().split("=");

            if (cookieName === name) {
                return decodeURIComponent(cookieValue.join("="));
            }
        }

        return "";
    }

    function getCurrentLanguage() {
        const urlLanguage = new URLSearchParams(window.location.search).get("lang");

        if (urlLanguage === "es" || urlLanguage === "en") {
            localStorage.setItem("skillbridgeLanguage", urlLanguage);
            document.cookie = `skillbridgeLanguage=${urlLanguage}; path=/; max-age=31536000`;
            return urlLanguage;
        }

        const storedLanguage = localStorage.getItem("skillbridgeLanguage");

        if (storedLanguage === "es" || storedLanguage === "en") {
            return storedLanguage;
        }

        const cookieLanguage = getCookie("skillbridgeLanguage");

        if (cookieLanguage === "es" || cookieLanguage === "en") {
            localStorage.setItem("skillbridgeLanguage", cookieLanguage);
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
        return (TEXTS[language] && TEXTS[language][key]) || TEXTS.en[key] || key;
    }

    function announceAccessibilityMessage(message) {
        const messageBox = document.getElementById("accessibilityMessage");

        if (messageBox) {
            messageBox.textContent = message;
        }
    }

    function validateEmailFormat(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(String(email).trim());
    }

    function setErrorTextNearField(inputElement, messageKey) {
        if (!inputElement) {
            return;
        }

        const group = inputElement.closest(".form-group-custom, .form-group-checkbox");

        if (!group) {
            return;
        }

        const errorText = group.querySelector(".error-text");

        if (errorText) {
            errorText.textContent = t(messageKey);
        }
    }

    function translateAuthValidationTexts() {
        setErrorTextNearField(document.getElementById("regName"), "name_short");
        setErrorTextNearField(document.getElementById("regEmail"), "valid_email");
        setErrorTextNearField(document.getElementById("userType"), "select_account_type");
        setErrorTextNearField(document.getElementById("regPassword"), "password_short");
        setErrorTextNearField(document.getElementById("termsCheckbox"), "terms_required");

        setErrorTextNearField(document.getElementById("loginEmail"), "valid_email");
        setErrorTextNearField(document.getElementById("loginPassword"), "password_empty");

        setErrorTextNearField(document.getElementById("recoverEmail"), "valid_email");
        setErrorTextNearField(document.getElementById("newPassword"), "password_short");
        setErrorTextNearField(document.getElementById("confirmPassword"), "passwords_match");
    }

    function showInputError(inputElement) {
        if (!inputElement) {
            return;
        }

        const inputContainer = inputElement.closest(".form-group-custom");
        const checkboxContainer = inputElement.closest(".form-group-checkbox");

        if (inputContainer) {
            inputContainer.classList.add("invalid");
        }

        if (checkboxContainer) {
            checkboxContainer.classList.add("invalid");
        }
    }

    function clearInputError(inputElement) {
        if (!inputElement) {
            return;
        }

        const inputContainer = inputElement.closest(".form-group-custom");
        const checkboxContainer = inputElement.closest(".form-group-checkbox");

        if (inputContainer) {
            inputContainer.classList.remove("invalid");
        }

        if (checkboxContainer) {
            checkboxContainer.classList.remove("invalid");
        }
    }

    function setupLiveValidation(inputElement, validationFunction, eventName = "input") {
        if (!inputElement || typeof validationFunction !== "function") {
            return;
        }

        inputElement.addEventListener(eventName, () => {
            if (validationFunction()) {
                clearInputError(inputElement);
            }
        });
    }

    function setupPasswordToggle(toggleId, inputId) {
        const toggleButton = document.getElementById(toggleId);
        const input = document.getElementById(inputId);

        if (!toggleButton || !input) {
            return;
        }

        toggleButton.setAttribute("aria-label", t("show_password"));

        toggleButton.addEventListener("click", () => {
            const icon = toggleButton.querySelector("i");
            const willShowPassword = input.type === "password";

            input.type = willShowPassword ? "text" : "password";

            if (icon) {
                icon.classList.toggle("fa-eye", !willShowPassword);
                icon.classList.toggle("fa-eye-slash", willShowPassword);
            }

            toggleButton.setAttribute(
                "aria-label",
                willShowPassword ? t("hide_password") : t("show_password")
            );
        });
    }

    function evaluatePasswordStrength(password) {
        const meter = document.querySelector(".password-strength-meter");

        if (!meter) {
            return;
        }

        const label = meter.querySelector(".meter-label");

        if (password.length === 0) {
            meter.style.display = "none";
            meter.classList.remove("weak", "medium", "strong");

            if (label) {
                label.textContent = t("password_strength");
            }

            return;
        }

        meter.style.display = "block";
        meter.classList.remove("weak", "medium", "strong");

        let score = 0;

        if (password.length >= 8) {
            score++;
        }

        if (/[A-Z]/.test(password)) {
            score++;
        }

        if (/[0-9]/.test(password)) {
            score++;
        }

        if (/[^A-Za-z0-9]/.test(password)) {
            score++;
        }

        if (score <= 1) {
            meter.classList.add("weak");

            if (label) {
                label.textContent = t("weak_password");
            }
        } else if (score <= 3) {
            meter.classList.add("medium");

            if (label) {
                label.textContent = t("medium_password");
            }
        } else {
            meter.classList.add("strong");

            if (label) {
                label.textContent = t("strong_password");
            }
        }
    }

    function setSubmitLoading(submitButton, messageKey, iconClass) {
        if (!submitButton) {
            return;
        }

        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span>${t(messageKey)}</span>
            <i class="fa-solid ${iconClass} fa-spin"></i>
        `;
    }

    function setupRegisterForm() {
        const registerForm = document.getElementById("registerForm");

        if (!registerForm) {
            return;
        }

        const nameInput = document.getElementById("regName");
        const emailInput = document.getElementById("regEmail");
        const typeSelect = document.getElementById("userType");
        const passwordInput = document.getElementById("regPassword");
        const termsCheckbox = document.getElementById("termsCheckbox");
        const submitButton = document.getElementById("btnRegisterSubmit");

        setupLiveValidation(nameInput, () => nameInput.value.trim().length >= 3);
        setupLiveValidation(emailInput, () => validateEmailFormat(emailInput.value));
        setupLiveValidation(typeSelect, () => typeSelect.value !== "", "change");
        setupLiveValidation(passwordInput, () => passwordInput.value.trim().length >= 8);
        setupLiveValidation(termsCheckbox, () => termsCheckbox.checked, "change");

        if (passwordInput) {
            passwordInput.addEventListener("input", () => {
                evaluatePasswordStrength(passwordInput.value);
            });

            evaluatePasswordStrength(passwordInput.value);
        }

        registerForm.addEventListener("submit", (event) => {
            let isValid = true;
            let firstInvalidField = null;

            if (!nameInput || nameInput.value.trim().length < 3) {
                showInputError(nameInput);
                firstInvalidField = firstInvalidField || nameInput;
                isValid = false;
            } else {
                clearInputError(nameInput);
            }

            if (!emailInput || !validateEmailFormat(emailInput.value)) {
                showInputError(emailInput);
                firstInvalidField = firstInvalidField || emailInput;
                isValid = false;
            } else {
                clearInputError(emailInput);
            }

            if (!typeSelect || typeSelect.value === "") {
                showInputError(typeSelect);
                firstInvalidField = firstInvalidField || typeSelect;
                isValid = false;
            } else {
                clearInputError(typeSelect);
            }

            if (!passwordInput || passwordInput.value.trim().length < 8) {
                showInputError(passwordInput);
                firstInvalidField = firstInvalidField || passwordInput;
                isValid = false;
            } else {
                clearInputError(passwordInput);
            }

            if (!termsCheckbox || !termsCheckbox.checked) {
                showInputError(termsCheckbox);
                firstInvalidField = firstInvalidField || termsCheckbox;
                isValid = false;
            } else {
                clearInputError(termsCheckbox);
            }

            if (!isValid) {
                event.preventDefault();
                announceAccessibilityMessage(t("registration_errors"));

                if (firstInvalidField) {
                    firstInvalidField.focus();
                }

                return;
            }

            setSubmitLoading(submitButton, "creating_account", "fa-spinner");
        });
    }

    function setupLoginForm() {
        const loginForm = document.getElementById("loginForm");

        if (!loginForm) {
            return;
        }

        const emailInput = document.getElementById("loginEmail");
        const passwordInput = document.getElementById("loginPassword");
        const submitButton = document.getElementById("btnLoginSubmit");

        setupLiveValidation(emailInput, () => validateEmailFormat(emailInput.value));
        setupLiveValidation(passwordInput, () => passwordInput.value.trim() !== "");

        loginForm.addEventListener("submit", (event) => {
            let isValid = true;
            let firstInvalidField = null;

            if (!emailInput || !validateEmailFormat(emailInput.value)) {
                showInputError(emailInput);
                firstInvalidField = firstInvalidField || emailInput;
                isValid = false;
            } else {
                clearInputError(emailInput);
            }

            if (!passwordInput || passwordInput.value.trim() === "") {
                showInputError(passwordInput);
                firstInvalidField = firstInvalidField || passwordInput;
                isValid = false;
            } else {
                clearInputError(passwordInput);
            }

            if (!isValid) {
                event.preventDefault();
                announceAccessibilityMessage(t("login_errors"));

                if (firstInvalidField) {
                    firstInvalidField.focus();
                }

                return;
            }

            setSubmitLoading(submitButton, "logging_in", "fa-spinner");
        });
    }

    function setupRecoverForm() {
        const recoverForm = document.getElementById("recoverForm");

        if (!recoverForm) {
            return;
        }

        const emailInput = document.getElementById("recoverEmail");
        const newPasswordInput = document.getElementById("newPassword");
        const confirmPasswordInput = document.getElementById("confirmPassword");
        const submitButton = document.getElementById("btnRecoverSubmit");

        setupLiveValidation(emailInput, () => validateEmailFormat(emailInput.value));
        setupLiveValidation(newPasswordInput, () => newPasswordInput.value.trim().length >= 8);
        setupLiveValidation(confirmPasswordInput, () => {
            return confirmPasswordInput.value.trim() !== "" &&
                confirmPasswordInput.value === newPasswordInput.value;
        });

        recoverForm.addEventListener("submit", (event) => {
            let isValid = true;
            let firstInvalidField = null;

            if (!emailInput || !validateEmailFormat(emailInput.value)) {
                showInputError(emailInput);
                firstInvalidField = firstInvalidField || emailInput;
                isValid = false;
            } else {
                clearInputError(emailInput);
            }

            if (!newPasswordInput || newPasswordInput.value.trim().length < 8) {
                showInputError(newPasswordInput);
                firstInvalidField = firstInvalidField || newPasswordInput;
                isValid = false;
            } else {
                clearInputError(newPasswordInput);
            }

            if (
                !confirmPasswordInput ||
                confirmPasswordInput.value.trim() === "" ||
                confirmPasswordInput.value !== newPasswordInput.value
            ) {
                showInputError(confirmPasswordInput);
                firstInvalidField = firstInvalidField || confirmPasswordInput;
                isValid = false;
            } else {
                clearInputError(confirmPasswordInput);
            }

            if (!isValid) {
                event.preventDefault();
                announceAccessibilityMessage(t("recovery_errors"));

                if (firstInvalidField) {
                    firstInvalidField.focus();
                }

                return;
            }

            setSubmitLoading(submitButton, "updating_password", "fa-spinner");
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        translateAuthValidationTexts();

        setupPasswordToggle("togglePassword", "regPassword");
        setupPasswordToggle("togglePasswordLogin", "loginPassword");
        setupPasswordToggle("toggleRecoverPassword", "newPassword");
        setupPasswordToggle("toggleConfirmPassword", "confirmPassword");

        setupRegisterForm();
        setupLoginForm();
        setupRecoverForm();
    });
})();
