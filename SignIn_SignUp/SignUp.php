<?php
session_start();
if (isset($_SESSION['Email_Session'])) {
    header("Location: Signin.php");
    die();
}
include('config.php');

// Establecer la codificación de caracteres
$conx->set_charset("utf8mb4");

// Mensajes de respuesta
$msg = "";
$Error_Pass = "";

// Manejo del formulario de registro
if (isset($_POST['submit'])) {
    // Recoger datos del formulario
    $name = trim(mysqli_real_escape_string($conx, $_POST['Username']));
    $email = trim(mysqli_real_escape_string($conx, $_POST['Email']));
    $password = trim($_POST['Password']);
    $confirm_password = trim($_POST['Conf-Password']);

    // Validar los datos de entrada
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $msg = "<div class='alert alert-danger'>Todos los campos son necesarios. Porfavor rellena todos los campos.</div>";
    } elseif (!preg_match('/^[a-zA-Z0-9\s_]+$/', $name)) {
        $msg = "<div class='alert alert-danger'>El nombre de usuario solamente puede tener letras, números y guión bajo.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<div class='alert alert-danger'>El correo ingresado no es válido, porfavor ingresa un correo válido.</div>";
    } elseif ($password !== $confirm_password) {
        $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match.</div>";
        $Error_Pass = 'style="border:1px solid red;box-shadow:0px 1px 11px 0px red"';
    } else {
        // Verificar si el correo ya está registrado
        $stmt = $conx->prepare("SELECT * FROM register WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $msg = "<div class='alert alert-danger'>Este correo ya está registrado, Porfavor ingresa un correo electrónico diferente.</div>";
        } else {
            // Verificar si el nombre de usuario ya está registrado
            $stmt = $conx->prepare("SELECT * FROM register WHERE Username = ?");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $msg = "<div class='alert alert-danger'>Este nombre de usuario ya está registrado. Porfavor escoje uno diferente.</div>";
            } else {
                
                // --- INICIO DE LA CORRECCIÓN ---
                
                // Insertar nuevo usuario
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $verification_code = ""; // Valor vacío por defecto para CodeV
                $verification_status = 1;  // 1 = verificado por defecto

                // ¡Corregido! -> Añadimos CodeV y verification
                $stmt = $conx->prepare("INSERT INTO register (Username, email, Password, CodeV, verification) VALUES (?, ?, ?, ?, ?)");
                
                // ¡Corregido! -> "ssssi" (string, string, string, string, integer)
                $stmt->bind_param("ssssi", $name, $email, $password_hash, $verification_code, $verification_status);

                if ($stmt->execute()) {
                    $msg = "<div class='alert alert-info'>Registration successful. Ahora puedes iniciar sesión.</div>";
                } else {
                    $msg = "<div class='alert alert-danger'>Algo salió mal. Porfavor inténtalo de nuevo.</div>";
                }
                
                // --- FIN DE LA CORRECCIÓN ---
            }
        }
        $stmt->close();
    }
}
// $conx->close(); // Opcional: Puedes dejarla abierta si la necesitas en el HTML, pero es buena práctica cerrarla aquí si ya no se usa.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="../img/TRACKLY favicon.ico" type="image/png">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css" />
    <title>Registrarse</title>
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
        .input-field input[style] { border: 1px solid red; box-shadow: 0px 1px 11px 0px red; }
    </style>
</head>
<body>
    <div class="container sign-up-mode">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="" method="POST" class="sign-up-form">
                    <h2 class="title">Registrarse</h2>
                    <?php echo $msg ?>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="Username" placeholder="Nombre de Usuario" value="<?php if (isset($_POST['Username'])) { echo htmlspecialchars($name); } ?>" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="Email" placeholder="Correo Electrónico" value="<?php if (isset($_POST['Email'])) { echo htmlspecialchars($email); } ?>" />
                    </div>
                    <div class="input-field" <?php echo $Error_Pass ?>>
                        <i class="fas fa-lock"></i>
                        <input type="password" name="Password" placeholder="Contraseña" />
                    </div>
                    <div class="input-field" <?php echo $Error_Pass ?>>
                        <i class="fas fa-lock"></i>
                        <input type="password" name="Conf-Password" placeholder="Confirmar Contraseña" />
                    </div>
                    <input type="submit" name="submit" class="btn" value="Registrarse" />
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
            <div class="panel left-panel"></div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>¿Ya eres parte de TRACKLY?</h3>
                    <p>Inicia sesión para navegar en tu perfil.</p>
                    <a href="Signin.php" class="btn transparent" id="sign-in-btn" style="padding:10px 20px;text-decoration:none">Iniciar Sesión</a>
                </div>
                <img src="img/Computadora.png" class="image" alt="" />
            </div>
        </div>
    </div>
</body>
</html>