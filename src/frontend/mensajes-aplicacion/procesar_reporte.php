<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_reportado = trim($_POST['usuario_reportado']);
    $razon = trim($_POST['razon']);
    
    if (!empty($usuario_reportado) && !empty($razon)) {
        // Simula guardar en BD (verifica si el usuario existe y no es auto-reporte)
        // Ejemplo: $pdo->prepare("INSERT INTO reportes (reportante, reportado, razon) VALUES (?, ?, ?)")->execute([$_SESSION['usuario_id'], $usuario_reportado, $razon]);
        
        $_SESSION['mensaje'] = "Reporte enviado exitosamente. Revisaremos la situación.";
        $_SESSION['tipo'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error: Completa todos los campos.";
        $_SESSION['tipo'] = "error";
    }
    
    header("Location: reportar.php");
    exit();
}
?>