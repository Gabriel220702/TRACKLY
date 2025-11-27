<?php
// --- obtener_ubicacion_actual.php (¡Versión 2.0 Segura!) ---
session_start();
include 'SignIn_SignUp/config.php'; // Nos da $conx

// 1. Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['Email_Session'])) {
    http_response_code(401); // No autorizado
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// 2. Buscar qué dispositivo tiene asignado el usuario de la sesión
$email_usuario = $_SESSION['Email_Session'];
$id_esp_a_buscar = null;

$sql_user = "SELECT id_esp_asignado FROM register WHERE email = ? LIMIT 1";
$stmt_user = $conx->prepare($sql_user);
$stmt_user->bind_param("s", $email_usuario);
$stmt_user->execute();
$resultado_user = $stmt_user->get_result();

if ($resultado_user->num_rows > 0) {
    $fila_user = $resultado_user->fetch_assoc();
    $id_esp_a_buscar = $fila_user['id_esp_asignado'];
}
$stmt_user->close();

// 3. Si el usuario tiene un dispositivo, buscar su ubicación
$datos = null;
if ($id_esp_a_buscar) {
    $sql_dev = "SELECT ultima_lat, ultima_lon, ultima_actualizacion FROM dispositivos WHERE identificador_esp = ? LIMIT 1";
    $stmt_dev = $conx->prepare($sql_dev);
    $stmt_dev->bind_param("s", $id_esp_a_buscar);
    $stmt_dev->execute();
    $resultado_dev = $stmt_dev->get_result();

    if ($resultado_dev->num_rows > 0) {
        $datos = $resultado_dev->fetch_assoc();
    }
    $stmt_dev->close();
}

$conx->close();

// 4. Devolver los datos en formato JSON
header('Content-Type: application/json');
if ($datos) {
    echo json_encode($datos);
} else {
    echo json_encode(['error' => 'No se encontraron datos o dispositivo']);
}
?>