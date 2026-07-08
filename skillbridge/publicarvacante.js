document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formVacante");

    form.addEventListener("submit", function (event) {
        let camposVacios = false;

        // Selección de campos obligatorios
        const camposObligatorios = form.querySelectorAll("[required]");

        camposObligatorios.forEach(campo => {
            if (!campo.value.trim()) {
                camposVacios = true;
                campo.style.borderColor = "red";
            } else {
                campo.style.borderColor = "#ccc";
            }
        });

        if (camposVacios) {
            event.preventDefault(); // Detiene el envío del formulario
            alert("Por favor, rellene todos los campos marcados como obligatorios (*).");
        }
    });
});