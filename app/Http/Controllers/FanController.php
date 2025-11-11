<?php

namespace App\Http\Controllers;

use App\Events\FanControl;

class FanController extends Controller
{
    public function turnOnFan()
    {
        // Emitir evento de Laravel que será transmitido por el driver de broadcasting (reverb)
        event(new FanControl('on'));

        return response()->json(['message' => 'Comando enviado para encender el ventilador'], 200);
    }
}