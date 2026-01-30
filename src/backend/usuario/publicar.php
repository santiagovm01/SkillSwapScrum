<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (
  empty($data["titulo"]) ||
  empty($data["descripcion"])
) {
  echo json_encode([
    "ok" => false,
    "mensaje" => "Datos incompletos"
  ]);
  exit;
}

// aquí normalmente insertarías en la BD
// INSERT INTO ...

echo json_encode([
  "ok" => true
]);
