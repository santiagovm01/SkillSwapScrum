<?php
session_start();
require_once __DIR__ . "/../bd/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $contrasena = trim($_POST["contrasena"] ?? "");

    // Validación básica
    if (empty($email) || empty($contrasena)) {
        $_SESSION["error"] = "Todos los campos son obligatorios.";
        header("Location: ../../frontend/login.html");
        exit();
    }

    // Buscar usuario (incluyendo ubicación y descripción)
    $stmt = $pdo->prepare("SELECT id_usuario, nombre, email, contrasena, ubicacion, descripcion
                           FROM Usuario 
                           WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar credenciales
    if ($usuario && password_verify($contrasena, $usuario["contrasena"])) {

        // Guardar sesión COMPLETA
        $_SESSION["id_usuario"] = $usuario["id_usuario"];
        $_SESSION["nombre"] = $usuario["nombre"];
        $_SESSION["email"] = $usuario["email"];
        $_SESSION["ubicacion"] = $usuario["ubicacion"];
        $_SESSION["descripcion"] = $usuario["descripcion"];

        // Redirigir al perfil
        header("Location: ../../frontend/perfil.php");
        exit();
    }

    // Si llega aquí, credenciales incorrectas
    $_SESSION["error"] = "Correo o contraseña incorrectos.";
    header("Location: ../../frontend/login.html");
    exit();
}
?>
