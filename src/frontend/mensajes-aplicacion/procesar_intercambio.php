<?php
// frontend/mensajes-aplicacion/procesar_intercambio.php
require_once __DIR__ . "/../../backend/autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../../backend/bd/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario    = $_SESSION["id_usuario"];
    $id_intercambio = (int)($_POST["id_intercambio"] ?? 0);
    $accion         = $_POST["accion"] ?? ""; // aceptar, rechazar, finalizar

    if ($id_intercambio && in_array($accion, ["aceptar", "rechazar", "finalizar"])) {
        $estado = [
            "aceptar"   => "aceptado",
            "rechazar"  => "rechazado",
            "finalizar" => "completado"
        ][$accion];

        // Solo el destinatario debería poder aceptar/rechazar
        $stmt = $pdo->prepare(
            "UPDATE Intercambio 
             SET estado = ? 
             WHERE id_intercambio = ? AND (id_destinatario = ? OR id_solicitante = ?)"
        );
        $stmt->execute([$estado, $id_intercambio, $id_usuario, $id_usuario]);

        // Notificación al otro usuario
        $stmtDatos = $pdo->prepare(
            "SELECT id_solicitante, id_destinatario FROM Intercambio WHERE id_intercambio = ?"
        );
        $stmtDatos->execute([$id_intercambio]);
        $fila = $stmtDatos->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $otro = ($fila["id_solicitante"] == $id_usuario)
                ? $fila["id_destinatario"]
                : $fila["id_solicitante"];

            $stmtN = $pdo->prepare(
                "INSERT INTO Notificacion (id_usuario, tipo) VALUES (?, 'estado_intercambio')"
            );
            $stmtN->execute([$otro]);
        }

        // Si se finaliza, guardamos en historial
        if ($accion === "finalizar") {
            $stmtH = $pdo->prepare(
                "INSERT IGNORE INTO Historial (id_usuario, id_intercambio, estado_final)
                 VALUES (?, ?, ?)"
            );
            $stmtH->execute([$id_usuario, $id_intercambio, $estado]);
        }
    }

    header("Location: ../intercambio.html");
    exit;
}
?>