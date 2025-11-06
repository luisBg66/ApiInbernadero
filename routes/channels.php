<?php

use Illuminate\Support\Facades\Broadcast;
use Reverb\Server\Reverb;
use Reverb\Server\ReverbChannel;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Reverb::channel('fan-control', function (ReverbChannel $channel) {
    $channel->on('turnOnFan', function ($data, $connection) {
        // Aquí se maneja la lógica cuando se recibe el evento turnOnFan
        $connection->send(json_encode(['status' => 'Fan turned on']));
    });
});
