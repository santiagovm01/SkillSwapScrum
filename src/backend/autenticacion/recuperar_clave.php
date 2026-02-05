<?php
require_once '../bd/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"] ?? '');

    if ($correo === '') {
        echo "Debes ingresar un correo.";
        exit;
    }

    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        // Aquí podrías generar un token y enviar email
        echo "Se ha enviado un enlace de recuperación (simulado).";
    } else {
        echo "Correo no encontrado.";
    }
}
?>