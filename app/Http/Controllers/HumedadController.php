<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HumedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           return response()->json(\App\Models\Humedad::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
              $validated = $request->validate([
                  'humedad' => 'required|numeric',
              ]);
              $humedad = \App\Models\Humedad::create($validated);
              return response()->json($humedad, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
           $humedad = \App\Models\Humedad::find($id);
           if (!$humedad) {
              return response()->json(['error' => 'No encontrado intentadenuevo'], 404);
           }
           return response()->json($humedad);
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
