<?php
// frontend/mensajes-aplicacion/procesar_reporte.php
require_once __DIR__ . "/../../backend/autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../../backend/bd/conexion.php";

if (empty($_SESSION["es_admin"])) {
    header("Location: ../../frontend/index.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_reporte = (int)($_POST["id_reporte"] ?? 0);
    $accion     = $_POST["accion"] ?? ""; // revisar, descartar

    if ($id_reporte && in_array($accion, ["revisar", "descartar"])) {
        // Aquí podrías tener un campo estado en Reporte; como no está, solo ejemplo:
        // podrías borrar el reporte si se descarta
        if ($accion === "descartar") {
            $stmt = $pdo->prepare("DELETE FROM Reporte WHERE id_reporte = ?");
            $stmt->execute([$id_reporte]);
        }
        // si "revisar", podrías dejarlo tal cual o moverlo a otra tabla
    }

    header("Location: ../../backend/admin/reportes.php");
    exit;
}
?>