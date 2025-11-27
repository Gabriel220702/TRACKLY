<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion_be.php';

// Obtener los datos enviados por el ESP32
$id_dispositivo = $_POST['id_dispositivo'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$fecha_hora = $_POST['fecha_hora'];
$posicion_caida = $_POST['posicion_caida'];

// Insertar los datos en la tabla 'sensores'
$sql = "INSERT INTO sensores (id_dispositivo, timestamp, posicion, caida_detectada, gps_latitud, gps_longitud) 
        VALUES (?, ?, ?, 1, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("issdd", $id_dispositivo, $fecha_hora, $posicion_caida, $latitud, $longitud);

if ($stmt->execute()) {
    echo "Datos insertados correctamente";
} else {
    echo "Error al insertar datos: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>
