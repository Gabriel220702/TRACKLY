<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra nuestro producto</title>
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
            margin: 0; /* Eliminar margen predeterminado del body */
        }

        /* Navigation */
        #menu {
            padding: 0.5px 0;
            background-color: #323435; /* Color azul cielo */
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
            color: #e02525ff;
        }
        .navbar-toggler {
            border-radius: 0;
        }
        .navbar-toggler:hover, .navbar-toggler:focus {
            background-color: #ff0000ff;
            border-color: #323435;
        }
        .navbar-toggler:hover > .icon-bar {
            background-color: #FFF;
        }

        /* Main content */
        main {
            padding: 20px; /* Agrega un relleno de 20px alrededor del contenido principal */
            border-radius: 8px; /* Agrega un borde redondeado al contenido principal */
            margin: 20px; /* Agrega un margen de 20px alrededor del contenido principal */
            font-size: 16px; /* Establece el tamaño de letra del contenido principal */
        }

        /* Default background color for larger screens */
        @media (min-width: 768px) {
            main {
                background-color: rgba(255, 255, 255, 0.0); /* Fondo blanco y ligeramente translúcido en pantallas grandes */
            }
        }

/* Default background color for larger screens */
@media (min-width: 768px) {
    main {
        background-color: rgba(255, 255, 255, 0.0); /* Fondo blanco y ligeramente translúcido en pantallas grandes */
    }
}

/* Media Queries para dispositivos móviles */
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
        background-color: #87CEEB; /* Mantener el mismo color azul cielo en dispositivos móviles */
        text-align: center; /* Centrar el contenido dentro del menú */
        padding: 0; /* Eliminar padding si es necesario */
    }

    #menu .navbar-brand {
        color: #ffffff; /* Mantener el color del texto original */
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
        color: #ff86d7; /* Color rosa claro para el estado hover en dispositivos móviles */
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
<link rel="icon" href="img/TRACKLY favicon.ico" type="image/png">
<!-- Otros metadatos y enlaces -->
<body>
    <!-- Navigation -->
    <nav id="menu" class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/Logo TRACKLY Transparente.png" alt="Logo de TRACKLY">
            </a>
            <span class="welcome-message">Comprar producto</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="usuario.php">Usuario</a>
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
    
    <main>
        <h1>Página en desarrollo...</h1>
    </main>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
