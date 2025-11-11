<?php

namespace App\Http\Controllers;

use App\Models\CalidadAire;
use Illuminate\Http\Request;

class CalidadAireController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'estado' => 'required|string',
        ]);

        CalidadAire::create(['estado' => $data['estado']]);

        return response()->json(['message' => 'Estado de calidad de aire guardado correctamente']);
    }
}
