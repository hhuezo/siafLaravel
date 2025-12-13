<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Procedencia;
use Illuminate\Http\Request;

class ProcedenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $procedencias = Procedencia::get();

        return view('catalogo.procedencia.index', compact('procedencias'));
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
            'descripcion' => 'required|unique:procedencia,descripcion'
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una procedencia con esta descripción.',
        ]);

        $procedencia = new Procedencia();
        $procedencia->descripcion = $request->descripcion;
        $procedencia->save();

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
            'descripcion' => 'required|unique:procedencia,descripcion,' . $id
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una procedencia con esta descripción.',
        ]);

        $procedencia = Procedencia::findOrFail($id);
        $procedencia->descripcion = $request->descripcion;
        $procedencia->save();

        return back()->with('success', 'Registro actualizado correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $procedencia = Procedencia::findOrFail($id);
        $procedencia->delete();

        return back()->with('success', 'Registro eliminado correctamente');
        //
    }
}
