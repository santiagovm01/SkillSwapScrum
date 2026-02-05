<?php
// backend/autenticacion/inicio_sesion.php
session_start();
<<<<<<< Updated upstream
require_once '../bd/conexion.php'; 
=======
require_once __DIR__ . "/../bd/conexion.php";
>>>>>>> Stashed changes

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

<<<<<<< Updated upstream
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../../frontend/login.html");
        exit();
    }

    $stmt = $conn->prepare("SELECT id_usuario, nombre, correo, clave FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['clave'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['correo'] = $usuario['correo'];

            header("Location: ../../frontend/perfil.html");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: ../../frontend/login.html");
            exit();
        }
=======
    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasena, $usuario["contrasena"])) {
        $_SESSION["id_usuario"] = $usuario["id_usuario"];
        $_SESSION["nombre"] = $usuario["nombre"];
        $_SESSION["es_admin"] = (bool)$usuario["es_admin"];
        header("Location: ../../frontend/habilidades.html");
        exit;
>>>>>>> Stashed changes
    } else {
        $_SESSION["error"] = "Credenciales incorrectas.";
        header("Location: ../../frontend/login.html");
        exit;
    }
}
?>