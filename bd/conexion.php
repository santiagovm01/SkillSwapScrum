<?php
$host = "localhost";
$user = "root"; // o el usuario que estés usando
$pass = "";     // o la contraseña que hayas definido
$dbname = "skillswap";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
