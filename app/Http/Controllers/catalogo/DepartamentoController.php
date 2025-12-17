<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentos = Departamento::get();

        return view('catalogo.departamento.index', compact('departamentos'));
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
            'descripcion' => 'required|unique:departamento,descripcion',
            'codigo' => 'required|integer',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un departamento con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.integer'       => 'El código solo puede contener números.',
        ]);

        $departamento = new Departamento();
        $departamento->descripcion = $request->descripcion;
        $departamento->codigo = $request->codigo;
        $departamento->save();

        return back()->with('success', 'Departamento creado correctamente');
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
            'descripcion' => 'required|unique:departamento,descripcion',
            'codigo' => 'required|integer',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un departamento con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.integer'       => 'El código solo puede contener números.',
        ]);

        $departamento = Departamento::findOrFail($id);
        $departamento->descripcion = $request->descripcion;
        $departamento->codigo = $request->codigo;
        $departamento->save();

        return back()->with('success', 'Departamento creado correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();

        return back()->with('success', 'Departamento  eliminado correctamente');
        //
    }
}
