# Guía MQTT para ESP8266/ESP32 — Comunicación con Laravel

## Requisitos previos

1. **Broker MQTT** (Mosquitto) ejecutándose localmente en puerto 1883
2. **Laravel** publicando a MQTT cuando se controle el ventilador
3. **ESP8266/ESP32** con librería PubSubClient

---

## 1. Instalar y ejecutar Mosquitto (Windows)

### Opción A: Con Chocolatey
```powershell
choco install mosquitto
```

### Opción B: Descarga manual
Descarga desde: https://mosquitto.org/download/

### Iniciar Mosquitto
```powershell
mosquitto -v
```

Debe mostrar:
```
mosquitto version 2.x.x starting
```

---

## 2. Configuración Laravel (.env)

Ya está configurado en tu proyecto:
```
MQTT_HOST=127.0.0.1
MQTT_PORT=1883
MQTT_USERNAME=
MQTT_PASSWORD=
MQTT_TOPIC_FAN=invernadero/fan
```

Tu `FanController.php` ya publica a este topic cuando llamas:
- `POST /api/fan/on` → publica `{"action":"on"}` a `invernadero/fan`
- `POST /api/fan/off` → publica `{"action":"off"}` a `invernadero/fan`

---

## 3. Código Arduino para ESP8266/ESP32

### Dependencias necesarias en Arduino IDE

1. Abre **Sketch → Include Library → Manage Libraries**
2. Busca e instala:
   - `PubSubClient` (Nick O'Leary)
   - `ESP8266WiFi` (ya viene con ESP8266)

### Código ESP8266

Copia este código en Arduino IDE:

```cpp
#include <ESP8266WiFi.h>
#include <PubSubClient.h>

// === CONFIGURACIÓN ===
const char* ssid = "TU_SSID";              // Tu WiFi SSID
const char* password = "TU_PASSWORD";      // Tu WiFi password
const char* mqtt_server = "192.168.1.100"; // IP de tu PC donde corre Mosquitto
const char* mqtt_topic = "invernadero/fan";
const int mqtt_port = 1883;

const int FAN_PIN = D1; // GPIO5 en ESP8266 (D1), cambiar según tu conexión

// === OBJETOS ===
WiFiClient espClient;
PubSubClient client(espClient);

// === FUNCIONES ===

void setup_wifi() {
  delay(10);
  Serial.println();
  Serial.print("Conectando a WiFi: ");
  Serial.println(ssid);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 20) {
    delay(500);
    Serial.print(".");
    attempts++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("");
    Serial.println("WiFi conectado");
    Serial.print("IP: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("Falló conexión WiFi");
  }
}

void callback(char* topic, byte* payload, unsigned int length) {
  Serial.print("Mensaje recibido en topic [");
  Serial.print(topic);
  Serial.print("] ");

  // Convertir payload a string
  String message = "";
  for (unsigned int i = 0; i < length; i++) {
    message += (char)payload[i];
  }
  Serial.println(message);

  // Parsear JSON (simple)
  // Esperamos: {"action":"on",...}
  if (message.indexOf("\"action\":\"on\"") >= 0) {
    digitalWrite(FAN_PIN, HIGH);
    Serial.println("Fan ON");
  } else if (message.indexOf("\"action\":\"off\"") >= 0) {
    digitalWrite(FAN_PIN, LOW);
    Serial.println("Fan OFF");
  }
}

void reconnect() {
  // Intentar conectar al broker MQTT
  while (!client.connected()) {
    Serial.print("Conectando MQTT...");
    // Genera ID único
    String clientId = "ESP8266-";
    clientId += String(random(0xffff), HEX);

    if (client.connect(clientId.c_str())) {
      Serial.println("MQTT conectado");
      // Suscribirse al topic
      client.subscribe(mqtt_topic);
      Serial.print("Suscrito a: ");
      Serial.println(mqtt_topic);
    } else {
      Serial.print("Error: ");
      Serial.print(client.state());
      Serial.println(" Reintentando en 5 segundos...");
      delay(5000);
    }
  }
}

void setup() {
  Serial.begin(115200);
  delay(100);

  // Configurar pin del ventilador
  pinMode(FAN_PIN, OUTPUT);
  digitalWrite(FAN_PIN, LOW); // Apagado al inicio

  // Conectar a WiFi
  setup_wifi();

  // Configurar MQTT
  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(callback);
}

void loop() {
  // Asegurar conexión WiFi
  if (WiFi.status() != WL_CONNECTED) {
    setup_wifi();
  }

  // Reconectar MQTT si es necesario
  if (!client.connected()) {
    reconnect();
  }

  // Procesar mensajes MQTT
  client.loop();

  delay(100);
}
```

### Cambios necesarios en el código

1. **SSID y Password WiFi:**
   ```cpp
   const char* ssid = "tu_wifi";
   const char* password = "tu_password";
   ```

2. **IP del broker MQTT** (donde corre Mosquitto):
   ```cpp
   const char* mqtt_server = "192.168.1.X"; // Tu IP local
   ```
   Averigua tu IP local con `ipconfig` en PowerShell.

3. **Pin del ventilador:**
   ```cpp
   const int FAN_PIN = D1; // Cambiar según tu conexión
   ```
   - ESP8266: D0=GPIO16, D1=GPIO5, D2=GPIO4, D3=GPIO0, D4=GPIO2, etc.
   - ESP32: GPIO0-GPIO39 (consulta el pinout de tu board)

---

## 4. Código para ESP32 (similar, ajustes mínimos)

```cpp
#include <WiFi.h>
#include <PubSubClient.h>

const char* ssid = "TU_SSID";
const char* password = "TU_PASSWORD";
const char* mqtt_server = "192.168.1.100";
const int mqtt_port = 1883;
const char* mqtt_topic = "invernadero/fan";

const int FAN_PIN = 5; // GPIO5 en ESP32

WiFiClient espClient;
PubSubClient client(espClient);

// ... resto del código es igual, solo cambiar WiFi.h por WiFi.h y ajustar pines
```

---

## 5. Flujo de prueba

1. **Inicia Mosquitto** en tu PC:
   ```powershell
   mosquitto -v
   ```

2. **Carga el código en el ESP** y abre Monitor Serial (115200 baud)

3. **Desde tu navegador o Postman**, haz:
   ```
   POST http://localhost:8000/api/fan/on
   ```

4. **Observa en el monitor serial del ESP:**
   ```
   Mensaje recibido en topic [invernadero/fan] {"action":"on",...}
   Fan ON
   ```

5. El ventilador debe activarse (pin en HIGH)

---

## 6. Troubleshooting

### El ESP no se conecta a WiFi
- Verifica SSID y password correctos
- Asegúrate que la red es 2.4GHz (ESP8266 no soporta 5GHz)

### No se conecta a MQTT
- Verifica que Mosquitto está corriendo (`mosquitto -v`)
- Verifica la IP correcta (usa `ipconfig` para encontrar tu IP local)
- Prueba conectividad: `ping 192.168.1.100` (reemplaza con tu IP)

### No recibe mensajes
- En otra terminal, suscribete para monitorear:
  ```powershell
  mosquitto_sub -h 192.168.1.100 -t invernadero/fan
  ```
- Luego publica desde Laravel y deberías ver el mensaje

### Mensajes llegan pero el pin no se activa
- Verifica el número de pin correcto
- Comprueba que el relay/módulo está correctamente conectado
- Prueba manualmente: `digitalWrite(FAN_PIN, HIGH);` en setup()

---

## 7. Comando útil: Monitorear MQTT desde PC

Abre una terminal PowerShell y ejecuta:
```powershell
mosquitto_sub -h 127.0.0.1 -t invernadero/fan -v
```

Luego dispara eventos desde Laravel y verás los mensajes en tiempo real.

---

## Resumen

- Laravel → publica a MQTT en `invernadero/fan`
- ESP → se suscribe a `invernadero/fan`
- Cuando Laravel publica, ESP recibe y activa el pin
- Todo ocurre en la red local (no requiere Internet)

¿Preguntas o problemas? Pega el error/salida del monitor serial del ESP.
