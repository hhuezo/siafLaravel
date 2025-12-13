<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Fuente;
use Illuminate\Http\Request;

class FuenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fuentes = Fuente::get();

        return view('catalogo.fuente.index', compact('fuentes'));
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
            'descripcion' => 'required|unique:fuente,descripcion'
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una fuente con esta descripción.',
        ]);

        $fuente = new Fuente();
        $fuente->descripcion = $request->descripcion;
        $fuente->save();

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
            'descripcion' => 'required|unique:fuente,descripcion,' . $id
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una fuente con esta descripción.',
        ]);

        $fuente = Fuente::findOrFail($id);
        $fuente->descripcion = $request->descripcion;
        $fuente->save();

        return back()->with('success', 'Registro actualizado correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fuente = Fuente::findOrFail($id);
        $fuente->delete();

        return back()->with('success', 'Registro eliminado correctamente');
        //
    }
}
