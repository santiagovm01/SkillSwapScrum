<?php
include("../bd/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
    $ubicacion = $_POST["ubicacion"];
    $descripcion = $_POST["descripcion"];

    // Validar email único
    $check = $conn->prepare("SELECT id_usuario FROM Usuario WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $resultado = $check->get_result();

    if ($resultado->num_rows > 0) {
        echo "El correo ya está registrado";
        exit;
    }

    // Insertar usuario
    $sql = "INSERT INTO Usuario (nombre, email, contrasena, ubicacion, descripcion)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $email, $contrasena, $ubicacion, $descripcion);

    if ($stmt->execute()) {
        header("Location: ../../frontend/login.html?registro=ok");
        exit;
    } else {
        echo "Error al registrar: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
