<?php
/**
 * Fichero de validación de datos
 * Proyecto: Login de usuarios
 */

/**
 * Limpia un dato de entrada
 */
function limpiarDato($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

/**
 * Valida los datos del login
 */
function validarLogin($email, $password) {
    $errores = [];

    // --- VALIDACIÓN EMAIL ---
    if (empty($email)) {
        $errores[] = "El email es obligatorio.";
    } else {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El formato del email no es válido.";
        }

        if (strlen($email) > 100) {
            $errores[] = "El email es demasiado largo.";
        }
    }

    // --- VALIDACIÓN CONTRASEÑA ---
    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria.";
    } else {
        if (strlen($password) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres.";
        }

        // Validación opcional de seguridad (no obligatoria)
        if (preg_match('/\s/', $password)) {
            $errores[] = "La contraseña no puede contener espacios.";
        }
    }

    return $errores;
}
?>
