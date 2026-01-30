<?php
/**
 * guardar.php
 * Guardar una nueva publicación en la base de datos
 * Este archivo procesa la lógica del lado del servidor para guardar publicaciones
 * después de que el usuario pulsa el botón de publicar
 */

header('Content-Type: application/json');

session_start();

// Verificar que el usuario tenga sesión activa
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Debes iniciar sesión para publicar'
    ]);
    exit;
}

require_once '../bd/conexion.php';

// Obtener el ID del usuario de la sesión
$usuario_id = $_SESSION['id_usuario'];

// Obtener datos del cuerpo de la petición
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    // Si no viene JSON, intentar obtener de POST
    $input = $_POST;
}

// Validar contenido obligatorio
$contenido = isset($input['contenido']) ? trim($input['contenido']) : '';

if (empty($contenido)) {
    echo json_encode([
        'success' => false,
        'message' => 'El contenido de la publicación es obligatorio'
    ]);
    exit;
}

// Validar longitud máxima del contenido
if (strlen($contenido) > 5000) {
    echo json_encode([
        'success' => false,
        'message' => 'El contenido no puede exceder 5000 caracteres'
    ]);
    exit;
}

// Obtener título (opcional)
$titulo = isset($input['titulo']) ? trim($input['titulo']) : null;

if ($titulo && strlen($titulo) > 200) {
    echo json_encode([
        'success' => false,
        'message' => 'El título no puede exceder 200 caracteres'
    ]);
    exit;
}

// Obtener tipo de publicación (opcional, valor por defecto: 'publicacion')
$tipo = isset($input['tipo']) ? $input['tipo'] : 'publicacion';

// Validar tipo de publicación
$tipos_permitidos = ['publicacion', 'intercambio', 'ayuda'];
if (!in_array($tipo, $tipos_permitidos)) {
    $tipo = 'publicacion';
}

try {
    $conn = Conexion::getInstancia()->getConexion();
    
    // Insertar la publicación en la base de datos
    $sql = "INSERT INTO publicaciones (usuario_id, contenido, titulo, tipo) 
            VALUES (:usuario_id, :contenido, :titulo, :tipo)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $publicacion_id = $conn->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Publicación guardada correctamente',
            'data' => [
                'id' => $publicacion_id,
                'contenido' => $contenido,
                'titulo' => $titulo,
                'tipo' => $tipo,
                'fecha' => date('Y-m-d H:i:s')
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar la publicación'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
}

exit;
?>

