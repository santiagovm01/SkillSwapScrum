<?php
session_start();
require_once '../bd/conexion.php';

header("Content-Type: application/json");

// Verificar sesión
if (!isset($_SESSION["id_usuario"])) {
    echo json_encode([
        "success" => false,
        "message" => "Debes iniciar sesión"
    ]);
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

// Consulta del historial
$sql = "SELECT id_historial, accion, descripcion, fecha
        FROM historial
        WHERE id_usuario = ?
        ORDER BY fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$historial = [];

while ($fila = $resultado->fetch_assoc()) {
    $historial[] = $fila;
}

echo json_encode([
    "success" => true,
    "historial" => $historial
]);
?>
