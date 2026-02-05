document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-perfil");
    if (!form) return;

    form.addEventListener("submit", (e) => {
        // aquí solo dejamos que el backend procese
        console.log("Enviando actualización de perfil...");
    });
});
