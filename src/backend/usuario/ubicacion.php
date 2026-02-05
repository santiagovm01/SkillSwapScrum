<?php
session_start();
require_once '../bd/conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Debes iniciar sesión para guardar tu ubicación'
    ]);
    exit;
}

$usuario_id = $_SESSION['id_usuario'];

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$conn = Conexion::getInstancia()->getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $latitud = $input['latitud'] ?? null;
    $longitud = $input['longitud'] ?? null;
    $ciudad = $input['ciudad'] ?? null;
    $pais = $input['pais'] ?? null;
    $mostrar_ubicacion = isset($input['mostrar_ubicacion']) ? (int)$input['mostrar_ubicacion'] : 0;

    if ($latitud !== null && ($latitud < -90 || $latitud > 90)) {
        echo json_encode(['success' => false, 'message' => 'Latitud inválida']);
        exit;
    }

    if ($longitud !== null && ($longitud < -180 || $longitud > 180)) {
        echo json_encode(['success' => false, 'message' => 'Longitud inválida']);
        exit;
    }

    try {
        $sql = "UPDATE usuarios SET 
                latitud = :latitud,
                longitud = :longitud,
                ciudad = :ciudad,
                pais = :pais,
                mostrar_ubicacion = :mostrar_ubicacion
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':latitud', $latitud);
        $stmt->bindParam(':longitud', $longitud);
        $stmt->bindParam(':ciudad', $ciudad);
        $stmt->bindParam(':pais', $pais);
        $stmt->bindParam(':mostrar_ubicacion', $mostrar_ubicacion, PDO::PARAM_INT);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);

        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Ubicación guardada correctamente'
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    }

    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    try {
        $sql = "SELECT latitud, longitud, ciudad, pais, mostrar_ubicacion
                FROM usuarios WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        $ubicacion = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $ubicacion ?: []
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    }

    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    try {
        $sql = "UPDATE usuarios SET 
                latitud = NULL,
                longitud = NULL,
                ciudad = NULL,
                pais = NULL,
                mostrar_ubicacion = 0
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Ubicación eliminada correctamente'
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    }

    exit;
}

http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Método no permitido'
]);
