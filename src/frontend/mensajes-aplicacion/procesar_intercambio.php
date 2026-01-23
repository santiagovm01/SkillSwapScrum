<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_intercambio = trim($_POST['usuario_intercambio']);
    $habilidad_ofrecida = trim($_POST['habilidad_ofrecida']);
    $habilidad_solicitada = trim($_POST['habilidad_solicitada']);
    
    if (!empty($usuario_intercambio) && !empty($habilidad_ofrecida) && !empty($habilidad_solicitada)) {
        // Simula guardar en BD (verifica compatibilidad de habilidades)
        // Ejemplo: $pdo->prepare("INSERT INTO intercambios (usuario1, usuario2, habilidad1, habilidad2) VALUES (?, ?, ?, ?)")->execute([$_SESSION['usuario_id'], $usuario_intercambio, $habilidad_ofrecida, $habilidad_solicitada]);
        
        $_SESSION['mensaje'] = "Intercambio iniciado exitosamente con $usuario_intercambio. Espera confirmación.";
        $_SESSION['tipo'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error: Todos los campos son obligatorios.";
        $_SESSION['tipo'] = "error";
    }
    
    header("Location: iniciar_intercambio.php");
    exit();
}
?>