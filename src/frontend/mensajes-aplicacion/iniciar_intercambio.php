<?php
// frontend/mensajes-aplicacion/iniciar_intercambio.php
require_once __DIR__ . "/../../backend/autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../../backend/bd/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_solicitante  = $_SESSION["id_usuario"];
    $id_destinatario = (int)($_POST["id_destinatario"] ?? 0);
    $id_habilidad    = (int)($_POST["id_habilidad"] ?? 0);

    if ($id_destinatario && $id_habilidad) {
        $stmt = $pdo->prepare(
            "INSERT INTO Intercambio (id_solicitante, id_destinatario, id_habilidad, fecha_solicitud, estado)
             VALUES (?, ?, ?, CURDATE(), 'pendiente')"
        );
        $stmt->execute([$id_solicitante, $id_destinatario, $id_habilidad]);

        // Notificación al destinatario
        $stmtN = $pdo->prepare(
            "INSERT INTO Notificacion (id_usuario, tipo) VALUES (?, 'nueva_solicitud')"
        );
        $stmtN->execute([$id_destinatario]);
    }

    header("Location: ../intercambio.html");
    exit;
}
?>