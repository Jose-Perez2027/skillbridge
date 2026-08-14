/* =======================================================
   PUBLICAR VACANTE JS | SKILLBRIDGE
======================================================= */

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("publishVacancyForm");

    if (!form) {
        return;
    }

    const fechaLimite = document.getElementById("fecha_limite");
    const salario = document.getElementById("salario");

    configurarFechaMinima(fechaLimite);

    form.addEventListener("submit", (event) => {
        const camposObligatorios = form.querySelectorAll("[required]");
        let formularioValido = true;

        camposObligatorios.forEach((campo) => {
            if (!campo.value.trim()) {
                formularioValido = false;
                marcarError(campo);
            } else {
                limpiarError(campo);
            }
        });

        if (fechaLimite && fechaLimite.value) {
            if (!validarFecha(fechaLimite.value)) {
                formularioValido = false;
                marcarError(fechaLimite);
            }
        }

        if (salario && salario.value.trim() !== "") {
            if (Number(salario.value) < 0) {
                formularioValido = false;
                marcarError(salario);
            } else {
                limpiarError(salario);
            }
        }

        if (!formularioValido) {
            event.preventDefault();

            mostrarMensajeAccesible(
                "El formulario tiene campos incompletos o incorrectos."
            );

            alert("Por favor, completa correctamente todos los campos obligatorios.");
            return;
        }

        mostrarMensajeAccesible("Formulario enviado correctamente.");
    });
});

/* =========================
   FECHA MÍNIMA
========================= */

function configurarFechaMinima(inputFecha) {
    if (!inputFecha) {
        return;
    }

    const hoy = new Date();
    const fechaFormateada = hoy.toISOString().split("T")[0];

    inputFecha.setAttribute("min", fechaFormateada);
}

/* =========================
   VALIDAR FECHA
========================= */

function validarFecha(fechaIngresada) {
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);

    const fecha = new Date(fechaIngresada);
    fecha.setHours(0, 0, 0, 0);

    return fecha >= hoy;
}

/* =========================
   ERRORES VISUALES
========================= */

function marcarError(campo) {
    if (!campo) {
        return;
    }

    campo.classList.add("input-error");
}

function limpiarError(campo) {
    if (!campo) {
        return;
    }

    campo.classList.remove("input-error");
}

/* =========================
   ACCESIBILIDAD
========================= */

function mostrarMensajeAccesible(mensaje) {
    const cajaMensaje = document.getElementById("accessibilityMessage");

    if (cajaMensaje) {
        cajaMensaje.textContent = mensaje;
    }
}