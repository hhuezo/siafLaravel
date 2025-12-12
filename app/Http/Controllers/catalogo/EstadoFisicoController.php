<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\EstadoFisico;
use Illuminate\Http\Request;

class EstadoFisicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados_fisicos = EstadoFisico::get();

        return view('catalogo.estado_fisico.index', compact('estados_fisicos'));
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
            'descripcion' => 'required|unique:estado_fisico,descripcion'
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un estado con esta descripción.',
        ]);

        $estado_fisico = new EstadoFisico();
        $estado_fisico->descripcion = $request->descripcion;
        $estado_fisico->save();

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
            'descripcion' => 'required|unique:color,descripcion,' . $id
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un color con esta descripción.',
        ]);

        $estado_fisico = EstadoFisico::findOrFail($id);
        $estado_fisico->descripcion = $request->descripcion;
        $estado_fisico->save();

        return back()->with('success', 'Registro actualizado correctamente');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $estado_fisico = EstadoFisico::findOrFail($id);
        $estado_fisico->delete();

        return back()->with('success', 'Registro eliminado correctamente');
        //
    }
}
