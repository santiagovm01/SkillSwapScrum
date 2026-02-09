<?php
require_once __DIR__ . "/../bd/conexion.php";

$stmt = $pdo->query("
    SELECT H.*, U.nombre AS usuario_nombre, U.ubicacion 
    FROM Habilidad H
    JOIN Usuario U ON H.id_usuario = U.id_usuario
    ORDER BY H.id_habilidad DESC
");

$habilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json");
echo json_encode($habilidades);
?>
