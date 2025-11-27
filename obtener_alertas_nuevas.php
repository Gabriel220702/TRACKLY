<?php
// --- obtener_alertas_nuevas.php ---
// Consulta la BD y devuelve las alertas NO LEÍDAS en formato JSON.

session_start();
// Asegúrate de que la ruta sea la correcta (la corregimos antes)
include 'SignIn_SignUp/config.php'; // Nos da $conx

// 1. Buscamos todas las alertas que no estén marcadas como leídas
// (leida_web = FALSE, que es lo mismo que leida_web = 0)
$sql = "SELECT id, tipo_alerta, lat_alerta, lon_alerta, fecha_hora 
        FROM alertas 
        WHERE leida_web = FALSE 
        ORDER BY fecha_hora DESC";

$stmt = $conx->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();

$alertas = []; // Un array para guardar todas las alertas

if ($resultado->num_rows > 0) {
    // Recorremos todos los resultados y los guardamos en el array
    while($fila = $resultado->fetch_assoc()) {
        $alertas[] = $fila;
    }
}

$stmt->close();
$conx->close();

// 2. Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($alertas);

?>