/*
  ESP8266 - Invernadero IoT Controller
  
  Sistema completo de monitoreo y control para invernadero:
  - Sensores: DHT22, MQ135, BMP280, LDR
  - Pantalla OLED para visualización en tiempo real
  - Control de ventilador vía WebSocket + MQTT desde Laravel
  - Envío de datos por HTTP y WebSocket
  
  Librerías requeridas:
  - PubSubClient (Nick O'Leary) - MQTT
  - ArduinoJson - Parseo JSON
  - Adafruit GFX, Adafruit SSD1306 - OLED
  - Adafruit BMP280 - Sensor de presión
  - DHT - Sensor DHT22
  - MQ135 - Sensor de calidad del aire
  - WebSocketsClient - Comunicación WebSocket
  
  Pasos antes de cargar:
  1. Instala todas las librerías listadas arriba
  2. Cambia SSID, PASSWORD y direcciones IP
  3. Abre Monitor Serial a 115200 baud
*/

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <MQ135.h>
#include <DHT.h>
#include <WiFiClient.h>
#include <vector>
#include <PubSubClient.h>

#include <Wire.h>
#include <SPI.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <Adafruit_BMP280.h>
#include <WebSocketsClient.h>

#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
#define OLED_SDA 12
#define OLED_SCL 14

// ============================================
// OBJETOS
// ============================================
Adafruit_SSD1306 pantalla(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, -1);
WebSocketsClient webSocket;
WiFiClient espClient;
PubSubClient mqttClient(espClient);

// ============================================
// CONFIGURACIÓN - CAMBIAR ESTOS VALORES
// ============================================
const char* ssid = "INFINITUM7137";
const char* password = "g6YMUUh5H4";
const char* websocket_host = "192.168.1.87";
const int websocket_port = 6001;

// MQTT Configuration
const char* mqtt_server = "192.168.1.87";  // IP del servidor MQTT (puede ser mismo que Laravel)
const int mqtt_port = 1883;
const char* mqtt_topic_fan = "invernadero/fan";
const char* mqtt_topic_status = "invernadero/status";

// API URLs
const char* apiUrl = "http://192.168.1.87:8000/api/medidas";
const char* apiUrl1 = "http://192.168.1.87:8000/api/calidadA";
const char* apiUrl2 = "http://192.168.1.87:8000/api/presiones";
const char* apiUrl3 = "http://192.168.1.87:8000/api/iluminaciones";

// ============================================
// PINES Y CONFIGURACIÓN DE SENSORES
// ============================================
#define PIN_MQ135 A0
#define DHTPIN D8
#define DHTTYPE DHT22
#define PIN_LDR A0
#define FAN_PIN D1  // GPIO5 - Pin del ventilador

#define VALOR_OSCURO 50
#define VALOR_LUZ 900

// ============================================
// INSTANCIAS DE SENSORES
// ============================================
DHT dht(DHTPIN, DHTTYPE);
MQ135 mq135_sensor(PIN_MQ135);
Adafruit_BMP280 bmp;

// ============================================
// VARIABLES GLOBALES
// ============================================
bool bmpAvailable = false;
bool fanEstado = false;
bool wifiConectado = false;
bool mqttConectado = false;
unsigned long lastSensorReadTime = 0;
const unsigned long SENSOR_INTERVAL = 10000; // Leer sensores cada 10 segundos

void imprimirPantalla(std::vector<String> lineas) {
  pantalla.clearDisplay();
  pantalla.setTextSize(1);
  pantalla.setTextColor(SSD1306_WHITE);

  int numLineas = lineas.size(); 

  switch (numLineas) {
    case 1:
      pantalla.setCursor(0, 15);
      pantalla.println(lineas[0]); 
      break;
    case 2:
      pantalla.setCursor(0, 10);
      pantalla.println(lineas[0]);
      pantalla.setCursor(0, 20);
      pantalla.println(lineas[1]);
      break;
    case 3:
      pantalla.setCursor(0, 0);
      pantalla.println(lineas[0]);
      pantalla.setCursor(0, 25);
      pantalla.println(lineas[1]);
      pantalla.setCursor(90, 25);
      pantalla.println(lineas[2]);
      break;
    case 4:
      pantalla.setCursor(0, 0);
      pantalla.println(lineas[0]);
      pantalla.setCursor(0, 25);
      pantalla.println(lineas[1]);
      pantalla.setCursor(0, 50);
      pantalla.println(lineas[2]);
      pantalla.setCursor(90, 50);
      pantalla.println(lineas[3]);
      break;
  }

  pantalla.display();
}

bool iniciarBMP280() {
  Serial.println("Iniciando BMP280...");
  
  if (bmp.begin(0x76)) {
    Serial.println("BMP280 encontrado en 0x76");
    return true;
  }
  
  if (bmp.begin(0x77)) {
    Serial.println("BMP280 encontrado en 0x77");
    return true;
  }
  
  Serial.println("BMP280 NO encontrado");
  return false;
}

// ============================================
// CALLBACK MQTT - Procesa mensajes del broker
// ============================================
void mqttCallback(char* topic, byte* payload, unsigned int length) {
  Serial.print("MQTT Mensaje en [");
  Serial.print(topic);
  Serial.print("]: ");

  String mensaje = "";
  for (unsigned int i = 0; i < length; i++) {
    mensaje += (char)payload[i];
  }
  Serial.println(mensaje);

  // Parsear JSON del comando MQTT
  StaticJsonDocument<200> doc;
  DeserializationError error = deserializeJson(doc, mensaje);
  
  if (!error) {
    String action = doc["action"] | "";
    
    if (action == "on") {
      digitalWrite(FAN_PIN, HIGH);
      fanEstado = true;
      Serial.println(">>> VENTILADOR ENCENDIDO (MQTT)");
      imprimirPantalla({"MQTT:", "Vent: ON"});
      
      // Publicar estado de confirmación
      mqttClient.publish(mqtt_topic_status, "{\"fan\":\"on\",\"source\":\"mqtt\"}");
      
    } else if (action == "off") {
      digitalWrite(FAN_PIN, LOW);
      fanEstado = false;
      Serial.println(">>> VENTILADOR APAGADO (MQTT)");
      imprimirPantalla({"MQTT:", "Vent: OFF"});
      
      // Publicar estado de confirmación
      mqttClient.publish(mqtt_topic_status, "{\"fan\":\"off\",\"source\":\"mqtt\"}");
    }
  } else {
    Serial.println("Error parseando JSON MQTT");
  }
}

// ============================================
// RECONECTAR MQTT
// ============================================
void mqttReconnect() {
  if (mqttClient.connected()) {
    return;
  }

  Serial.print("Conectando MQTT...");
  
  String clientId = "ESP8266_";
  clientId += String(random(0xffff), HEX);

  if (mqttClient.connect(clientId.c_str())) {
    Serial.println(" OK");
    mqttClient.subscribe(mqtt_topic_fan);
    Serial.println("Suscrito a: " + String(mqtt_topic_fan));
    mqttConectado = true;
    
    // Publicar estado inicial
    mqttClient.publish(mqtt_topic_status, "{\"status\":\"connected\",\"device\":\"esp8266\"}");
  } else {
    Serial.print("Error (");
    Serial.print(mqttClient.state());
    Serial.println(") - Reintentando...");
    mqttConectado = false;
  }
}

// ============================================
// CALLBACK WEBSOCKET
// ============================================
void webSocketEvent(WStype_t type, uint8_t * payload, size_t length) {
  switch(type) {
    case WStype_DISCONNECTED:
      Serial.println("WebSocket desconectado");
      imprimirPantalla({"WebSocket:", "Desconectado"});
      break;
      
    case WStype_CONNECTED:
      Serial.println("WebSocket conectado!");
      imprimirPantalla({"WebSocket:", "Conectado!"});
      webSocket.sendTXT("{\"type\":\"device_connected\",\"device\":\"esp8266\"}");
      break;
      
    case WStype_TEXT:
      {
        String mensaje = String((char*)payload);
        Serial.println("WebSocket recibido: " + mensaje);
        
        StaticJsonDocument<200> doc;
        DeserializationError error = deserializeJson(doc, mensaje);
        
        if (!error) {
          String action = doc["action"] | "";
          if (action == "on") {
            digitalWrite(FAN_PIN, HIGH);
            fanEstado = true;
            Serial.println("Ventilador ENCENDIDO (WebSocket)");
            imprimirPantalla({"WebSocket:", "Vent: ON"});
            webSocket.sendTXT("{\"type\":\"fan_status\",\"status\":\"on\"}");
            
          } else if (action == "off") {
            digitalWrite(FAN_PIN, LOW);
            fanEstado = false;
            Serial.println("Ventilador APAGADO (WebSocket)");
            imprimirPantalla({"WebSocket:", "Vent: OFF"});
            webSocket.sendTXT("{\"type\":\"fan_status\",\"status\":\"off\"}");
          }
        }
      }
      break;
      
    case WStype_ERROR:
      Serial.println("Error WebSocket");
      break;
  }
}

void conectarWebSocket() {
  webSocket.begin(websocket_host, websocket_port, "/");
  webSocket.onEvent(webSocketEvent);
  webSocket.setReconnectInterval(5000);
  Serial.println("WebSocket iniciado: " + String(websocket_host) + ":" + String(websocket_port));
}

// ============================================
// FUNCIONES DE SENSORES
// ============================================
void sensorDHT22(){
  float humedad = dht.readHumidity();
  float temperatura = dht.readTemperature();

  if (isnan(humedad) || isnan(temperatura)) {
    Serial.println("Error al leer DHT!");
    return;
  }

  StaticJsonDocument<200> jsonDocument;
  jsonDocument["humedad"] = humedad;
  jsonDocument["temperatura"] = temperatura;

  String jsonDatos;
  serializeJson(jsonDocument, jsonDatos);
    
  Serial.println("DHT22 - Temp: " + String(temperatura) + "°C, Hum: " + String(humedad) + "%");

  if (webSocket.isConnected()) {
    String wsData = "{\"type\":\"sensor_data\",\"sensor\":\"dht22\",\"temperature\":" + 
                   String(temperatura) + ",\"humidity\":" + String(humedad) + "}";
    webSocket.sendTXT(wsData);
  }

  if (wifiConectado) {
    WiFiClient client;
    HTTPClient http;
    http.begin(client, apiUrl);
    http.addHeader("Content-Type", "application/json");
    int httpResponseCode = http.POST(jsonDatos);

    if (httpResponseCode > 0) {
      Serial.println("DHT22 enviado - HTTP: " + String(httpResponseCode));
      imprimirPantalla({"DHT22:", String(temperatura,1) + "C " + String(humedad,1) + "%"});
    }
    http.end();
  }
}

void SensorMq135(){
  float ppm = mq135_sensor.getPPM();

  Serial.println("MQ135 - PPM: " + String(ppm));

  String jsonDatos = "{\"calidad_aire\":" + String(ppm) + "}";

  if (webSocket.isConnected()) {
    String wsData = "{\"type\":\"sensor_data\",\"sensor\":\"mq135\",\"ppm\":" + String(ppm) + "}";
    webSocket.sendTXT(wsData);
  }

  if (wifiConectado) {
    WiFiClient client;
    HTTPClient http;
    http.begin(client, apiUrl1);
    http.addHeader("Content-Type", "application/json");
    int httpResponseCode = http.POST(jsonDatos);

    if (httpResponseCode > 0) {
      Serial.println("MQ135 enviado: " + String(ppm) + " ppm");
      imprimirPantalla({"MQ135:", String(ppm, 1) + " ppm"});
    }
    http.end();
  } 
}

void SensorBMP280(){
  float presion = 1017.0;
  float nivelM = 29.9;

  if (bmpAvailable) {
    presion = bmp.readPressure();
    nivelM = presion / 3386.39; // Convertir Pa a inHg
  }

  Serial.println("BMP280 - Presion: " + String(presion) + " Pa");

  StaticJsonDocument<100> jsonDocument;
  jsonDocument["presion"] = presion;
  jsonDocument["Merc"] = nivelM;

  String jsonDatos;
  serializeJson(jsonDocument, jsonDatos);

  if (webSocket.isConnected()) {
    String wsData = "{\"type\":\"sensor_data\",\"sensor\":\"bmp280\",\"pressure\":" + 
                   String(presion) + "}";
    webSocket.sendTXT(wsData);
  }

  if (wifiConectado) {
    WiFiClient client;
    HTTPClient http;
    http.begin(client, apiUrl2);
    http.addHeader("Content-Type", "application/json");
    int httpResponseCode = http.POST(jsonDatos);

    if (httpResponseCode > 0) {
      Serial.println("BMP280 enviado");
      imprimirPantalla({"BMP280:", String(presion/100.0, 1) + " hPa"});
    }
    http.end();
  }
}

void SensorLDR(){
  int valorLuz = analogRead(PIN_LDR);
  int porcentajeLuz = map(valorLuz, VALOR_OSCURO, VALOR_LUZ, 0, 100);
  porcentajeLuz = constrain(porcentajeLuz, 0, 100);

  String nivelLuz;
  if (porcentajeLuz < 20) {
    nivelLuz = "Oscuro";
  } else if (porcentajeLuz < 50) {
    nivelLuz = "Moderado";
  } else if (porcentajeLuz < 80) {
    nivelLuz = "Luminoso";
  } else {
    nivelLuz = "Muy Luminoso";
  }

  Serial.println("LDR - Valor: " + String(valorLuz) + ", Luz: " + String(porcentajeLuz) + "%");

  String jsonDatos = "{\"iluminacions\":" + String(porcentajeLuz) + "}";

  if (webSocket.isConnected()) {
    String wsData = "{\"type\":\"sensor_data\",\"sensor\":\"ldr\",\"light_level\":" + 
                   String(porcentajeLuz) + "}";
    webSocket.sendTXT(wsData);
  }

  if (wifiConectado) {
    WiFiClient client;
    HTTPClient http;
    http.begin(client, apiUrl3);
    http.addHeader("Content-Type", "application/json");
    int httpResponseCode = http.POST(jsonDatos);

    if (httpResponseCode > 0) {
      Serial.println("LDR enviado");
      imprimirPantalla({"LDR:", String(porcentajeLuz) + "%", nivelLuz});
    }
    http.end();
  }
}

// ============================================
// SETUP
// ============================================
void setup() {
  Serial.begin(115200);
  delay(1000);
  
  Serial.println("\n\n╔════════════════════════════════════╗");
  Serial.println("║  Invernadero IoT - Iniciando...  ║");
  Serial.println("╚════════════════════════════════════╝");

  // Configurar pin del ventilador
  pinMode(FAN_PIN, OUTPUT);
  digitalWrite(FAN_PIN, LOW);

  // Inicializar pantalla OLED
  Wire.begin(OLED_SDA, OLED_SCL);
  if(!pantalla.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
    Serial.println("Error: Pantalla OLED no encontrada!");
    while(1);
  }
  pantalla.clearDisplay();
  pantalla.setTextColor(SSD1306_WHITE);
  imprimirPantalla({"Iniciando..."});

  // Inicializar sensores
  dht.begin();
  Serial.println("DHT22 iniciado");

  pinMode(PIN_LDR, INPUT);
  Serial.println("LDR configurado");

  bmpAvailable = iniciarBMP280();
  if (bmpAvailable) {
    bmp.setSampling(Adafruit_BMP280::MODE_NORMAL,     
                    Adafruit_BMP280::SAMPLING_X2,     
                    Adafruit_BMP280::SAMPLING_X16,    
                    Adafruit_BMP280::FILTER_X16,       
                    Adafruit_BMP280::STANDBY_MS_500);
    Serial.println("BMP280 OK");
    imprimirPantalla({"BMP280:", "Iniciado OK"});
  } else {
    Serial.println("BMP280 no disponible");
    imprimirPantalla({"BMP280:", "No encontrado"});
  }

  delay(2000);

  // Conexión WiFi
  WiFi.begin(ssid, password);
  Serial.println("Conectando WiFi...");
  imprimirPantalla({"Conectando", "WiFi..."});

  int intentos = 0;
  while (WiFi.status() != WL_CONNECTED && intentos < 20) {
    delay(500);
    Serial.print(".");
    intentos++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi conectado!");
    Serial.print("IP: "); 
    Serial.println(WiFi.localIP());
    imprimirPantalla({"WiFi OK!", WiFi.localIP().toString()});
    wifiConectado = true;
    
    delay(1000);
    
    // Conectar WebSocket
    conectarWebSocket();
    
    // Configurar MQTT
    mqttClient.setServer(mqtt_server, mqtt_port);
    mqttClient.setCallback(mqttCallback);
    Serial.println("MQTT configurado en " + String(mqtt_server) + ":" + String(mqtt_port));
  } else {
    Serial.println("\nError: No se pudo conectar WiFi");
    imprimirPantalla({"Error WiFi!"});
    wifiConectado = false;
  }

  delay(2000);
}

// ============================================
// LOOP PRINCIPAL
// ============================================
void loop() {
  // Mantener WiFi y conexiones activas
  if (wifiConectado) {
    webSocket.loop();
    
    // Reconectar MQTT si es necesario
    if (!mqttClient.connected()) {
      mqttReconnect();
    }
    mqttClient.loop();
  } else {
    // Intentar reconectar WiFi
    if (WiFi.status() != WL_CONNECTED) {
      Serial.println("WiFi desconectado, intentando reconectar...");
      WiFi.reconnect();
      delay(1000);
    }
  }

  // Leer sensores cada SENSOR_INTERVAL ms
  if (millis() - lastSensorReadTime >= SENSOR_INTERVAL) {
    lastSensorReadTime = millis();
    
    Serial.println("\n--- Ciclo de lecturas ---");
    
    sensorDHT22();
    delay(1000);
    
    SensorMq135();
    delay(1000);
    
    SensorBMP280();
    delay(1000);
    
    SensorLDR();
    delay(1000);

    // Mostrar estado en pantalla
    String fanStatus = fanEstado ? "Vent: ON" : "Vent: OFF";
    String wsStatus = webSocket.isConnected() ? "WS: OK" : "WS: NO";
    String mqStatus = mqttClient.connected() ? "MQ: OK" : "MQ: NO";
    
    imprimirPantalla({"Estado:", 
                     fanStatus,
                     wsStatus, 
                     mqStatus});
  }

  delay(100); // No bloquear el loop
}
