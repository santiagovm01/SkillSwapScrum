<<<<<<< Updated upstream
=======
<?php
// backend/usuario/perfil.php
require_once __DIR__ . "/../autenticacion/verificar_sesion.php";
require_once __DIR__ . "/../bd/conexion.php";

$id = $_SESSION["id_usuario"];

$stmt = $pdo->prepare("SELECT * FROM Usuario WHERE id_usuario = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado");
}

// aquí podrías devolver JSON si lo llamas por AJAX
header("Content-Type: application/json");
echo json_encode($usuario);

?>
>>>>>>> Stashed changes
