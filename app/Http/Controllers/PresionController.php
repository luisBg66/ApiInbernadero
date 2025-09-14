<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           return response()->json(\App\Models\Presion::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
              $validated = $request->validate([
                  'presions' => 'required|numeric',
              ]);
              $presion = \App\Models\Presion::create($validated);
              return response()->json($presion, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
           $presion = \App\Models\Presion::find($id);
           if (!$presion) {
              return response()->json(['error' => 'No encontrado'], 404);
           }
           return response()->json($presion);
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
