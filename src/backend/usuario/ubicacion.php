<?php
/**
 * API para gestionar la ubicación del usuario
 * Permite guardar y actualizar la ubicación del usuario logueado
 */

session_start();
require_once '../bd/conexion.php';

header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Debes iniciar sesión para guardar tu ubicación'
    ]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener datos del cuerpo de la petición
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Guardar o actualizar ubicación
    
    $latitud = isset($input['latitud']) ? $input['latitud'] : null;
    $longitud = isset($input['longitud']) ? $input['longitud'] : null;
    $ciudad = isset($input['ciudad']) ? $input['ciudad'] : null;
    $pais = isset($input['pais']) ? $input['pais'] : null;
    $mostrar_ubicacion = isset($input['mostrar_ubicacion']) ? ($input['mostrar_ubicacion'] ? 1 : 0) : 0;
    
    // Si se proporciona latitud y longitud pero no ciudad/pais, intentar obtenerlos
    if ($latitud && $longitud && (!$ciudad || !$pais)) {
        $ubicacion = obtenerCiudadPais($latitud, $longitud);
        $ciudad = $ciudad ?: $ubicacion['ciudad'];
        $pais = $pais ?: $ubicacion['pais'];
    }
    
    // Validar coordenadas
    if ($latitud && ($latitud < -90 || $latitud > 90)) {
        echo json_encode([
            'success' => false,
            'message' => 'Latitud inválida. Debe estar entre -90 y 90'
        ]);
        exit;
    }
    
    if ($longitud && ($longitud < -180 || $longitud > 180)) {
        echo json_encode([
            'success' => false,
            'message' => 'Longitud inválida. Debe estar entre -180 y 180'
        ]);
        exit;
    }
    
    try {
        $conn = Conexion::getInstancia()->getConexion();
        
        $sql = "UPDATE usuarios SET 
                latitud = :latitud, 
                longitud = :longitud, 
                ciudad = :ciudad, 
                pais = :pais,
                mostrar_ubicacion = :mostrar_ubicacion
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':latitud', $latitud, PDO::PARAM_STR);
        $stmt->bindParam(':longitud', $longitud, PDO::PARAM_STR);
        $stmt->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
        $stmt->bindParam(':pais', $pais, PDO::PARAM_STR);
        $stmt->bindParam(':mostrar_ubicacion', $mostrar_ubicacion, PDO::PARAM_INT);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Ubicación guardada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al guardar la ubicación'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener ubicación del usuario
    
    try {
        $conn = Conexion::getInstancia()->getConexion();
        
        $sql = "SELECT latitud, longitud, ciudad, pais, mostrar_ubicacion 
                FROM usuarios WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $ubicacion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($ubicacion) {
            echo json_encode([
                'success' => true,
                'data' => $ubicacion
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
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Eliminar ubicación (opcional)
    
    try {
        $conn = Conexion::getInstancia()->getConexion();
        
        $sql = "UPDATE usuarios SET 
                latitud = NULL, 
                longitud = NULL, 
                ciudad = NULL, 
                pais = NULL,
                mostrar_ubicacion = 0
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Ubicación eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al eliminar la ubicación'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error de base de datos: ' . $e->getMessage()
        ]);
    }
}

/**
 * Obtener ciudad y país a partir de coordenadas usando geocodificación inversa
 * Esta es una versión simplificada. En producción, usar una API como OpenStreetMap o Google Maps
 */
function obtenerCiudadPais($latitud, $longitud) {
    // Versión básica - en producción integrar con API de geocodificación
    // Por ejemplo: Nominatim (OpenStreetMap) o Google Geocoding API
    
    // Aquí puedes implementar la llamada a una API externa
    // Ejemplo con Nominatim (gratuito):
    /*
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitud}&lon={$longitud}";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
 [
        'ciudad' => $    
    returndata['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? '',
        'pais' => $data['address']['country'] ?? ''
    ];
    */
    
    // Por ahora devolvemos valores vacíos para completar manualmente
    return [
        'ciudad' => '',
        'pais' => ''
    ];
}

