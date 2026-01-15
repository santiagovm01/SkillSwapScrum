<form action="procesar_correo.php" method="post">
    <input type="text" name="destinatario" placeholder="Usuario destinatario" required>
    <textarea name="mensaje" placeholder="Mensaje" required></textarea>
    <button type="submit">Enviar Correo</button>
</form>

<?php
session_start();
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
$tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : '';
unset($_SESSION['mensaje'], $_SESSION['tipo']);
?>

<?php if ($mensaje): ?>
    <div class="mensaje <?php echo htmlspecialchars($tipo); ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php endif; ?>