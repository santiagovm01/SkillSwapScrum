<?php
function crearNotificacion($id_usuario, $mensaje) {
    include("../bd/conexion.php");

    $sql = "INSERT INTO Notificacion (id_usuario, mensaje, leida)
            VALUES (?, ?, 0)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id_usuario, $mensaje);
    $stmt->execute();
}
?>
