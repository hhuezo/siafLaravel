<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $materiales = Material::get();

        return view('catalogo.material.index', compact('materiales'));
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
            'descripcion' => 'required|unique:material,descripcion'
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un material con esta descripción.',
        ]);

        $material = new Material();
        $material->descripcion = $request->descripcion;
        $material->save();

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
            'descripcion' => 'required|unique:material,descripcion,' . $id
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un material con esta descripción.',
        ]);

        $material = Material::findOrFail($id);
        $material->descripcion = $request->descripcion;
        $material->save();

        return back()->with('success', 'Registro actualizado correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return back()->with('success', 'Registro eliminado correctamente');
        //
    }
}
