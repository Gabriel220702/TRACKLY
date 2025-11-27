/*
 * CÓDIGO PARA TRACKLY - ESP32 C3 SUPER MINI
 * Adaptado de Amoxtli-Jap
 * * Funciones:
 * 1. Rastreo en vivo: Envía ubicación a XAMPP cada 30 segundos.
 * 2. Botón de Pánico: Envía alerta a XAMPP y WhatsApp al presionar.
 * 3. Geocerca (Casa): Envía alerta a XAMPP y WhatsApp si sale del radio.
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <TinyGPS++.h>

// --- 1. CONFIGURACIÓN (¡Revisa esto!) ---

// WiFi
const char *ssid = "INFINITUM86C2"; // Tu SSID
const char *password = "ELd4TfVbHW";  // Tu Contraseña

// Servidor XAMPP (¡IMPORTANTE! Debe ser la IP de tu PC, no 'localhost')
const char *host = "192.168.1.74"; 

// Dispositivo
const char *idDispositivo = "TRACKLY_01"; // El ID que pusimos en la BD

// Geocerca (Tu Casita)
#define LATITUD_CASA 24.437417
#define LONGITUD_CASA -104.116083
#define RADIO_SEGURO 200 // Radio en metros (ej. 200 metros)

// --- 2. PINES (¡REVISA EL PINOUT DE TU ESP32-C3!) ---
// ¡ESTOS PINES SON EJEMPLOS Y PROBABLEMENTE INCORRECTOS!
// ¡Debes buscar el diagrama de tu placa "ESP32 C3 Super Mini"!

// Pines para el GPS (UART1)
#define RXD2 8  // Ejemplo: Conecta al TX del GPS
#define TXD2 9  // Ejemplo: Conecta al RX del GPS

// Pin para el botón de pánico
#define buttonPin 10 // Ejemplo: GPIO 10

// --- 3. VARIABLES GLOBALES ---
TinyGPSPlus gps;
HardwareSerial neogps(1); // Usamos UART1 para el GPS

// Timers (para no usar delay())
unsigned long timerUbicacionVivo = 0;
unsigned long timerGeocerca = 0;
unsigned long timerBotonPanico = 0;

// Estado de la Geocerca
bool esta_fuera_de_zona = false;

// --- 4. SETUP ---
void setup() {
  Serial.begin(115200);
  
  // Iniciar GPS
  neogps.begin(9600, SERIAL_8N1, RXD2, TXD2); // Inicia Serial1 con los pines definidos
  Serial.println("Iniciando GPS...");

  // Configurar Botón
  pinMode(buttonPin, INPUT_PULLUP); // Botón conectado a GND y al Pin
  Serial.println("Botón de pánico listo.");

  // Conectar a WiFi
  Serial.print("Conectando a ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\n¡WiFi Conectado!");
  Serial.print("IP: ");
  Serial.println(WiFi.localIP());
}

// --- 5. LOOP PRINCIPAL ---
void loop() {
  
  // 1. Leer el GPS constantemente
  while (neogps.available() > 0) {
    gps.encode(neogps.read());
  }

  // 2. Tareas (se ejecutan sin bloquear el código)
  
  // Tarea A: Enviar ubicación en vivo (cada 30 segundos)
  if (millis() - timerUbicacionVivo > 30000) {
    timerUbicacionVivo = millis();
    enviarUbicacionVivo();
  }

  // Tarea B: Revisar el botón de pánico
  revisarBotonPanico();

  // Tarea C: Revisar la geocerca (cada 10 segundos)
  if (millis() - timerGeocerca > 10000) {
    timerGeocerca = millis();
    revisarGeocerca();
  }
}

// --- 6. FUNCIONES DE TRACKLY ---

/**
 * Tarea A: Envía la ubicación actual al servidor (para el mapa en vivo)
 */
void enviarUbicacionVivo() {
  if (gps.location.isValid()) {
    String lat = String(gps.location.lat(), 6);
    String lon = String(gps.location.lng(), 6);

    String url = "http://" + String(host) + "/trackly/actualizar_ubicacion.php";
    String payload = "lat=" + lat + "&lon=" + lon + "&id_esp=" + idDispositivo;

    Serial.println("[Vivo] Enviando ubicación: " + lat + "," + lon);
    httpPost(url, payload);
    
  } else {
    Serial.println("[Vivo] Esperando ubicación GPS válida...");
  }
}

/**
 * Tarea B: Revisa si el botón de pánico fue presionado
 */
void revisarBotonPanico() {
  // Si el botón está presionado (LOW) y han pasado más de 10 seg (para evitar spam)
  if (digitalRead(buttonPin) == LOW && millis() - timerBotonPanico > 10000) {
    timerBotonPanico = millis(); // Reiniciar timer

    Serial.println("¡BOTÓN DE PÁNICO PRESIONADO!");

    if (gps.location.isValid()) {
      String lat = String(gps.location.lat(), 6);
      String lon = String(gps.location.lng(), 6);

      String url = "http://" + String(host) + "/trackly/registrar_alerta.php";
      String payload = "lat=" + lat + "&lon=" + lon + "&id_esp=" + idDispositivo + "&tipo_alerta=PANICO";
      
      Serial.println("[Pánico] Enviando alerta...");
      httpPost(url, payload);

      // Enviar también a WhatsApp
      enviarAlertaWhatsApp("PANICO");
      
    } else {
      Serial.println("[Pánico] No se pudo enviar, GPS sin señal.");
    }
  }
}

/**
 * Tarea C: Revisa si el dispositivo salió de la zona segura
 */
void revisarGeocerca() {
  if (gps.location.isValid()) {
    
    float lat_actual = gps.location.lat();
    float lon_actual = gps.location.lng();
    
    // Calcular la distancia
    float distancia = TinyGPSPlus::distanceBetween(
        lat_actual, lon_actual,
        LATITUD_CASA, LONGITUD_CASA
    );
    
    Serial.print("[Geocerca] Distancia a casa: ");
    Serial.print(distancia);
    Serial.println(" metros.");

    // Lógica de Geocerca
    
    // 1. Si se SALIÓ de la zona
    if (distancia > RADIO_SEGURO && esta_fuera_de_zona == false) {
      Serial.println("[Geocerca] ¡SALIÓ DE LA ZONA SEGURA!");
      esta_fuera_de_zona = true; // Actualizar estado

      String url = "http://" + String(host) + "/trackly/registrar_alerta.php";
      String payload = "lat=" + String(lat_actual, 6) + "&lon=" + String(lon_actual, 6) + 
                       "&id_esp=" + idDispositivo + "&tipo_alerta=GEOCERCA_SALIDA";
                       
      httpPost(url, payload);
      enviarAlertaWhatsApp("GEOCERCA_SALIDA");
    }
    
    // 2. Si REGRESÓ a la zona
    else if (distancia <= RADIO_SEGURO && esta_fuera_de_zona == true) {
      Serial.println("[Geocerca] ¡REGRESÓ A LA ZONA SEGURA!");
      esta_fuera_de_zona = false; // Actualizar estado
      
      // Opcional: Enviar alerta de que regresó (descomenta si lo quieres)
      /*
      String url = "http://" + String(host) + "/trackly/registrar_alerta.php";
      String payload = "lat=" + String(lat_actual, 6) + "&lon=" + String(lon_actual, 6) + 
                       "&id_esp=" + idDispositivo + "&tipo_alerta=GEOCERCA_ENTRADA";
      httpPost(url, payload);
      */
    }

  } else {
    Serial.println("[Geocerca] GPS sin señal, no se puede verificar la zona.");
  }
}

// --- 7. FUNCIONES DE RED (Reutilizadas de tu código) ---

/**
 * Función genérica para enviar datos por POST a nuestro servidor
 */
void httpPost(String url, String payload) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(url);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    Serial.println("Enviando POST a: " + url);
    Serial.println("Payload: " + payload);

    int httpResponseCode = http.POST(payload);
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.print("Respuesta del servidor: ");
      Serial.println(response);
    } else {
      Serial.print("Error en POST. Código: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  } else {
    Serial.println("Error: WiFi desconectado. No se puede enviar POST.");
  }
}

/**
 * Función para enviar la alerta de WhatsApp (adaptada de tu código)
 */
void enviarAlertaWhatsApp(String tipoAlerta) {
  if (WiFi.status() == WL_CONNECTED) {
    
    // Tus credenciales de WhatsApp (de tu código)
    String token = "Bearer EAAQ8j5c9GKUBOy43CLu6XuIIJ4xFZB56gclkmwGj7Nc7lw4Wh3NY9mf3gTYrQboTwymBh1i8MVvQ7jEEJrakcUqq5Lf8B4QZAOsdLzZCs5ckZAjU1elzN23ZCxTao4zRaP3TxAU7QxWBMuQ6KRrOa9gZAltv7ZBgHVuwz7is2FdEXFBUwc7FyyAeDKWaTzEWPqh";
    String servidor = "https://graph.facebook.com/v19.0/345081398691756/messages";
    String telefono = "526761055560"; // Tu número

    // Mensaje personalizado
    String mensaje = "";
    if (tipoAlerta == "PANICO") {
      mensaje = "¡ALERTA DE PÁNICO! Se ha presionado el botón de auxilio de TRACKLY.";
    } else {
      mensaje = "¡ALERTA DE GEOCERCA! El dispositivo TRACKLY ha salido de la zona segura.";
    }
    mensaje += " Revisa la ubicación en el panel: http://" + String(host) + "/trackly/panel_de_control.php";

    HTTPClient httpWhatsApp;
    httpWhatsApp.begin(servidor);
    httpWhatsApp.addHeader("Content-Type", "application/json");
    httpWhatsApp.addHeader("Authorization", token);

    // Crear el payload JSON
    String payloadWhatsApp = "{\"messaging_product\":\"whatsapp\",\"to\":\"" + telefono + "\",\"type\":\"text\",\"text\":{\"body\":\"" + mensaje + "\"}}";

    Serial.println("Enviando alerta de WhatsApp...");
    Serial.println(payloadWhatsApp);
    
    int httpPostCode = httpWhatsApp.POST(payloadWhatsApp);
    
    if (httpPostCode > 0) {
      String response = httpWhatsApp.getString();
      Serial.print("Respuesta de WhatsApp: ");
      Serial.println(response);
    } else {
      Serial.print("Error al enviar WhatsApp. Código: ");
      Serial.println(httpPostCode);
    }
    httpWhatsApp.end();
  }
}