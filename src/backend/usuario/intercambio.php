<?php
session_start();
include("../bd/conexion.php");
include("../utilidades/notificaciones.php");

if (!isset($_SESSION["id_usuario"])) {
    die("Debes iniciar sesión");
}

$accion = $_POST["accion"] ?? "";

if ($accion === "solicitar") {

    $id_usuario_solicitante = $_SESSION["id_usuario"];
    $id_habilidad = $_POST["id_habilidad"];

    // Obtener dueño de la habilidad
    $sql = "SELECT id_usuario FROM Habilidad WHERE id_habilidad = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_habilidad);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $id_usuario_receptor = $res["id_usuario"];

    // Validación: no puedes solicitar tu propia habilidad
    if ($id_usuario_receptor == $id_usuario_solicitante) {
        die("No puedes solicitar tu propia habilidad");
    }

    // Crear intercambio
    $sql = "INSERT INTO Intercambio (id_usuario_solicitante, id_usuario_receptor, id_habilidad, estado)
            VALUES (?, ?, ?, 'pendiente')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $id_usuario_solicitante, $id_usuario_receptor, $id_habilidad);

    if ($stmt->execute()) {
        crearNotificacion($id_usuario_receptor, "Tienes una nueva solicitud de intercambio");
        echo "Solicitud enviada correctamente";
    } else {
        echo "Error al solicitar intercambio";
    }
}
?>
