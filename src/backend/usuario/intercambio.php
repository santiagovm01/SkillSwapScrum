<?php
// backend/usuario/intercambio.php
require_once __DIR__ . "/../autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../bd/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_solicitante = $_SESSION["id_usuario"];
    $id_destinatario = (int)($_POST["id_destinatario"] ?? 0);
    $id_habilidad = (int)($_POST["id_habilidad"] ?? 0);

    $stmt = $pdo->prepare("INSERT INTO Intercambio (id_solicitante, id_destinatario, id_habilidad, fecha_solicitud, estado)
                           VALUES (?, ?, ?, CURDATE(), 'pendiente')");
    $stmt->execute([$id_solicitante, $id_destinatario, $id_habilidad]);

    header("Location: ../../frontend/intercambio.html");
    exit;
}
?>