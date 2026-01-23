<?php
/**
 * verificar_sesion.php
 * Verifica si el usuario tiene una sesión activa y devuelve la información
 */

header('Content-Type: application/json');

session_start();

$respuesta = [
    'sesion_activa' => false,
    'nombre_usuario' => '',
    'email' => ''
];

if (isset($_SESSION['id_usuario'])) {
    $respuesta['sesion_activa'] = true;
    $respuesta['nombre_usuario'] = $_SESSION['nombre'] ?? 'Usuario';
    $respuesta['email'] = $_SESSION['email'] ?? '';
}

echo json_encode($respuesta);
exit;

