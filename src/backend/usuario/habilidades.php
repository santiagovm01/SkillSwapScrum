<?php
session_start();
include '../bd/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    die("No has iniciado sesión");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hab = $_POST['habilidad'];
    $desc = $_POST['descripcion'];
    $id = $_SESSION['usuario_id'];

    $stmt = $pdo->prepare("INSERT INTO habilidades (usuario_id, habilidad, descripcion) VALUES (?, ?, ?)");
    $stmt->execute([$id, $hab, $desc]);

    echo "Habilidad añadida correctamente";
}
?>
