<?php

namespace App\Http\Controllers;

use Reverb\Server\Reverb;

class FanController extends Controller
{
    public function turnOnFan()
    {
        // Enviar el evento al canal de WebSocket
        Reverb::broadcast('fan-control', 'turnOnFan', ['action' => 'on']);

        return response()->json(['message' => 'Comando enviado para encender el ventilador'], 200);
    }
}