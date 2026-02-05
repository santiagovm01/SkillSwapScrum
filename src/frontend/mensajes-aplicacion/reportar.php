<?php
// frontend/mensajes-aplicacion/reportar.php
require_once __DIR__ . "/../../backend/autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../../backend/bd/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_reportante = $_SESSION["id_usuario"];
    $id_reportado  = (int)($_POST["id_reportado"] ?? 0);
    $motivo        = trim($_POST["motivo"] ?? "");

    if ($id_reportado && $motivo !== "") {
        $stmt = $pdo->prepare(
            "INSERT INTO Reporte (id_reportado, id_reportante, motivo) VALUES (?, ?, ?)"
        );
        $stmt->execute([$id_reportado, $id_reportante, $motivo]);

        // Notificación a admin (simplificado: id 1)
        $stmtN = $pdo->prepare(
            "INSERT INTO Notificacion (id_usuario, tipo) VALUES (1, 'nuevo_reporte')"
        );
        $stmtN->execute();
    }

    header("Location: ../reportar.html");
    exit;
}
?>