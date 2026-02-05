<?php
// backend/usuario/publicar.php
require_once __DIR__ . "/../autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../bd/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_SESSION["id_usuario"];
    $titulo = trim($_POST["titulo"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $imagen = null;

    if (!empty($_FILES["imagen"]["name"])) {
        $nombreTmp = $_FILES["imagen"]["tmp_name"];
        $nombreFinal = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaDestino = "../../frontend/img/" . $nombreFinal;
        if (move_uploaded_file($nombreTmp, $rutaDestino)) {
            $imagen = "img/" . $nombreFinal;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO Habilidad (id_usuario, titulo, descripcion, imagen) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_usuario, $titulo, $descripcion, $imagen]);

    header("Location: ../../frontend/habilidades.html");
    exit;
}
?>