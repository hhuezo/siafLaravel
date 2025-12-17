<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Gerencia;
use Illuminate\Http\Request;

class GerenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gerencias = Gerencia::get();

        return view('catalogo.gerencia.index', compact('gerencias'));
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
            'descripcion' => 'required|unique:gerencia,descripcion',
            'codigo' => 'required|integer',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una gerencia con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.integer'       => 'El código solo puede contener números.',
        ]);

        $gerencia = new Gerencia();
        $gerencia->descripcion = $request->descripcion;
        $gerencia->codigo = $request->codigo;
        $gerencia->save();

        return back()->with('success', 'Gerencia creada correctamente');
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
            'descripcion' => 'required|unique:cuenta_contable,descripcion',
            'codigo' => 'required|integer',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una gerencia con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.integer'       => 'El código solo puede contener números.',
        ]);

        $gerencia = Gerencia::findOrFail($id);
        $gerencia->descripcion = $request->descripcion;
        $gerencia->codigo = $request->codigo;
        $gerencia->save();

        return back()->with('success', 'Gerencia creada correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gerencia = Gerencia::findOrFail($id);
        $gerencia->delete();

        return back()->with('success', 'Gerencia  eliminada correctamente');
        //
    }
}
