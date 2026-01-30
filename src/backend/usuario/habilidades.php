<?php
session_start();
include("../bd/conexion.php");

if (!isset($_SESSION["id_usuario"])) {
    die("Debes iniciar sesión");
}

$accion = $_POST["accion"] ?? "";

if ($accion === "crear") {

    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $categoria = $_POST["categoria"];
    $nivel = $_POST["nivel"];
    $id_usuario = $_SESSION["id_usuario"];

    // Imagen opcional
    $ruta_imagen = null;
    if (!empty($_FILES["imagen"]["name"])) {
        $nombre_archivo = time() . "_" . basename($_FILES["imagen"]["name"]);
        $ruta = "../../uploads/" . $nombre_archivo;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
        $ruta_imagen = $nombre_archivo;
    }

    $sql = "INSERT INTO Habilidad (id_usuario, titulo, descripcion, categoria, nivel, imagen)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $id_usuario, $titulo, $descripcion, $categoria, $nivel, $ruta_imagen);

    if ($stmt->execute()) {
        header("Location: ../../frontend/habilidades.html?creada=ok");
        exit;
    } else {
        echo "Error al crear habilidad: " . $conn->error;
    }
}
?>
