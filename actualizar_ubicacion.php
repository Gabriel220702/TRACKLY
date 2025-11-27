<?php
// --- actualizar_ubicacion.php ---
// Este archivo recibe datos de la ESP32 y ACTUALIZA la última ubicación.

// 1. Incluimos nuestro archivo de conexión
include 'SignIn_SignUp/config.php';// Esto nos da acceso a la variable $conn

// 2. Verificamos que la ESP32 nos esté enviando los datos que esperamos.
// Usamos $_REQUEST para que acepte datos por POST (de la ESP32) o GET (para pruebas)
if (isset($_REQUEST['lat']) && isset($_REQUEST['lon']) && isset($_REQUEST['id_esp'])) {

    // 3. Recibimos los datos
    $lat = $_REQUEST['lat'];
    $lon = $_REQUEST['lon'];
    $id_esp = $_REQUEST['id_esp']; // Ej: "TRACKLY_01"

    // 4. Preparamos la consulta SQL (¡La forma MÁS SEGURA de hacerlo!)
    // Esto previene ataques de "Inyección SQL".
    // "Actualiza la tabla 'dispositivos' cambiando 'ultima_lat' y 'ultima_lon'
    // DONDE el 'identificador_esp' sea el que nos enviaron."
    
    $sql = "UPDATE dispositivos SET ultima_lat = ?, ultima_lon = ? WHERE identificador_esp = ?";
    
    // 5. Preparamos el "statement"
    $stmt = $conx->prepare($sql);

    // 6. Vinculamos las variables a los '?'
    // "dds" significa que las variables son:
    // d = decimal (para $lat)
    // d = decimal (para $lon)
    // s = string (para $id_esp)
    $stmt->bind_param("dds", $lat, $lon, $id_esp);

    // 7. Ejecutamos la consulta
    if ($stmt->execute()) {
        // Si todo salió bien, le respondemos "OK" a la ESP32
        echo "OK"; 
    } else {
        // Si algo falló
        echo "Error al actualizar: " . $stmt->error;
    }

    // 8. Cerramos el statement
    $stmt->close();

} else {
    // Si alguien abre este archivo sin enviar los datos correctos
    echo "Error: Faltan datos (lat, lon, id_esp).";
}

// 9. Cerramos la conexión a la base de datos
$conx->close();

?>