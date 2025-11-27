<?php
session_start();
include 'conexion_be.php';

// Establecer la zona horaria
date_default_timezone_set('America/Mexico_City'); // Ajusta esto según tu zona horaria

$correo = trim($_POST['correo']);
$contrasena = trim($_POST['contrasena']);

// Verificar si todos los campos están completos
if (empty($correo) || empty($contrasena)) {
    echo '
        <script>
            alert("Por favor, complete todos los campos.");
            window.location = "../formulario.php";
        </script>
    ';
    exit();
}

// Preparar y ejecutar consulta para obtener la contraseña encriptada y el nombre del usuario
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $contrasena_hash = $usuario['contrasena'];
    $nombre_completo = $usuario['nombre_completo'];

    // Verificar intentos fallidos
    $ip_usuario = $_SERVER['REMOTE_ADDR'];
    $max_intentos = 5;
    $bloqueo_tiempo = 15 * 60; // 15 minutos en segundos

    $stmt_intentos = $conexion->prepare("SELECT * FROM intentos_login WHERE correo = ? AND ip_usuario = ?");
    $stmt_intentos->bind_param("ss", $correo, $ip_usuario);
    $stmt_intentos->execute();
    $result_intentos = $stmt_intentos->get_result();
    
    $intentos_fallidos = 0;
    $ultimo_intento = null;
    $bloqueado_hasta = null;

    if ($result_intentos->num_rows > 0) {
        $intentos = $result_intentos->fetch_assoc();
        $intentos_fallidos = $intentos['intentos_fallidos'];
        $ultimo_intento = strtotime($intentos['ultimo_intento']);
        $bloqueado_hasta = $intentos['bloqueado_hasta'];

        // Verificar si el usuario está bloqueado
        if ($bloqueado_hasta && (strtotime($bloqueado_hasta) > time())) {
            echo '
                <script>
                    alert("Demasiados intentos fallidos. Inténtalo de nuevo en 15 minutos.");
                    window.location = "../formulario.php";
                </script>
            ';
            exit();
        }

        if ($intentos_fallidos >= $max_intentos) {
            if ((time() - $ultimo_intento) < $bloqueo_tiempo) {
                $bloqueado_hasta = date('Y-m-d H:i:s', time() + $bloqueo_tiempo);
                $stmt_update = $conexion->prepare("UPDATE intentos_login SET bloqueado_hasta = ? WHERE correo = ? AND ip_usuario = ?");
                $stmt_update->bind_param("sss", $bloqueado_hasta, $correo, $ip_usuario);
                $stmt_update->execute();
                echo '
                    <script>
                        alert("Demasiados intentos fallidos. Inténtalo de nuevo en 15 minutos.");
                        window.location = "../formulario.php";
                    </script>
                ';
                exit();
            } else {
                // Si el tiempo de bloqueo ha pasado, restablecer intentos fallidos
                $stmt_reset = $conexion->prepare("UPDATE intentos_login SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE correo = ? AND ip_usuario = ?");
                $stmt_reset->bind_param("ss", $correo, $ip_usuario);
                $stmt_reset->execute();
                $intentos_fallidos = 0;  // Restablecer el contador de intentos fallidos
            }
        }
    }

    // Verificar la contraseña
    if (password_verify($contrasena, $contrasena_hash)) {
        // Solo permitir el acceso si el número de intentos fallidos es menor al máximo permitido
        if ($intentos_fallidos < $max_intentos) {
            $_SESSION['usuario'] = $correo;
            $_SESSION['nombre_completo'] = $nombre_completo;

            // Resetear intentos fallidos
            $stmt_reset_intentos = $conexion->prepare("UPDATE intentos_login SET intentos_fallidos = 0 WHERE correo = ? AND ip_usuario = ?");
            $stmt_reset_intentos->bind_param("ss", $correo, $ip_usuario);
            $stmt_reset_intentos->execute();

            header("Location: ../usuario.php");
            exit();
        } else {
            echo '
                <script>
                    alert("Has excedido el número máximo de intentos. Inténtalo más tarde.");
                    window.location = "../formulario.php";
                </script>
            ';
            exit();
        }
    } else {
        // Manejo de intentos fallidos
        $intentos_fallidos++;
        $stmt_update_intentos = $conexion->prepare("INSERT INTO intentos_login (correo, ip_usuario, intentos_fallidos, ultimo_intento) 
                                                    VALUES (?, ?, ?, NOW()) 
                                                    ON DUPLICATE KEY UPDATE intentos_fallidos = VALUES(intentos_fallidos), ultimo_intento = NOW(), bloqueado_hasta = NULL");
        $stmt_update_intentos->bind_param("ssi", $correo, $ip_usuario, $intentos_fallidos);
        $stmt_update_intentos->execute();

        echo '
            <script>
                alert("Correo o contraseña incorrectos.");
                window.location = "../formulario.php";
            </script>
        ';
        exit();
    }
} else {
    echo '
        <script>
            alert("Correo no registrado.");
            window.location = "../formulario.php";
        </script>
    ';
    exit();
}

$stmt->close();
$conexion->close();
?>
