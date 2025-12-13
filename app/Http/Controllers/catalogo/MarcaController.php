<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = Marca::get();

        return view('catalogo.marca.index', compact('marcas'));
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
            'descripcion.unique'   => 'Ya existe una marca con esta descripción.',
        ]);

        $marca = new Marca();
        $marca->descripcion = $request->descripcion;
        $marca->save();

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
            'descripcion' => 'required|unique:marca,descripcion,' . $id
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una marca con esta descripción.',
        ]);

        $marca = Marca::findOrFail($id);
        $marca->descripcion = $request->descripcion;
        $marca->save();

        return back()->with('success', 'Registro actualizado correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $marca = Marca::findOrFail($id);
        $marca->delete();

        return back()->with('success', 'Registro eliminado correctamente');
        //
    }
}
