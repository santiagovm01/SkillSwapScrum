<?php
// frontend/mensajes-aplicacion/enviar_correo.php
require_once __DIR__ . "/../../backend/autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../../backend/bd/conexion.php";

// Este script sería para enviar un correo (por ejemplo, confirmación o aviso).
// Para la demo, dejamos una simulación sencilla.

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $para    = trim($_POST["para"] ?? "");
    $asunto  = trim($_POST["asunto"] ?? "");
    $mensaje = trim($_POST["mensaje"] ?? "");

    // Aquí podrías usar mail() si el servidor lo permite:
    // mail($para, $asunto, $mensaje);

    // Para la demo, simplemente redirigimos como si se hubiera enviado.
    header("Location: ../index.html");
    exit;
}
?>