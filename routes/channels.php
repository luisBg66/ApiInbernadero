<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Registro del canal para Reverb (WebSockets) — protegido para evitar errores
// si el paquete de Reverb no está instalado o disponible en tiempo de ejecución.
if (class_exists(\Laravel\Reverb\Reverb::class) || class_exists('Reverb\\Server\\Reverb')) {
    // Determinar la clase disponible (soporte para distintos nombres/namespaces)
    $reverbClass = class_exists(\Laravel\Reverb\Reverb::class)
        ? \Laravel\Reverb\Reverb::class
        : 'Reverb\\Server\\Reverb';

    // Evitar type-hinting directo porque la clase del channel podría no existir
    $reverbClass::channel('fan-control', function ($channel) {
        // Espera que $channel ofrezca un método `on` similar al servidor Reverb
        $channel->on('turnOnFan', function ($data, $connection) {
            // Aquí se maneja la lógica cuando se recibe el evento turnOnFan
            $connection->send(json_encode(['status' => 'Fan turned on']));
        });
    });
}
