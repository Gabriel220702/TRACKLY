<?php
session_start();
if (!isset($_SESSION['Email_Session'])) {
    header("Location: SignIn_SignUp/SignIn.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - TRACKLY</title>
    <link rel="icon" href="img/TRACKLY favicon.ico" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-image: url('img/Difuminado 7.png'); /* Asegúrate de que la ruta sea correcta */
            background-size: cover; background-repeat: no-repeat;
            background-position: center; background-attachment: fixed;
        }
        #menu { padding: 0.5px 0; background-color: #323435; }
        #menu .navbar-brand img { width: 65px; margin-right: 5px; }
        #menu .welcome-message { font-size: 22px; color: #ffffff; margin-left: 10px; }
        #menu .navbar-nav > li > a { color: #ffffff; }
        #menu .navbar-nav > li > a:hover { color: #ff0000ff; }
        .panel-container { display: flex; flex-wrap: wrap; padding: 20px; gap: 20px; }
        .map-widget {
            flex: 3; min-width: 300px; height: 550px;
            border-radius: 8px; overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2); border: 2px solid #323435;
        }
        .alerts-widget {
            flex: 2; min-width: 300px; height: 550px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 8px; padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2); overflow-y: auto;
        }
        .alerts-widget h3 {
            color: #323435; border-bottom: 3px solid #e02020ff;
            padding-bottom: 15px; margin-bottom: 20px; font-weight: bold;
        }
        /* COLORES DE ALERTAS */
        .alert-item {
            background: #fff; border: 1px solid #ddd; padding: 15px;
            border-radius: 6px; margin-bottom: 12px; transition: all 0.3s ease;
            border-left: 8px solid #ccc; 
        }
        .alert-item:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .alert-item.panico { border-left-color: #dc3545; background-color: #fff5f5; } /* ROJO */
        .alert-item.salida { border-left-color: #ffc107; background-color: #fffff0; } /* NARANJA */
        .alert-item.regreso { border-left-color: #28a745; background-color: #f0fff0; } /* VERDE */
        .alert-item p { margin-bottom: 5px; color: #333; }
        .alert-item strong { font-size: 1.05em; }
        .time { font-size: 0.85em; color: #777; margin-bottom: 10px; }
    </style>
</head>
<body>
    
<nav id="menu" class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="img/Logo TRACKLY Transparente.png" alt="Logo">
        </a>
        <span class="welcome-message">Panel de Control TRACKLY</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto text-uppercase">
                <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="usuario.php">Mi Perfil</a></li>
                <li class="nav-item"><a class="nav-link" href="php/cerrar_sesion.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid panel-container">
    <div class="map-widget" id="mapa"></div>
    <div class="alerts-widget">
        <h3>🚨 Historial de Alertas</h3>
        <div id="lista-alertas"><p class="text-muted text-center mt-4">Cargando...</p></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // Inicializar mapa
    var map = L.map('mapa').setView([24.0277, -104.6530], 13);
    var marker = null;
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    async function actualizarDatos() {
        // --- 1. ACTUALIZAR MAPA Y COORDENADAS ---
        try {
            const resLoc = await fetch('obtener_ubicacion_actual.php');
            if (resLoc.ok) {
                const data = await resLoc.json();
                // Verificamos que tengamos datos válidos y no sean 0.0
                if (data && !data.error && parseFloat(data.ultima_lat) != 0) {
                    const pos = [parseFloat(data.ultima_lat), parseFloat(data.ultima_lon)];
                    
                    if (marker) {
                        marker.setLatLng(pos);
                    } else {
                        marker = L.marker(pos).addTo(map);
                    }

                    // --- AQUÍ ESTÁ EL CAMBIO CLAVE ---
                    // Mostramos las coordenadas exactas en el globito del marcador
                    marker.bindPopup(`
                        <div style="text-align: center;">
                            <strong>📍 Ubicación Actual TRACKLY</strong><br>
                            Lat: ${data.ultima_lat}<br>
                            Lon: ${data.ultima_lon}<br>
                            <small style="color: gray;">${data.ultima_actualizacion}</small>
                        </div>
                    `).openPopup(); // .openPopup() hace que se abra solo al actualizar
                    
                    map.setView(pos, 18); // Zoom cercano para ver mejor
                }
            }
        } catch (e) { console.error("Error mapa:", e); }

        // --- 2. ACTUALIZAR ALERTAS (Con traductor amigable) ---
        try {
            const resAlerts = await fetch('obtener_alertas_nuevas.php');
            if (resAlerts.ok) {
                const alerts = await resAlerts.json();
                const list = document.getElementById('lista-alertas');
                
                if (alerts && alerts.length > 0) {
                    list.innerHTML = ''; // Limpiar lista anterior
                    alerts.forEach(a => {
                        const tipo = (a.tipo_alerta || "").toUpperCase();
                        // Configuración por defecto
                        let cfg = { t: a.tipo_alerta, c: 'salida', i: '⚠️' };
                        
                        // Traducción de códigos a mensajes amigables
                        if (tipo.includes('PANICO')) {
                            cfg = { t: '¡S.O.S! SE HA PRESIONADO EL BOTÓN DE PÁNICO.', c: 'panico', i: '🚨' };
                        } else if (tipo.includes('SALIDA')) {
                            cfg = { t: 'ATENCIÓN: Su hijo ha SALIDO de la zona segura.', c: 'salida', i: '⚠️' };
                        } else if (tipo.includes('REGRESO') || tipo.includes('ENTRADA')) {
                            cfg = { t: 'INFO: Su hijo ha REGRESADO a la zona segura.', c: 'regreso', i: '✅' };
                        }

                        list.innerHTML += `
                            <div class="alert-item ${cfg.c}">
                                <p><strong>${cfg.i} ${cfg.t}</strong></p>
                                <p class="time">${a.fecha_hora}</p>
                                <a href="https://www.google.com/maps/search/?api=1&query=${a.lat_alerta},${a.lon_alerta}" target="_blank" class="btn btn-sm btn-outline-dark">Ver en Google Maps ↗</a>
                            </div>`;
                    });
                } else {
                    list.innerHTML = '<p class="text-muted text-center mt-4">No hay alertas nuevas.</p>';
                }
            }
        } catch (e) { console.error("Error alertas:", e); }
    }

    // Ejecutar al inicio y luego cada 5 segundos
    actualizarDatos();
    setInterval(actualizarDatos, 5000);
</script>
</body>
</html>