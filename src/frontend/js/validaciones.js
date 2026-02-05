<<<<<<< Updated upstream
=======
function validarRegistro(form) {
    const nombre = form.nombre.value.trim();
    const email = form.email.value.trim();
    const pass = form.contrasena.value.trim();
    const pass2 = form.contrasena2.value.trim();

    let ok = true;
    let msg = "";

    if (nombre.length < 3) {
        ok = false;
        msg += "El nombre es demasiado corto.\n";
    }
    if (!email.includes("@")) {
        ok = false;
        msg += "El email no es válido.\n";
    }
    if (pass.length < 6) {
        ok = false;
        msg += "La contraseña debe tener al menos 6 caracteres.\n";
    }
    if (pass !== pass2) {
        ok = false;
        msg += "Las contraseñas no coinciden.\n";
    }

    if (!ok) {
        alert(msg);
    }
    return ok;
}

function validarLogin(form) {
    const email = form.email.value.trim();
    const pass = form.contrasena.value.trim();
    if (!email || !pass) {
        alert("Rellena email y contraseña.");
        return false;
    }
    return true;
}
>>>>>>> Stashed changes
