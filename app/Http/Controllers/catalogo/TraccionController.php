<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Traccion;
use Illuminate\Http\Request;

class TraccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tracciones = Traccion::get();

        return view('catalogo.traccion.index', compact('tracciones'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|unique:traccion,descripcion'
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una traccion con esta descripción.',
        ]);

        $traccion = new Traccion();
        $traccion->descripcion = $request->descripcion;
        $traccion->save();

        return back()->with('success', 'Registro creado correctamente');
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'descripcion' => 'required|unique:traccion,descripcion,' . $id

        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una traccion con esta descripción.',
        ]);

        $traccion = Traccion::findOrFail($id);
        $traccion->descripcion = $request->descripcion;
        $traccion->save();

        return back()->with('success', 'Registro actualizado correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fuente = Traccion::findOrFail($id);

        $fuente->delete();


        return back()->with('success', 'Registro eliminado correctamente');
        //
    }
}
