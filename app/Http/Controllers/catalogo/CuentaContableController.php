<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\CuentaContable;
use Illuminate\Http\Request;

class CuentaContableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuentas_contables = CuentaContable::get();

        return view('catalogo.cuenta_contable.index', compact('cuentas_contables'));
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
            'descripcion' => 'required|unique:cuenta_contable,descripcion',
            'codigo' => 'required|integer',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una cuenta contable con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.integer'       => 'El código solo puede contener números.',
        ]);

        $cuenta_contable = new CuentaContable();
        $cuenta_contable->descripcion = $request->descripcion;
        $cuenta_contable->codigo = $request->codigo;
        $cuenta_contable->save();

        return back()->with('success', 'Cuenta contable creada correctamente');

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
            'descripcion.unique'   => 'Ya existe una cuenta contable con esta descripción.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.integer'       => 'El código solo puede contener números.',
        ]);

        $cuenta_contable = CuentaContable::findOrFail($id);
        $cuenta_contable->descripcion = $request->descripcion;
        $cuenta_contable->codigo = $request->codigo;
        $cuenta_contable->save();

        return back()->with('success', 'Cuenta contable creada correctamente');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cuenta_contable = CuentaContable::findOrFail($id);
        $cuenta_contable->delete();

        return back()->with('success', 'Cuenta contable  eliminada correctamente');
        //
    }
}
