<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo '
        <script>
            alert("Por favor debes iniciar sesión");
            window.location = "formulario.php";
        </script>
    ';
    session_destroy();
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario - Amoxtli-Jap</title>
    <!-- Incluir CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
/* Background */
body {
    font-family: 'Open Sans', sans-serif;
    background-image: url('img/Amoxtli_fondo_difuminado_2.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;
}

/* Navigation */
#menu {
    padding: 0.5px 0;
    background-color: rgb(255, 59, 190);
    border-color: rgba(231, 231, 231, 0);
}

#menu .navbar-header {
    display: flex;
    align-items: center;
}

#menu .navbar-brand {
    display: flex;
    align-items: center;
    font-size: 22px;
    color: #ffffff;
    font-weight: 700;
    text-decoration: none;
}

#menu .navbar-brand img {
    width: 65px;
    height: auto;
    margin-right: 5px;
}

#menu .welcome-message {
    font-size: 22px;
    color: #ffffff;
    margin-left: 10px;
}

#menu .navbar-nav > li > a {
    text-transform: uppercase;
    color: #ffffff;
    font-size: 13px;
    letter-spacing: 1px;
}

#menu .navbar-nav > li > a:hover {
    color: #2541e0;
}

.navbar-toggler {
    border-radius: 0;
}

.navbar-toggler:hover, .navbar-toggler:focus {
    background-color: #2541e0;
    border-color: #bd46b3;
}

.navbar-toggler:hover > .icon-bar {
    background-color: #FFF;
}

/* Description Section */
.description-section {
    display: flex;
    flex-wrap: wrap; /* Permitir que los elementos se ajusten en pantallas pequeñas */
    justify-content: center; /* Centrar elementos horizontalmente */
    align-items: flex-start; /* Alinear elementos al inicio del contenedor */
    margin-top: 40px;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0);
    border-radius: 10px;
}

.description-section img {
    max-width: 40%; /* Ajustar la imagen al 40% del contenedor */
    height: auto;
    margin: 50px 0; /* Añadir márgenes verticales para equilibrar el espacio */
}

.description-section .description {
    flex: 1;
    margin: 19px;
    min-width: 250px; /* Asegurar un ancho mínimo para mantener la estructura en pantallas grandes */
}

.description-section .description h3 {
    color: #bd46b3;
    font-size: 15.5px;
}

.description-section .description p {
    font-size: 12.5px;
    color: #333;
}

/* Media Queries */
@media (min-width: 768px) {
    .description-section {
        flex-direction: row; /* Cambiar a fila en pantallas grandes */
        justify-content: space-between; /* Espacio entre los elementos */
    }

    .description-section img {
        max-width: 40%; /* Ajustar el tamaño de la imagen en pantallas grandes */
    }
}

/* Media Queries */
@media (max-width: 767px) {
    body {
        background-color: #ffffff !important; /* Fondo blanco en dispositivos móviles */
        background-image: none; /* Eliminar imagen de fondo en dispositivos móviles */
    }

    .description-section {
        flex-direction: column; /* Cambiar a columna en pantallas pequeñas */
        text-align: center; /* Centrar el contenido de la sección de descripción */
    }

    .description-section img {
        max-width: 100%; /* Ajustar la imagen al 100% en pantallas pequeñas */
        margin: 20px 0; /* Añadir márgenes verticales en pantallas pequeñas */
    }

    #menu {
        background-color: rgb(255, 59, 190); /* Mantener color rosa original */
        text-align: center; /* Centrar el contenido dentro del menú */
        padding: 0; /* Eliminar padding si es necesario */
    }

    #menu .navbar-brand {
        color: #ffffff; /* Mantener color del texto original */
        font-size: 20px; /* Tamaño del título en dispositivos móviles */
        display: inline-block; /* Asegura que la marca se alinee en línea */
        margin: 0; /* Eliminar márgenes para centrarlo mejor */
    }

    #menu .welcome-message {
        font-size: 20px; /* Tamaño del título en dispositivos móviles */
        display: block; /* Asegura que el título se comporte como bloque */
        margin: 0 auto; /* Centrar el título horizontalmente */
        text-align: center; /* Centrar el texto del título */
    }

    #menu .navbar-nav {
        display: block; /* Mostrar los elementos de navegación en columna */
        margin: 0; /* Eliminar márgenes para centrarlos */
        text-align: center; /* Alinear los enlaces al centro dentro del contenedor */
    }

    #menu .navbar-nav > li {
        display: block; /* Mostrar los elementos de la lista en columna */
    }

    #menu .navbar-nav > li > a {
        color: #ffffff; /* Mantener color de los enlaces original */
        margin: 10px 0; /* Ajustar márgenes para espaciado vertical */
        display: block; /* Asegura que los enlaces se comporten como bloques */
        padding: 5px; /* Ajustar el padding para un mejor espaciado */
    }

    #menu .navbar-nav > li > a:hover {
    color: #2541e0; /* Color azul para el estado hover */
    }
}

/* Footer */
#footer {
	background-color: #222222;
	color: #777;
	padding: 15px 0 10px 0;
}
#footer p {
	font-size: 13px;
	margin-top: 10px;
}
#footer a {
	color: #aaa;
}
#footer a:hover, #footer a:focus {
	text-decoration: none;
	color: #bd46b3;
}


    </style>
</head>
<body>
    <!-- Navigation -->
    <nav id="menu" class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" alt="Logo de Amoxtli-Jap">
            </a>
            <span class="welcome-message">Hola, <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>!</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="panel_de_control.php">Panel de Control</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="comprar.php">Comprar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="php/cerrar_sesion.php">Cerrar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="politicas_de_privacidad.php">Política de Privacidad</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Description Section -->
    <div class="container description-section">
        <div class="description">
            <h3>Módulo WiFi (ESP32)</h3>
            <p>Nuestro cinturón Amoxtli-Jap cuenta con un módulo WiFi para otorgar conectividad entre el cinturón y nuestra base de datos, así como a la página web y WhatsApp.</p>
            <h3>Sensor Detector de Caídas (MPU6050)</h3>
            <p>Nuestro cinturón Amoxtli-Jap cuenta con un sensor capaz de detectar caídas con el uso de su tecnología basada en acelerómetros y giroscópios con ejes coordenados.</p>
            <h3>Módulo GPS (NEO-6M)</h3>
            <p>Nuestro cinturón Amoxtli-Jap cuenta un módulo GPS para otorgar una conectividad satelital y recopilar las coordenadas para saber la ubicación del incidente.</p>
        </div>
        <img src="img/Descripcion.jpg" alt="Cinturón con Sensores">
        <div class="description">
            <h3>Registro de Usuarios en Página Web</h3>
            <p>Amoxtli-Jap cuenta una página web para navegar a través de ella y obtener información sobre nuestro producto, además puedes registrarte para volverte usuario y acceder a más información sobre tu cinturón y la persona portadora de este.</p>
            <h3>Material Ergonómico</h3>
            <p>Amoxtli-Jap cuenta con un proceso de fabricación de alta calidad, ofreciendo a los usuarios un producto con material ergonómico, volviendolo llamativo, cómodo, y totalmente funcional para asistir a la movilidad de nuestros adultos mayores mediante sus puntos de apoyo.</p>
        </div>
    </div>
    <div id="footer">
  <div class="container">
    <p>&copy; 2024 Amoxtli-Jap.</p>
  </div>
</div>
    <!-- Incluir JavaScript de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
