╔══════════════════════════════════════════════════════════════════════════╗
║                 🚀 MQTT + ESP SETUP - GUÍA RÁPIDA                        ║
╚══════════════════════════════════════════════════════════════════════════╝

✅ YA COMPLETADO EN TU PROYECTO:
═════════════════════════════════════════════════════════════════════════

1. ✅ Librería PHP instalada: php-mqtt/client
2. ✅ .env configurado con MQTT:
   - MQTT_HOST=127.0.0.1
   - MQTT_PORT=1883
   - MQTT_TOPIC_FAN=invernadero/fan
3. ✅ FanController.php actualizado para publicar a MQTT
4. ✅ Documentación completa: MQTT_ESP_SETUP.md
5. ✅ Ejemplo Arduino listo: example_esp8266.ino


🔧 PASOS SIGUIENTES (Para tu ESP):
═════════════════════════════════════════════════════════════════════════

PASO 1: Instalar Mosquitto (Windows)
──────────────────────────────────
Opción A - Con Chocolatey:
  choco install mosquitto

Opción B - Manual:
  Descarga desde https://mosquitto.org/download/


PASO 2: Iniciar Mosquitto
──────────────────────────
Abre PowerShell y ejecuta:
  mosquitto -v

Deberías ver:
  mosquitto version 2.x.x starting
  1733590400: Config loaded from *.conf
  ...


PASO 3: Preparar el ESP8266/ESP32
──────────────────────────────────
1. Abre Arduino IDE
2. Instala librerías:
   - Sketch > Include Library > Manage Libraries
   - Busca: "PubSubClient" (Nick O'Leary) → Install
   
3. Abre el archivo: example_esp8266.ino (en tu proyecto)

4. CAMBIAR ESTOS VALORES:
   const char* ssid = "TU_WIFI";
   const char* password = "TU_PASSWORD";
   const char* mqtt_server = "192.168.1.X"; // Tu IP local


PASO 4: Encontrar tu IP Local
──────────────────────────────
En PowerShell:
  ipconfig

Busca la sección de tu adaptador de red activo:
  IPv4 Address . . . . . . . . . . : 192.168.1.XXX


PASO 5: Cargar código en ESP
──────────────────────────────
1. Conecta ESP8266/ESP32 por USB
2. Selecciona Board: Tools > Board > ESP8266 / ESP32
3. Selecciona Puerto: Tools > Port > COMX
4. Presiona Upload (flecha)
5. Abre Monitor Serial (115200 baud)

Deberías ver:
  WiFi conectado
  IP: 192.168.X.X
  Conectando MQTT... OK
  Suscrito a: invernadero/fan


PASO 6: Prueba rápida desde PowerShell
────────────────────────────────────────
Abre otra ventana PowerShell y ejecuta:

Monitorear mensajes:
  mosquitto_sub -h 127.0.0.1 -t invernadero/fan -v

En otra ventana, publica un comando:
  mosquitto_pub -h 127.0.0.1 -t invernadero/fan -m "{\"action\":\"on\"}"

Deberías ver en el monitor del ESP:
  Mensaje en [invernadero/fan]: {"action":"on"}
  >>> VENTILADOR ENCENDIDO


PASO 7: Prueba desde Laravel
──────────────────────────────
Desde tu navegador o Postman:
  POST http://localhost:8000/api/fan/on

Deberías ver:
  - En monitor del ESP: mensaje recibido y "VENTILADOR ENCENDIDO"
  - En respuesta JSON: {"message":"Comando enviado...","status":"on"}


⚠️  TROUBLESHOOTING
═════════════════════════════════════════════════════════════════════════

❌ "MQTT connection failed"
   → Verifica que Mosquitto esté corriendo (mosquitto -v)
   → Verifica IP correcta (ipconfig)
   → Prueba: ping 192.168.1.100

❌ "WiFi conectado" pero "no MQTT"
   → Firewall bloquea puerto 1883
   → Solución: abre puerto en firewall o cambia MQTT_PORT en .env (ej: 8883)

❌ "Mensaje recibido" pero el pin no se activa
   → Verifica número de pin correcto (D0-D8 en ESP8266)
   → Verifica conexión física del relay

❌ "No veo salida en Monitor Serial"
   → Verifica velocidad: 115200 baud
   → Comprueba que USB está conectado
   → Verifica selección de puerto (Tools > Port)


📖 DOCUMENTACIÓN COMPLETA
═════════════════════════════════════════════════════════════════════════

Lee estos archivos para más detalles:
  - MQTT_ESP_SETUP.md (detalles técnicos completos)
  - example_esp8266.ino (código con comentarios)
  - app/Http/Controllers/FanController.php (código Laravel)


🎯 FLUJO COMPLETO
═════════════════════════════════════════════════════════════════════════

1. Usuario en Filament → Click en "Encender"
2. Livewire emite evento → FanControlWidget
3. FanControlWidget dispara event(new FanControl('on'))
4. Laravel routes/api.php → FanController@turnOnFan
5. FanController.php llama publishToMqtt('on')
6. MQTT publica a invernadero/fan
7. ESP recibe mensaje y activa el pin → VENTILADOR ENCIENDE


✨ ¡LISTO PARA EMPEZAR!
═════════════════════════════════════════════════════════════════════════

Sigue los pasos arriba y tendrás tu ventilador controlado desde Laravel.

¿Preguntas? Pega los errores o salida del Monitor Serial aquí.
