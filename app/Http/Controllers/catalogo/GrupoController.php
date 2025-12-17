<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\general\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $grupos = Grupo::get();

        return view('catalogo.grupo.index', compact('grupos'));
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
            'descripcion' => 'required|unique:grupo,descripcion',
            'codigo' => 'required|integer',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un grupo con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.integer'       => 'El código solo puede contener números.',
        ]);

        $grupo = new Grupo();
        $grupo->descripcion = $request->descripcion;
        $grupo->codigo = $request->codigo;
        $grupo->save();

        return back()->with('success', 'Grupo creadO correctamente');

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
            'descripcion' => 'required|unique:grupo,descripcion',
            'codigo' => 'required|integer',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un grupo con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.integer'       => 'El código solo puede contener números.',
        ]);

        $grupo = Grupo::findOrFail($id);
        $grupo->descripcion = $request->descripcion;
        $grupo->codigo = $request->codigo;
        $grupo->save();

        return back()->with('success', 'Grupo creada correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();

        return back()->with('success', 'Grupo  eliminada correctamente');
        //
    }
}
