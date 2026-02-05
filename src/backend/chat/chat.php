<?php
// backend/chat/chat.php
require_once __DIR__ . "/../autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../bd/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_intercambio = (int)($_POST["id_intercambio"] ?? 0);
    $contenido = trim($_POST["contenido"] ?? "");
    $id_emisor = $_SESSION["id_usuario"];

    if ($id_intercambio && $contenido !== "") {
        $stmt = $pdo->prepare("INSERT INTO Mensaje (id_intercambio, id_emisor, contenido) VALUES (?, ?, ?)");
        $stmt->execute([$id_intercambio, $id_emisor, $contenido]);
    }

    header("Location: ../../frontend/chat.html");
    exit;
}
?>