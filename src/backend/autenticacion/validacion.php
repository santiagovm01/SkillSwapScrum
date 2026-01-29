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

    // Email
    if (empty($email)) {
        $errores[] = "El email es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del email no es válido.";
    }

    // Contraseña
    if (empty($password)) {
        $errores[] = "La contraseña es obligatoria.";
    } elseif (strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    return $errores;
}
?>