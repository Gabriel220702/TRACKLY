<?php
    // El nombre de la variable ($conx) es de tu código original, lo respetamos.
    $conx = mysqli_connect("localhost", "root", "", "trackly_db", 3307);

    if(!$conx){
        // Cambiamos el 'echo' por un 'die' para que sea más robusto
        // y nos diga exactamente cuál fue el error.
        die("Error de Conexión: " . mysqli_connect_error());
    }

    // (Opcional pero recomendado) Aseguramos que use UTF-8 para acentos
    mysqli_set_charset($conx, "utf8mb4");
?>