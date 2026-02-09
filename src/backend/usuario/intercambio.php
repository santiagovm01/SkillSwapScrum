<?php
session_start();
require_once __DIR__ . "/../bd/conexion.php";

// Verificar sesión
if (!isset($_SESSION["id_usuario"])) {
    die("No has iniciado sesión.");
}

$id_solicitante = $_SESSION["id_usuario"];
$id_habilidad = $_POST["id_habilidad"] ?? null;

if (!$id_habilidad) {
    die("ID de habilidad no recibido.");
}

// Obtener el dueño de la habilidad
$stmt = $pdo->prepare("SELECT id_usuario FROM Habilidad WHERE id_habilidad = ?");
$stmt->execute([$id_habilidad]);
$id_destinatario = $stmt->fetchColumn();

if (!$id_destinatario) {
    die("La habilidad no existe.");
}

// Crear solicitud de intercambio
$stmt = $pdo->prepare("
    INSERT INTO Intercambio (id_solicitante, id_destinatario, id_habilidad, estado)
    VALUES (?, ?, ?, 'pendiente')
");
$stmt->execute([$id_solicitante, $id_destinatario, $id_habilidad]);

// Redirigir de vuelta
header("Location: ../../frontend/intercambio.html?ok=1");
exit();
?>
