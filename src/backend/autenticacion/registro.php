<?php
<<<<<<< Updated upstream
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
=======
// backend/autenticacion/registro.php
session_start();
require_once __DIR__ . "/../bd/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $ubicacion = trim($_POST["ubicacion"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";
    $contrasena2 = $_POST["contrasena2"] ?? "";

    if ($contrasena !== $contrasena2) {
        $_SESSION["error"] = "Las contraseñas no coinciden.";
        header("Location: ../../frontend/registro.html");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error"] = "El email no es válido.";
        header("Location: ../../frontend/registro.html");
        exit;
    }

    // Comprobar si ya existe
    $stmt = $pdo->prepare("SELECT id_usuario FROM Usuario WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION["error"] = "Ya existe un usuario con ese correo.";
        header("Location: ../../frontend/registro.html");
        exit;
    }

    $hash = password_hash($contrasena, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO Usuario (nombre, email, contrasena, ubicacion, descripcion) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $hash, $ubicacion, $descripcion]);

    $_SESSION["mensaje"] = "Registro completado. Ahora puedes iniciar sesión.";
    header("Location: ../../frontend/login.html");
    exit;
>>>>>>> Stashed changes
}
?>
