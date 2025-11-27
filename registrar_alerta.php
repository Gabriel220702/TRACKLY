<?php
// --- registrar_alerta.php ---
// Este archivo recibe una ALERTA de la ESP32 y la GUARDA (INSERT) en la base de datos.

include 'SignIn_SignUp/config.php'; // Nos conectamos a la BD

// 1. Verificamos que nos envíen todos los datos
if (isset($_REQUEST['lat']) && isset($_REQUEST['lon']) && 
    isset($_REQUEST['id_esp']) && isset($_REQUEST['tipo_alerta'])) {

    // 2. Recibimos los datos
    $lat = $_REQUEST['lat'];
    $lon = $_REQUEST['lon'];
    $id_esp = $_REQUEST['id_esp']; // Ej: "TRACKLY_01"
    $tipo_alerta = $_REQUEST['tipo_alerta']; // Ej: "PANICO" o "GEOCERCA_SALIDA"

    // --- Paso Importante: Traducir "TRACKLY_01" a su ID numérico (ej. 1) ---
    // La tabla 'alertas' se conecta con el 'dispositivo_id' (que es un número)
    // No podemos guardar "TRACKLY_01" ahí.
    
    $dispositivo_id = null;

    $sql_find_id = "SELECT id FROM dispositivos WHERE identificador_esp = ?";
    $stmt_find = $conx->prepare($sql_find_id);
    $stmt_find->bind_param("s", $id_esp);
    
    if ($stmt_find->execute()) {
        $resultado = $stmt_find->get_result();
        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $dispositivo_id = $fila['id']; // ¡Encontramos el ID! (ej. 1)
        }
    }
    $stmt_find->close();

    // ----------------------------------------------------------------------

    // 3. Si encontramos el ID del dispositivo, procedemos a guardar la alerta
    if ($dispositivo_id !== null) {

        // 4. Preparamos la consulta SQL de INSERCIÓN
        $sql_insert = "INSERT INTO alertas (dispositivo_id, tipo_alerta, lat_alerta, lon_alerta) VALUES (?, ?, ?, ?)";
        
        $stmt_insert = $conx->prepare($sql_insert);
        
        // Vinculamos los parámetros:
        // i = integer (para $dispositivo_id)
        // s = string (para $tipo_alerta)
        // d = decimal (para $lat)
        // d = decimal (para $lon)
        $stmt_insert->bind_param("isdd", $dispositivo_id, $tipo_alerta, $lat, $lon);

        // 5. Ejecutamos la inserción
        if ($stmt_insert->execute()) {
            
            // ¡ÉXITO!
            echo "OK - Alerta Registrada";

            // ------------------------------------------------------------------
            // ¡¡AQUÍ ES DONDE LLAMARÍAS A LA API DE WHATSAPP!!
            // ------------------------------------------------------------------
            // $mensaje = "¡ALERTA DE PÁNICO! Ubicación: http://maps.google.com/maps?q=$lat,$lon";
            // tu_funcion_para_enviar_whatsapp($mensaje);
            // ------------------------------------------------------------------

        } else {
            echo "Error al registrar la alerta: " . $stmt_insert->error;
        }
        
        $stmt_insert->close();

    } else {
        echo "Error: Dispositivo con id_esp '$id_esp' no encontrado.";
    }

} else {
    // Si alguien abre este archivo sin enviar los datos correctos
    echo "Error: Faltan datos (lat, lon, id_esp, tipo_alerta).";
}

// 6. Cerramos la conexión a la base de datos
$conx->close();

?>