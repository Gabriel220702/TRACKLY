<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: usuario.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Amoxtli-Jap</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
    <link rel="icon" href="img/logo_favicon.png" type="image/png">
    <!-- Otros metadatos y enlaces -->
<body>
    <!-- Navigation -->
    <nav id="menu" class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" alt="Logo de Amoxtli-Jap">
            </a>
            <span class="welcome-message">Usuario</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="politicas_de_privacidad.php" class="nav-link">Política de Privacidad</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <!-- Login and Register Section -->
        <section id="login-register-section" class="text-center">
            <div class="container">
                <div class="contenedor__todo">
                    <div class="caja__trasera">
                        <div class="caja__trasera-login">
                            <h3>¿Ya tienes una cuenta?</h3>
                            <p>Inicia sesión para entrar en la página</p>
                            <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                        </div>
                        <div class="caja__trasera-register">
                            <h3>¿Aún no tienes una cuenta?</h3>
                            <p>Regístrate para que puedas iniciar sesión</p>
                            <button id="btn__registrarse">Registrarse</button>
                        </div>
                    </div>
                    <!-- Formulario de Login y registro -->
                    <div class="contenedor__login-register">
                        <!-- Login -->
                        <!-- Formulario de Login -->
                        <form id="login-form" action="php/login_usuario_be.php" method="POST" class="formulario__login">
                            <h2>Iniciar Sesión</h2>
                            <input type="text" placeholder="Correo Electrónico" name="correo" required>
                            <input type="password" placeholder="Contraseña" name="contrasena" required>
                            <button type="submit">Entrar</button>
                            <!-- Contenedor para mostrar mensajes de error -->
                            <div id="mensaje-bloqueo" style="display: none;">Estás bloqueado por muchos intentos fallidos</div>
                        </form>
                        <!-- Register -->
                        <form action="php/registro_usuario_be.php" method="POST" class="formulario__register">
                            <h2>Registrarse</h2>
                            <input type="text" placeholder="Nombre completo" name="nombre_completo" required>
                            <input type="text" placeholder="Correo Electrónico" name="correo" required>
                            <input type="text" placeholder="Usuario" name="usuario" required>
                            <input type="password" placeholder="Contraseña" name="contrasena" required>
                            <button type="submit">Registrarse</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>


