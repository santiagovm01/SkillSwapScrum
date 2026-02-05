document.addEventListener("DOMContentLoaded", () => {
    const btnIniciar = document.getElementById("btn-iniciar-intercambio");
    if (btnIniciar) {
        btnIniciar.addEventListener("click", () => {
            const form = document.getElementById("form-iniciar-intercambio");
            if (form) form.submit();
        });
    }
});
