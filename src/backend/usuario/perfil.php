<?php
session_start();
include("../bd/conexion.php");

$id_usuario = $_SESSION["id_usuario"];

$sql = "SELECT * FROM Habilidad WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$habilidades = [];

while ($fila = $resultado->fetch_assoc()) {
    $habilidades[] = $fila;
}

echo json_encode($habilidades);
?>
