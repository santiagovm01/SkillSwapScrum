<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi perfil - SkillSwap</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header class="header">
    <div class="logo">SkillSwap</div>
    <nav>
        <a href="habilidades.html">Habilidades</a>
        <a href="chat.html">Chat</a>
        <a href="notificaciones.html">Notificaciones</a>
    <a href="../backend/autenticacion/cerrar_sesion.php" class="btn-login">Salir</a>
    </nav>
</header>

<main class="main">
    <section class="card">
        <h2>Mi perfil</h2>
        <p>Datos básicos del usuario.</p>

        <p><strong>Nombre:</strong> <?php echo $_SESSION["nombre"]; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION["email"]; ?></p>
        <p><strong>Ubicación:</strong> <?php echo $_SESSION["ubicacion"] ?? "No especificada"; ?></p>
        <p><strong>Descripción:</strong> <?php echo $_SESSION["descripcion"] ?? "Sin descripción"; ?></p>
    </section>
</main>

</body>
</html>
