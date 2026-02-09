<?php
session_start();
require_once __DIR__ . "/../bd/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recibir datos del formulario
    $nombre      = trim($_POST["nombre"] ?? "");
    $email       = trim($_POST["email"] ?? "");
    $ubicacion   = trim($_POST["ubicacion"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $password    = trim($_POST["contrasena"] ?? "");
    $password2   = trim($_POST["contrasena2"] ?? "");

    // Validación básica
    if (empty($nombre) || empty($email) || empty($password) || empty($password2)) {
        $_SESSION['error'] = "Todos los campos obligatorios deben estar completos.";
        header("Location: ../../frontend/registro.html");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El correo no es válido.";
        header("Location: ../../frontend/registro.html");
        exit();
    }

    if ($password !== $password2) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
        header("Location: ../../frontend/registro.html");
        exit();
    }

    // Comprobar si el correo ya existe
    $stmt = $pdo->prepare("SELECT id_usuario FROM Usuario WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = "El correo ya está registrado.";
        header("Location: ../../frontend/registro.html");
        exit();
    }

    // Cifrar contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario
    $stmt = $pdo->prepare(
        "INSERT INTO Usuario (nombre, email, contrasena, ubicacion, descripcion)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->execute([$nombre, $email, $password_hash, $ubicacion, $descripcion]);

    // Mensaje de éxito
    $_SESSION['mensaje'] = "Registro exitoso. Ahora puedes iniciar sesión.";

    header("Location: ../../frontend/login.html");
    exit();
}
?>