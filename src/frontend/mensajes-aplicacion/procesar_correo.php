<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinatario = trim($_POST['destinatario']);
    $mensaje = trim($_POST['mensaje']);
    
    if (!empty($destinatario) && !empty($mensaje)) {
        // Simula guardar en BD (reemplaza con tu lógica: verifica si el usuario existe)
        // Ejemplo: $pdo->prepare("INSERT INTO mensajes (remitente, destinatario, contenido) VALUES (?, ?, ?)")->execute([$_SESSION['usuario_id'], $destinatario, $mensaje]);
        
        $_SESSION['mensaje'] = "Correo enviado exitosamente a $destinatario.";
        $_SESSION['tipo'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error: Todos los campos son obligatorios.";
        $_SESSION['tipo'] = "error";
    }
    
    header("Location: enviar_correo.php");
    exit();
}
?>