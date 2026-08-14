/* =======================================================
   AUTH JS - REGISTRO Y LOGIN | SKILLBRIDGE
======================================================= */

document.addEventListener("DOMContentLoaded", () => {
    setupPasswordToggle("togglePassword", "regPassword");
    setupPasswordToggle("togglePasswordLogin", "loginPassword");

    setupRegisterForm();
    setupLoginForm();
});

/* =========================
   MOSTRAR / OCULTAR CLAVE
========================= */

function setupPasswordToggle(toggleId, inputId) {
    const toggleButton = document.getElementById(toggleId);
    const input = document.getElementById(inputId);

    if (!toggleButton || !input) {
        return;
    }

    toggleButton.addEventListener("click", () => {
        const icon = toggleButton.querySelector("i");

        if (input.type === "password") {
            input.type = "text";

            if (icon) {
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        } else {
            input.type = "password";

            if (icon) {
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    });
}

/* =========================
   REGISTRO
========================= */

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

    if (passwordInput) {
        passwordInput.addEventListener("input", () => {
            evaluatePasswordStrength(passwordInput.value);
        });
    }

    registerForm.addEventListener("submit", (event) => {
        event.preventDefault();

        let isValid = true;

        if (!nameInput || nameInput.value.trim().length < 3) {
            showInputError(nameInput);
            isValid = false;
        } else {
            clearInputError(nameInput);
        }

        if (!emailInput || !validateEmailFormat(emailInput.value)) {
            showInputError(emailInput);
            isValid = false;
        } else {
            clearInputError(emailInput);
        }

        if (!typeSelect || typeSelect.value === "") {
            showInputError(typeSelect);
            isValid = false;
        } else {
            clearInputError(typeSelect);
        }

        if (!passwordInput || passwordInput.value.trim().length < 8) {
            showInputError(passwordInput);
            isValid = false;
        } else {
            clearInputError(passwordInput);
        }

        if (!termsCheckbox || !termsCheckbox.checked) {
            isValid = false;
            alert("Debes aceptar los términos y condiciones para continuar.");
        }

        if (!isValid) {
            announceAccessibilityMessage(
                "El formulario de registro contiene errores. Revisa los campos marcados."
            );
            return;
        }

        announceAccessibilityMessage("Registro validado correctamente.");

        alert(
            "Cuenta creada correctamente. Esta es una simulación visual; después se conectará con MySQL."
        );

        registerForm.reset();
        resetPasswordStrength();
    });
}

/* =========================
   LOGIN
========================= */

function setupLoginForm() {
    const loginForm = document.getElementById("loginForm");

    if (!loginForm) {
        return;
    }

    const emailInput = document.getElementById("loginEmail");
    const passwordInput = document.getElementById("loginPassword");

    loginForm.addEventListener("submit", (event) => {
        event.preventDefault();

        let isValid = true;

        if (!emailInput || !validateEmailFormat(emailInput.value)) {
            showInputError(emailInput);
            isValid = false;
        } else {
            clearInputError(emailInput);
        }

        if (!passwordInput || passwordInput.value.trim() === "") {
            showInputError(passwordInput);
            isValid = false;
        } else {
            clearInputError(passwordInput);
        }

        if (!isValid) {
            announceAccessibilityMessage(
                "El formulario de inicio de sesión contiene errores."
            );
            return;
        }

        announceAccessibilityMessage("Inicio de sesión validado correctamente.");

        alert(
            "Inicio de sesión validado. Esta es una simulación visual; después se conectará con MySQL."
        );

        loginForm.reset();
    });
}

/* =========================
   VALIDACIONES
========================= */

function validateEmailFormat(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email.trim());
}

function showInputError(inputElement) {
    if (!inputElement) {
        return;
    }

    const container = inputElement.closest(".form-group-custom");

    if (container) {
        container.classList.add("invalid");
    }
}

function clearInputError(inputElement) {
    if (!inputElement) {
        return;
    }

    const container = inputElement.closest(".form-group-custom");

    if (container) {
        container.classList.remove("invalid");
    }
}

/* =========================
   FUERZA DE CONTRASEÑA
========================= */

function evaluatePasswordStrength(password) {
    const meter = document.querySelector(".password-strength-meter");

    if (!meter) {
        return;
    }

    if (password.length === 0) {
        meter.style.display = "none";
        meter.classList.remove("weak", "medium", "strong");
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

    if (score <= 1) {
        meter.classList.add("weak");
    } else if (score === 2) {
        meter.classList.add("medium");
    } else {
        meter.classList.add("strong");
    }
}

function resetPasswordStrength() {
    const meter = document.querySelector(".password-strength-meter");

    if (!meter) {
        return;
    }

    meter.style.display = "none";
    meter.classList.remove("weak", "medium", "strong");
}

/* =========================
   ACCESIBILIDAD
========================= */

function announceAccessibilityMessage(message) {
    const messageBox = document.getElementById("accessibilityMessage");

    if (messageBox) {
        messageBox.textContent = message;
    }
}