<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad</title>
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
    background-color: #323435;/* Color del fondo del menú */
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
    color: #ffffff; /* Color del texto de la marca */
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
    color: #ffffff; /* Color del texto de bienvenida */
    margin-left: 10px;
}

#menu .navbar-nav > li > a {
    text-transform: uppercase;
    color: #ffffff; /* Color del texto de los enlaces */
    font-size: 13px;
    letter-spacing: 1px;
}

#menu .navbar-nav > li > a:hover {
    color: #e02525ff; /* Color del texto de los enlaces al pasar el cursor */
}

.navbar-toggler {
    border-radius: 0;
}

.navbar-toggler:hover, .navbar-toggler:focus {
    background-color: #ff0000ff; /* Color de fondo del botón de alternancia */
    border-color: #323435; /* Color del borde del botón de alternancia */
}

.navbar-toggler:hover > .icon-bar {
    background-color: #FFF; /* Color de las barras del icono del botón de alternancia */
}

.container-politicas{
    padding: 20px;
    margin: 0 auto;
    max width: 800px;
}



/* Media Queries */
@media (max-width: 767px) {
    body {
        background-color: #ffffff; /* Fondo blanco en dispositivos móviles */
        background-image: none; /* Eliminar imagen de fondo en dispositivos móviles */
    }

    main {
        background-color: #ffffff; /* Fondo blanco en dispositivos móviles */
        margin: 0; /* Eliminar margen en dispositivos móviles */
        padding: 15px; /* Reducir el relleno en dispositivos móviles */
        min-height: 100vh; /* Asegura que main cubra toda la altura de la ventana */
        display: flex; /* Utiliza flexbox */
        flex-direction: column; /* Dirección de los elementos flexibles */
    }

    .container {
        width: 100%;
        padding: 0 15px; /* Añadir algo de padding horizontal */
    }

    .table {
        font-size: 12px; /* Ajustar el tamaño de la fuente en pantallas pequeñas */
        background-color: #ffffff; /* Fondo blanco en la tabla para pantallas pequeñas */
        border-radius: 0; /* Eliminar bordes redondeados en la tabla para pantallas pequeñas */
        margin: 0 auto; /* Centrar la tabla horizontalmente */
        padding: 0; /* Eliminar relleno en la tabla para pantallas pequeñas */
        width: 100%; /* Asegura que la tabla ocupe todo el ancho disponible en pantallas pequeñas */
        border-collapse: collapse; /* Eliminar espacios entre celdas */
    }

    /* Ajustar el tamaño de la fuente en el encabezado y celdas de la tabla */
    .table thead th, .table tbody td {
        font-size: 8px; /* Tamaño de fuente más pequeño en pantallas pequeñas */
    }

    /* Ajustar el tamaño del texto de los enlaces en la navegación */
    #menu .navbar-nav > li > a {
        font-size: 12px; /* Tamaño de fuente más pequeño para los enlaces en pantallas pequeñas */
    }

    /* Ajustar el tamaño del texto de bienvenida */
    #menu .welcome-message {
        font-size: 20px; /* Tamaño del título en dispositivos móviles */
    }

    /* Ajustar la descripción de la sección */
    .description-section {
        flex-direction: column; /* Cambiar a columna en pantallas pequeñas */
        text-align: center; /* Centrar el contenido de la sección de descripción */
    }

    .description-section img {
        max-width: 100%; /* Ajustar la imagen al 100% en pantallas pequeñas */
        margin: 20px 0; /* Añadir márgenes verticales en pantallas pequeñas */
    }

    /* Centrar el título en pantallas pequeñas */
    .panel-title {
        text-align: center; /* Centrar el título */
        font-size: 22px; /* Tamaño de fuente del título */
        margin: 20px 0; /* Margen superior e inferior */
    }

    /* Ajustar el menú */
    #menu {
        text-align: center; /* Centrar el contenido dentro del menú */
    }

    #menu .navbar-brand {
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
        color: #25e0d0; /* Mantener color de hover original */
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
	color: #323435;
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
            <span class="welcome-message">Conoce nuestras políticas</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="SignIn_SignUp/SignIn.php">Usuario</a>
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
        <div class="container-politicas">
        <h1>Política de Privacidad</h1>
        <p>Tu privacidad es importante para nosotros. Esta política de privacidad explica qué datos personales recopilamos y cómo los utilizamos. Te pedimos que leas detenidamente esta información para que conozcas nuestras prácticas de privacidad.</p>
        
        <h2>1. Datos Recogidos</h2>
        <p>Recopilamos información personal que nos proporcionas voluntariamente al registrarte en nuestro sitio, como:</p>
        <ul>
            <li><strong>Nombre completo:</strong> Para identificarte y personalizar tu experiencia.</li>
            <li><strong>Dirección de correo electrónico:</strong> Para enviarte confirmaciones de registro, notificaciones importantes y comunicados.</li>
            <li><strong>Datos de ubicación:</strong> Para mejorar la precisión de nuestros servicios y personalizar tu experiencia según tu localización.</li>
        </ul>
        
        <h2>2. Uso de Datos</h2>
        <p>Utilizamos tus datos para los siguientes propósitos:</p>
        <ul>
            <li><strong>Proporcionar y mejorar nuestros servicios:</strong> Para asegurarnos de que nuestros servicios cumplan con tus expectativas y necesidades.</li>
            <li><strong>Personalizar tu experiencia:</strong> Para adaptar el contenido y los servicios ofrecidos a tus preferencias y localización.</li>
            <li><strong>Enviar comunicaciones relacionadas:</strong> Para informarte sobre actualizaciones, promociones y otras novedades relacionadas con nuestros productos y servicios.</li>
            <li><strong>Cumplir con obligaciones legales:</strong> Para cumplir con cualquier ley, reglamento o requerimiento legal aplicable.</li>
        </ul>
        
        <h2>3. Compartición de Datos</h2>
        <p>No compartimos tus datos personales con terceros, salvo en los siguientes casos:</p>
        <ul>
            <li><strong>Con tu consentimiento explícito:</strong> Cuando hayas dado tu consentimiento explícito para compartir la información.</li>
            <li><strong>Cumplimiento legal:</strong> Para cumplir con la ley, procesos legales, o solicitudes gubernamentales.</li>
            <li><strong>Protección de derechos:</strong> Para proteger nuestros derechos, privacidad, seguridad o propiedad, y los de nuestros usuarios y el público.</li>
        </ul>
        <p>En todos los casos, nos aseguramos de que los terceros con los que compartimos datos mantengan prácticas de privacidad y seguridad adecuadas.</p>
        
        <h2>4. Seguridad de Datos</h2>
        <p>Implementamos una variedad de medidas de seguridad para proteger tus datos personales contra:</p>
        <ul>
            <li><strong>Acceso no autorizado:</strong> Utilizamos tecnología de encriptación y cortafuegos para proteger los datos sensibles.</li>
            <li><strong>Alteración indebida:</strong> Mantenemos registros de acceso y monitoreamos actividades sospechosas para prevenir cambios no autorizados.</li>
            <li><strong>Divulgación y destrucción indebida:</strong> Limitamos el acceso a tus datos personales a empleados, agentes y contratistas que necesitan conocer esa información para procesarla en nuestro nombre.</li>
        </ul>
        <p>Regularmente revisamos nuestras prácticas de seguridad para mejorar la protección de tus datos.</p>
        
        <h2>5. Derechos del Usuario</h2>
        <p>Tienes derecho a:</p>
        <ul>
            <li><strong>Acceder a tus datos personales:</strong> Solicitar una copia de los datos personales que tenemos sobre ti.</li>
            <li><strong>Rectificar tus datos:</strong> Corregir cualquier dato personal incorrecto o incompleto.</li>
            <li><strong>Eliminar tus datos:</strong> Solicitar la eliminación de tus datos personales, salvo que tengamos que conservarlos por motivos legales.</li>
            <li><strong>Limitar el tratamiento de tus datos:</strong> Restringir el uso de tus datos personales en determinadas circunstancias.</li>
            <li><strong>Oponerse al tratamiento:</strong> Oponerte al uso de tus datos personales para ciertos propósitos.</li>
        </ul>
        <p>Para ejercer cualquiera de estos derechos, por favor, contacta con nosotros a través de la información de contacto proporcionada en nuestro sitio web.</p>
        
        <h2>6. Cambios a Esta Política</h2>
        <p>Podemos actualizar nuestra política de privacidad ocasionalmente. Te notificaremos sobre cualquier cambio publicando la nueva política en nuestro sitio web. Te recomendamos que revises periódicamente esta página para estar al tanto de cualquier cambio.</p>
        
        <h2>Contacto</h2>
        <p>Si tienes alguna pregunta sobre esta política de privacidad, por favor, contáctanos a través de la información de contacto proporcionada en nuestro sitio web.</p>
          </div>
         </main>
     <div id="footer">
      <div class="container">
          <p>&copy; 2025 TRACKLY.</p>
     </div>
</div>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
