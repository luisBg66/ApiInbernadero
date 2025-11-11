<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedidaController extends Controller
{
       public function store(Request $request)
    {
              $validated = $request->validate([
                  'temperatura' => 'required|numeric',
                  'humedad' => 'required|numeric',
              ]);
              $temperatura = \App\Models\Temperatura::create($validated);
              $humedad = \App\Models\Humedad::create($validated);
              return response()->json([$temperatura,$humedad], 201);
    }



}
