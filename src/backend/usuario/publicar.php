<?php
session_start();
require_once __DIR__ . "/../bd/conexion.php";

// Verificar sesión
if (!isset($_SESSION["id_usuario"])) {
    die("No has iniciado sesión.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo = trim($_POST["titulo"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $id_usuario = $_SESSION["id_usuario"];

    if (empty($titulo) || empty($descripcion)) {
        die("Todos los campos son obligatorios.");
    }

    // Insertar habilidad
    $stmt = $pdo->prepare("INSERT INTO Habilidad (id_usuario, titulo, descripcion) VALUES (?, ?, ?)");
    $stmt->execute([$id_usuario, $titulo, $descripcion]);

    // Redirigir de vuelta a habilidades
    header("Location: ../../frontend/habilidades.html");
    exit();
}
?>
