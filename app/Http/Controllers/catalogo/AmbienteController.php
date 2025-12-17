<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Ambiente;
use App\Models\catalogo\Unidad;
use Illuminate\Http\Request;

class AmbienteController extends Controller
{
    public function index()
    {
        $ambientes = Ambiente::get();
        $unidades = Unidad::get();

        return view('catalogo.ambiente.index', compact('ambientes', 'unidades'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|unique:ambiente,descripcion',
            'unidad_id'    => 'required|exists:unidad,id',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe un ambiente con esta descripción.',
            'unidad_id.required'    => 'Debe seleccionar una unidad.',
            'unidad_id.exists'      => 'La unidad seleccionado no es válido.',
        ]);

        $ambiente = new Ambiente();
        $ambiente->descripcion = $request->descripcion;
        $ambiente->unidad_id = $request->unidad_id;
        $ambiente->save();

        return back()->with('success', 'ambientes creada correctamente');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'descripcion' => 'required|unique:ambiente,descripcion,' . $id,
            'unidad_id'    => 'required|exists:unidad,id',
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.unique'   => 'Ya existe una clase con esta descripción.',
            'unidad_id.required'    => 'Debe seleccionar una unidad.',
            'unidad_id.exists'      => 'La unidad seleccionada no es válida.',
        ]);

        $ambiente = Ambiente::findOrFail($id);
        $ambiente->descripcion = $request->descripcion;
        $ambiente->unidad_id = $request->unidad_id;
        $ambiente->save();

        return back()->with('success', 'Ambiente actualizada correctamente');
    }


    public function destroy(string $id)
    {

        $ambiente = Ambiente::findOrFail($id);
        //dd($ambiente);
        $ambiente->delete();

        return back()->with('success', 'Ambiente eliminado correctamente');
    }
}
