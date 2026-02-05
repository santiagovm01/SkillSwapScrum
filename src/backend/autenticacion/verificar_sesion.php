<?php
// backend/autenticacion/verificar_sesion.php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../frontend/login.html");
    exit;
}
?>