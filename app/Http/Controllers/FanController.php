<?php

namespace App\Http\Controllers;

use App\Events\FanControl;
use PhpMqtt\Client\MqttClient;
use Illuminate\Support\Facades\Log;

class FanController extends Controller
{
    /**
     * Publicar comando a MQTT para el ESP
     */
    private function publishToMqtt(string $action): void
    {
        try {
            $host = env('MQTT_HOST', '127.0.0.1');
            $port = env('MQTT_PORT', 1883);
            $username = env('MQTT_USERNAME', '');
            $password = env('MQTT_PASSWORD', '');
            $topic = env('MQTT_TOPIC_FAN', 'invernadero/fan');

            $mqtt = new MqttClient($host, $port, 'laravel_fan_' . uniqid());

            if ($username && $password) {
                $mqtt->connect(null, true, ['username' => $username, 'password' => $password]);
            } else {
                $mqtt->connect();
            }

            $payload = json_encode([
                'action' => $action,
                'source' => 'laravel',
                'timestamp' => now()->toIso8601String(),
            ]);

            $mqtt->publish($topic, $payload, 0);
            $mqtt->disconnect();

            Log::info("MQTT published to {$topic}: {$action}");
        } catch (\Exception $e) {
            Log::error('MQTT Error: ' . $e->getMessage());
        }
    }

    public function turnOnFan()
    {
        // Publicar a MQTT para que el ESP lo reciba
        $this->publishToMqtt('on');

        // También emitir evento de Laravel (para WebSockets si quieres)
        event(new FanControl('on'));

        return response()->json(['message' => 'Comando enviado para encender el ventilador', 'status' => 'on'], 200);
    }

    public function turnOffFan()
    {
        // Publicar a MQTT para que el ESP lo reciba
        $this->publishToMqtt('off');

        // También emitir evento de Laravel
        event(new FanControl('off'));

        return response()->json(['message' => 'Comando enviado para apagar el ventilador', 'status' => 'off'], 200);
    }
}