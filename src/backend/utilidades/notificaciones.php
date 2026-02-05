<?php
// backend/utilidades/notificaciones.php
require_once __DIR__ . "/../autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../bd/conexion.php";

$id_usuario = $_SESSION["id_usuario"];

$stmt = $pdo->prepare("SELECT * FROM Notificacion WHERE id_usuario = ? ORDER BY fecha DESC");
$stmt->execute([$id_usuario]);
$notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json");
echo json_encode($notificaciones);
?>