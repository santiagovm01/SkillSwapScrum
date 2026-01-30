<?php
include("../bd/conexion.php");

$texto = $_GET["texto"] ?? "";
$categoria = $_GET["categoria"] ?? "";

$sql = "SELECT H.*, U.nombre AS usuario
        FROM Habilidad H
        JOIN Usuario U ON H.id_usuario = U.id_usuario
        WHERE H.titulo LIKE ?";

$params = ["%" . $texto . "%"];
$types = "s";

if ($categoria !== "") {
    $sql .= " AND H.categoria = ?";
    $params[] = $categoria;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();

$habilidades = [];

while ($fila = $resultado->fetch_assoc()) {
    $habilidades[] = $fila;
}

echo json_encode($habilidades);
?>
