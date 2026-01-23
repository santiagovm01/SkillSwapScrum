<?php
/**
 * Gestionar datos del usuario
 * Permite editar perfil, ubicación y otros datos personales
 */

session_start();
require_once '../bd/conexion.php';

header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Debes iniciar sesión'
    ]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$conn = Conexion::getInstancia()->getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener datos del usuario
    try {
        $sql = "SELECT id, nombre, email, biografia, habilidades, foto_perfil, 
                       latitud, longitud, ciudad, pais, mostrar_ubicacion
                FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            echo json_encode([
                'success' => true,
                'data' => $usuario
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Actualizar datos del usuario
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    $nombre = isset($input['nombre']) ? trim($input['nombre']) : null;
    $biografia = isset($input['biografia']) ? trim($input['biografia']) : null;
    $habilidades = isset($input['habilidades']) ? trim($input['habilidades']) : null;
    $ciudad = isset($input['ciudad']) ? trim($input['ciudad']) : null;
    $pais = isset($input['pais']) ? trim($input['pais']) : null;
    $latitud = isset($input['latitud']) ? $input['latitud'] : null;
    $longitud = isset($input['longitud']) ? $input['longitud'] : null;
    $mostrar_ubicacion = isset($input['mostrar_ubicacion']) ? (int)$input['mostrar_ubicacion'] : 0;
    
    // Validaciones
    if ($nombre && strlen($nombre) < 2) {
        echo json_encode([
            'success' => false,
            'message' => 'El nombre debe tener al menos 2 caracteres'
        ]);
        exit;
    }
    
    if ($nombre && strlen($nombre) > 50) {
        echo json_encode([
            'success' => false,
            'message' => 'El nombre no puede exceder 50 caracteres'
        ]);
        exit;
    }
    
    // Validar coordenadas si se proporcionan
    if ($latitud && ($latitud < -90 || $latitud > 90)) {
        echo json_encode([
            'success' => false,
            'message' => 'Latitud inválida'
        ]);
        exit;
    }
    
    if ($longitud && ($longitud < -180 || $longitud > 180)) {
        echo json_encode([
            'success' => false,
            'message' => 'Longitud inválida'
        ]);
        exit;
    }
    
    try {
        $sql = "UPDATE usuarios SET 
                nombre = :nombre,
                biografia = :biografia,
                habilidades = :habilidades,
                ciudad = :ciudad,
                pais = :pais,
                latitud = :latitud,
                longitud = :longitud,
                mostrar_ubicacion = :mostrar_ubicacion
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':biografia', $biografia, PDO::PARAM_STR);
        $stmt->bindParam(':habilidades', $habilidades, PDO::PARAM_STR);
        $stmt->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
        $stmt->bindParam(':pais', $pais, PDO::PARAM_STR);
        $stmt->bindParam(':latitud', $latitud, PDO::PARAM_STR);
        $stmt->bindParam(':longitud', $longitud, PDO::PARAM_STR);
        $stmt->bindParam(':mostrar_ubicacion', $mostrar_ubicacion, PDO::PARAM_INT);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Actualizar sesión si el nombre cambió
            if ($nombre) {
                $_SESSION['usuario_nombre'] = $nombre;
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Datos actualizados correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar los datos'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Método no permitido
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Método no permitido'
]);

