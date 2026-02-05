<?php
// frontend/mensajes-aplicacion/procesar_correo.php
// Podrías usarlo como intermediario para preparar datos y llamar a enviar_correo.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Aquí podrías validar datos, construir el mensaje, etc.
    // Para la demo, simplemente redirigimos a enviar_correo.php
    header("Location: enviar_correo.php");
    exit;
}
?>