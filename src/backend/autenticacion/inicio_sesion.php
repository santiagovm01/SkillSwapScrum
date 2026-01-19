<<<<<<< HEAD
=======
<?php
//hacemos la conexion
session_start();
require_once '../bd/conexion.php'; 
require_once '../utils/validar_datos.php';

$email = limpiarDato($_POST["email"] ?? '');
$password = limpiarDato($_POST["password"] ?? '');

$errores = validarLogin($email, $password);

if (!empty($errores)) {
    $_SESSION['error'] = implode("<br>", $errores);
    header("Location: ../../frontend/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

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
    } else {
        $_SESSION['error'] = "Usuario no encontrado.";
        header("Location: ../../frontend/login.html");
        exit();
    }
} else {
    header("Location: ../../frontend/login.html");
    exit();
}
?>
>>>>>>> 59d13c4 (Añade validación de datos y actualiza inicio de sesión)
