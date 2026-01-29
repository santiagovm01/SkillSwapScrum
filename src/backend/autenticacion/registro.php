<?php
session_start();
require_once '../bd/conexion.php'; // Ajusta la ruta si es necesario

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $password2 = trim($_POST["password2"] ?? '');

    // Validación básica
    if (empty($nombre) || empty($correo) || empty($password) || empty($password2)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../../frontend/registro.html");
        exit();
    }

    if ($password !== $password2) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
        header("Location: ../../frontend/registro.html");
        exit();
    }

    // Comprobar si el correo ya existe
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $stmt-zz>bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION['error'] = "El correo ya está registrado.";
        header("Location: ../../frontend/registro.html");
        exit();
    }

    // Cifrar contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, clave) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $correo, $password_hash);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Registro exitoso. Ahora puedes iniciar sesión.";
        header("Location: ../../frontend/login.html");
        exit();
    } else {
        $_SESSION['error'] = "Error al registrar usuario.";
        header("Location: ../../frontend/registro.html");
        exit();
    }

} else {
    header("Location: ../../frontend/registro.html");
    exit();
}
?>
