<?php

include 'conexion_be.php';

// Establecer la codificación de caracteres
$conexion->set_charset("utf8mb4");

// Recoger datos del formulario
$nombre_completo = trim($_POST['nombre_completo']);
$correo = trim($_POST['correo']);
$usuario = trim($_POST['usuario']);
$contrasena = trim($_POST['contrasena']);

// Validar los datos de entrada para evitar caracteres peligrosos
if (!preg_match('/^[a-zA-Z\sáéíóúüñÁÉÍÓÚÜÑ]+$/u', $nombre_completo)) {
    echo '
        <script>
            alert("El nombre completo solo debe contener letras, espacios y caracteres acentuados.");
            window.location = "../formulario.php";
        </script>
    ';
    exit();
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $usuario)) {
    echo '
        <script>
            alert("El nombre de usuario solo debe contener letras, números y guiones bajos.");
            window.location = "../formulario.php";
        </script>
    ';
    exit();
}

// Verificar si todos los campos están completos
if (empty($nombre_completo) || empty($correo) || empty($usuario) || empty($contrasena)) {
    echo '
        <script>
            alert("Todos los campos son obligatorios. Por favor, complete todos los campos.");
            window.location = "../formulario.php";
        </script>
    ';
    exit();
}

// Validar formato del correo electrónico
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo '
        <script>
            alert("El correo electrónico proporcionado no es válido. Por favor, ingresa un correo electrónico válido.");
            window.location = "../formulario.php";
        </script>
    ';
    exit();
}

// Encriptamiento de la contraseña
$contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

// Preparar y ejecutar consulta para verificar si el correo ya está registrado
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '
        <script>
            alert("Este correo ya está registrado, intenta con otro diferente.");
            window.location = "../formulario.php";
        </script>
    ';
    $stmt->close();
    exit();  
}

// Preparar y ejecutar consulta para verificar si el usuario ya está registrado
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '
        <script>
            alert("Este usuario ya está registrado, intenta con otro diferente.");
            window.location = "../formulario.php";
        </script>
    ';
    $stmt->close();
    exit();
}

// Preparar e insertar nuevo usuario
$stmt = $conexion->prepare("INSERT INTO usuarios (nombre_completo, correo, usuario, contrasena) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre_completo, $correo, $usuario, $contrasena_hash);

if ($stmt->execute()) {
    echo '
        <script>
            alert("Usuario almacenado correctamente.");
            window.location = "../formulario.php";
        </script>
    ';
} else {
    echo '
        <script>
            alert("Inténtalo de nuevo, usuario no almacenado.");
            window.location = "../formulario.php";
        </script>
    ';
}

$stmt->close();
$conexion->close();
?>
