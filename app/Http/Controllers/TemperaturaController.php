<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemperaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           return response()->json(\App\Models\Temperatura::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           $validated = $request->validate([
              'valor' => 'required|numeric',
              'unidad' => 'required|string',
              'timestamp' => 'nullable|date',
           ]);
           $temperatura = \App\Models\Temperatura::create($validated);
           return response()->json($temperatura, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
           $temperatura = \App\Models\Temperatura::find($id);
           if (!$temperatura) {
              return response()->json(['error' => 'No encontrado'], 404);
           }
           return response()->json($temperatura);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
