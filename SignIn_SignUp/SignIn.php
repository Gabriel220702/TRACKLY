<?php
session_start();
date_default_timezone_set('America/Mexico_City');

if (isset($_SESSION['Email_Session'])) {
    header("Location: ../usuario.php");
    die();
}

include('config.php');

$msg = "";
$intentos_fallidos = 0; // Inicializamos
$bloqueado_hasta = null; // Inicializamos

// Verificar si el usuario está bloqueado
if (isset($_POST['submit'])) {
    $email = trim(mysqli_real_escape_string($conx, $_POST['email']));
    $ip_usuario = $_SERVER['REMOTE_ADDR'];
    $max_intentos = 5;
    $bloqueo_tiempo = 15 * 60; // 15 minutos en segundos

    $stmt_intentos = $conx->prepare("SELECT * FROM intentos_login WHERE correo = ? AND ip_usuario = ?");
    $stmt_intentos->bind_param("ss", $email, $ip_usuario);
    $stmt_intentos->execute();
    $result_intentos = $stmt_intentos->get_result();

    if ($result_intentos->num_rows > 0) {
        $intentos = $result_intentos->fetch_assoc();
        $intentos_fallidos = $intentos['intentos_fallidos'];
        $ultimo_intento = strtotime($intentos['ultimo_intento']);
        $bloqueado_hasta = $intentos['bloqueado_hasta'];

        // Verificar si el usuario está bloqueado
        if ($bloqueado_hasta && (strtotime($bloqueado_hasta) > time())) {
            $msg = "<div class='alert alert-danger'>Demasiados intentos fallidos. Inténtalo de nuevo en 15 minutos.</div>";
        } elseif ($intentos_fallidos >= $max_intentos) {
            // Bloquear el acceso
            $bloqueado_hasta = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            $stmt_update = $conx->prepare("UPDATE intentos_login SET bloqueado_hasta = ? WHERE correo = ? AND ip_usuario = ?");
            $stmt_update->bind_param("sss", $bloqueado_hasta, $email, $ip_usuario);
            $stmt_update->execute();
            $msg = "<div class='alert alert-danger'>Demasiados intentos fallidos. Inténtalo de nuevo en 15 minutos.</div>";
        }
    }
}

// Manejo del formulario de inicio de sesión
// Solo procedemos si no hay un mensaje de bloqueo
if (isset($_POST['submit']) && $msg === "") {
    $email = trim(mysqli_real_escape_string($conx, $_POST['email']));
    $password = trim($_POST['Password']);
    $ip_usuario = $_SERVER['REMOTE_ADDR']; // Necesitamos la IP de nuevo
    $max_intentos = 5; // Y los intentos de nuevo

    // Validar los datos de entrada
    if (empty($email) || empty($password)) {
        $msg = "<div class='alert alert-danger'>Todos los campos son obligatorios. Por favor, completa todos los campos.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<div class='alert alert-danger'>El correo electrónico proporcionado no es válido. Por favor, ingresa una dirección de correo válida.</div>";
    } else {
        // Buscar el usuario en la base de datos
        $sql = "SELECT * FROM register WHERE email=?";
        $stmt = $conx->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resulte = $stmt->get_result();
        
        // --- INICIO DE LA CORRECCIÓN ---
        // Se eliminó el bloque "if ($resulte->num_rows === 0)" que intentaba registrar un usuario aquí.
        // La lógica correcta continúa abajo.
        // --- FIN DE LA CORRECCIÓN ---

        if ($resulte->num_rows === 1) {
            $user = $resulte->fetch_assoc();

            // Verificar la contraseña
            if (password_verify($password, $user['Password'])) {
                // Verificar si el usuario está bloqueado (redundante, pero es un doble check)
                if ($bloqueado_hasta && (strtotime($bloqueado_hasta) > time())) {
                    $msg = "<div class='alert alert-danger'>Demasiados intentos fallidos. Inténtalo de nuevo en 15 minutos.</div>";
                } else {
                    // Solo permitir el acceso si el número de intentos fallidos es menor al máximo permitido
                    if ($intentos_fallidos < $max_intentos) {
                        $_SESSION['Email_Session'] = $email;
                        $_SESSION['Username_Session'] = $user['Username'];
                        // Resetear intentos fallidos - ¡Mejor es borrar la fila!
                        $stmt_reset_intentos = $conx->prepare("DELETE FROM intentos_login WHERE correo = ? AND ip_usuario = ?");
                        $stmt_reset_intentos->bind_param("ss", $email, $ip_usuario);
                        $stmt_reset_intentos->execute();
                        header("Location: ../usuario.php");
                        die(); // Importante salir después de redirigir
                    } else {
                        $msg = "<div class='alert alert-danger'>Has excedido el número máximo de intentos. Inténtalo más tarde.</div>";
                    }
                }
            } else {
                // Contraseña incorrecta - Manejo de intentos fallidos
                if ($result_intentos->num_rows === 0) {
                    // Si no hay un registro en intentos_login, insertarlo
                    $stmt_insert_intentos = $conx->prepare("INSERT INTO intentos_login (correo, ip_usuario, intentos_fallidos, ultimo_intento) VALUES (?, ?, 1, NOW())");
                    $stmt_insert_intentos->bind_param("ss", $email, $ip_usuario);
                    $stmt_insert_intentos->execute();
                } else {
                    // Si ya existe un registro, actualizar los intentos fallidos
                    $stmt_update_intentos = $conx->prepare("UPDATE intentos_login SET intentos_fallidos = intentos_fallidos + 1, ultimo_intento = NOW() WHERE correo = ? AND ip_usuario = ?");
                    $stmt_update_intentos->bind_param("ss", $email, $ip_usuario);
                    $stmt_update_intentos->execute();
                }
                $msg = "<div class='alert alert-danger'>Correo o contraseña incorrectos.</div>";
            }
        } else {
            // Usuario no encontrado - Manejo de intentos fallidos
            if ($result_intentos->num_rows === 0) {
                // Si no hay un registro en intentos_login, insertarlo
                $stmt_insert_intentos = $conx->prepare("INSERT INTO intentos_login (correo, ip_usuario, intentos_fallidos, ultimo_intento) VALUES (?, ?, 1, NOW())");
                $stmt_insert_intentos->bind_param("ss", $email, $ip_usuario);
                $stmt_insert_intentos->execute();
            } else {
                // Si ya existe un registro, actualizar los intentos fallidos
                $stmt_update_intentos = $conx->prepare("UPDATE intentos_login SET intentos_fallidos = intentos_fallidos + 1, ultimo_intento = NOW() WHERE correo = ? AND ip_usuario = ?");
                $stmt_update_intentos->bind_param("ss", $email, $ip_usuario);
                $stmt_update_intentos->execute();
            }
            $msg = "<div class='alert alert-danger'>Correo o contraseña incorrectos.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="../img/TRACKLY favicon.ico" type="image/png">
    <title>Iniciar sesión</title>
    <style>
        .alert {
            padding: 1rem;
            border-radius: 5px;
            color: white;
            margin: 1rem 0;
            font-weight: 500;
            width: 65%;
        }
        .alert-success { background-color: #42ba96; }
        .alert-danger { background-color: #fc5555; }
        .alert-info { background-color: #2E9AFE; }
        .alert-warning { background-color: #ff9966; }
        .Forget-Pass { display: flex; width: 65%; }
        .Forget { color: #2E9AFE; font-weight: 500; text-decoration: none; margin-left: auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="" method="POST" class="sign-in-form">
                    <h2 class="title">Iniciar sesión</h2>
                    <?php echo $msg ?>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="email" placeholder="Correo" value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="Password" placeholder="Contraseña" required />
                    </div>
                    <input type="submit" name="submit" value="Iniciar sesión" class="btn solid" />
                    <p class="social-text">Regresa a nuestra página principal</p>
                    <div class="social-media">
                    <a href="../index.php" class="social-icon">
                    <i class="fas fa-arrow-left"></i>
                    </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>¿Aún no eres parte de TRACKLY?</h3>
                    <p>Registrate y obtén los beneficios que tenemos para ti!</p>
                    <a href="SignUp.php" class="btn transparent" id="sign-in-btn" style="padding:10px 20px;text-decoration:none">Registrarse</a>
                </div>
                <img src="img/Logo Blanco.png" class="image" alt="" />
            </div>
        </div>
    </div>

    <script src="app.js"></script>
</body>
</html>