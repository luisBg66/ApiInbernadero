<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IluminacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           return response()->json(\App\Models\Iluminacion::all());
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
           $iluminacion = \App\Models\Iluminacion::create($validated);
           return response()->json($iluminacion, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
           $iluminacion = \App\Models\Iluminacion::find($id);
           if (!$iluminacion) {
              return response()->json(['error' => 'No encontrado'], 404);
           }
           return response()->json($iluminacion);
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
